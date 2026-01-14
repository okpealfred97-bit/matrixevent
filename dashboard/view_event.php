<?php
require_once '../includes/auth_check.php';
require_once '../includes/db.php';

if (!isset($_GET['id'])) {
    redirect('index.php');
}

$event_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM events WHERE id = ? AND user_id = ?");
$stmt->execute([$event_id, $user_id]);
$event = $stmt->fetch();

if (!$event) {
    redirect('index.php');
}

$stmt = $pdo->prepare("SELECT * FROM registrations WHERE event_id = ? ORDER BY registered_at DESC");
$stmt->execute([$event_id]);
$registrations = $stmt->fetchAll();

$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$base_url = $protocol . "://" . $host . dirname(dirname($_SERVER['PHP_SELF']));
$invite_link = $base_url . "/event/register.php?id=" . $event_id;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($event['title']); ?> - MatrixEvent</title>

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
    transition: 0.3s;
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

.event-header {
    background: #ffffff;
    padding: 2rem;
    border-radius: 16px;
    margin-bottom: 2rem;
}

.event-image {
    width: 100%;
    height: 220px;
    object-fit: cover;
    border-radius: 12px;
}

.stat-card::before {
    content:'';
    display:block;
    height:4px;
    border-radius:4px 4px 0 0;
    background: linear-gradient(90deg,#3b82f6,#06b6d4);
}

.btn-pill {
    border-radius: 50px;
    font-weight: 600;
}

.table thead {
    background: #f1f5f9;
}

.badge-active {
    background: linear-gradient(135deg,#10b981,#059669);
    border-radius: 20px;
    padding: .35rem .75rem;
}
</style>
</head>

<body>

<div style="display:flex; min-height:100vh">

<!-- Sidebar -->
<aside class="sidebar">
    <h4>MatrixEvent</h4>
    <ul class="nav flex-column mt-4">
        <li><a href="index.php" class="nav-link active"><i class="fas fa-th-large me-2"></i> Dashboard</a></li>
        <li><a href="create_event.php" class="nav-link"><i class="fas fa-plus-circle me-2"></i> Create Event</a></li>
        <li><a href="../index.php" class="nav-link"><i class="fas fa-globe me-2"></i> View Site</a></li>
        <li class="mt-4"><a href="../auth/logout.php" class="nav-link text-danger"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
    </ul>
</aside>

<!-- Main -->
<main class="main-content">

<div class="event-header d-flex justify-content-between align-items-center">
    <div>
        <small class="text-muted">Event Details</small>
        <h2 class="fw-bold"><?php echo htmlspecialchars($event['title']); ?></h2>
    </div>
    <div class="d-flex gap-2">
        <a href="../event/register.php?id=<?php echo $event_id; ?>" target="_blank" class="btn btn-light btn-pill">
            <i class="fas fa-eye me-1"></i> Preview
        </a>
        <a href="edit_event.php?id=<?php echo $event_id; ?>" class="btn btn-primary btn-pill">
            <i class="fas fa-edit me-1"></i> Edit
        </a>
    </div>
</div>

<div class="row">

<!-- Left -->
<div class="col-lg-4">

<div class="card stat-card mb-4 p-4">
    <h6 class="text-muted">Total Registrations</h6>
    <h2 class="fw-bold"><?php echo count($registrations); ?></h2>
</div>

<div class="card mb-4">
    <img src="../<?php echo htmlspecialchars($event['image_path']); ?>" class="event-image">
    <div class="card-body">
        <h5 class="fw-bold mb-3">Event Info</h5>

        <p><i class="fas fa-calendar text-primary me-2"></i>
            <?php echo date('F d, Y', strtotime($event['event_date'])); ?>
        </p>

        <p><i class="fas fa-clock text-primary me-2"></i>
            <?php echo date('h:i A', strtotime($event['event_time'])); ?>
        </p>

        <p><i class="fas fa-map-marker-alt text-primary me-2"></i>
            <?php echo htmlspecialchars($event['location']); ?>
        </p>
    </div>
</div>

<div class="card p-4">
    <h5 class="fw-bold mb-2">Invite Link</h5>
    <input type="text" class="form-control mb-2" value="<?php echo htmlspecialchars($invite_link); ?>" id="inviteLink" readonly>
    <button class="btn btn-primary w-100" onclick="copyInviteLink()">Copy Link</button>
    <small id="copyStatus" class="text-success d-none mt-2">Copied!</small>
</div>

</div>

<!-- Right -->
<div class="col-lg-8">
<div class="card">
<div class="card-header bg-white">
    <h5 class="fw-bold mb-0">Registrations</h5>
</div>
<div class="card-body p-0">
<table class="table mb-0">
<thead>
<tr>
<th>Name</th><th>Email</th><th>Phone</th><th>Date</th>
</tr>
</thead>
<tbody>
<?php if (empty($registrations)): ?>
<tr><td colspan="4" class="text-center text-muted p-4">No registrations yet</td></tr>
<?php else: foreach ($registrations as $r): ?>
<tr>
<td><?php echo htmlspecialchars($r['full_name']); ?></td>
<td><?php echo htmlspecialchars($r['email']); ?></td>
<td><?php echo htmlspecialchars($r['phone']); ?></td>
<td><?php echo date('M d, Y', strtotime($r['registered_at'])); ?></td>
</tr>
<?php endforeach; endif; ?>
</tbody>
</table>
</div>
</div>
</div>

</div>

</main>
</div>

<script>
function copyInviteLink(){
    const i=document.getElementById("inviteLink");
    navigator.clipboard.writeText(i.value);
    document.getElementById("copyStatus").classList.remove("d-none");
    setTimeout(()=>{document.getElementById("copyStatus").classList.add("d-none")},2000);
}
</script>

</body>
</html>
