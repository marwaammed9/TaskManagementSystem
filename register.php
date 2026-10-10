<?php
session_start();
require_once __DIR__ . '/Classes/Database.php';
require_once __DIR__ . '/Classes/User.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName  = trim($_POST['last_name']  ?? '');
    $email     = trim($_POST['email']      ?? '');
    $password  = trim($_POST['password']   ?? '');

    if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
        $error = "يرجى ملء جميع الحقول.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "صيغة البريد الإلكتروني غير صحيحة.";
    } elseif (strlen($password) < 6) {
        $error = "كلمة المرور يجب أن تكون 6 أحرف على الأقل.";
    } else {
        $userObj = new User();
        $result  = $userObj->save($firstName, $lastName, $email, $password);

        if ($result) {
            $success = "تم إنشاء الحساب بنجاح!";
        } else {
            $error = "البريد الإلكتروني مستخدم مسبقاً.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>إنشاء حساب</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>

<body class="d-flex align-items-center justify-content-center min-vh-100">
    <div class="card p-4" style="width: 400px;">
        <div class="card-header text-center py-3 mb-3">
            <h4 class="mb-0">✨ إنشاء حساب جديد</h4>
        </div>
        <div class="card-body">
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($success) ?>
                    <a href="login.php">سجّل دخولك</a>
                </div>
            <?php else: ?>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">الاسم الأول</label>
                        <input type="text" name="first_name" class="form-control" placeholder="الاسم الأول" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الاسم الأخير</label>
                        <input type="text" name="last_name" class="form-control" placeholder="الاسم الأخير" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">البريد الإلكتروني</label>
                        <input type="email" name="email" class="form-control" placeholder="example@email.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">كلمة المرور</label>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">إنشاء حساب</button>
                </form>
                <div class="text-center mt-3">
                    عندك حساب؟ <a href="login.php">سجّل دخولك</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>