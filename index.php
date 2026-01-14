 <?php
/**
 * MatrixEvent - Optimized Index Page with Complete 3D Sticker Icons
 * 
 * Improvements:
 * 1. Security: Added XSS protection for dynamic content.
 * 2. Performance: Optimized database query and added error handling.
 * 3. Code Quality: Cleaned up PHP logic and added comments.
 * 4. UX: Improved image handling and accessibility.
 * 5. Animation: Complete 3D sticker-like icons with advanced depth effects.
 */

session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Fetch featured events with error handling
try {
    $stmt = $pdo->query("SELECT id, title, description, image_path, event_date FROM events ORDER BY created_at DESC LIMIT 3");
    $featured_events = $stmt->fetchAll();
} catch (PDOException $e) {
    // Log error and set empty array to prevent page crash
    error_log("Database Error: " . $e->getMessage());
    $featured_events = [];
}

/**
 * Helper function for XSS protection
 */
function h($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MatrixEvent - Modern Event Management</title>
    
    <!-- Preload critical assets -->
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* --- Ultra-Professional Glassmorphism Navbar Styles --- */
        .navbar {
            background-color: transparent !important;
            box-shadow: none !important;
            transition: all 0.4s ease-in-out;
            z-index: 1030;
            padding: 1rem 0;
        }

        .navbar.scrolled {
            background-color: rgba(255, 255, 255, 0.15) !important;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
            padding: 0.6rem 0;
        }

        .navbar-brand,
        .navbar-nav .nav-link {
            transition: all 0.3s ease-in-out;
            color: #fff !important;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .navbar.scrolled .navbar-brand,
        .navbar.scrolled .navbar-nav .nav-link {
            color: #1a1a1a !important;
        }

        .navbar-nav .nav-link {
            position: relative;
            margin: 0 10px;
        }

        .navbar-nav .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -4px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #4e73df;
            transition: width 0.3s ease-in-out;
        }

        .navbar-nav .nav-link:hover::after {
            width: 100%;
        }

        .navbar .btn-primary {
            background: linear-gradient(45deg, #4e73df, #224abe);
            border: none;
            transition: all 0.3s ease;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(78, 115, 223, 0.3);
        }

        .navbar .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(78, 115, 223, 0.4);
        }

        /* Hero Section Adjustment */
        .hero-section {
            margin-top: -88px;
            padding-top: 120px;
            min-height: 100vh;
            background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            display: flex;
            align-items: center;
        }

        /* ========== COMPLETE 3D STICKER ICON STYLES ========== */
        
        /* Enhanced Stat Cards with 3D Effects */
        .stat-card {
            background: #fff;
            border-radius: 20px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border: 1px solid rgba(0,0,0,0.02);
            position: relative;
            z-index: 1;
            perspective: 1200px;
            overflow: visible;
        }

        .stat-card:hover {
            transform: translateY(-15px) rotateX(5deg);
            box-shadow: 0 25px 50px rgba(78, 115, 223, 0.15);
        }

        /* 3D Icon Wrapper with Complete Depth */
        .stat-icon-wrapper {
            position: relative;
            height: 120px;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            perspective: 1500px;
            filter: drop-shadow(0 0 0 rgba(0,0,0,0));
        }

        /* Complete 3D Sticker Icon */
        .stat-icon {
            font-size: 4.5rem;
            color: #4e73df;
            transition: all 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            transform-style: preserve-3d;
            animation: float3D 4s ease-in-out infinite;
            position: relative;
            z-index: 2;
            
            /* Multiple layered shadows for 3D depth */
            filter: 
                drop-shadow(0 2px 4px rgba(78, 115, 223, 0.15))
                drop-shadow(0 4px 8px rgba(78, 115, 223, 0.1))
                drop-shadow(0 8px 16px rgba(78, 115, 223, 0.08));
            
            /* Gradient text effect for depth */
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Sticker-like background with 3D effect */
        .stat-icon::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 110%;
            height: 110%;
            background: radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.4), rgba(255, 255, 255, 0) 70%);
            border-radius: 50%;
            z-index: -1;
            animation: shimmer 3s ease-in-out infinite;
        }

        /* Depth layer - creates 3D sticker effect */
        .stat-icon::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) translateZ(-5px);
            width: 95%;
            height: 95%;
            background: linear-gradient(135deg, rgba(78, 115, 223, 0.1), rgba(34, 74, 190, 0.05));
            border-radius: 50%;
            z-index: -2;
            filter: blur(2px);
        }

        /* Hover state with enhanced 3D effects */
        .stat-card:hover .stat-icon {
            animation: rotate3D 0.8s ease-in-out forwards;
            color: #224abe;
            filter: 
                drop-shadow(0 4px 8px rgba(78, 115, 223, 0.25))
                drop-shadow(0 8px 16px rgba(78, 115, 223, 0.2))
                drop-shadow(0 16px 32px rgba(78, 115, 223, 0.15))
                drop-shadow(0 20px 40px rgba(78, 115, 223, 0.1));
        }

        /* Glow effect on hover */
        .stat-card:hover .stat-icon::before {
            animation: shimmerGlow 0.8s ease-in-out forwards;
        }

        /* Counter Display */
        .counter-display {
            display: inline-block;
            font-variant-numeric: tabular-nums;
            letter-spacing: 0.05em;
        }

        /* ========== KEYFRAME ANIMATIONS ========== */

        /* Floating 3D Animation */
        @keyframes float3D {
            0% {
                transform: translateY(0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg);
            }
            25% {
                transform: translateY(-15px) rotateX(5deg) rotateY(5deg) rotateZ(2deg);
            }
            50% {
                transform: translateY(-20px) rotateX(-3deg) rotateY(-8deg) rotateZ(-2deg);
            }
            75% {
                transform: translateY(-15px) rotateX(5deg) rotateY(5deg) rotateZ(2deg);
            }
            100% {
                transform: translateY(0px) rotateX(0deg) rotateY(0deg) rotateZ(0deg);
            }
        }

        /* Complete 3D Rotation Animation */
        @keyframes rotate3D {
            0% {
                transform: rotateX(0deg) rotateY(0deg) rotateZ(0deg) scale(1);
            }
            25% {
                transform: rotateX(90deg) rotateY(90deg) rotateZ(45deg) scale(1.05);
            }
            50% {
                transform: rotateX(180deg) rotateY(180deg) rotateZ(90deg) scale(1.1);
            }
            75% {
                transform: rotateX(270deg) rotateY(270deg) rotateZ(135deg) scale(1.05);
            }
            100% {
                transform: rotateX(360deg) rotateY(360deg) rotateZ(180deg) scale(1);
            }
        }

        /* Shimmer Animation */
        @keyframes shimmer {
            0%, 100% {
                opacity: 0.3;
                transform: translate(-50%, -50%) scale(1);
            }
            50% {
                opacity: 0.6;
                transform: translate(-50%, -50%) scale(1.1);
            }
        }

        /* Enhanced Shimmer on Hover */
        @keyframes shimmerGlow {
            0% {
                opacity: 0.3;
                transform: translate(-50%, -50%) scale(1);
            }
            50% {
                opacity: 1;
                transform: translate(-50%, -50%) scale(1.3);
            }
            100% {
                opacity: 0.4;
                transform: translate(-50%, -50%) scale(1.1);
            }
        }

        /* Pulse Effect for Counter */
        @keyframes countPulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
        }

        /* Stat Card Label */
        .stat-label {
            font-size: 0.95rem;
            font-weight: 500;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: #666;
        }

        /* Image handling for event cards */
        .event-card img {
            height: 200px;
            object-fit: cover;
        }

        /* Additional 3D perspective for stat section */
        .stats-section-container {
            perspective: 1200px;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <i class="fas fa-layer-group me-2 fs-3"></i>
                <span class="fs-4">MatrixEvent</span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="events.php">Events</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
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

    <!-- Hero Section -->
    <header class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-3 fw-bold mb-4">Create Unforgettable <span class="text-primary">Events</span></h1>
                    <p class="lead mb-5">The most powerful and intuitive platform to host, manage, and discover amazing events worldwide.</p>
                    <div>
                        <a href="events.php" class="btn btn-primary btn-lg px-5 py-3 rounded-pill me-3">Explore Events</a>
                        <a href="auth/register.php" class="btn btn-outline-light btn-lg px-5 py-3 rounded-pill">Host Event</a>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block">
                    <img src="https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?auto=format&fit=crop&w=800&q=80" alt="Hero Image" class="img-fluid rounded-4 shadow-lg">
                </div>
            </div>
        </div>
    </header>

    <!-- Stats Section with Complete 3D Sticker Icons -->
    <section class="py-5 bg-light">
        <div class="container stats-section-container">
            <div class="row text-center">
                <!-- Events Hosted Stat -->
                <div class="col-md-4 mb-4">
                    <div class="stat-card p-5">
                        <div class="stat-icon-wrapper">
                            <i class="fas fa-calendar-check stat-icon"></i>
                        </div>
                        <h2 class="display-4 fw-bold text-primary">
                            <span class="counter-display" data-target="500">0</span><span>+</span>
                        </h2>
                        <p class="stat-label">Events Hosted</p>
                    </div>
                </div>

                <!-- Happy Attendees Stat -->
                <div class="col-md-4 mb-4">
                    <div class="stat-card p-5">
                        <div class="stat-icon-wrapper">
                            <i class="fas fa-users stat-icon"></i>
                        </div>
                        <h2 class="display-4 fw-bold text-primary">
                            <span class="counter-display" data-target="10">0</span><span>k+</span>
                        </h2>
                        <p class="stat-label">Happy Attendees</p>
                    </div>
                </div>

                <!-- Cities Covered Stat -->
                <div class="col-md-4 mb-4">
                    <div class="stat-card p-5">
                        <div class="stat-icon-wrapper">
                            <i class="fas fa-globe stat-icon"></i>
                        </div>
                        <h2 class="display-4 fw-bold text-primary">
                            <span class="counter-display" data-target="50">0</span><span>+</span>
                        </h2>
                        <p class="stat-label">Cities Covered</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Events -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Featured Events</h2>
                <p class="text-muted">Don't miss out on these top-rated experiences</p>
            </div>
            <div class="row">
                <?php if (empty($featured_events)): ?>
                    <div class="col-12 text-center">
                        <p class="text-muted">No events found. Be the first to host one!</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($featured_events as $event): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card event-card h-100 border-0 shadow-sm">
                                <img src="<?php echo h($event['image_path']); ?>" class="card-img-top" alt="<?php echo h($event['title']); ?>" loading="lazy">
                                <div class="card-body">
                                    <h5 class="card-title fw-bold"><?php echo h($event['title']); ?></h5>
                                    <p class="text-muted small mb-2"><i class="fas fa-calendar me-1"></i> <?php echo h($event['event_date']); ?></p>
                                    <p class="card-text"><?php echo h(substr($event['description'], 0, 100)) . '...'; ?></p>
                                    <a href="event/register.php?id=<?php echo (int)$event['id']; ?>" class="btn btn-primary w-100 rounded-pill">View Details</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5 bg-primary text-white text-center">
        <div class="container">
            <h2 class="fw-bold mb-4">Ready to host your own event?</h2>
            <p class="lead mb-4">Join thousands of organizers and start creating today.</p>
            <a href="auth/register.php" class="btn btn-light btn-lg px-5 py-3 rounded-pill fw-bold">Get Started Now</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-5 bg-dark text-white">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h4 class="fw-bold text-primary mb-4">MatrixEvent</h4>
                    <p class="text-muted">Making event management simple, beautiful, and accessible for everyone.</p>
                </div>
                <div class="col-md-2 mb-4">
                    <h5 class="fw-bold mb-4">Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php" class="text-muted text-decoration-none">Home</a></li>
                        <li><a href="events.php" class="text-muted text-decoration-none">Events</a></li>
                        <li><a href="about.php" class="text-muted text-decoration-none">About</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h5 class="fw-bold mb-4">Contact</h5>
                    <p class="text-muted mb-1"><i class="fas fa-envelope me-2"></i> hello@matrixevent.com</p>
                    <p class="text-muted"><i class="fas fa-phone me-2"></i> +1 234 567 890</p>
                </div>
                <div class="col-md-3">
                    <h5 class="fw-bold mb-4">Follow Us</h5>
                    <div class="d-flex">
                        <a href="#" class="text-white me-3 fs-4" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-white me-3 fs-4" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white me-3 fs-4" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-4 bg-secondary">
            <div class="text-center text-muted">
                <p class="mb-0">&copy; <?php echo date('Y'); ?> MatrixEvent. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Navbar Scroll Effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Enhanced Counter Animation with 3D Effects
        document.addEventListener('DOMContentLoaded', () => {
            const counters = document.querySelectorAll('.counter-display');
            const duration = 2500;
            let hasAnimated = false;

            const animateCounter = (counter) => {
                const target = +counter.getAttribute('data-target');
                const startTime = performance.now();

                const update = (currentTime) => {
                    const elapsed = currentTime - startTime;
                    const progress = Math.min(elapsed / duration, 1);
                    
                    // Ease-out-cubic easing
                    const easeProgress = 1 - Math.pow(1 - progress, 3);
                    const currentValue = Math.floor(easeProgress * target);
                    
                    counter.innerText = currentValue;

                    if (progress < 1) {
                        requestAnimationFrame(update);
                    } else {
                        counter.innerText = target;
                    }
                };
                requestAnimationFrame(update);
            };

            // Use Intersection Observer to trigger animation
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && !hasAnimated) {
                        hasAnimated = true;
                        counters.forEach((counter, index) => {
                            setTimeout(() => {
                                animateCounter(counter);
                            }, index * 150);
                        });
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.3 });

            const statsSection = document.querySelector('.bg-light');
            if (statsSection) {
                observer.observe(statsSection);
            }
        });

        // Add mouse tracking for 3D perspective effect
        document.querySelectorAll('.stat-icon-wrapper').forEach(wrapper => {
            wrapper.addEventListener('mousemove', (e) => {
                const rect = wrapper.getBoundingClientRect();
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                const mouseX = e.clientX - rect.left;
                const mouseY = e.clientY - rect.top;
                
                const rotateX = (mouseY - centerY) / 10;
                const rotateY = (centerX - mouseX) / 10;
                
                const icon = wrapper.querySelector('.stat-icon');
                icon.style.transform = `rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
            });

            wrapper.addEventListener('mouseleave', () => {
                const icon = wrapper.querySelector('.stat-icon');
                icon.style.transform = 'rotateX(0) rotateY(0)';
            });
        });
    </script>
</body>
</html>