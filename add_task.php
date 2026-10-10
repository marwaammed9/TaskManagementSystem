<?php
session_start();
require_once __DIR__ . '/Classes/Database.php';
require_once __DIR__ . '/Classes/Task.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title']       ?? '');
    $description = trim($_POST['description'] ?? '');

    if (empty($title)) {
        $error = "عنوان المهمة مطلوب.";
    } else {
        $taskObj = new Task();
        $result  = $taskObj->save($_SESSION['user_id'], $title, $description);

        if ($result) {
            $success = "تمت إضافة المهمة بنجاح!";
        } else {
            $error = "حدث خطأ، حاول مرة أخرى.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>إضافة مهمة</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold">📝 إدارة المهام</a>
            <div class="d-flex gap-3">
                <a href="dashboard.php" class="nav-link">لوحة التحكم</a>
                <a href="logout.php" class="nav-link">تسجيل الخروج</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header py-3">
                        <h5 class="mb-0">➕ إضافة مهمة جديدة</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>

                        <?php if ($success): ?>
                            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">عنوان المهمة</label>
                                <input type="text" name="title" class="form-control" placeholder="عنوان المهمة" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">الوصف / الملاحظات</label>
                                <textarea name="description" class="form-control" rows="4" placeholder="الوصف..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">إضافة</button>
                        </form>

                        <div class="text-center mt-3">
                            <a href="dashboard.php">← العودة للوحة التحكم</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>