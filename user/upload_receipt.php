<?php
require_once __DIR__ . '/../php/init.php';

ini_set('upload_max_filesize', '15M');
ini_set('post_max_size', '15M');
ini_set('max_execution_time', '300');

if (!$account) {
    header("Location: /login/index.php");
    exit;
}

$user = $account->user;
$userId = $user->user_id;
$output = null;
$error = null;
$successCount = 0;

$uploadError = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_FILES) && $_SERVER['CONTENT_LENGTH'] > 0) {
    $uploadError = "The file was too large for the server. Maximum allowed is 15MB.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['receipt'])) {
    $file = $_FILES['receipt'];

    $allowedMimes = ['image/png', 'image/jpeg', 'image/jpg'];
    $fileType = mime_content_type($file['tmp_name']);

    if (!in_array($fileType, $allowedMimes)) {
        $error = "Unsupported file type. Please upload a PNG or JPEG image.";
    } elseif ($file['error'] !== UPLOAD_ERR_OK) {
        $error = "Upload failed with PHP error code: " . $file['error'];
    } else {
        $tmpPath = $file['tmp_name'];
        $scriptPath = realpath(__DIR__ . '/../receipt_parser/main.py');

        if (!$scriptPath) {
            $error = "Parser script not found at: " . __DIR__ . '/../receipt_parser/main.py';
        } else {
            $pythonBin = "/opt/lampp/htdocs/receipt_parser/.venv/bin/python";
            $command = "env -u LD_LIBRARY_PATH " . escapeshellcmd($pythonBin) . " " . escapeshellarg($scriptPath) . " " . escapeshellarg($tmpPath) . " 2>&1";

            $result = shell_exec($command);

            if ($result) {
                $output = json_decode($result, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    $error = "The parser returned an invalid format. Check debug info.";
                    $rawResult = $result;
                } else if (isset($output['items']) && is_array($output['items'])) {
                    $conn->begin_transaction();
                    try {
                        foreach ($output['items'] as $item) {
                            $name = $item['name'] ?? 'Unknown Item';
                            $price = floatval($item['price'] ?? 0);
                            $category = $item['category'] ?? 'Shopping';
                            $store = $output['company'] ?? 'Receipt Upload';

                            $stmt = $conn->prepare("INSERT INTO `Transactions` (`user_id`, `amount`, `category`, `description`, `type`, `transaction_date`) VALUES (?, ?, ?, ?, 'expense', NOW())");
                            $description = $store . ": " . $name;
                            $stmt->bind_param("sdss", $userId, $price, $category, $description);
                            $stmt->execute();
                            $successCount++;
                        }
                        $conn->commit();
                    } catch (Exception $e) {
                        $conn->rollback();
                        $error = "Saved items to parser but failed to update database: " . $e->getMessage();
                    }
                }
            } else {
                $error = "The parser failed to run. Check if $pythonBin exists.";
            }
        }
    }
}

echo html_get_header();
echo html_get_navbar();
?>

<style>
    .loading-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.8);
        z-index: 9999;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .spinner {
        width: 50px;
        height: 50px;
        border: 5px solid #f3f3f3;
        border-top: 5px solid #3498db;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-bottom: 15px;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>

<div id="loader" class="loading-overlay">
    <div class="spinner"></div>
    <p><strong>Analyzing Receipt...</strong></p>
    <p class="text-muted text-sm">Extracting items and calculating totals.</p>
</div>

<main class="page">
    <div class="container">
        <div class="card">
            <h2 class="card__title">Receipt Scanner</h2>
            <p class="text-muted">Upload a photo of your receipt (up to 15MB) to automatically log your expenses.</p>

            <form action="" method="POST" enctype="multipart/form-data" class="margin-bottom-sm"
                onsubmit="document.getElementById('loader').style.display = 'flex';">
                <div class="form-group">
                    <label class="button button--secondary" style="cursor: pointer; display: inline-block;">
                        <i class="fas fa-camera"></i> Select Receipt Image
                        <input type="file" name="receipt" accept="image/png, image/jpeg" required style="display:none;"
                            onchange="document.getElementById('file-name').innerText = 'Selected: ' + this.files[0].name">
                    </label>
                    <span id="file-name" class="text-muted text-xs" style="margin-left: 10px;">No file chosen</span>
                </div>
                <button type="submit" class="button button--primary">
                    <i class="fas fa-magic"></i> Analyze & Save Expenses
                </button>
            </form>

            <hr class="divider">

            <?php if ($successCount > 0): ?>
                <div class="alert"
                    style="background: #e8f5e9; color: #2e7d32; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border: 1px solid #a5d6a7;">
                    <i class="fas fa-check-circle"></i> <strong>Success!</strong> Added <?= $successCount ?> items to your
                    expenses.
                    <div style="margin-top: 10px;">
                        <a href="/user/transactions.php" class="button button--small"
                            style="background: #2e7d32; color: white;">View in History</a>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($uploadError): ?>
                <div class="alert"
                    style="background: #fff3e0; color: #e65100; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border: 1px solid #ffcc80;">
                    <strong>Server Limit Hit:</strong> <?= htmlspecialchars($uploadError) ?><br>
                    <small>If this persists, contact your administrator to update php.ini.</small>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert"
                    style="background: #ffebee; color: #c62828; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border: 1px solid #ef9a9a;">
                    <strong>Notice:</strong> <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <?php if ($output && !isset($output['error'])): ?>
                <div class="parsed-results">
                    <h3 class="margin-bottom-sm">Recently Processed:
                        <?= htmlspecialchars($output['company'] ?? 'Unknown Store') ?>
                    </h3>
                    <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 12px; border: 1px solid #e0e0e0;">
                        <h4 class="text-muted text-xs">Full Data Dump:</h4>
                        <pre style="font-size: 0.85rem; color: #333;"><?php var_dump($output); ?></pre>
                    </div>
                </div>
            <?php elseif (isset($output['error']) || isset($rawResult)): ?>
                <div class="raw-output" style="margin-top: 20px;">
                    <h4 class="text-danger">Parser Debug Info:</h4>
                    <pre
                        style="background: #212121; color: #ffeb3b; padding: 1rem; border-radius: 8px; overflow-x: auto;"><?php var_dump($output ?? $rawResult); ?></pre>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php echo html_get_footer(); ?>