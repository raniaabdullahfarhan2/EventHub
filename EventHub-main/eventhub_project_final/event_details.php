<?php
require_once __DIR__ . '/config/auth.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
global $pdo;
$eventId = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT e.*, c.category_name, v.venue_name FROM events e LEFT JOIN categories c ON e.category_id = c.category_id LEFT JOIN venues v ON e.venue_id = v.venue_id WHERE e.event_id = :id");
$stmt->execute([':id' => $eventId]);
$event = $stmt->fetch();
?>
<div class="container mt-4">
    <?php if (!$event): ?>
        <div class="alert alert-danger mt-4">Event not found.</div>
    <?php else: ?>
        <div class="row">
            <div class="col-md-7">
                <img src="<?= $event['image_path'] ?: 'assets/default.jpg' ?>" class="img-fluid mb-3 rounded" alt="">
                <h3 class="page-title mb-2"><?= htmlspecialchars($event['event_name']) ?></h3>
                <p class="text-muted mb-1"><?= htmlspecialchars($event['category_name'] ?? '') ?></p>
                <p class="mb-2"><strong>Venue:</strong> <?= htmlspecialchars($event['venue_name'] ?? '') ?></p>
                <p><?= nl2br(htmlspecialchars($event['description'])) ?></p>
            </div>
            <div class="col-md-5">
                <div class="card p-3">
                    <p class="mb-2"><strong>Date:</strong> <?= htmlspecialchars($event['event_date']) ?> <?= htmlspecialchars($event['event_time']) ?></p>
                    <p class="mb-2"><strong>Price:</strong> <?= htmlspecialchars($event['ticket_price']) ?> SAR</p>
                    <p class="mb-2"><strong>Capacity:</strong> <?= (int)$event['capacity'] ?></p>
                    <p class="mb-3"><strong>Available:</strong> <?= (int)$event['tickets_available'] ?></p>
                    <?php if ($event['tickets_available'] > 0): ?>
                        <form method="post" action="book_event.php">
                            <input type="hidden" name="event_id" value="<?= (int)$event['event_id'] ?>">
                            <div class="mb-2">
                                <label class="form-label">Quantity</label>
                                <input type="number" name="ticket_quantity" class="form-control" min="1" max="<?= (int)$event['tickets_available'] ?>" value="1" required>
                            </div>
                            <button class="btn-main w-100 mt-2" type="submit">Book Now</button>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-warning">Sold out.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
