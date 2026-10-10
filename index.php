<?php
session_start();
require_once __DIR__ . '/Classes/Database.php';

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['user_role'] === 'admin') {
        header("Location: Admin/Admin_index.php");
    } else {
        header("Location: dashboard.php");
    }
    exit;
}

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>نظام إدارة المهام</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold">📝 نظام إدارة المهام</a>
            <div class="d-flex gap-3">
                <a href="login.php" class="nav-link">تسجيل الدخول</a>
                <a href="register.php" class="nav-link">إنشاء حساب</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <!-- Hero -->
        <div class="card text-center p-5 mb-4">
            <h1 class="mb-3" style="color:#7B2FBE">📝 نظام إدارة المهام</h1>
            <p class="text-muted mb-4">تابع مهامك اليومية بسهولة وكفاءة</p>
            <div class="d-flex gap-3 justify-content-center">
                <a href="login.php" class="btn btn-primary px-4">تسجيل الدخول</a>
                <a href="register.php" class="btn btn-outline-secondary px-4">إنشاء حساب</a>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>