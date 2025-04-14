<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<?php require_once 'components/head.php'; ?>
<body class="bg-dark">
    <?php include_once 'components/header.php'; ?>
    <?php include_once 'components/sidebar.php'; ?>

    <div class="container">
        <div class="container_info">
            <img src="assets/RomeLogo.svg" alt="Rome-Project Logo" height="200" width="200"/>
            <h1 class="title">Hello, World!</h1>
            <p class="subtitle">scripts.php</p>
        </div>
    </div>
    
    <?php include_once 'components/footer.php'; ?>
</body>
</html>