<?php
require_once __DIR__ . '/config/auth.php';
require_role(['Admin']);
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
global $pdo;
$issues = $pdo->query("SELECT i.*, u.full_name AS requester_name FROM support_issues i LEFT JOIN users u ON i.support_user_id = u.user_id ORDER BY i.created_at DESC")->fetchAll();
?>
<div class="container mt-4">
    <h3 class="mb-4 page-title">All Support Issues</h3>
    <table class="table table-bordered table-purple">
        <thead>
            <tr>
                <th>#</th><th>User</th><th>Subject</th><th>Status</th><th>Priority</th><th>Created</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($issues as $i): ?>
                <tr>
                    <td><?= (int)$i['issue_id'] ?></td>
                    <td><?= htmlspecialchars($i['requester_name'] ?? '') ?></td>
                    <td><?= htmlspecialchars($i['subject']) ?></td>
                    <td><?= htmlspecialchars($i['issue_status']) ?></td>
                    <td><?= htmlspecialchars($i['priority']) ?></td>
                    <td><?= htmlspecialchars($i['created_at']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
