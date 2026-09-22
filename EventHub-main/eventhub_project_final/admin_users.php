<?php
require_once __DIR__ . '/config/auth.php';
require_role(['Admin']);
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
global $pdo;
$users = $pdo->query("SELECT user_id, full_name, email, phone_number, user_role, account_status, created_at FROM users ORDER BY created_at DESC")->fetchAll();
?>
<div class="container mt-4">
    <h3 class="mb-4 page-title">Manage Users</h3>
    <table class="table table-bordered table-striped table-purple">
        <thead>
            <tr>
                <th>#</th><th>Name</th><th>Email</th><th>Phone</th><th>Role</th><th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $u): ?>
                <tr>
                    <td><?= (int)$u['user_id'] ?></td>
                    <td><?= htmlspecialchars($u['full_name']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= htmlspecialchars($u['phone_number']) ?></td>
                    <td><?= htmlspecialchars($u['user_role']) ?></td>
                    <td><?= htmlspecialchars($u['account_status']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
