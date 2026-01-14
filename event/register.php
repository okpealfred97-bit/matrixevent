 <?php
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

/* ===== FIX START (DO NOT REMOVE) ===== */
if (isset($_GET['id'])) {
    $event_id = $_GET['id'];
} elseif (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];
} else {
    redirect('../events.php');
}
/* ===== FIX END ===== */

// Fetch event details
$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$stmt->execute([$event_id]);
$event = $stmt->fetch();

if (!$event) {
    redirect('../events.php');
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = sanitize($_POST['full_name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);

    try {
        $stmt = $pdo->prepare(
            "INSERT INTO registrations (event_id, full_name, email, phone) 
             VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([$event_id, $full_name, $email, $phone]);
        $success = "Registration successful! We'll see you there.";
    } catch (PDOException $e) {
        $error = "Registration failed. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register for <?php echo $event['title']; ?> - MatrixEvent</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="../index.php">
            <i class="fas fa-layer-group me-2"></i>MatrixEvent
        </a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="../index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="../events.php">Events</a></li>
            </ul>
        </div>
    </div>
</nav>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-lg overflow-hidden rounded-4">
                    <div class="row g-0">
                        <div class="col-md-5">
                            <img src="../<?php echo $event['image_path']; ?>" 
                                 class="img-fluid h-100 w-100" 
                                 style="object-fit: cover;" 
                                 alt="Event Image">
                        </div>

                        <div class="col-md-7">
                            <div class="card-body p-5">
                                <h2 class="fw-bold mb-4"><?php echo $event['title']; ?></h2>

                                <div class="mb-4">
                                    <p class="text-muted mb-2">
                                        <i class="fas fa-calendar text-primary me-2"></i>
                                        <?php echo date('F d, Y', strtotime($event['event_date'])); ?>
                                    </p>
                                    <p class="text-muted mb-2">
                                        <i class="fas fa-clock text-primary me-2"></i>
                                        <?php echo date('h:i A', strtotime($event['event_time'])); ?>
                                    </p>
                                    <p class="text-muted mb-0">
                                        <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                        <?php echo $event['location']; ?>
                                    </p>
                                </div>

                                <hr class="my-4">

                                <h5 class="fw-bold mb-3">About this event</h5>
                                <p class="text-muted mb-4">
                                    <?php echo nl2br($event['description']); ?>
                                </p>

                                <?php if ($success): ?>
                                    <div class="alert alert-success py-3">
                                        <i class="fas fa-check-circle me-2"></i>
                                        <?php echo $success; ?>
                                    </div>
                                    <a href="../events.php" class="btn btn-primary rounded-pill px-4">
                                        Back to Events
                                    </a>
                                <?php else: ?>

                                    <h5 class="fw-bold mb-3">Register Now</h5>

                                    <?php if ($error): ?>
                                        <div class="alert alert-danger">
                                            <?php echo $error; ?>
                                        </div>
                                    <?php endif; ?>

                                    <form method="POST">
                                        <div class="mb-3">
                                            <label class="form-label">Full Name</label>
                                            <input type="text" name="full_name" class="form-control" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Email Address</label>
                                            <input type="email" name="email" class="form-control" required>
                                        </div>

                                        <div class="mb-4">
                                            <label class="form-label">Phone Number</label>
                                            <input type="tel" name="phone" class="form-control" required>
                                        </div>

                                        <button type="submit"
                                                class="btn btn-primary btn-lg w-100 rounded-pill shadow-3d">
                                            Confirm Registration
                                        </button>
                                    </form>

                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="py-5 bg-dark text-white mt-5">
    <div class="container text-center">
        <p class="mb-0">&copy; 2026 MatrixEvent. All rights reserved.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/ui.js"></script>
</body>
</html>
