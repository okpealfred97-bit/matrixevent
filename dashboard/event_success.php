<?php require_once '../includes/auth_check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Created 🎉</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
         body {
                min-height: 100vh;
                background:
                    radial-gradient(circle at top left, #dbeafe, transparent 40%),
                    radial-gradient(circle at bottom right, #dcfce7, transparent 40%),
                    linear-gradient(135deg, #f8fafc, #eef2ff);
            }


        .success-card {
            max-width: 520px;
            border-radius: 20px;
            animation: fadeUp 0.8s ease;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .icon-circle {
            width: 90px;
            height: 90px;
            background: #e7f8ef;
            color: #22c55e;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 42px;
            margin: -70px auto 20px;
            box-shadow: 0 10px 30px rgba(34,197,94,.3);
        }

        .btn-main {
            border-radius: 50px;
            padding: 12px 26px;
            font-weight: 600;
        }

        .btn-outline-soft {
            border-radius: 50px;
            padding: 12px 26px;
            font-weight: 600;
            border: 1px solid #dbeafe;
            color: #2563eb;
        }

        .btn-outline-soft:hover {
            background: #eff6ff;
        }
        @keyframes softGlow {
            0% { background-position: 0% 50%; }
            100% { background-position: 100% 50%; }
        }

        body {
            background-size: 200% 200%;
            animation: softGlow 12s ease infinite;
        }

    </style>
</head>

<body class="d-flex align-items-center justify-content-center min-vh-100">

<div class="card success-card shadow-lg border-0 text-center p-4 p-md-5">

    <div class="icon-circle">
        <i class="fas fa-check"></i>
    </div>

    <h1 class="fw-bold text-success mb-2">Event Created!</h1>

    <p class="text-muted fs-5">
        Your event is live and ready to impress 🎉  
        You can now manage it, share it, or create another one.
    </p>

    <div class="d-grid gap-3 mt-4">
        <a href="index.php" class="btn btn-primary btn-main">
            <i class="fas fa-chart-line me-2"></i> Go to Dashboard
        </a>

        <a href="create_event.php" class="btn btn-outline-soft">
            <i class="fas fa-plus me-2"></i> Create Another Event
        </a>
    </div>

    <div class="mt-4 text-muted small">
        💡 Tip: Share your event to get more attendees
    </div>
</div>

</body>
</html>

