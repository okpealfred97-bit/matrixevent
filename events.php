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
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link active" href="events.php">Events</a></li>
                <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                <?php if (isLoggedIn()): ?>
                    <li class="nav-item"><a class="btn btn-primary ms-lg-3 rounded-pill" href="dashboard/index.php">Dashboard</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="auth/login.php">Login</a></li>
                    <li class="nav-item"><a class="btn btn-primary ms-lg-3 rounded-pill" href="auth/register.php">Get Started</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<section class="py-5">
    <div class="container">

        <!-- Search -->
        <div class="row mb-5">
            <div class="col-md-8">
                <h2 class="fw-bold">Discover Events</h2>
                <p class="text-muted">Browse through all upcoming events.</p>
            </div>
            <div class="col-md-4 text-md-end">
                <div class="input-group">
                    <input type="text" id="searchInput" class="form-control rounded-pill-start" placeholder="Search events...">
                    <button class="btn btn-primary rounded-pill-end" id="searchBtn">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Events -->
        <div class="row" id="eventsContainer">

            <?php if (empty($events)): ?>
                <div class="col-12 text-center py-5">
                    <h3>No events found</h3>
                </div>
            <?php else: ?>

                <?php foreach ($events as $event): ?>
                    <div class="col-md-4 mb-4 event-item"
                         data-title="<?php echo strtolower($event['title']); ?>"
                         data-location="<?php echo strtolower($event['location']); ?>"
                         data-description="<?php echo strtolower($event['description']); ?>">

                        <div class="card h-100 shadow-sm border-0">
                            <img src="<?php echo $event['image_path']; ?>" class="card-img-top">

                            <div class="card-body">
                                <h5 class="fw-bold"><?php echo $event['title']; ?></h5>

                                <small class="text-muted">
                                    <i class="fas fa-map-marker-alt"></i> <?php echo $event['location']; ?>
                                </small>

                                <p class="mt-2 text-muted">
                                    <?php echo substr($event['description'], 0, 120) . '...'; ?>
                                </p>

                                <a href="event/register.php?id=<?php echo $event['id']; ?>"
                                   class="btn btn-outline-primary w-100 rounded-pill">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

            <?php endif; ?>
        </div>
    </div>
</section>

<footer class="bg-dark text-white py-4 text-center">
    &copy; 2026 MatrixEvent
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- SEARCH SCRIPT -->
<script>
document.getElementById("searchBtn").addEventListener("click", filterEvents);
document.getElementById("searchInput").addEventListener("keyup", filterEvents);

function filterEvents() {
    let keyword = document.getElementById("searchInput").value.toLowerCase();
    let events = document.querySelectorAll(".event-item");

    events.forEach(event => {
        let title = event.dataset.title;
        let location = event.dataset.location;
        let description = event.dataset.description;

        if (
            title.includes(keyword) ||
            location.includes(keyword) ||
            description.includes(keyword)
        ) {
            event.style.display = "block";
        } else {
            event.style.display = "none";
        }
    });
}
</script>

</body>
</html>
