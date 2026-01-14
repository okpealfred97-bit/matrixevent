 <?php
require_once '../includes/auth_check.php';
require_once '../includes/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize($_POST['title']);
    $description = sanitize($_POST['description']);
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];
    $location = sanitize($_POST['location']);
    $user_id = $_SESSION['user_id'] ?? null;

    if (!$user_id) {
        $error = "Session expired. Please login again.";
    } else {
        $image_path = 'assets/images/events/default.jpg';

        if (!empty($_FILES['image']['name'])) {
            $target_dir = "../assets/images/events/";
            $file_ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $file_name = time() . "_" . uniqid() . "." . $file_ext;
            move_uploaded_file($_FILES['image']['tmp_name'], $target_dir . $file_name);
            $image_path = 'assets/images/events/' . $file_name;
        }

        $stmt = $pdo->prepare(
            "INSERT INTO events (user_id, title, description, event_date, event_time, location, image_path)
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );

        $stmt->execute([$user_id, $title, $description, $event_date, $event_time, $location, $image_path]);
        header("Location: event_success.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Create Event - MatrixEvent</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-light">

<div class="d-flex">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <h4 class="fw-bold text-primary mb-4">MatrixEvent</h4>

        <ul class="nav flex-column gap-2">
            <li class="nav-item">
                <a href="index.php" class="nav-link">
                    <i class="fas fa-th-large me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="create_event.php" class="nav-link active">
                    <i class="fas fa-plus-circle me-2"></i> Create Event
                </a>
            </li>
            <li class="nav-item">
                <a href="../index.php" class="nav-link">
                    <i class="fas fa-globe me-2"></i> View Site
                </a>
            </li>
            <li class="nav-item mt-auto">
                <a href="../auth/logout.php" class="nav-link text-danger">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </a>
            </li>
        </ul>
    </aside>

    <!-- MAIN -->
    <div class="main-content flex-grow-1 p-4">

        <h2 class="fw-bold">Create New Event</h2>
        <p class="text-muted mb-4">Make it beautiful, clear and engaging ✨</p>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-5">

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <!-- PROGRESS -->
                <div class="mb-4">
                    <div class="progress" style="height:8px">
                        <div id="progressBar" class="progress-bar bg-primary"></div>
                    </div>
                    <small class="text-muted">Step <span id="stepNumber">1</span> of 4</small>
                </div>

                <form method="POST" enctype="multipart/form-data">

                    <!-- STEP 1 -->
                    <div class="step step-card">
                        <h4 class="step-title">🎯 What’s your event?</h4>
                        <p class="step-subtitle">Give your event a catchy identity</p>

                        <label class="fancy-label">
                            <span class="icon-badge title">✨</span> Event Name
                        </label>
                        <input type="text" name="title" class="form-control fancy-input mb-4" required>
        
                        <label class="fancy-label">
                            <span class="icon-badge type">🎉</span> Event Type
                        </label>
                        <select id="eventType" class="form-select fancy-input mb-4">
                            <option>Party</option>
                            <option>Conference</option>
                            <option>Wedding</option>
                            <option>Online</option>
                            <option>Others</option>
                        </select>

                        <label class="fancy-label">
                            <span class="icon-badge desc">📝</span> Description
                        </label>
                        <textarea name="description" class="form-control fancy-input" rows="4" required></textarea>
                    </div>

                     <!-- STEP 2 -->
                        <div class="step d-none step-card">
                            <h4 class="step-title">📅 When & Where?</h4>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="fancy-label">
                                        <span class="icon-badge date">📅</span> Date
                                    </label>
                                    <input type="date" name="event_date" class="form-control fancy-input" required>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label class="fancy-label">
                                        <span class="icon-badge time">⏰</span> Time
                                    </label>
                                    <input type="time" name="event_time" class="form-control fancy-input" required>
                                </div>
                            </div>

                            <!-- Event Type -->
                            <label class="fancy-label">
                                <span class="icon-badge location">📍</span> Event Type
                            </label>
                            <select name="event_type" id="eventType" class="form-control fancy-input" required>
                                <option value="">Select event type</option>
                                <option value="physical">Physical Event</option>
                                <option value="online">Online Event</option>
                                <option value="hybrid">Hybrid Event</option>
                            </select>
                            <!-- Physical Location -->
                            <div class="mt-4 d-none" id="physicalBox">
                                <label class="fancy-label">
                                    <span class="icon-badge location">📍</span> Event Venue
                                </label>
                                <input
                                    type="text"
                                    name="physical_location"
                                    class="form-control fancy-input"
                                    placeholder="Enter venue address"
                                >
                            </div>

                            <!-- Online Link -->
                            <div class="mt-4 d-none" id="onlineBox">
                                <label class="fancy-label">
                                    <span class="icon-badge location">📍</span> Online Event Link
                                </label>
                                <input
                                    type="url"
                                    name="online_link"
                                    class="form-control fancy-input"
                                    placeholder="https://zoom.us / meet.google.com"
                                >
                            </div>


                            <!-- Physical Location -->
                            <div class="mt-4 d-none" id="physicalLocation">
                                <label class="fancy-label">
                                    <span class="icon-badge location">📍</span> Physical Location
                                </label>
                                <input type="text" name="physical_location" class="form-control fancy-input" placeholder="Enter venue address">
                            </div>

                            <!-- Online Link -->
                            <div class="mt-4 d-none" id="onlineLocation">
                                <label class="fancy-label">
                                    <span class="icon-badge location">📍</span> Online Event Link
                                </label>
                                <input type="url" name="online_link" class="form-control fancy-input" placeholder="https://zoom.us / meet.google.com">
                            </div>
                            

                            <!-- Legacy / Combined Location (kept for compatibility) -->
                            <input type="hidden" name="location">
                        </div>

                    <!-- STEP 3 -->
                    <div class="step d-none step-card">
                        <h4 class="step-title">🖼 Make it attractive</h4>

                        <label class="fancy-label">
                            <span class="icon-badge image">📸</span> Cover Image
                        </label>
                        <input type="file" name="image" class="form-control fancy-input" onchange="previewImage(event)">

                        <img id="imagePreview" class="img-fluid rounded shadow mt-4 d-none" style="max-height:220px">
                    </div>

                    <!-- STEP 4 -->
                    <div class="step d-none step-card">
                        <h4 class="step-title">👀 Preview</h4>

                        <div class="preview-box">
                            <p><span class="icon-badge preview">🎯</span> <strong id="previewTitle"></strong></p>
                            <p><span class="icon-badge date">📅</span> <span id="previewDate"></span></p>
                            <p><span class="icon-badge location">📍</span> <span id="previewLocation"></span></p>
                        </div>
                    </div>

                    <!-- NAV -->
                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-outline-secondary" onclick="prevStep()">⬅ Back</button>
                        <button type="button" class="btn btn-primary" onclick="nextStep()">Next ➡</button>
                        <button type="submit" id="submitBtn" class="btn btn-success d-none">🎉 Publish</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script src="../assets/js/ui.js"></script>

<script>
let currentStep = 0;
const steps = document.querySelectorAll('.step');
const progress = document.getElementById('progressBar');
const stepText = document.getElementById('stepNumber');
const submitBtn = document.getElementById('submitBtn');

function showStep(i) {
    steps.forEach(s => s.classList.add('d-none'));
    steps[i].classList.remove('d-none');

    stepText.innerText = i + 1;
    progress.style.width = ((i + 1) / steps.length) * 100 + '%';
    submitBtn.classList.toggle('d-none', i !== steps.length - 1);

    if (i === steps.length - 1) {
        previewTitle.innerText = document.querySelector('[name="title"]').value;
        previewDate.innerText =
            document.querySelector('[name="event_date"]').value + ' @ ' +
            document.querySelector('[name="event_time"]').value;
        previewLocation.innerText =
            document.querySelector('[name="location"]').value;
    }
}

function nextStep() {
    if (currentStep < steps.length - 1) showStep(++currentStep);
}

function prevStep() {
    if (currentStep > 0) showStep(--currentStep);
}

function previewImage(e) {
    const img = document.getElementById('imagePreview');
    img.src = URL.createObjectURL(e.target.files[0]);
    img.classList.remove('d-none');
}

showStep(0);
</script>
<script>
document.getElementById('eventType').addEventListener('change', function () {
    const physical = document.getElementById('physicalLocation');
    const online = document.getElementById('onlineLocation');

    physical.classList.add('d-none');
    online.classList.add('d-none');

    if (this.value === 'physical') {
        physical.classList.remove('d-none');
    }

    if (this.value === 'online') {
        online.classList.remove('d-none');
    }

    if (this.value === 'hybrid') {
        physical.classList.remove('d-none');
        online.classList.remove('d-none');
    }
});
</script>
<script>
const eventType = document.getElementById('eventType');
const physicalBox = document.getElementById('physicalBox');
const onlineBox = document.getElementById('onlineBox');

eventType.addEventListener('change', function () {
    physicalBox.classList.add('d-none');
    onlineBox.classList.add('d-none');

    if (this.value === 'physical') physicalBox.classList.remove('d-none');
    if (this.value === 'online') onlineBox.classList.remove('d-none');
    if (this.value === 'hybrid') {
        physicalBox.classList.remove('d-none');
        onlineBox.classList.remove('d-none');
    }
});
</script>



</body>
</html>
