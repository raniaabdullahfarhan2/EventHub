<?php
require_once __DIR__ . '/config/auth.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
global $pdo;
$stmt = $pdo->query("SELECT event_id, event_name, event_date, event_time, image_path, ticket_price FROM events WHERE event_status = 'Approved' ORDER BY event_date ASC LIMIT 6");
$events = $stmt->fetchAll();
?>
<div class="hero">
    <h1>Discover And Book Amazing Events</h1>
    <p>Find workshops, conferences, entertainment and more in your city.</p>
    <a href="events.php" class="btn-main mt-3">Browse Events</a>
</div>
<div class="container mt-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h3 class="page-title mb-3">Featured Events</h3>
        </div>
        <div class="col-md-4 text-md-end">
            <div class="card p-3">
                <div class="mb-1" style="font-weight:600;color:#4c1d95;">Follow us on Instagram</div>
                <a href="https://www.instagram.com/evenhuboffical" target="_blank" class="link-purple">@evenhuboffical</a>
            </div>
        </div>
    </div>
    <div class="row">
        <?php foreach ($events as $event): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="<?= $event['image_path'] ?: 'assets/default.jpg' ?>" alt="">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= htmlspecialchars($event['event_name']) ?></h5>
                        <p class="mb-1 text-muted"><?= htmlspecialchars($event['event_date']) ?> <?= htmlspecialchars($event['event_time']) ?></p>
                        <p class="fw-semibold mb-2">Price: <?= htmlspecialchars($event['ticket_price']) ?> SAR</p>
                        <a href="event_details.php?id=<?= (int)$event['event_id'] ?>" class="btn-main mt-auto w-100">View Details</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if (!$events): ?>
            <p>No events available.</p>
        <?php endif; ?>
    </div>
</div>
<section style="padding: 60px 0; background: linear-gradient(135deg,#f3eaff,#faf5ff);">
    <div class="container" style="max-width: 1000px;">

        <h2 class="text-center mb-4"
            style="font-weight:700; font-size:32px; color:#4C1D95;">
            🌟 Our Vision
        </h2>

        <div class="p-4 shadow-lg"
             style="background:white; border-radius:18px; padding:35px;
                    border:1px solid #cdbaf3;">

            <ul style="line-height:1.9; font-size:17px; list-style:none; padding-left:0;">

                <li style="margin-bottom:12px;">
                    <strong style="color:#4C1D95;">For:</strong>
                    Event enthusiasts, event organizers, venue managers, and admins 
                    looking for an easy and efficient way to manage and attend events of all kinds.
                </li>

                <li style="margin-bottom:12px;">
                    <strong style="color:#4C1D95;">Who:</strong>
                    Need a simple and reliable solution to discover events, reserve tickets, 
                    and receive updates without navigating multiple platforms.
                </li>

                <li style="margin-bottom:12px;">
                    <strong style="color:#4C1D95;">The Event Hub is:</strong>
                    An online event booking and management platform.
                </li>

                <li style="margin-bottom:12px;">
                    <strong style="color:#4C1D95;">That provides:</strong>
                    Users with seamless access to browse events, book tickets, receive reminders, 
                    and organize their own events through one convenient interface.
                </li>

                <li style="margin-bottom:12px;">
                    <strong style="color:#4C1D95;">Unlike:</strong>
                    Traditional event websites or manual booking systems that are limited, confusing, 
                    or lack personalization.
                </li>

                <li style="margin-bottom:12px;">
                    <strong style="color:#4C1D95;">Our product offers:</strong>
                    A user-friendly, all-in-one digital platform that connects organizers and attendees 
                    efficiently, saves time, and enhances participation through real-time updates.
                </li>
            </ul>

           

        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
