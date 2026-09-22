<?php
require_once __DIR__ . '/config/auth.php';
require_role(['Admin']);
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
global $pdo;
$usersCount = $pdo->query("SELECT COUNT(*) AS c FROM users")->fetch()['c'];
$eventsCount = $pdo->query("SELECT COUNT(*) AS c FROM events")->fetch()['c'];
$openIssues = $pdo->query("SELECT COUNT(*) AS c FROM support_issues WHERE issue_status = 'Open'")->fetch()['c'];
?>
<div class="container mt-4">
    <h3 class="mb-4 page-title">Admin Dashboard</h3>
    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card p-3 text-center">
                <h5>Users</h5>
                <p class="display-6"><?= (int)$usersCount ?></p>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card p-3 text-center">
                <h5>Events</h5>
                <p class="display-6"><?= (int)$eventsCount ?></p>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card p-3 text-center">
                <h5>Open Support Issues</h5>
                <p class="display-6"><?= (int)$openIssues ?></p>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
