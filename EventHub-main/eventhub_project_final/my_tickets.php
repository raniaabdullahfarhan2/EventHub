<?php
require_once __DIR__ . '/config/auth.php';
require_login();
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
global $pdo;
$userId = current_user_id();
$stmt = $pdo->prepare("SELECT t.*, e.event_name, e.event_date, e.event_time FROM tickets t JOIN events e ON t.event_id = e.event_id WHERE t.user_id = :uid ORDER BY t.created_at DESC");
$stmt->execute([':uid' => $userId]);
$tickets = $stmt->fetchAll();
?>
<div class="container mt-4">
    <h3 class="mb-4 page-title">My Tickets</h3>
    <table class="table table-striped table-bordered table-purple">
        <thead>
            <tr>
                <th>Event</th>
                <th>Date</th>
                <th>QR Code</th>
                <th>Status</th>
                <th>Checked In</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tickets as $t): ?>
                <tr>
                    <td><?= htmlspecialchars($t['event_name']) ?></td>
                    <td><?= htmlspecialchars($t['event_date']) ?> <?= htmlspecialchars($t['event_time']) ?></td>
                    <td><small><?= htmlspecialchars($t['qr_code']) ?></small></td>
                    <td><?= htmlspecialchars($t['ticket_status']) ?></td>
                    <td><?= $t['checked_in'] ? 'Yes' : 'No' ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$tickets): ?>
                <tr><td colspan="5">No tickets yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
