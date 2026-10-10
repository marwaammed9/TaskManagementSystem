<?php
session_start();
require_once __DIR__ . '/../Classes/Database.php';
require_once __DIR__ . '/../Classes/User.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$userObj = new User();
$users   = $userObj->getAllUsers();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>لوحة الأدمن - المستخدمون</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../style.css" rel="stylesheet">
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold">🔧 لوحة الأدمن</a>
            <div class="d-flex gap-3">
                <a href="Admin_index.php" class="nav-link fw-bold">المستخدمون</a>
                <a href="Admin_Task.php" class="nav-link">المهام</a>
                <a href="../profile.php" class="nav-link">الملف الشخصي</a>
                <a href="../logout.php" class="nav-link">تسجيل الخروج</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">قائمة المستخدمين</h4>
            <span class="badge bg-secondary"><?= count($users) ?> مستخدم</span>
        </div>

        <div class="card">
            <div class="card-header py-3">
                <h5 class="mb-0">👥 المستخدمون</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الاسم</th>
                            <th>البريد الإلكتروني</th>
                            <th>الدور</th>
                            <th>تاريخ التسجيل</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $i => $user): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td>
                                    <?php if ($user['role'] === 'admin'): ?>
                                        <span class="badge" style="background:#7B2FBE">أدمن</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">مستخدم</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $user['created_at'] ?></td>
                                <td>
                                    <?php if ($user['id'] === $_SESSION['user_id']): ?>
                                        <span class="text-muted">أنت</span>
                                    <?php elseif ($user['role'] === 'admin'): ?>
                                        <a href="#" class="btn btn-sm btn-outline-danger"
                                            onclick="alert('🔒 هذا المستخدم أدمن ولا يمكن حذفه!'); return false;">حذف</a>
                                    <?php else: ?>
                                        <a href="Admin_DeleteUser.php?id=<?= $user['id'] ?>"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')">حذف</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>