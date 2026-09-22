<?php
require_once __DIR__ . '/config/auth.php';
require_login();
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
global $pdo;
$userId = current_user_id();
$stmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id = :uid ORDER BY created_at DESC");
$stmt->execute([':uid' => $userId]);
$notifications = $stmt->fetchAll();
?>
<div class="container mt-4">
    <h3 class="mb-4 page-title">Notifications</h3>
    <ul class="list-group">
        <?php foreach ($notifications as $n): ?>
            <li class="list-group-item d-flex justify-content-between">
                <div>
                    <strong><?= htmlspecialchars($n['title']) ?></strong><br>
                    <small class="text-muted"><?= htmlspecialchars($n['created_at']) ?></small>
                    <p class="mb-0"><?= nl2br(htmlspecialchars($n['message'])) ?></p>
                </div>
                <span class="badge badge-status align-self-start"><?= $n['is_read'] ? 'Read' : 'New' ?></span>
            </li>
        <?php endforeach; ?>
        <?php if (!$notifications): ?>
            <li class="list-group-item">No notifications.</li>
        <?php endif; ?>
    </ul>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
