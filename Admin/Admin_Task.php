<?php
session_start();
require_once __DIR__ . '/../Classes/Database.php';
require_once __DIR__ . '/../Classes/Task.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$taskObj = new Task();
$tasks   = $taskObj->getAllTasks();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>لوحة الأدمن - المهام</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../style.css" rel="stylesheet">
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold">🔧 لوحة الأدمن</a>
            <div class="d-flex gap-3">
                <a href="Admin_index.php" class="nav-link">المستخدمون</a>
                <a href="Admin_Task.php" class="nav-link fw-bold">المهام</a>
                <a href="../profile.php" class="nav-link">الملف الشخصي</a>
                <a href="../logout.php" class="nav-link">تسجيل الخروج</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">كل المهام</h4>
            <span class="badge bg-secondary"><?= count($tasks) ?> مهمة</span>
        </div>

        <?php if (empty($tasks)): ?>
            <div class="card text-center p-5">
                <p class="text-muted mb-0">لا توجد مهام في النظام.</p>
            </div>
        <?php else: ?>
            <div class="card">
                <div class="card-header py-3">
                    <h5 class="mb-0">📋 المهام</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>العنوان</th>
                                <th>الوصف</th>
                                <th>صاحب المهمة</th>
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
                                    <td><?= htmlspecialchars($task['first_name'] . ' ' . $task['last_name']) ?></td>
                                    <td>
                                        <span class="badge-<?= $task['status'] ?>">
                                            <?= $task['status'] === 'pending' ? 'معلّقة' : 'مكتملة' ?>
                                        </span>
                                    </td>
                                    <td><?= $task['created_at'] ?></td>
                                    <td>
                                        <a href="../Edit_task.php?id=<?= $task['id'] ?>" class="btn btn-sm btn-outline-primary">تعديل</a>
                                        <a href="../delete_task.php?id=<?= $task['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('هل أنت متأكد من الحذف؟')">حذف</a>
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