<?php
require_once __DIR__ . '/../php/init.php';

// Check if user is logged in AND is an adviser (Role check)
if (!$account || $account->user->role !== 'finance') {
    header("Location: /user/index.php"); // Regular users can't see this
    exit;
}

$adviser = $account->user;

echo html_get_header();
echo html_get_navbar();
?>

<main class="page dashboard">
    <div class="container container--wide">

        <!-- Adviser Profile Header -->
        <div class="dashboard__header-row">
            <div class="profile-card">
                <div class="profile-avatar" style="background: #2c3e50;"></div>
                <div class="profile-info">
                    <div class="text-xs text-primary">SENIOR FINANCIAL ADVISER</div>
                    <h1 class="section-title no-margin"><?= htmlspecialchars(strtoupper($adviser->name)) ?></h1>
                    <p class="text-muted">Licensed Professional Adviser</p>
                </div>
            </div>

            <!-- Stats Grid from Wireframe Page 32 -->
            <div class="card card--flex-grow">
                <div class="overview-grid">
                    <div class="overview-item">
                        <div class="stat-value">96</div>
                        <div class="text-muted text-xs">Active Clients</div>
                    </div>
                    <div class="overview-item">
                        <div class="stat-value">24</div>
                        <div class="text-muted text-xs">Reports Generated</div>
                    </div>
                    <div class="overview-item">
                        <div class="stat-value" style="color: #27ae60;">73%</div>
                        <div class="text-muted text-xs">Customer Satisfaction</div>
                    </div>
                    <div class="overview-item">
                        <div class="stat-value">152</div>
                        <div class="text-muted text-xs">Consultations</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="dashboard__main-grid" style="grid-template-columns: 2fr 1fr;">

            <!-- Recent Activity Table -->
            <div class="card">
                <div class="card__header-flex">
                    <h3 class="card__title">Recent Client Activity</h3>
                    <a href="/finance-adviser/clients.php" class="card__link">View All Clients</a>
                </div>
                <table class="transaction-table" style="width: 100%;">
                    <thead>
                        <tr class="text-muted text-xs" style="text-align: left; border-bottom: 1px solid #eee;">
                            <th style="padding: 10px;">CLIENT</th>
                            <th style="padding: 10px;">ACTION</th>
                            <th style="padding: 10px;">DATE</th>
                            <th style="padding: 10px; text-align: right;">STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="transaction-table__row">
                            <td class="transaction-table__cell"><strong>Daniel Hale</strong></td>
                            <td class="transaction-table__cell text-sm">Updated Risk Profile</td>
                            <td class="transaction-table__cell text-muted text-xs">Today, 2:14 PM</td>
                            <td class="transaction-table__cell text-right"><span style="color: #27ae60;">●
                                    Verified</span></td>
                        </tr>
                        <tr class="transaction-table__row">
                            <td class="transaction-table__cell"><strong>Sophia Turner</strong></td>
                            <td class="transaction-table__cell text-sm">Uploaded New Receipt</td>
                            <td class="transaction-table__cell text-muted text-xs">Yesterday</td>
                            <td class="transaction-table__cell text-right"><span style="color: #f39c12;">● Pending
                                    Review</span></td>
                        </tr>
                        <tr class="transaction-table__row">
                            <td class="transaction-table__cell"><strong>Liam Reed</strong></td>
                            <td class="transaction-table__cell text-sm">Requested Portfolio Report</td>
                            <td class="transaction-table__cell text-muted text-xs">12 May 2026</td>
                            <td class="transaction-table__cell text-right"><span style="color: #3498db;">●
                                    Processing</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Sidebar Actions -->
            <div class="dashboard__sidebar">
                <div class="card card--compact" style="background: #2c3e50; color: white;">
                    <h4 class="margin-bottom-sm">Adviser Toolbox</h4>
                    <button class="button button--primary w-full margin-bottom-xs" style="background: #34495e;">
                        <i class="fas fa-file-invoice-dollar"></i> Generate Report
                    </button>
                    <button class="button button--secondary w-full"
                        style="background: transparent; border-color: #7f8c8d; color: white;">
                        <i class="fas fa-calendar-alt"></i> Schedule Consultation
                    </button>
                </div>

                <div class="card card--compact">
                    <h4 class="card__title">Market Overview</h4>
                    <div class="text-xs text-muted margin-bottom-sm">Real-time indicators</div>
                    <div class="trend trend--up text-sm">FTSE 100: +0.45%</div>
                    <div class="trend trend--down text-sm">S&P 500: -0.12%</div>
                </div>
            </div>

        </div>
    </div>
</main>

<?php echo html_get_footer(); ?>