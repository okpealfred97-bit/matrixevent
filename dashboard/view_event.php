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

$stmt = $pdo->prepare("
    SELECT full_name, email, phone, gender, registered_at
    FROM registrations
    WHERE event_id = ?
    ORDER BY registered_at DESC
");
$stmt->execute([$event_id]);
$registrations = $stmt->fetchAll();

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
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
body{background:#f8fafb}
.sidebar{width:280px;min-height:100vh;background:linear-gradient(135deg,#0f172a,#1e293b);padding:2rem 1rem;position:fixed}
.sidebar h4{text-align:center;font-weight:800;background:linear-gradient(135deg,#3b82f6,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
.sidebar .nav-link{color:#cbd5e1!important;border-radius:8px;margin-bottom:.5rem}
.sidebar .nav-link.active,.sidebar .nav-link:hover{background:rgba(59,130,246,.15);color:#3b82f6!important}
.main-content{margin-left:280px;padding:2rem}
.event-image{width:100%;height:220px;object-fit:cover;border-radius:12px}
.badge-gender{border-radius:20px;padding:.35rem .75rem;font-size:.8rem}
</style>
</head>

<body>

<div style="display:flex">

<!-- SIDEBAR -->
<aside class="sidebar">
    <h4>MatrixEvent</h4>
    <ul class="nav flex-column mt-4">
        <li><a href="index.php" class="nav-link active"><i class="fas fa-th-large me-2"></i> Dashboard</a></li>
        <li><a href="create_event.php" class="nav-link"><i class="fas fa-plus-circle me-2"></i> Create Event</a></li>
        <li><a href="../index.php" class="nav-link"><i class="fas fa-globe me-2"></i> View Site</a></li>
        <li class="mt-4"><a href="../auth/logout.php" class="nav-link text-danger"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
    </ul>
</aside>

<!-- MAIN -->
<main class="main-content">

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <small class="text-muted">Event Details</small>
        <h2 class="fw-bold"><?php echo htmlspecialchars($event['title']); ?></h2>
    </div>
    <div class="d-flex gap-2">
        <a href="../event/register.php?id=<?php echo $event_id; ?>" target="_blank" class="btn btn-light">
            <i class="fas fa-eye"></i> Preview
        </a>
        <a href="edit_event.php?id=<?php echo $event_id; ?>" class="btn btn-primary">
            <i class="fas fa-edit"></i> Edit
        </a>
    </div>
</div>

<div class="row">

<!-- LEFT -->
<div class="col-lg-4">
    <div class="card mb-4 p-4">
        <h6 class="text-muted">Total Registrations</h6>
        <h2 class="fw-bold"><?php echo count($registrations); ?></h2>
    </div>

    <div class="card mb-4">
        <img src="../<?php echo htmlspecialchars($event['image_path']); ?>" class="event-image">
        <div class="card-body">
            <p><i class="fas fa-calendar me-2"></i><?php echo date('F d, Y', strtotime($event['event_date'])); ?></p>
            <p><i class="fas fa-clock me-2"></i><?php echo date('h:i A', strtotime($event['event_time'])); ?></p>
            <p><i class="fas fa-map-marker-alt me-2"></i><?php echo htmlspecialchars($event['location']); ?></p>
        </div>
    </div>

    <div class="card p-4">
        <h6>Invite Link</h6>
        <input type="text" class="form-control mb-2" value="<?php echo htmlspecialchars($invite_link); ?>" id="inviteLink" readonly>
        <button class="btn btn-primary w-100" onclick="copyInviteLink()">Copy Link</button>
        <small id="copyStatus" class="text-success d-none mt-2">Copied!</small>
    </div>
</div>

<!-- RIGHT -->
<div class="col-lg-8">
<div class="card">
<div class="card-header bg-white">
    <h5 class="fw-bold mb-0">Registrations</h5>
</div>
<div class="card-body p-0">
<table class="table mb-0">
<thead>
<tr>
    <th>Name</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Gender</th>
    <th>Date</th>
</tr>
</thead>
<tbody>

<?php if (empty($registrations)): ?>
<tr>
    <td colspan="5" class="text-center text-muted p-4">No registrations yet</td>
</tr>
<?php else: foreach ($registrations as $r): ?>
<tr>
    <td><?php echo htmlspecialchars($r['full_name']); ?></td>
    <td><?php echo htmlspecialchars($r['email']); ?></td>
    <td><?php echo htmlspecialchars($r['phone']); ?></td>
    <td>
        <span class="badge bg-info text-dark badge-gender">
            <?php echo ucfirst(htmlspecialchars($r['gender'])); ?>
        </span>
    </td>
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
