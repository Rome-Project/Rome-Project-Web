<?php
session_start();
$token = $_GET['token'] ?? '';
$error = $_SESSION['register_error'] ?? '';
unset($_SESSION['register_error']);

if (!$token) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<?php require_once 'components/head.php'; ?>
<body class="bg-dark">
    <div class="container">
        <div class="container_info">
            <img src="assets/RomeLogo.svg" alt="Rome-Project Logo" height="200" width="200"/>
            <h1 class="title">Register Account</h1>
        </div>

        <?php if ($error): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form method="POST" action="../backend/logic/register.php?token=<?php echo htmlspecialchars($token); ?>" class="login_form">
            <div class="form_content">
                <div class="item">
                    <label for="username">Username</label>
                    <input class="input" id="username" name="username" type="text" required>
                </div>
                <div class="item">
                    <label for="password">Password (15+ chars, numbers, special chars)</label>
                    <input class="input" id="password" name="password" type="password" required>
                </div>
                <div class="item">
                    <label for="confirm_password">Confirm Password</label>
                    <input class="input" id="confirm_password" name="confirm_password" type="password" required>
                </div>
            </div>
            <div class="login_button_div">
                <button type="submit" class="button_primary">Register</button>
            </div>
        </form>
    </div>

    <?php include_once 'components/footer.php'; ?>
</body>
</html>