<?php
include_once 'components/require.php';

if ($UserClass->getRole() !== 'Developer') {
    exit;
}

$token_link = $_SESSION['token_link'] ?? '';
unset($_SESSION['token_link']);

$error = $_SESSION['token_error'] ?? '';
unset($_SESSION['token_error']);
?>

<!DOCTYPE html>
<html lang="en">
<?php require_once 'components/head.php'; ?>
<body class="bg-dark">
    <?php include_once 'components/header.php'; ?>
    <?php include_once 'components/sidebar.php'; ?>

    <div class="container">
        <h1 class="title">Generate Token</h1>

        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST" action="backend_router.php?action=token" class="login_form">
            <div class="form_content">
                <div class="item">
                    <label for="role">Role</label>
                    <select class="input" id="role" name="role" required>
                        <option value="Developer">Developer</option>
                        <option value="Influencer">Influencer</option>
                        <option value="Player">Player</option>
                    </select>
                </div>
            </div>

            <div class="login_button_div">
                <button type="submit" class="button_primary">Generate Token</button>
            </div>
        </form>

        <?php if ($token_link): ?>
            <p>Token Link: <a href="<?php echo htmlspecialchars($token_link); ?>"><?php echo htmlspecialchars($token_link); ?></a></p>
        <?php endif; ?>
    </div>

    <?php include_once 'components/footer.php'; ?>
</body>
</html>
