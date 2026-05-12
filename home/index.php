<!DOCTYPE html>
<html lang="en">
<?php

require_once '../src/php/requirements.php';

?>
<head>
	<?= render_header(title: 'home'); ?>
</head>
<body>
	<?= render_navbar() ?>

	<main class="home">
		<section class="title">
			<h1 class="title-text">Rolsa</h1>
			<p class="splash-text">Leaders of a greener future</p>
		</section>

		<section class="news">
			<h2 class="capitalise sub-heading" style="color: black">News</h2>
			<!--
				* placeholder text and items
				* add more in future development
			-->
			<article class="article">
				<h2 class="title">Book now!</h2>
				<p class="content">Book now. To get your seat in our hotly contested slots to get your own consolation with one of our experts.</p>
				<button class="button call-to-action" onclick="window.location.href = '../consultation/';">book</button>
			</article>
			<article class="article">
				<h2 class="title">More of our solar panels</h2>
				<p class="content">This is a photo given to us from a happy customer. They where happy to be making money back off the grid, and saving the planet. 
					<img style="width:10rem; display:block; margin:0.1rem; border-radius:0.3rem" src="../src/assets/images/solar_panels_article.webp" alt="solar panels"></p>
				<button class="button call-to-action" onclick="window.location.href = '../consultation/'">want some?</button>
			</article>
			<article class="article">
				<h2 class="title">Optio saepe eaque praesentium!</h2>
				<p class="content">Suscipit illo perspiciatis doloribus asperiores eius, itaque ut quis iure qui vel, aliquid, eos recusandae rem rerum facere possimus molestias adipisci nesciunt non sed vitae perferendis dolorum fuga. Officiis vitae sed quibusdam aut velit quidem, maiores ex quod ullam deserunt.</p>
				<button class="button call-to-action" onclick="alert('this is a placeholder button and does nothing');">Numquam, doloribus!</button>
			</article>
			<article class="article">
				<h2 class="title">Deserunt fugit dolore possimus?</h2>
				<p class="content">Illum esse ut reprehenderit quam debitis, saepe natus eos ducimus modi quibusdam? Dolores mollitia magnam necessitatibus atque delectus ullam itaque omnis recusandae a, minima consectetur sapiente officiis! Voluptates quasi quae magnam voluptatem minus dolore illo, tempora aut sequi iusto sint.</p>
				<button class="button call-to-action" onclick="alert('this is a placeholder button and does nothing');">Neque, ut.</button>
			</article>
			<article class="article">
				<h2 class="title">Amet, cumque? Recusandae, similique?</h2>
				<p class="content">Possimus tenetur dolor temporibus fugiat aut quisquam provident voluptatem quam dolores rerum, labore facilis accusantium! Aut quis nobis illo incidunt consequatur officiis quo quod totam aliquid, consequuntur odit dolorem veritatis nemo vero aperiam repellendus dolorum eveniet voluptates obcaecati tenetur ea!</p>
				<button class="button call-to-action" onclick="alert('this is a placeholder button and does nothing');">Quia, quos!</button>
			</article>
			<article class="article">
				<h2 class="title">Veniam voluptatem exercitationem aperiam.</h2>
				<p class="content">Dolorum ut nostrum saepe quasi repellendus ex doloremque quod, suscipit consequatur nam debitis, corporis itaque, asperiores sint. Natus exercitationem mollitia, quod magni aspernatur iusto quaerat repudiandae! Quod beatae quos laboriosam molestias explicabo quisquam voluptatem sit, ab, quo perferendis, quam perspiciatis?</p>
				<button class="button call-to-action" onclick="alert('this is a placeholder button and does nothing');">Illo, nemo.</button>
			</article>
		</section>  
	</main>
    <?= render_footer() ?>
</body>
</html>