<?php
require_once __DIR__ . '/config/auth.php';
require_role(['Event Organizer']);
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
global $pdo;
$uid = current_user_id();
$eventId = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM events WHERE event_id = :id AND organizer_id = :org");
$stmt->execute([':id' => $eventId, ':org' => $uid]);
$event = $stmt->fetch();
if (!$event) {
    echo "<div class='container mt-5'><div class='alert alert-danger'>Event not found.</div></div>";
    require_once __DIR__ . '/includes/footer.php';
    exit;
}
$cats = $pdo->query("SELECT category_id, category_name FROM categories")->fetchAll();
$venues = $pdo->query("SELECT venue_id, venue_name FROM venues")->fetchAll();
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['event_name'] ?? '');
    $desc = trim($_POST['description'] ?? '');
    $date = $_POST['event_date'] ?? '';
    $time = $_POST['event_time'] ?? '';
    $price = (float)($_POST['ticket_price'] ?? 0);
    $cap = (int)($_POST['capacity'] ?? 0);
    $catId = (int)($_POST['category_id'] ?? 1);
    $venue = (int)($_POST['venue_id'] ?? 1);
    if ($name && $date && $time) {
        $up = $pdo->prepare("UPDATE events SET category_id = :cat, venue_id = :venue, event_name = :name, description = :descr, event_date = :date, event_time = :time, ticket_price = :price, capacity = :cap WHERE event_id = :id AND organizer_id = :org");
        $up->execute([
            ':cat' => $catId,
            ':venue' => $venue,
            ':name' => $name,
            ':descr' => $desc,
            ':date' => $date,
            ':time' => $time,
            ':price' => $price,
            ':cap' => $cap,
            ':id' => $eventId,
            ':org' => $uid
        ]);
        $message = 'Event updated.';
        $stmt->execute([':id' => $eventId, ':org' => $uid]);
        $event = $stmt->fetch();
    }
}
?>
<div class="container mt-4">
    <h3 class="mb-3 page-title">Edit Event</h3>
    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <div class="card">
        <div class="card-body">
            <form method="post">
                <div class="mb-2">
                    <label class="form-label">Event Name</label>
                    <input type="text" name="event_name" class="form-control" value="<?= htmlspecialchars($event['event_name']) ?>" required>
                </div>
                <div class="mb-2">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3" required><?= htmlspecialchars($event['description']) ?></textarea>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Date</label>
                        <input type="date" name="event_date" class="form-control" value="<?= htmlspecialchars($event['event_date']) ?>" required>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Time</label>
                        <input type="time" name="event_time" class="form-control" value="<?= htmlspecialchars($event['event_time']) ?>" required>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Ticket Price</label>
                        <input type="number" step="0.01" name="ticket_price" class="form-control" value="<?= htmlspecialchars($event['ticket_price']) ?>" required>
                    </div>
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Capacity</label>
                        <input type="number" name="capacity" class="form-control" value="<?= (int)$event['capacity'] ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select">
                            <?php foreach ($cats as $c): ?>
                                <option value="<?= (int)$c['category_id'] ?>" <?= (int)$event['category_id'] === (int)$c['category_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($c['category_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="form-label">Venue</label>
                        <select name="venue_id" class="form-select">
                            <?php foreach ($venues as $v): ?>
                                <option value="<?= (int)$v['venue_id'] ?>" <?= (int)$event['venue_id'] === (int)$v['venue_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($v['venue_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <button class="btn-main mt-3" type="submit">Save Changes</button>
            </form>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
