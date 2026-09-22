<?php
require_once __DIR__ . '/config/auth.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
global $pdo;

$cats = $pdo->query("SELECT category_id, category_name FROM categories")->fetchAll();
$venues = $pdo->query("SELECT venue_id, venue_name FROM venues")->fetchAll();

$categoryId = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;
$venueId = isset($_GET['venue_id']) ? (int)$_GET['venue_id'] : 0;
$dateFilter = trim($_GET['event_date'] ?? '');
$searchName = trim($_GET['search_name'] ?? '');

$sql = "SELECT e.event_id, e.event_name, e.description, e.event_date, e.event_time,
        e.ticket_price, e.tickets_available, e.image_path,
        c.category_name, v.venue_name
        FROM events e
        LEFT JOIN categories c ON e.category_id = c.category_id
        LEFT JOIN venues v ON e.venue_id = v.venue_id
        WHERE e.event_status = 'Approved'";

$params = [];

if ($categoryId > 0) {
    $sql .= " AND e.category_id = :cat";
    $params[':cat'] = $categoryId;
}

if ($venueId > 0) {
    $sql .= " AND e.venue_id = :venue";
    $params[':venue'] = $venueId;
}

if ($dateFilter !== '') {
    $sql .= " AND e.event_date = :date";
    $params[':date'] = $dateFilter;
}

if ($searchName !== '') {
    $sql .= " AND e.event_name LIKE :name";
    $params[':name'] = "%$searchName%";
}

$sql .= " ORDER BY e.event_date, e.event_time";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$events = $stmt->fetchAll();
?>

<div class="container mt-4">
    <h3 class="mb-3 page-title">Browse Events</h3>

    <div class="search-bar">
        <form method="get" class="row g-2">

            <div class="col-md-3">
                <label class="form-label">Search by Name</label>
                <input type="text" name="search_name" class="form-control"
                       placeholder="Search..."
                       value="<?= htmlspecialchars($searchName) ?>">
            </div>

            <div class="col-md-3">
                <label class="form-label">Type</label>
                <select name="category_id" class="form-select">
                    <option value="0">All Types</option>
                    <?php foreach ($cats as $c): ?>
                        <option value="<?= (int)$c['category_id'] ?>"
                            <?= $categoryId === (int)$c['category_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['category_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Place</label>
                <select name="venue_id" class="form-select">
                    <option value="0">All Venues</option>
                    <?php foreach ($venues as $v): ?>
                        <option value="<?= (int)$v['venue_id'] ?>"
                            <?= $venueId === (int)$v['venue_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($v['venue_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label">Date</label>
                <input type="date" name="event_date" class="form-control"
                       value="<?= htmlspecialchars($dateFilter) ?>">
            </div>

            <div class="col-md-1 d-flex align-items-end">
                <button class="btn-main w-100" type="submit">Search</button>
            </div>

        </form>
    </div>

    <div class="row">
        <?php foreach ($events as $event): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="<?= $event['image_path'] ?: 'assets/default.jpg' ?>">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= htmlspecialchars($event['event_name']) ?></h5>
                        <p class="text-muted small mb-1">
                            <?= htmlspecialchars($event['category_name'] ?? '') ?> |
                            <?= htmlspecialchars($event['venue_name'] ?? '') ?>
                        </p>
                        <p class="small mb-2">
                            <?= htmlspecialchars(substr($event['description'], 0, 120)) ?>...
                        </p>
                        <p class="mb-1"><strong>Date:</strong>
                            <?= htmlspecialchars($event['event_date']) ?> <?= htmlspecialchars($event['event_time']) ?>
                        </p>
                        <p class="mb-2"><strong>Price:</strong> <?= htmlspecialchars($event['ticket_price']) ?> SAR</p>
                        <a href="event_details.php?id=<?= (int)$event['event_id'] ?>"
                           class="btn-main mt-auto w-100">View Details</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (!$events): ?>
            <p>No events found.</p>
        <?php endif; ?>
    </div>
</div>

<?php require_once DIR . '/includes/footer.php'; ?>