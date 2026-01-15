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
    $gender = sanitize($_POST['gender']); // ✅ ADDED

    try {
        $stmt = $pdo->prepare(
            "INSERT INTO registrations (event_id, full_name, email, phone, gender) 
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([$event_id, $full_name, $email, $phone, $gender]);
        $success = "🎉 Registration successful! We’re excited to have you.";
    } catch (PDOException $e) {
        $error = "Registration failed. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register for <?php echo $event['title']; ?> - MatrixEvent</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Custom Styling -->
    <style>
        body {
            background: linear-gradient(135deg, #eef2ff, #f8fafc);
            font-family: 'Segoe UI', sans-serif;
        }

        .navbar {
            background: #ffffff;
        }

        .event-card {
            border-radius: 24px;
            overflow: hidden;
            background: #ffffff;
        }

        .event-title {
            color: #1e3a8a;
        }

        .event-meta i {
            color: #6366f1;
        }

        .section-title {
            color: #111827;
        }

        .form-control {
            border-radius: 14px;
            padding: 12px 14px;
            border: 1px solid #d1d5db;
        }

        .form-control:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 0.15rem rgba(99,102,241,.25);
        }

        .btn-gradient {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            border: none;
            color: #fff;
            font-weight: 600;
            letter-spacing: .3px;
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(79,70,229,.35);
        }

        .success-box {
            background: linear-gradient(135deg, #ecfdf5, #d1fae5);
            border-left: 5px solid #10b981;
            border-radius: 14px;
            padding: 16px;
        }

        footer {
            background: #0f172a;
            color: #c7d2fe;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary" href="../index.php">
            <i class="fas fa-layer-group me-2"></i>MatrixEvent
        </a>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link" href="../index.php">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="../events.php">Events</a></li>
        </ul>
    </div>
</nav>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card event-card shadow-lg">
                    <div class="row g-0">

                        <div class="col-md-5">
                            <img src="../<?php echo $event['image_path']; ?>" 
                                 class="img-fluid h-100 w-100"
                                 style="object-fit:cover;">
                        </div>

                        <div class="col-md-7 p-5">
                            <h2 class="event-title fw-bold mb-3">
                                <?php echo $event['title']; ?>
                            </h2>

                            <div class="event-meta mb-4 text-muted">
                                <p><i class="fas fa-calendar me-2"></i>
                                    <?php echo date('F d, Y', strtotime($event['event_date'])); ?>
                                </p>
                                <p><i class="fas fa-clock me-2"></i>
                                    <?php echo date('h:i A', strtotime($event['event_time'])); ?>
                                </p>
                                <p><i class="fas fa-location-dot me-2"></i>
                                    <?php echo $event['location']; ?>
                                </p>
                            </div>

                            <h5 class="section-title fw-bold">About this event</h5>
                            <p class="text-muted mb-4">
                                <?php echo nl2br($event['description']); ?>
                            </p>

                            <?php if ($success): ?>
                                <div class="success-box mb-4">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <?php echo $success; ?>
                                </div>
                                <a href="../events.php" class="btn btn-gradient rounded-pill px-4">
                                    Browse More Events
                                </a>
                            <?php else: ?>

                                <h5 class="section-title fw-bold mb-3">Register Now</h5>

                                <?php if ($error): ?>
                                    <div class="alert alert-danger"><?php echo $error; ?></div>
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

                                    <div class="mb-3">
                                        <label class="form-label">Phone Number</label>
                                        <input type="tel" name="phone" class="form-control" required>
                                    </div>

                                    <!-- ✅ ONLY NEW FIELD -->
                                    <div class="mb-4">
                                        <label class="form-label">Gender</label>
                                        <select name="gender" class="form-control" required>
                                            <option value="">Select gender</option>
                                            <option>Male</option>
                                            <option>Female</option>
                                            <option>Non-binary</option>
                                            <option>Prefer not to say</option>
                                        </select>
                                    </div>

                                    <button type="submit"
                                            class="btn btn-gradient btn-lg w-100 rounded-pill">
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
</section>

<footer class="py-4 text-center">
    <p class="mb-0">&copy; 2026 MatrixEvent. All rights reserved.</p>
</footer>

</body>
</html>
