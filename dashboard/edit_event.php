 <?php
require_once '../includes/auth_check.php';
require_once '../includes/db.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$event_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ? AND user_id = ?");
$stmt->execute([$event_id, $user_id]);
$event = $stmt->fetch();

if (!$event) {
    header('Location: index.php');
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];
    $location = trim($_POST['location']);

    $image_path = $event['image_path'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../assets/images/events/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_ext = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($file_ext, $allowed_extensions)) {
            $file_name = time() . "_" . uniqid() . "." . $file_ext;
            $target_file = $target_dir . $file_name;

            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_path = 'assets/images/events/' . $file_name;
            } else {
                $error = "Failed to upload image.";
            }
        } else {
            $error = "Invalid image format. Allowed: JPG, PNG, GIF.";
        }
    }

    if (empty($error)) {
        $stmt = $pdo->prepare("UPDATE events SET title=?, description=?, event_date=?, event_time=?, location=?, image_path=? WHERE id=? AND user_id=?");
        $stmt->execute([$title, $description, $event_date, $event_time, $location, $image_path, $event_id, $user_id]);
        $success = "Event updated successfully!";

        $stmt = $pdo->prepare("SELECT * FROM events WHERE id=? AND user_id=?");
        $stmt->execute([$event_id, $user_id]);
        $event = $stmt->fetch();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Event - <?php echo htmlspecialchars($event['title']); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
body {
    background: #f8fafb;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #1a202c;
}

.sidebar {
    width: 280px;
    min-height: 100vh;
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    padding: 2rem 1rem;
    position: fixed;
}

.sidebar h4 {
    font-size: 1.8rem;
    font-weight: 800;
    text-align: center;
    background: linear-gradient(135deg, #3b82f6 0%, #06b6d4 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.sidebar .nav-link {
    color: #cbd5e1 !important;
    border-radius: 8px;
    margin-bottom: .5rem;
}

.sidebar .nav-link:hover,
.sidebar .nav-link.active {
    background: rgba(59,130,246,.15);
    color: #3b82f6 !important;
}

.main-content {
    margin-left: 280px;
    padding: 2rem;
}

.card {
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 20px rgba(15,23,42,.08);
}

.form-label {
    font-weight: 600;
    color: #0f172a;
}

.form-control {
    border-radius: 10px;
    padding: .75rem;
}

.current-image-preview {
    width: 100%;
    height: 220px;
    object-fit: cover;
    border-radius: 12px;
    margin-bottom: 1rem;
}

.btn-pill {
    border-radius: 50px;
    font-weight: 600;
}
</style>
</head>

<body>

<div style="display:flex; min-height:100vh">

<!-- Sidebar -->
<aside class="sidebar">
    <h4>MatrixEvent</h4>
    <ul class="nav flex-column mt-4">
        <li><a href="index.php" class="nav-link"><i class="fas fa-th-large me-2"></i> Dashboard</a></li>
        <li><a href="create_event.php" class="nav-link"><i class="fas fa-plus-circle me-2"></i> Create Event</a></li>
        <li><a href="../index.php" class="nav-link"><i class="fas fa-globe me-2"></i> View Site</a></li>
        <li class="mt-4"><a href="../auth/logout.php" class="nav-link text-danger"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
    </ul>
</aside>

<!-- Main -->
<main class="main-content">

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <small class="text-muted">Edit Event</small>
        <h2 class="fw-bold">Update Event Details</h2>
    </div>
    <a href="view_event.php?id=<?php echo $event_id; ?>" class="btn btn-outline-secondary btn-pill">
        <i class="fas fa-arrow-left me-2"></i> Back
    </a>
</div>

<?php if ($success): ?>
<div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<?php if ($error): ?>
<div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<div class="card p-4">
<form method="POST" enctype="multipart/form-data">
<div class="row">

<div class="col-lg-8">
    <div class="mb-4">
        <label class="form-label">Event Title</label>
        <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($event['title']); ?>" required>
    </div>

    <div class="mb-4">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="6" required><?php echo htmlspecialchars($event['description']); ?></textarea>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <label class="form-label">Date</label>
            <input type="date" name="event_date" class="form-control" value="<?php echo $event['event_date']; ?>" required>
        </div>
        <div class="col-md-6 mb-4">
            <label class="form-label">Time</label>
            <input type="time" name="event_time" class="form-control" value="<?php echo $event['event_time']; ?>" required>
        </div>
    </div>

    <div class="mb-4">
        <label class="form-label">Location</label>
        <input type="text" name="location" class="form-control" value="<?php echo htmlspecialchars($event['location']); ?>" required>
    </div>
</div>

<div class="col-lg-4">
    <label class="form-label">Current Image</label>
    <img src="../<?php echo htmlspecialchars($event['image_path']); ?>" class="current-image-preview">

    <label class="form-label">Change Image</label>
    <input type="file" name="image" class="form-control">

    <div class="d-grid gap-2 mt-4">
        <button type="submit" class="btn btn-primary btn-lg btn-pill">
            <i class="fas fa-save me-2"></i> Save Changes
        </button>
        <a href="view_event.php?id=<?php echo $event_id; ?>" class="btn btn-light btn-lg btn-pill">Cancel</a>
    </div>
</div>

</div>
</form>
</div>

</main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
