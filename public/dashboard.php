<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

require_once '../backend/includes/Database.php';

$db = new Database();
$pdo = $db->getConnection();

$connected = $pdo->query("SELECT 1") !== false;
?>

<!DOCTYPE html>
<html lang="en">
<?php require_once 'components/head.php'; ?>
<body class="bg-dark">
    <?php include_once 'components/header.php'; ?>
    <?php include_once 'components/sidebar.php'; ?>

    <div class="container">
        <div class="container_info">
            <img src="assets/RomeLogo_Big.svg" alt="Rome-Project Logo" height="200" width="200"/>
            <h1 class="title">Developer Dashboard</h1>
            <p class="subtitle">Welcome, <?php echo htmlspecialchars($_SESSION['account']['username']); ?>!</p>
        </div>
        
        <!-- Database Connection Status -->
        <section>
            <h3>Database Status</h3>
            <p class="<?php echo $connected ? 'success' : 'error'; ?>">
                <?php echo $connected ? 'Database connected' : 'Database failed to connect'; ?>
            </p>
        </section>
        
        <!-- Account info -->
        <section>
            <h3>Your Account</h3>
            <p>User ID: <?php echo $_SESSION['account']['id']; ?></p>
            <p>Username: <?php echo htmlspecialchars($_SESSION['account']['username']); ?></p>
            <p>Role: <?php echo htmlspecialchars($_SESSION['account']['role']); ?></p>
            <p>Last Login: <?php echo htmlspecialchars($_SESSION['account']['last_login']); ?></p>
            <p>Last IP: <?php echo htmlspecialchars(inet_ntop($_SESSION['account']['last_ip'])); ?></p>
        </section>
    </div>
    
    <?php include_once 'components/footer.php'; ?>
</body>
</html>