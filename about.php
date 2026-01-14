<?php
session_start();
require_once 'includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - MatrixEvent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="index.php">
                <i class="fas fa-layer-group me-2"></i>MatrixEvent
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="index.php"><i class="fas fa-home me-1"></i> Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="events.php"><i class="fas fa-calendar-alt me-1"></i> Events</a></li>
                    <li class="nav-item"><a class="nav-link active" href="about.php"><i class="fas fa-info-circle me-1"></i> About</a></li>
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item"><a class="btn btn-primary ms-lg-3 px-4 rounded-pill" href="dashboard/index.php">Dashboard</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="auth/login.php">Login</a></li>
                        <li class="nav-item"><a class="btn btn-primary ms-lg-3 px-4 rounded-pill" href="auth/register.php">Get Started</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <header class="py-5 bg-light">
        <div class="container text-center py-5">
            <h1 class="display-4 fw-bold mb-3">Our Story</h1>
            <p class="lead text-muted mx-auto" style="max-width: 700px;">We believe that every event has the potential to change lives. Our mission is to provide the tools to make that happen.</p>
        </div>
    </header>

    <!-- Content -->
    <section class="py-5">
        <div class="container">
            <div class="row align-items-center mb-5">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <img src="https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&w=800&q=80" alt="About Us" class="img-fluid rounded-4 shadow-3d">
                </div>
                <div class="col-lg-6 ps-lg-5">
                    <h2 class="fw-bold mb-4">Why MatrixEvent?</h2>
                    <p class="text-muted mb-4">MatrixEvent was born out of a simple need: to make event organization less stressful and more impactful. We've built a platform that combines powerful backend logic with a beautiful, intuitive interface.</p>
                    <div class="row g-4">
                        <div class="col-sm-6">
                            <div class="card border-0 shadow-sm p-3 h-100 hover-3d">
                                <div class="text-primary mb-2 fs-3"><i class="fas fa-bolt"></i></div>
                                <h5 class="fw-bold">Fast & Reliable</h5>
                                <p class="small text-muted mb-0">Built with core technologies for maximum performance.</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card border-0 shadow-sm p-3 h-100 hover-3d">
                                <div class="text-primary mb-2 fs-3"><i class="fas fa-shield-alt"></i></div>
                                <h5 class="fw-bold">Secure</h5>
                                <p class="small text-muted mb-0">Your data is protected with industry-standard security.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Values -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Our Core Values</h2>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm p-4 text-center h-100 animate-card">
                        <div class="icon-sticker mx-auto mb-4"><i class="fas fa-heart"></i></div>
                        <h4 class="fw-bold">Passion</h4>
                        <p class="text-muted">We are passionate about creating experiences that matter.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm p-4 text-center h-100 animate-card">
                        <div class="icon-sticker mx-auto mb-4"><i class="fas fa-lightbulb"></i></div>
                        <h4 class="fw-bold">Innovation</h4>
                        <p class="text-muted">Constantly evolving to provide the best tools for our users.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm p-4 text-center h-100 animate-card">
                        <div class="icon-sticker mx-auto mb-4"><i class="fas fa-users"></i></div>
                        <h4 class="fw-bold">Community</h4>
                        <p class="text-muted">Building a global community of event enthusiasts.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-5 bg-dark text-white">
        <div class="container text-center">
            <p class="mb-0">&copy; 2026 MatrixEvent. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/ui.js"></script>
</body>
</html>
