<?php
require_once __DIR__ . '/config/auth.php';
require_role(['Event Organizer']);
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
global $pdo;
$uid = current_user_id();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_event'])) {
    $name = trim($_POST['event_name'] ?? '');
    $desc = trim($_POST['description'] ?? '');
    $date = $_POST['event_date'] ?? '';
    $time = $_POST['event_time'] ?? '';
    $price = (float)($_POST['ticket_price'] ?? 0);
    $cap = (int)($_POST['capacity'] ?? 0);
    $catId = (int)($_POST['category_id'] ?? 1);
    $venue = (int)($_POST['venue_id'] ?? 1);
    if ($name && $date && $time) {
        $stmt = $pdo->prepare("INSERT INTO events (organizer_id, category_id, venue_id, event_name, description, event_date, event_time, ticket_price, capacity, tickets_sold, tickets_available, image_path, event_status, created_at) VALUES (:org, :cat, :venue, :name, :desc, :date, :time, :price, :cap, 0, :cap, '', 'Pending', NOW())");
        $stmt->execute([
            ':org' => $uid,
            ':cat' => $catId,
            ':venue' => $venue,
            ':name' => $name,
            ':desc' => $desc,
            ':date' => $date,
            ':time' => $time,
            ':price' => $price,
            ':cap' => $cap
        ]);
    }
}
$stmt = $pdo->prepare("SELECT e.*, v.venue_name FROM events e LEFT JOIN venues v ON e.venue_id = v.venue_id WHERE e.organizer_id = :id ORDER BY e.created_at DESC");
$stmt->execute([':id' => $uid]);
$events = $stmt->fetchAll();
$cats = $pdo->query("SELECT category_id, category_name FROM categories")->fetchAll();
$venues = $pdo->query("SELECT venue_id, venue_name FROM venues")->fetchAll();
?>
<div class="container mt-4">
    <h3 class="mb-3 page-title">My Events</h3>
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="mb-3">Create New Event</h5>
            <form method="post">
                <input type="hidden" name="create_event" value="1">
                <div class="mb-2">
                    <label class="form-label">Event Name</label>
                    <input type="text" name="event_name" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3" required></textarea>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Date</label>
                        <input type="date" name="event_date" class="form-control" required>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Time</label>
                        <input type="time" name="event_time" class="form-control" required>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Ticket Price</label>
                        <input type="number" step="0.01" name="ticket_price" class="form-control" required>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Capacity</label>
                        <input type="number" name="capacity" class="form-control" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select">
                            <?php foreach ($cats as $c): ?>
                                <option value="<?= (int)$c['category_id'] ?>"><?= htmlspecialchars($c['category_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Venue</label>
                        <select name="venue_id" class="form-select">
                            <?php foreach ($venues as $v): ?>
                                <option value="<?= (int)$v['venue_id'] ?>"><?= htmlspecialchars($v['venue_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <button class="btn-main mt-2" type="submit">Create</button>
            </form>
        </div>
    </div>
    <table class="table table-bordered table-purple">
        <thead>
            <tr>
                <th>#</th><th>Name</th><th>Date</th><th>Venue</th><th>Status</th><th>Tickets</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($events as $e): ?>
                <tr>
                    <td><?= (int)$e['event_id'] ?></td>
                    <td><?= htmlspecialchars($e['event_name']) ?></td>
                    <td><?= htmlspecialchars($e['event_date']) ?> <?= htmlspecialchars($e['event_time']) ?></td>
                    <td><?= htmlspecialchars($e['venue_name'] ?? '') ?></td>
                    <td><?= htmlspecialchars($e['event_status']) ?></td>
                    <td><?= (int)$e['tickets_sold'] ?>/<?= (int)$e['capacity'] ?></td>
                    <td>
                        <a href="organizer_edit_event.php?id=<?= (int)$e['event_id'] ?>" class="btn-main-outline btn-sm me-1">Edit</a>
                        <form method="post" action="organizer_delete_event.php" style="display:inline-block" onsubmit="return confirm('Delete this event?');">
                            <input type="hidden" name="event_id" value="<?= (int)$e['event_id'] ?>">
                            <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$events): ?>
                <tr><td colspan="7">No events yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
