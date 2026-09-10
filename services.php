<?php
require_once 'config/database.php';

$services = [];
$error_message = '';

$statement = $conn->prepare('SELECT id, service_name, category, description, price, duration FROM services ORDER BY category, service_name');

if ($statement === false) {
    $error_message = 'We are unable to load our services right now. Please try again later.';
} elseif (!$statement->execute()) {
    $error_message = 'We are unable to load our services right now. Please try again later.';
} else {
    $statement->bind_result($id, $service_name, $category, $description, $price, $duration);

    while ($statement->fetch()) {
        $services[] = [
            'id' => $id,
            'service_name' => $service_name,
            'category' => $category,
            'description' => $description,
            'price' => $price,
            'duration' => $duration,
        ];
    }
    $statement->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Explore The Glam Room's hair, skin and beauty services.">
    <title>Services | The Glam Room</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/services.css">
</head>
<body>
    <a class="skip-link" href="#main-content">Skip to main content</a>
    <header class="services-header">
        <nav class="navbar navbar-expand-lg" aria-label="Primary navigation">
            <div class="container">
                <a class="navbar-brand" href="index.php" aria-label="The Glam Room home"><span class="brand-mark">G</span><span>The Glam <em>Room</em></span></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main-navigation" aria-controls="main-navigation" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="main-navigation">
                    <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-4">
                        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="index.php#about">About</a></li>
                        <li class="nav-item"><a class="nav-link active" href="services.php" aria-current="page">Services</a></li>
                        <li class="nav-item"><a class="nav-link nav-link-cta" href="contact.php">Book an appointment <span aria-hidden="true">↗</span></a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main id="main-content">
        <section class="services-intro section-spacing" aria-labelledby="services-title">
            <div class="container"><p class="eyebrow">Our services</p><h1 id="services-title">Made for your<br><em>everyday glow.</em></h1><p class="section-lead">Thoughtful treatments, expert hands and a little time set aside just for you.</p></div>
        </section>

        <section class="service-list-section" aria-labelledby="service-list-title">
            <div class="container">
                <h2 class="visually-hidden" id="service-list-title">Available salon services</h2>
                <?php if ($error_message !== ''): ?>
                    <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?></div>
                <?php elseif (count($services) === 0): ?>
                    <div class="empty-services"><h2>Our service menu is being refreshed.</h2><p>Please check back soon or contact us to discuss your appointment.</p></div>
                <?php else: ?>
                    <div class="public-services-grid">
                        <?php foreach ($services as $service): ?>
                            <article class="public-service-card">
                                <div class="service-card-top"><span><?php echo htmlspecialchars($service['category'], ENT_QUOTES, 'UTF-8'); ?></span><span aria-hidden="true">✦</span></div>
                                <h2><?php echo htmlspecialchars($service['service_name'], ENT_QUOTES, 'UTF-8'); ?></h2>
                                <p><?php echo nl2br(htmlspecialchars($service['description'], ENT_QUOTES, 'UTF-8')); ?></p>
                                <div class="service-meta"><strong>$<?php echo number_format((float) $service['price'], 2); ?></strong><span><?php echo htmlspecialchars($service['duration'], ENT_QUOTES, 'UTF-8'); ?></span></div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <section class="services-cta" aria-labelledby="services-cta-title"><div class="container"><div class="services-cta-panel"><div><p class="eyebrow">Your time, well spent</p><h2 id="services-cta-title">Ready when<br><em>you are.</em></h2></div><a class="btn btn-light-custom" href="contact.php">Make an enquiry <span aria-hidden="true">↗</span></a></div></div></section>
    </main>
    <footer class="site-footer"><div class="container d-flex flex-wrap justify-content-between align-items-center gap-3"><a class="footer-brand" href="index.php">The Glam <em>Room</em></a><p>© <?php echo date('Y'); ?> The Glam Room. Beauty, thoughtfully done.</p><a href="#main-content" class="back-to-top">Back to top ↑</a></div></footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>