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

if (!$task || (!$isAdmin && $task['user_id'] !== $_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title']       ?? '');
    $description = trim($_POST['description'] ?? '');
    $status      = $_POST['status'] ?? 'pending';

    if (empty($title)) {
        $error = "عنوان المهمة مطلوب.";
    } else {
        $result = $taskObj->update($id, $title, $description, $status, $_SESSION['user_id'], $isAdmin);

        if ($result) {
            $success = "تم تعديل المهمة بنجاح!";
            $task    = $taskObj->getById($id);
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
    <title>تعديل المهمة</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold">📝 إدارة المهام</a>
            <div class="d-flex gap-3">
                <a href="<?= $isAdmin ? 'Admin/Admin_Task.php' : 'dashboard.php' ?>" class="nav-link">← العودة</a>
                <a href="<?= $isAdmin ? 'Admin/Admin_index.php' : 'dashboard.php' ?>" class="nav-link">لوحة التحكم</a>
                <a href="<?= $isAdmin ? '../' : '' ?>logout.php" class="nav-link">تسجيل الخروج</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header py-3">
                        <h5 class="mb-0">✏️ تعديل المهمة</h5>
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
                                <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($task['title']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">الوصف / الملاحظات</label>
                                <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($task['description'] ?? '') ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">الحالة</label>
                                <select name="status" class="form-select">
                                    <option value="pending" <?= $task['status'] === 'pending'   ? 'selected' : '' ?>>معلّقة</option>
                                    <option value="completed" <?= $task['status'] === 'completed' ? 'selected' : '' ?>>مكتملة</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">حفظ التعديلات</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>