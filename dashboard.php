<?php
session_start();
require_once __DIR__ . '/Classes/Database.php';
require_once __DIR__ . '/Classes/Task.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['user_role'] === 'admin') {
    header("Location: Admin/Admin_Index.php");
    exit;
}

$taskObj = new Task();
$tasks   = $taskObj->getByUser($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>لوحة التحكم</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold">📝 إدارة المهام</a>
            <div class="d-flex gap-3">
                <a href="profile.php" class="nav-link">👤<?= htmlspecialchars($_SESSION['user_name']) ?></a>
                <a href="logout.php" class="nav-link">تسجيل الخروج</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">أهلاً، <?= htmlspecialchars($_SESSION['user_name']) ?> 👋</h4>
            <a href="add_task.php" class="btn btn-primary">+ إضافة مهمة</a>
        </div>

        <!-- Tasks -->
        <?php if (empty($tasks)): ?>
            <div class="card text-center p-5">
                <p class="text-muted mb-3">لا توجد مهام بعد.</p>
                <a href="add_task.php" class="btn btn-primary w-25 mx-auto">أضف مهمة الآن</a>
            </div>
        <?php else: ?>
            <div class="card">
                <div class="card-header py-3">
                    <h5 class="mb-0">مهامي</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>العنوان</th>
                                <th>الوصف</th>
                                <th>الحالة</th>
                                <th>تاريخ الإنشاء</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tasks as $i => $task): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><?= htmlspecialchars($task['title']) ?></td>
                                    <td><?= htmlspecialchars($task['description'] ?? '-') ?></td>
                                    <td>
                                        <span class="badge-<?= $task['status'] ?>">
                                            <?= $task['status'] === 'pending' ? 'معلّقة' : 'مكتملة' ?>
                                        </span>
                                    </td>
                                    <td><?= $task['created_at'] ?></td>
                                    <td>
                                        <a href="edit_task.php?id=<?= $task['id'] ?>" class="btn btn-sm btn-outline-primary">تعديل</a>
                                        <a href="delete_task.php?id=<?= $task['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('هل أنت متأكد من الحذف؟')">حذف</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>