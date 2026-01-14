<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/functions.php';

$stmt = $pdo->query("SELECT * FROM events ORDER BY event_date ASC");
$events = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Events - MatrixEvent</title>
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
                    <li class="nav-item"><a class="nav-link active" href="events.php"><i class="fas fa-calendar-alt me-1"></i> Events</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php"><i class="fas fa-info-circle me-1"></i> About</a></li>
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

    <section class="py-5">
        <div class="container">
            <div class="row mb-5">
                <div class="col-md-8">
                    <h2 class="fw-bold">Discover Events</h2>
                    <p class="text-muted">Browse through all upcoming events and find your next experience.</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="input-group">
                        <input type="text" class="form-control rounded-pill-start" placeholder="Search events...">
                        <button class="btn btn-primary rounded-pill-end"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </div>

            <div class="row">
                <?php if (empty($events)): ?>
                    <div class="col-12 text-center py-5">
                        <div class="mb-4 fs-1 text-muted"><i class="fas fa-calendar-times"></i></div>
                        <h3>No events found</h3>
                        <p class="text-muted">Check back later or host your own event!</p>
                        <a href="auth/register.php" class="btn btn-primary rounded-pill px-4">Host an Event</a>
                    </div>
                <?php else: ?>
                    <?php foreach ($events as $event): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card event-card h-100 border-0 shadow-sm">
                                <div class="position-relative">
                                    <img src="<?php echo $event['image_path']; ?>" class="card-img-top" alt="<?php echo $event['title']; ?>">
                                    <div class="event-date-badge">
                                        <span class="day"><?php echo date('d', strtotime($event['event_date'])); ?></span>
                                        <span class="month"><?php echo date('M', strtotime($event['event_date'])); ?></span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title fw-bold"><?php echo $event['title']; ?></h5>
                                    <div class="d-flex text-muted small mb-3">
                                        <span class="me-3"><i class="fas fa-clock me-1"></i> <?php echo date('h:i A', strtotime($event['event_time'])); ?></span>
                                        <span><i class="fas fa-map-marker-alt me-1"></i> <?php echo $event['location']; ?></span>
                                    </div>
                                    <p class="card-text text-muted"><?php echo substr($event['description'], 0, 120) . '...'; ?></p>
                                    <a href="event/register.php?id=<?php echo $event['id']; ?>" class="btn btn-outline-primary w-100 rounded-pill">View Details</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <footer class="py-5 bg-dark text-white mt-auto">
        <div class="container text-center">
            <p class="mb-0">&copy; 2026 MatrixEvent. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/ui.js"></script>
</body>
</html>
