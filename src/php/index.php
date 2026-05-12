<?php

$cv_data = json_decode(json: file_get_contents(filename: __DIR__ . '/../../cv.json'),  associative: true);

function get_date(array $mini_json): string {
	return $mini_json['date'] ?? $mini_json['date from'] . ' - ' . ($mini_json['date until'] ?? '(on going)');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>CV</title>
	<style>
	<?= str_replace('\n', '', file_get_contents(__DIR__ . '/../css/styles.css')) ?>
	</style>
	<link rel="shortcut icon" href="../assets/images/favicon.png" type="image/x-icon">
</head>
<body>
	<main class="cv" style="
		--primary-text-colour: <?= $cv_data['styles']['primary text colour'] ?? '#333' ?>;
		--secondary-text-colour: <?= $cv_data['styles']['secondary text colour'] ?? '#555' ?>;
		--primary-colour: <?= $cv_data['styles']['primary colour'] ?? '#ffd556' ?>;
		--background: <?= $cv_data['styles']['background'] ?? '#fff' ?>;
		--border-width: <?= $cv_data['styles']['border width'] ?? '3px' ?>;
	">
		<header class="name"><?= $cv_data['name'] ?></header>
		<div class="body">
			<div class="column" id="left-column">
				<article class="about-me">
					<h2 class="title">about me</h2>
					<?= $cv_data['about me'] ?>
				</article>
				<div class="section-2">
					<h2 class="title">experience</h2>
					<?php foreach ($cv_data['experiences'] as $experience): ?>
						<div class="experience">
							<h3 class="sub-title">
								<span><?= $experience['title'] ?></span>
								<span><?= get_date($experience) ?></span>
								<span><?= $experience['location'] ?></span>
							</h3>
							<p class="description"><?= $experience['description'] ?></p>
						</div>
					<?php endforeach; ?>
					<h2 class="title">education</h2>
					<?php foreach ($cv_data['education'] as $education): ?>
						<div class="education">
							<h3 class="sub-title">
								<span><?= $education['title'] ?></span>
								<span><?= get_date($education) ?></span>
								<span><?= $education['location'] ?></span>
							</h3>
							<p class="description"><?= $education['description'] ?></p>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="column" id="right-column">
				<div class="section-2">
					<div class="volunteering">
						<h2 class="title">Volunteering</h2>
						<?php foreach ($cv_data['volunteering'] as $volunteer): ?>
							<h3 class="sub-title">
								<span><?= $volunteer['title'] ?></span>
								<span><?= get_date($volunteer) ?></span>
							</h3>
							<p class="description"><?= $volunteer['description'] ?></p>
						<?php endforeach; ?>
					</div>
					<div class="contact">
						<h2 class="title">Contact</h2>
						<ul>
							<?php if (isset($cv_data['contact']['phone'])): ?>
								<a
								href="tel:<?= $cv_data['contact']['phone'] ?>">
									<svg viewBox="0 0 32 32">
										<path d="M31.129 23.072c-0.118-0.743-0.365-1.409-0.719-2.004l0.014 0.026c-0.189-0.31-0.502-0.529-0.868-0.589l-0.007-0.001-8.969-1.424c-0.056-0.009-0.12-0.013-0.185-0.013-0.348 0-0.664 0.138-0.895 0.363l0-0c-0.605 0.609-1.094 1.334-1.429 2.14l-0.016 0.044c-3.506-1.573-6.252-4.319-7.785-7.729l-0.039-0.097c0.849-0.351 1.574-0.839 2.182-1.445l-0 0c0.226-0.226 0.366-0.539 0.366-0.885 0-0.069-0.006-0.137-0.017-0.204l0.001 0.007-1.423-8.97c-0.061-0.374-0.281-0.688-0.587-0.873l-0.006-0.003c-0.546-0.327-1.185-0.568-1.866-0.684l-0.033-0.005c-0.349-0.078-0.75-0.122-1.161-0.122-0.024 0-0.047 0-0.071 0l0.004-0h-0.010c-3.772 0.028-6.821 3.081-6.844 6.851v0.002c0.015 13.037 10.579 23.601 23.615 23.616h0.001c3.776-0.022 6.831-3.076 6.854-6.849v-0.002c0.001-0.029 0.001-0.063 0.001-0.098 0-0.373-0.039-0.737-0.114-1.088l0.006 0.034zM24.383 28.576c-11.657-0.013-21.103-9.459-21.116-21.115v-0.001c0.019-2.399 1.961-4.338 4.359-4.354h0.001c0.010-0 0.023-0 0.035-0 0.249 0 0.492 0.027 0.726 0.079l-0.022-0.004c0.233 0.039 0.438 0.093 0.635 0.163l-0.025-0.008 1.21 7.634c-0.516 0.379-1.135 0.651-1.807 0.768l-0.026 0.004c-0.591 0.107-1.033 0.618-1.033 1.232 0 0.146 0.025 0.286 0.071 0.416l-0.003-0.009c1.824 5.217 5.859 9.252 10.951 11.038l0.125 0.038c0.121 0.043 0.261 0.067 0.407 0.067 0.234 0 0.452-0.063 0.64-0.174l-0.006 0.003c0.311-0.184 0.532-0.492 0.597-0.854l0.001-0.008c0.121-0.698 0.393-1.317 0.78-1.846l-0.009 0.012 7.633 1.211c0.068 0.193 0.129 0.425 0.17 0.663l0.004 0.026c0.037 0.177 0.057 0.381 0.057 0.589 0 0.024-0 0.048-0.001 0.073l0-0.004c-0.013 2.4-1.954 4.343-4.352 4.359h-0.002z"></path>
									</svg>
									<?= $cv_data['contact']['phone'] ?>
								</a>
							<?php endif ?>
							<?php if (isset($cv_data['contact']['email'])): ?>
								<a
								href="mailto:<?= $cv_data['contact']['email'] ?>">
									<svg viewBox="0 0 24 24" fill="none">
										<rect x="3" y="6" width="18" height="12" rx="2" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
										<path d="M20.5737 7L12 13L3.42635 7" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
									</svg>
									<?= $cv_data['contact']['email'] ?>
								</a>
							<?php endif ?>
							<?php if (isset($cv_data['contact']['github'])): ?>
								<a
								target="_blank"
								href="<?= $cv_data['contact']['github href'] ?? "https://github.com/" . $cv_data['contact']['github'] ?>">
									<svg viewBox="0 0 48 48">
										<g id="Layer_2" data-name="Layer 2">
											<g id="invisible_box" data-name="invisible box">
												<rect width="48" height="48" fill="none"/>
												<rect width="48" height="48" fill="none"/>
											</g>
											<g id="icons_Q2" " data-name="icons Q2">
												<path d="M24,1.9a21.6,21.6,0,0,0-6.8,42.2c1,.2,1.8-.9,1.8-1.8V39.4c-6,1.3-7.9-2.9-7.9-2.9a6.5,6.5,0,0,0-2.2-3.2C6.9,31.9,9,32,9,32a4.3,4.3,0,0,1,3.3,2c1.7,2.9,5.5,2.6,6.7,2.1a5.4,5.4,0,0,1,.5-2.9C12.7,32,9,28,9,22.6A10.7,10.7,0,0,1,11.9,15a6.2,6.2,0,0,1,.3-6.4,8.9,8.9,0,0,1,6.4,2.9,15.1,15.1,0,0,1,5.4-.8,17.1,17.1,0,0,1,5.4.7,9,9,0,0,1,6.4-2.8,6.5,6.5,0,0,1,.4,6.4A10.7,10.7,0,0,1,39,22.6C39,28,35.3,32,28.5,33.2a5.4,5.4,0,0,1,.5,2.9v6.2a1.8,1.8,0,0,0,1.9,1.8A21.7,21.7,0,0,0,24,1.9Z"/>
											</g>
										</g>
									</svg>
									<?= $cv_data['contact']['github'] ?>
								</a>
							<?php endif ?>
							<?php if (isset($cv_data['contact']['linkedin'])): ?>
								<a
								target="_blank"
								href="<?= $cv_data['contact']['linkedin href'] ?? ''?>">
									<svg viewBox="0 0 45.959 45.959" xml:space="preserve">
										<g>
											<g>
												<path d="M5.392,0.492C2.268,0.492,0,2.647,0,5.614c0,2.966,2.223,5.119,5.284,5.119c1.588,0,2.956-0.515,3.957-1.489
													c0.96-0.935,1.489-2.224,1.488-3.653C10.659,2.589,8.464,0.492,5.392,0.492z M7.847,7.811C7.227,8.414,6.34,8.733,5.284,8.733
													C3.351,8.733,2,7.451,2,5.614c0-1.867,1.363-3.122,3.392-3.122c1.983,0,3.293,1.235,3.338,3.123
													C8.729,6.477,8.416,7.256,7.847,7.811z"/>
												<path d="M0.959,45.467h8.988V12.422H0.959V45.467z M2.959,14.422h4.988v29.044H2.959V14.422z"/>
												<path d="M33.648,12.422c-4.168,0-6.72,1.439-8.198,2.792l-0.281-2.792H15v33.044h9.959V28.099c0-0.748,0.303-2.301,0.493-2.711
													c1.203-2.591,2.826-2.591,5.284-2.591c2.831,0,5.223,2.655,5.223,5.797v16.874h10v-18.67
													C45.959,16.92,39.577,12.422,33.648,12.422z M43.959,43.467h-6V28.593c0-4.227-3.308-7.797-7.223-7.797
													c-2.512,0-5.358,0-7.099,3.75c-0.359,0.775-0.679,2.632-0.679,3.553v15.368H17V14.422h6.36l0.408,4.044h1.639l0.293-0.473
													c0.667-1.074,2.776-3.572,7.948-3.572c4.966,0,10.311,3.872,10.311,12.374V43.467z"/>
											</g>
										</g>
									</svg>
									<?= $cv_data['contact']['linkedin'] ?>
								</a>
							<?php endif ?>
						</ul>
					</div>
					<div class="passion">
						<h2 class="title">Passion</h2>
						<p><?= $cv_data['passion'] ?></p>
					</div>
				</div>
			</div>
		</div>
	</main>
</body>
</html>