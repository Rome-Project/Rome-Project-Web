<?php
    session_start();
    if (isset($_SESSION['logged_in'])) {
        header("Location: dashboard.php");
        exit;
    }
    
    $error = $_SESSION['login_error'] ?? '';
    unset($_SESSION['login_error']);

    include_once 'components/head.php';
?>

<!DOCTYPE html>
<html lang="en">
<body class="bg-dark">
    <div class="container"> 
        <div class="container_info">
            <img src="assets/RomeLogo_Big.svg" alt="Rome-Project Logo" height="200" width="200"/>
            <h1 class="title">Rome-Project</h1>
            <p class="subtitle">Developer Panel Access</p>
        </div>

        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <form class="login_form" id="loginForm" action="backend_router.php?action=login" method="POST">
            <div class="form_content">
                <!-- Username Item -->
                <div class="item">
                    <label for="username">Username</label>
                    <input
                        class="input"
                        id="username"
                        name="username"
                        type="text"
                        placeholder="Enter your username"
                        autocomplete="username"
                        required
                    >
                </div>
                <!-- Password Item -->
                <div class="item">
                    <label for="password">Password</label>
                    <input
                        class="input"
                        id="password"
                        name="password"
                        type="password"
                        placeholder="Enter your password"
                        autocomplete="current-password"
                        required
                    >
                </div>
            </div>
            
            <div class="login_button_div">
                <button type="submit" class="button_primary" id="loginButton">
                    Login
                </button>
            </div>
        </form>

        <div class="footer_info">
            <p>For technical support, contact system administrator</p>
        </div>
    </div>

    <?php include_once 'components/footer.php'; ?>
</body>
</html>