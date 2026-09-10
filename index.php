<?php
require_once 'config/database.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="The Glam Room is a modern beauty salon offering considered hair, skin and beauty treatments.">
	<title>The Glam Room | Beauty, thoughtfully done</title>

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
	<a class="skip-link" href="#main-content">Skip to main content</a>

	<header class="site-header" id="home">
		<nav class="navbar navbar-expand-lg" aria-label="Primary navigation">
			<div class="container">
				<a class="navbar-brand" href="#home" aria-label="The Glam Room home">
					<span class="brand-mark">G</span>
					<span>The Glam <em>Room</em></span>
				</a>

				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main-navigation" aria-controls="main-navigation" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>

				<div class="collapse navbar-collapse" id="main-navigation">
					<ul class="navbar-nav ms-auto align-items-lg-center gap-lg-4">
						<li class="nav-item"><a class="nav-link active" href="#home">Home</a></li>
						<li class="nav-item"><a class="nav-link" href="#about">About</a></li>
						<li class="nav-item"><a class="nav-link" href="services.php">Services</a></li>
						<li class="nav-item"><a class="nav-link nav-link-cta" href="contact.php">Book an appointment <span aria-hidden="true">↗</span></a></li>
					</ul>
				</div>
			</div>
		</nav>

		<div class="hero-section">
			<div class="container hero-content">
				<div class="hero-copy">
					<p class="eyebrow">Beauty, thoughtfully done</p>
					<h1>Your glow,<br><em>your ritual.</em></h1>
					<p class="hero-intro">A calm, considered beauty experience designed around the way you want to feel.</p>
					<a class="btn btn-primary-custom" href="services.php">Explore our services <span aria-hidden="true">↗</span></a>
				</div>
				<div class="hero-note" aria-label="Salon opening information">
					<span class="hero-note-line"></span>
					<p>Make time for<br><strong>feeling good.</strong></p>
				</div>
			</div>
			<div class="hero-image-wrap">
				<img src="https://images.unsplash.com/photo-1562322140-8baeececf9c?auto=format&amp;fit=crop&amp;w=1400&amp;q=85" alt="Beauty therapist styling a client's hair in a bright salon" class="hero-image" onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?auto=format&amp;fit=crop&amp;w=1400&amp;q=85';">
				<div class="hero-stamp" aria-hidden="true">G<br><span>EST. 2018</span></div>
			</div>
		</div>
	</header>

	<main id="main-content">
		<section class="about-section section-spacing" id="about">
			<div class="container">
				<div class="row align-items-center g-5">
					<div class="col-lg-5">
						<p class="eyebrow">A little about us</p>
						<h2>Beauty is a feeling.</h2>
					</div>
					<div class="col-lg-6 offset-lg-1">
						<p class="section-lead">The Glam Room is a modern salon for your most natural, confident self. We pair thoughtful expertise with a warm, unhurried atmosphere so every visit feels like time well spent.</p>
						<a class="text-link" href="#contact">Meet the team <span aria-hidden="true">→</span></a>
					</div>
				</div>
				<div class="about-details row g-4">
					<div class="col-md-4"><span class="detail-number">01</span><h3>Considered</h3><p>Personalised treatments, never a one-size-fits-all approach.</p></div>
					<div class="col-md-4"><span class="detail-number">02</span><h3>Knowledgeable</h3><p>Expert hands and honest advice you can come back to.</p></div>
					<div class="col-md-4"><span class="detail-number">03</span><h3>Comfortable</h3><p>A welcoming space where you can take a proper breath.</p></div>
				</div>
			</div>
		</section>

		<section class="services-section section-spacing" id="services">
			<div class="container">
				<div class="section-heading d-flex flex-wrap justify-content-between align-items-end gap-4">
					<div><p class="eyebrow">Our favourites</p><h2>Made for your<br><em>everyday glow.</em></h2></div>
					<p class="heading-note">Simple rituals. Beautiful results.<br>Always tailored to you.</p>
				</div>
				<div class="services-grid">
					<article class="service-card service-card-featured">
						<img src="https://images.unsplash.com/photo-1516975080664-ed2fc6a32937?auto=format&amp;fit=crop&amp;w=900&amp;q=80" alt="Stylist preparing a client's hair for a cut" loading="lazy">
						<div class="service-card-content"><span>01 / Hair</span><h3>The signature cut</h3><p>A shape that works with your life, not against it.</p><a href="contact.php" aria-label="Book the signature cut">Book now <span aria-hidden="true">↗</span></a></div>
					</article>
					<article class="service-card">
						<div class="service-icon" aria-hidden="true">✦</div><span>02 / Skin</span><h3>The reset facial</h3><p>A deeply restorative treatment for skin that feels like itself again.</p><a href="contact.php" aria-label="Book the reset facial">Book now <span aria-hidden="true">↗</span></a>
					</article>
					<article class="service-card service-card-dark">
						<div class="service-icon" aria-hidden="true">◌</div><span>03 / Beauty</span><h3>Soft glam</h3><p>Polished, effortless makeup for the moments worth remembering.</p><a href="contact.php" aria-label="Book soft glam makeup">Book now <span aria-hidden="true">↗</span></a>
					</article>
				</div>
			</div>
		</section>

		<section class="quote-section">
			<div class="container"><p class="eyebrow">The Glam Room promise</p><blockquote>“Leave feeling more<br><em>like yourself.</em>”</blockquote><span class="quote-mark" aria-hidden="true">✦</span></div>
		</section>

		<section class="contact-section section-spacing" id="contact">
			<div class="container"><div class="contact-panel"><div><p class="eyebrow">Come say hello</p><h2>Ready when<br><em>you are.</em></h2></div><div class="contact-details"><p>12 Willow Lane<br>Melbourne, VIC 3000</p><p><a href="tel:+61390001234">(03) 9000 1234</a><br><a href="mailto:hello@theglamroom.com.au">hello@theglamroom.com.au</a></p><a class="btn btn-light-custom" href="contact.php">Make an enquiry <span aria-hidden="true">↗</span></a></div></div></div>
		</section>
	</main>

	<footer class="site-footer"><div class="container d-flex flex-wrap justify-content-between align-items-center gap-3"><a class="footer-brand" href="#home">The Glam <em>Room</em></a><p>© <?php echo date('Y'); ?> The Glam Room. Beauty, thoughtfully done.</p><a href="#home" class="back-to-top">Back to top ↑</a></div></footer>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
	<script src="assets/js/main.js"></script>
</body>
</html>