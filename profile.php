<?php
session_start();
require_once __DIR__ . '/Classes/Database.php';
require_once __DIR__ . '/Classes/User.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userObj = new User();
$user    = $userObj->getById($_SESSION['user_id']);

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName  = trim($_POST['last_name']  ?? '');
    $email     = trim($_POST['email']      ?? '');

    if (empty($firstName) || empty($lastName) || empty($email)) {
        $error = "يرجى ملء جميع الحقول.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "صيغة البريد الإلكتروني غير صحيحة.";
    } else {
        $result = $userObj->update($_SESSION['user_id'], $firstName, $lastName, $email);

        if ($result) {
            $_SESSION['user_name'] = $firstName;
            $success = "تم تحديث الملف الشخصي بنجاح!";
            $user    = $userObj->getById($_SESSION['user_id']);
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
    <title>الملف الشخصي</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold">📝 إدارة المهام</a>
            <div class="d-flex gap-3">
                <a href="<?= $_SESSION['user_role'] === 'admin' ? 'Admin/Admin_index.php' : 'dashboard.php' ?>" class="nav-link">← العودة</a>
                <a href="logout.php" class="nav-link">تسجيل الخروج</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header py-3">
                        <h5 class="mb-0">👤 الملف الشخصي</h5>
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
                                <label class="form-label">الاسم الأول</label>
                                <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($user['first_name']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">الاسم الأخير</label>
                                <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($user['last_name']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">البريد الإلكتروني</label>
                                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                            </div>

                            <!-- تغيير كلمة المرور -->
                            <?php
                            $passError   = '';
                            $passSuccess = '';

                            if (isset($_POST['change_password'])) {
                                $currentPassword = $_POST['current_password'] ?? '';
                                $newPassword     = $_POST['new_password']     ?? '';
                                $confirmPassword = $_POST['confirm_password'] ?? '';

                                if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
                                    $passError = "يرجى ملء جميع حقول كلمة المرور.";
                                } elseif ($newPassword !== $confirmPassword) {
                                    $passError = "كلمة المرور الجديدة غير متطابقة.";
                                } elseif (strlen($newPassword) < 6) {
                                    $passError = "كلمة المرور يجب أن تكون 6 أحرف على الأقل.";
                                } else {
                                    $result = $userObj->updatePassword($_SESSION['user_id'], $currentPassword, $newPassword);
                                    if ($result === true) {
                                        $passSuccess = "تم تغيير كلمة المرور بنجاح!";
                                    } else {
                                        $passError = $result;
                                    }
                                }
                            }
                            ?>

                            <div class="card mt-4">
                                <div class="card-header py-3">
                                    <h5 class="mb-0">🔒 تغيير كلمة المرور</h5>
                                </div>
                                <div class="card-body">
                                    <?php if ($passError): ?>
                                        <div class="alert alert-danger"><?= htmlspecialchars($passError) ?></div>
                                    <?php endif; ?>

                                    <?php if ($passSuccess): ?>
                                        <div class="alert alert-success"><?= htmlspecialchars($passSuccess) ?></div>
                                    <?php endif; ?>

                                    <form method="POST">
                                        <div class="mb-3">
                                            <label class="form-label">كلمة المرور الحالية</label>
                                            <input type="password" name="current_password" class="form-control" placeholder="••••••••">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">كلمة المرور الجديدة</label>
                                            <input type="password" name="new_password" class="form-control" placeholder="••••••••">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">تأكيد كلمة المرور الجديدة</label>
                                            <input type="password" name="confirm_password" class="form-control" placeholder="••••••••">
                                        </div>
                                        <button type="submit" name="change_password" class="btn btn-primary w-100">تغيير كلمة المرور</button>
                                    </form>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">حفظ التغييرات</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>