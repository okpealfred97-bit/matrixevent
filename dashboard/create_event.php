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
    $max_people = (int)$_POST['max_people'];
    $user_id = $_SESSION['user_id'] ?? null;

    if (!$user_id) {
        $error = "Session expired. Please login again.";
    } elseif ($max_people < 1) {
        $error = "Max people must be at least 1.";
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
            "INSERT INTO events 
            (user_id, title, description, event_date, event_time, location, image_path, max_people)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $stmt->execute([
            $user_id,
            $title,
            $description,
            $event_date,
            $event_time,
            $location,
            $image_path,
            $max_people
        ]);

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

    <div class="main-content flex-grow-1 p-4">

        <h2 class="fw-bold">Create New Event</h2>
        <p class="text-muted mb-4">Make it beautiful, clear and engaging ✨</p>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-5">

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

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

                        <label class="fancy-label">Event Name</label>
                        <input type="text" name="title" class="form-control fancy-input mb-3" required>

                        <label class="fancy-label">Description</label>
                        <textarea name="description" class="form-control fancy-input mb-3" rows="4" required></textarea>

                        <label class="fancy-label">👥 Max People Allowed</label>
                        <input type="number" name="max_people" class="form-control fancy-input" min="1" required>
                    </div>

                    <!-- STEP 2 -->
                    <div class="step d-none step-card">
                        <h4 class="step-title">📅 When & Where?</h4>

                        <input type="date" name="event_date" class="form-control fancy-input mb-3" required>
                        <input type="time" name="event_time" class="form-control fancy-input mb-3" required>

                        <select name="event_type" id="eventType" class="form-control fancy-input mb-3" required>
                            <option value="">Select event type</option>
                            <option value="physical">Physical</option>
                            <option value="online">Online</option>
                            <option value="hybrid">Hybrid</option>
                        </select>

                        <div class="d-none" id="physicalBox">
                            <input type="text" class="form-control fancy-input mb-3" placeholder="Venue">
                        </div>

                        <div class="d-none" id="onlineBox">
                            <input type="url" class="form-control fancy-input mb-3" placeholder="Online link">
                        </div>

                        <input type="hidden" name="location">
                    </div>

                    <!-- STEP 3 -->
                    <div class="step d-none step-card">
                        <h4 class="step-title">🖼 Image</h4>
                        <input type="file" name="image" class="form-control fancy-input" onchange="previewImage(event)">
                        <img id="imagePreview" class="img-fluid rounded mt-3 d-none">
                    </div>

                    <!-- STEP 4 -->
                    <div class="step d-none step-card">
                        <h4 class="step-title">👀 Preview</h4>
                        <p><strong id="previewTitle"></strong></p>
                        <p id="previewDate"></p>
                        <p id="previewLocation"></p>
                    </div>

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
}

function nextStep(){ if(currentStep < steps.length-1) showStep(++currentStep); }
function prevStep(){ if(currentStep > 0) showStep(--currentStep); }

function previewImage(e){
    const img = document.getElementById('imagePreview');
    img.src = URL.createObjectURL(e.target.files[0]);
    img.classList.remove('d-none');
}

showStep(0);
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
