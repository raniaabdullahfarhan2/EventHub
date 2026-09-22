<?php
require_once __DIR__ . '/config/auth.php';
require_role(['Event Organizer']);
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
global $pdo;
$uid = current_user_id();
$eventsStmt = $pdo->prepare("SELECT COUNT(*) AS c FROM events WHERE organizer_id = :id");
$eventsStmt->execute([':id' => $uid]);
$eventsCount = $eventsStmt->fetch()['c'];
$bookingsStmt = $pdo->prepare("SELECT COUNT(*) AS c FROM bookings b JOIN events e ON b.event_id = e.event_id WHERE e.organizer_id = :id");
$bookingsStmt->execute([':id' => $uid]);
$bookingsCount = $bookingsStmt->fetch()['c'];
?>
<div class="container mt-4">
    <h3 class="mb-4 page-title">Organizer Dashboard</h3>
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="card p-3 text-center">
                <h5>My Events</h5>
                <p class="display-6"><?= (int)$eventsCount ?></p>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card p-3 text-center">
                <h5>Total Bookings</h5>
                <p class="display-6"><?= (int)$bookingsCount ?></p>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
