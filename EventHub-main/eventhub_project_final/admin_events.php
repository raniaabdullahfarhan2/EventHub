<?php
require_once __DIR__ . '/config/auth.php';
require_role(['Admin']);
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
global $pdo;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $eventId = (int)($_POST['event_id'] ?? 0);
    $status = $_POST['event_status'] ?? '';
    if ($eventId && $status) {
        $stmt = $pdo->prepare("UPDATE events SET event_status = :status WHERE event_id = :id");
        $stmt->execute([':status' => $status, ':id' => $eventId]);
    }
}
$events = $pdo->query("SELECT e.event_id, e.event_name, e.event_status, e.event_date, e.event_time, u.full_name AS organizer_name FROM events e LEFT JOIN users u ON e.organizer_id = u.user_id ORDER BY e.created_at DESC")->fetchAll();
?>
<div class="container mt-4">
    <h3 class="mb-4 page-title">Manage Events</h3>
    <table class="table table-bordered table-purple">
        <thead>
            <tr>
                <th>#</th><th>Name</th><th>Organizer</th><th>Date</th><th>Status</th><th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($events as $e): ?>
                <tr>
                    <td><?= (int)$e['event_id'] ?></td>
                    <td><?= htmlspecialchars($e['event_name']) ?></td>
                    <td><?= htmlspecialchars($e['organizer_name'] ?? '') ?></td>
                    <td><?= htmlspecialchars($e['event_date']) ?> <?= htmlspecialchars($e['event_time']) ?></td>
                    <td><?= htmlspecialchars($e['event_status']) ?></td>
                    <td>
                        <form method="post" class="d-flex gap-1">
                            <input type="hidden" name="event_id" value="<?= (int)$e['event_id'] ?>">
                            <select name="event_status" class="form-select form-select-sm">
                                <option value="Pending">Pending</option>
                                <option value="Approved">Approved</option>
                                <option value="Rejected">Rejected</option>
                            </select>
                            <button class="btn-main-outline btn-sm" type="submit">Update</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
