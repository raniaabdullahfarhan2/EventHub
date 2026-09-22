<?php
require_once __DIR__ . '/config/auth.php';
logout_user();
header("Location: index.php");
exit;
?>
