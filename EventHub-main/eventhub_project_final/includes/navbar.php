<?php
require_once __DIR__ . '/../config/auth.php';
$role = current_user_role();
?>
<nav class="navbar navbar-expand-lg px-4">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">EventHub</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <?php if ($role === 'Admin'): ?>
            <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="admin_users.php">Users</a></li>
            <li class="nav-item"><a class="nav-link" href="admin_events.php">Events</a></li>
            <li class="nav-item"><a class="nav-link" href="admin_support.php">Support</a></li>
        <?php elseif ($role === 'Event Organizer'): ?>
            <li class="nav-item"><a class="nav-link" href="organizer_dashboard.php">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="organizer_events.php">My Events</a></li>
    
        <?php elseif ($role === 'Support'): ?>
            <li class="nav-item"><a class="nav-link" href="support_dashboard.php">Support Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="support_all_issues.php">All Issues</a></li>
        <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="events.php">Events</a></li>
            <?php if (current_user_id()): ?>
                <li class="nav-item"><a class="nav-link" href="my_bookings.php">My Bookings</a></li>
                <li class="nav-item"><a class="nav-link" href="my_tickets.php">My Tickets</a></li>
                <li class="nav-item"><a class="nav-link" href="my_notifications.php">Notifications</a></li>
                <li class="nav-item"><a class="nav-link" href="support_my_issues.php">Support</a></li>
            <?php endif; ?>
        <?php endif; ?>
      </ul>
      <ul class="navbar-nav ms-auto">
        <?php if (current_user_id()): ?>
            <li class="nav-item">
                <span class="navbar-text me-2">
                    <?= htmlspecialchars($_SESSION['full_name'] ?? '') ?> (<?= htmlspecialchars($role ?? '') ?>)
                </span>
            </li>
            <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
        <?php else: ?>
            <li class="nav-item">
                <a href="login.php" class="btn-login me-2">Log In</a>
            </li>
            <li class="nav-item">
                <a href="signup.php" class="btn-signup">Sign Up</a>
            </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
