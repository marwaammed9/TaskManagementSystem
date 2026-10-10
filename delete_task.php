<?php
session_start();
require_once __DIR__ . '/Classes/Database.php';
require_once __DIR__ . '/Classes/Task.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$taskObj = new Task();
$id      = (int)($_GET['id'] ?? 0);
$isAdmin = $_SESSION['user_role'] === 'admin';
$task    = $taskObj->getById($id);

// تأكد المهمة موجودة وتابعة للمستخدم
if (!$task || (!$isAdmin && $task['user_id'] !== $_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$taskObj->delete($id, $_SESSION['user_id'], $isAdmin);

if ($isAdmin) {
    header("Location: Admin/Admin_task.php");
} else {
    header("Location: dashboard.php");
}
exit;
