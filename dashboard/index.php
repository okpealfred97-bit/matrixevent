<?php
require_once '../includes/auth_check.php';
require_once '../includes/db.php';

$user_id = $_SESSION['user_id'];

// Fetch user's events
$stmt = $pdo->prepare("SELECT e.*, (SELECT COUNT(*) FROM registrations r WHERE r.event_id = e.id) as reg_count FROM events e WHERE e.user_id = ? ORDER BY e.created_at DESC");
$stmt->execute([$user_id]);
$my_events = $stmt->fetchAll();

// Stats
$total_events = count($my_events);
$total_registrations = 0;
foreach ($my_events as $e) {
    $total_registrations += $e['reg_count'];
}

// Targets for level indicators
$event_target = 10; 
$reg_target = 100;  

$event_percent = min(($total_events / $event_target) * 100, 100);
$reg_percent = min(($total_registrations / $reg_target) * 100, 100);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - MatrixEvent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f8fafb;
            color: #1a202c;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .sidebar {
            width: 280px;
            min-height: 100vh;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            border-right: 1px solid #334155;
            padding: 2rem 1rem;
        }

        .sidebar h4 {
            color: #ffffff;
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 2rem;
            text-align: center;
            background: linear-gradient(135deg, #3b82f6 0%, #06b6d4 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .sidebar .nav-link {
            color: #cbd5e1 !important;
            padding: 0.75rem 1rem !important;
            border-radius: 8px;
            transition: all 0.3s ease;
            margin-bottom: 0.5rem;
        }

        .sidebar .nav-link:hover {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6 !important;
        }

        .sidebar .nav-link.active {
            background: rgba(59, 130, 246, 0.2);
            color: #3b82f6 !important;
            border-left: 3px solid #3b82f6;
            padding-left: calc(1rem - 3px);
        }

        .main-content {
            flex: 1;
            background: #f8fafb;
            padding: 2rem;
            overflow-y: auto;
        }

        .welcome-card {
            background: linear-gradient(135deg, #ffffff 0%, #f1f5f9 100%);
            border-radius: 16px;
            padding: 2rem;
            border: 1px solid #e2e8f0;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.08);
        }

        .welcome-card h1 {
            color: #0f172a;
            font-weight: 800;
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .welcome-card p {
            color: #475569;
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
        }

        .btn-create {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: #ffffff;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }

        .btn-create:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
            color: #ffffff;
        }

        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.05);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #3b82f6 0%, #06b6d4 100%);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 24px rgba(59, 130, 246, 0.15);
        }

        .stat-card h3 {
            color: #0f172a;
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .stat-card p {
            color: #64748b;
            font-size: 0.95rem;
        }

        .events-table {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.05);
        }

        .events-table h5 {
            color: #0f172a;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table thead {
            background: #f1f5f9;
        }

        table th {
            color: #475569;
            font-weight: 600;
            padding: 1rem;
            text-align: left;
            border-bottom: 2px solid #e2e8f0;
        }

        table td {
            padding: 1rem;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
        }

        table tr:hover {
            background: #f8fafc;
        }

        .btn-small {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            border: 1px solid #cbd5e1;
            background: #ffffff;
            color: #475569;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-right: 0.5rem;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
        }

        .btn-small:hover {
            background: #f1f5f9;
            border-color: #3b82f6;
            color: #3b82f6;
        }

        .btn-share {
            background: #ffffff;
            border: 2px solid #3b82f6;
            color: #3b82f6;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-share:hover {
            background: #3b82f6;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .level-wrap {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .level-bar {
            width: 100%;
            background: #e2e8f0;
            border-radius: 5px;
            height: 10px;
            overflow: hidden;
        }

        .level-fill {
            height: 10px;
            border-radius: 5px;
            transition: width 0.5s ease;
            background: linear-gradient(90deg, #3b82f6 0%, #06b6d4 100%);
        }

        .level-number {
            font-weight: 700;
            font-size: 0.85rem;
            color: #475569;
        }

        /* Share Modal */
        .share-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(15, 23, 42, 0.7);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            backdrop-filter: blur(4px);
        }

        .share-modal-overlay.active {
            display: flex;
        }

        .share-modal-content {
            background: #ffffff;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 20px 60px rgba(15, 23, 42, 0.3);
            max-width: 500px;
            width: 90%;
            border: 1px solid #e2e8f0;
        }

        .share-modal-header {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .share-modal-icons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .share-icon-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            border-radius: 12px;
            background: #f1f5f9;
            border: 2px solid #e2e8f0;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            color: #475569;
        }

        .share-icon-btn:hover {
            background: #eff6ff;
            transform: translateY(-5px);
            border-color: #3b82f6;
        }

        .share-icon-btn i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .share-icon-btn span {
            font-size: 0.85rem;
            font-weight: 600;
        }

        .share-modal-actions {
            display: flex;
            gap: 1rem;
            flex-direction: column;
        }

        .share-modal-actions button {
            padding: 0.75rem 1rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .copy-link-btn {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .copy-link-btn:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
        }

        .email-btn {
            background: #f1f5f9;
            color: #3b82f6;
            border: 2px solid #3b82f6;
        }

        .email-btn:hover {
            background: #eff6ff;
        }

        .close-modal-btn {
            background: #e2e8f0;
            color: #475569;
        }

        .close-modal-btn:hover {
            background: #cbd5e1;
        }

        /* Social media icon colors */
        .whatsapp-icon { color: #25d366; }
        .twitter-icon { color: #1da1f2; }
        .telegram-icon { color: #0088cc; }
        .email-icon { color: #3b82f6; }

        .badge-active {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>

<div style="display: flex; min-height: 100vh;">

    <!-- Sidebar -->
    <aside class="sidebar">
        <h4>MatrixEvent</h4>
        <ul class="nav flex-column gap-2">
            <li class="nav-item">
                <a href="index.php" class="nav-link active"><i class="fas fa-th-large me-2"></i> Dashboard</a>
            </li>
            <li class="nav-item">
                <a href="create_event.php" class="nav-link"><i class="fas fa-plus-circle me-2"></i> Create Event</a>
            </li>
            <li class="nav-item">
                <a href="../index.php" class="nav-link"><i class="fas fa-globe me-2"></i> View Site</a>
            </li>
            <li class="nav-item mt-auto">
                <a href="../auth/logout.php" class="nav-link" style="color: #ef4444;"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
            </li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="main-content">

        <!-- Welcome Banner -->
        <div class="welcome-card">
            <h1>👋 Welcome, <span style="background: linear-gradient(135deg, #3b82f6 0%, #06b6d4 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;"><?php echo $_SESSION['username']; ?></span>!</h1>
            <p>Manage your events, track registrations, and share your experiences with ease.</p>
            <a href="create_event.php" class="btn-create">
                <i class="fas fa-plus me-2"></i> Create New Event
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="stats-row">
            <div class="stat-card">
                <div style="display: flex; align-items: center;">
                    <div style="font-size: 2.5rem; margin-right: 1rem;">📅</div>
                    <div>
                        <h3><?php echo $total_events; ?></h3>
                        <p>Total Events</p>
                        <div class="level-wrap" style="max-width: 140px;">
                            <div class="level-bar">
                                <div class="level-fill" style="width: <?php echo $event_percent; ?>%;"></div>
                            </div>
                            <span class="level-number"><?php echo $total_events; ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="stat-card">
                <div style="display: flex; align-items: center;">
                    <div style="font-size: 2.5rem; margin-right: 1rem;">👥</div>
                    <div>
                        <h3><?php echo $total_registrations; ?></h3>
                        <p>Total Registrations</p>
                        <div class="level-wrap" style="max-width: 140px;">
                            <div class="level-bar">
                                <div class="level-fill" style="width: <?php echo $reg_percent; ?>%;"></div>
                            </div>
                            <span class="level-number"><?php echo $total_registrations; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Events Table -->
        <div class="events-table">
            <h5>My Events</h5>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Event Name</th>
                            <th>Date</th>
                            <th>Registrations</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($my_events)): ?>
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 2rem; color: #94a3b8;">
                                    No events created yet. <a href="create_event.php" style="color: #3b82f6; font-weight: 600; text-decoration: none;">Create your first event</a>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($my_events as $event): ?>
                                <tr>
                                    <td>
                                        <div style="display: flex; align-items: center;">
                                            <img src="../<?php echo $event['image_path']; ?>" style="width: 50px; height: 50px; border-radius: 8px; margin-right: 1rem; object-fit: cover;">
                                            <span><?php echo $event['title']; ?></span>
                                        </div>
                                    </td>
                                    <td><?php echo $event['event_date']; ?></td>
                                    <td>
                                        <div class="level-wrap" style="max-width: 140px;">
                                            <div class="level-bar">
                                                <?php 
                                                $percent = min(100, $event['reg_count']); 
                                                $color = $percent < 30 ? '#ef4444' : ($percent < 70 ? '#f59e0b' : '#10b981');
                                                ?>
                                                <div class="level-fill" style="width: <?php echo $percent; ?>%; background: <?php echo $color; ?>;"></div>
                                            </div>
                                            <span class="level-number"><?php echo $event['reg_count']; ?></span>
                                        </div>
                                    </td>
                                    <td><span class="badge-active">Active</span></td>
                                    <td>
                                        <a href="view_event.php?id=<?php echo $event['id']; ?>" class="btn-small">View</a>
                                        <a href="edit_event.php?id=<?php echo $event['id']; ?>" class="btn-small">Edit</a>
                                    <button class="btn-share share-btn" 
                                        data-title="<?php echo htmlspecialchars($event['title']); ?>" 
                                        data-url="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/matrixevent/event/register.php?event_id=' . $event['id']; ?>">
                                        Share
                                    </button>

                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</div>

<!-- Share Modal Overlay -->
<div class="share-modal-overlay" id="shareModal">
    <div class="share-modal-content">
        <div class="share-modal-header">Share Event</div>
        
        <div class="share-modal-icons" id="shareIcons">
            <!-- Icons will be populated by JavaScript -->
        </div>

        <div class="share-modal-actions">
            <button class="copy-link-btn" id="copyLinkBtn">
                <i class="fas fa-link me-2"></i> Copy Link
            </button>
            <button class="email-btn" id="emailBtn">
                <i class="fas fa-envelope me-2"></i> Share via Email
            </button>
            <button class="close-modal-btn" onclick="closeShareModal()">
                <i class="fas fa-times me-2"></i> Close
            </button>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>

<script>
let currentShareData = {};

// Share buttons
document.querySelectorAll('.share-btn').forEach(btn => {
    btn.addEventListener('click', e => {
        e.stopPropagation();
        
        const title = btn.dataset.title;
        const url = btn.dataset.url;
        
        currentShareData = { title, url };
        
        // Populate share icons (excluding Facebook)
        const shareIcons = document.getElementById('shareIcons');
        shareIcons.innerHTML = `
            <a class="share-icon-btn whatsapp-share" href="https://wa.me/?text=${encodeURIComponent(title + ' ' + url)}" target="_blank">
                <i class="fab fa-whatsapp whatsapp-icon"></i>
                <span>WhatsApp</span>
            </a>
            <a class="share-icon-btn twitter-share" href="https://twitter.com/intent/tweet?text=${encodeURIComponent(title)}&url=${encodeURIComponent(url)}" target="_blank">
                <i class="fab fa-twitter twitter-icon"></i>
                <span>Twitter</span>
            </a>
            <a class="share-icon-btn telegram-share" href="https://t.me/share/url?url=${encodeURIComponent(url)}&text=${encodeURIComponent(title)}" target="_blank">
                <i class="fab fa-telegram-plane telegram-icon"></i>
                <span>Telegram</span>
            </a>
            <a class="share-icon-btn email-share">
                <i class="fas fa-envelope email-icon"></i>
                <span>Email</span>
            </a>
        `;
        
        // Email share handler
        document.querySelector('.email-share').onclick = (e) => {
            e.preventDefault();
            window.location.href = `mailto:?subject=${encodeURIComponent(title)}&body=${encodeURIComponent(url)}`;
        };
        
        // Show modal
        document.getElementById('shareModal').classList.add('active');
    });
});

// Copy link button
document.getElementById('copyLinkBtn').addEventListener('click', () => {
    navigator.clipboard.writeText(currentShareData.url);
    alert('Link copied to clipboard!');
});

// Email button
document.getElementById('emailBtn').addEventListener('click', () => {
    window.location.href = `mailto:?subject=${encodeURIComponent(currentShareData.title)}&body=${encodeURIComponent(currentShareData.url)}`;
});

// Close modal
function closeShareModal() {
    document.getElementById('shareModal').classList.remove('active');
}

// Close modal when clicking outside
document.getElementById('shareModal').addEventListener('click', (e) => {
    if (e.target.id === 'shareModal') {
        closeShareModal();
    }
});
</script>

</body>
</html>
