<?php
session_start();
require_once __DIR__ . '/../Classes/Database.php';
require_once __DIR__ . '/../Classes/User.php';

// حماية — أدمن فقط
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$id = (int)($_GET['id'] ?? 0);

// منع الأدمن من حذف نفسه
if ($id === $_SESSION['user_id'] || $id === 0) {
    header("Location: Admin_Index.php");
    exit;
}

$userObj = new User();
$user    = $userObj->getById($id);

// منع حذف أي أدمن
if ($user['role'] === 'admin') {
    header("Location: Admin_index.php");
    exit;
}


if (!$user) {
    header("Location: Admin_Index.php");
    exit;
}

$userObj->delete($id);
header("Location: Admin_Index.php");
exit;
