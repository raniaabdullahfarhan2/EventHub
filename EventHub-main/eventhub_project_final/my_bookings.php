<?php
require_once __DIR__ . '/config/auth.php';
require_login();
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
global $pdo;
$userId = current_user_id();
$stmt = $pdo->prepare("SELECT b.*, e.event_name, e.event_date, e.event_time FROM bookings b JOIN events e ON b.event_id = e.event_id WHERE b.user_id = :uid ORDER BY b.booking_date DESC");
$stmt->execute([':uid' => $userId]);
$bookings = $stmt->fetchAll();
?>
<div class="container mt-4">
    <h3 class="mb-4 page-title">My Bookings</h3>
    <table class="table table-striped table-bordered table-purple">
        <thead>
            <tr>
                <th>Event</th>
                <th>Date</th>
                <th>Tickets</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Booked At</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bookings as $b): ?>
                <tr>
                    <td><?= htmlspecialchars($b['event_name']) ?></td>
                    <td><?= htmlspecialchars($b['event_date']) ?> <?= htmlspecialchars($b['event_time']) ?></td>
                    <td><?= (int)$b['ticket_quantity'] ?></td>
                    <td><?= htmlspecialchars($b['total_price']) ?> SAR</td>
                    <td><?= htmlspecialchars($b['booking_status']) ?></td>
                    <td><?= htmlspecialchars($b['booking_date']) ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$bookings): ?>
                <tr><td colspan="6">No bookings yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
