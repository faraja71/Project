<?php
require_once __DIR__ . '/auth.php';
requireLogin();

$employeeId = (int) $_SESSION['employee_id'];
$message = $_SESSION['settings_message'] ?? '';
$messageType = $_SESSION['settings_message_type'] ?? 'success';
unset($_SESSION['settings_message'], $_SESSION['settings_message_type']);

if (empty($_SESSION['settings_csrf_token'])) {
    $_SESSION['settings_csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $token = $_POST['csrf_token'] ?? '';
        if (!is_string($token) || !hash_equals($_SESSION['settings_csrf_token'], $token)) {
            throw new InvalidArgumentException('Ombi limeisha muda wake. Tafadhali jaribu tena.');
        }

        $action = $_POST['action'] ?? '';

        if ($action === 'profile') {
            $fullname = trim($_POST['fullname'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');

            if ($fullname === '') {
                throw new InvalidArgumentException('Jina kamili linahitajika.');
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new InvalidArgumentException('Tafadhali weka email sahihi.');
            }

            $check = $pdo->prepare(
                'SELECT id FROM employees WHERE email = ? AND id != ? LIMIT 1'
            );
            $check->execute([$email, $employeeId]);
            if ($check->fetch()) {
                throw new InvalidArgumentException('Email hii inatumiwa na akaunti nyingine.');
            }

            $stmt = $pdo->prepare(
                'UPDATE employees SET fullname = ?, email = ?, phone = ? WHERE id = ?'
            );
            $stmt->execute([$fullname, $email, $phone, $employeeId]);
            $_SESSION['fullname'] = $fullname;
            $_SESSION['settings_message'] = 'Taarifa za akaunti zimesasishwa.';
        } elseif ($action === 'password') {
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if ($currentPassword === '' || $newPassword === '' || $confirmPassword === '') {
                throw new InvalidArgumentException('Jaza sehemu zote za password.');
            }

            if (strlen($newPassword) < 8) {
                throw new InvalidArgumentException('Password mpya iwe na angalau herufi 8.');
            }

            if ($newPassword !== $confirmPassword) {
                throw new InvalidArgumentException('Password mpya hazifanani.');
            }

            $stmt = $pdo->prepare('SELECT password FROM employees WHERE id = ? LIMIT 1');
            $stmt->execute([$employeeId]);
            $employee = $stmt->fetch();

            if (!$employee || !password_verify($currentPassword, $employee['password'])) {
                throw new InvalidArgumentException('Password ya sasa si sahihi.');
            }

            $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
            $update = $pdo->prepare('UPDATE employees SET password = ? WHERE id = ?');
            $update->execute([$passwordHash, $employeeId]);
            $_SESSION['settings_message'] = 'Password imebadilishwa kikamilifu.';
        } else {
            throw new InvalidArgumentException('Kitendo ulichochagua hakitambuliki.');
        }
    } catch (InvalidArgumentException $exception) {
        $_SESSION['settings_message'] = $exception->getMessage();
        $_SESSION['settings_message_type'] = 'danger';
    } catch (PDOException $exception) {
        error_log($exception->getMessage());
        $_SESSION['settings_message'] = $exception->getCode() === '23000'
            ? 'Email hii inatumiwa na akaunti nyingine.'
            : 'Imeshindikana kuhifadhi mabadiliko. Tafadhali jaribu tena.';
        $_SESSION['settings_message_type'] = 'danger';
    }

    header('Location: setting.php');
    exit;
}

$stmt = $pdo->prepare(
    'SELECT fullname, email, phone FROM employees WHERE id = ? LIMIT 1'
);
$stmt->execute([$employeeId]);
$employee = $stmt->fetch();

if (!$employee) {
    http_response_code(404);
    exit('Akaunti haikupatikana.');
}
?>
<!DOCTYPE html>
<html lang="sw">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mipangilio ya Akaunti</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="hold-transition">
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Mipangilio ya Akaunti</h1>
            <p class="text-muted mb-0">Badilisha taarifa zako na password.</p>
        </div>
        <a href="index.php" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Dashboard
        </a>
    </div>

    <?php if ($message !== ''): ?>
        <div class="alert alert-<?= e($messageType) ?>" role="alert">
            <?= e($message) ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h2 class="card-title">Taarifa za Akaunti</h2>
                </div>
                <form method="post" action="setting.php">
                    <div class="card-body">
                        <input type="hidden" name="csrf_token" value="<?= e($_SESSION['settings_csrf_token']) ?>">
                        <input type="hidden" name="action" value="profile">

                        <div class="form-group">
                            <label for="fullname">Jina kamili</label>
                            <input type="text" class="form-control" id="fullname" name="fullname"
                                   value="<?= e($employee['fullname']) ?>" maxlength="150" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                   value="<?= e($employee['email']) ?>" maxlength="150" required>
                        </div>
                        <div class="form-group mb-0">
                            <label for="phone">Simu</label>
                            <input type="tel" class="form-control" id="phone" name="phone"
                                   value="<?= e($employee['phone'] ?? '') ?>" maxlength="50">
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Hifadhi taarifa
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card card-warning">
                <div class="card-header">
                    <h2 class="card-title">Badilisha Password</h2>
                </div>
                <form method="post" action="setting.php">
                    <div class="card-body">
                        <input type="hidden" name="csrf_token" value="<?= e($_SESSION['settings_csrf_token']) ?>">
                        <input type="hidden" name="action" value="password">

                        <div class="form-group">
                            <label for="current_password">Password ya sasa</label>
                            <input type="password" class="form-control" id="current_password"
                                   name="current_password" autocomplete="current-password" required>
                        </div>
                        <div class="form-group">
                            <label for="new_password">Password mpya</label>
                            <input type="password" class="form-control" id="new_password"
                                   name="new_password" minlength="8" autocomplete="new-password" required>
                            <small class="form-text text-muted">Tumia angalau herufi 8.</small>
                        </div>
                        <div class="form-group mb-0">
                            <label for="confirm_password">Rudia password mpya</label>
                            <input type="password" class="form-control" id="confirm_password"
                                   name="confirm_password" minlength="8" autocomplete="new-password" required>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-key mr-1"></i> Badilisha password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>