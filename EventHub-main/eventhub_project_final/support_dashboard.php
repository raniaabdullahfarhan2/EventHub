<?php
require_once __DIR__ . '/config/auth.php';
require_role(['Support']);
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
global $pdo;
$openIssues = $pdo->query("SELECT COUNT(*) AS c FROM support_issues WHERE issue_status = 'Open'")->fetch()['c'];
$closedIssues = $pdo->query("SELECT COUNT(*) AS c FROM support_issues WHERE issue_status = 'Closed'")->fetch()['c'];
?>
<div class="container mt-4">
    <h3 class="mb-4 page-title">Support Dashboard</h3>
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="card p-3 text-center">
                <h5>Open Issues</h5>
                <p class="display-6"><?= (int)$openIssues ?></p>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card p-3 text-center">
                <h5>Closed Issues</h5>
                <p class="display-6"><?= (int)$closedIssues ?></p>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
