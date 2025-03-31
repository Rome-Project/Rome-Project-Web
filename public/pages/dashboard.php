<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

require_once '../../backend/includes/Database.php';

$db = new Database();
$pdo = $db->getConnection();

$connected = $pdo->query("SELECT 1") !== false;
?>

<!DOCTYPE html>
<html lang="en">
<?php require_once '../components/head.php';?>
<body class="bg-dark">
    <?php include_once '../components/header.php';?>
    <div class="container">
        <div class="container_info">
            <img src="../Assets/RomeLogo.svg" alt="Rome-Project Logo" height="200" width="200"/>
            <h1 class="title">Developer Dashboard</h1>
            <p class="subtitle">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
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
            <p>User ID: <?php echo $_SESSION['user_id'];?></p>
            <p>Username: <?php echo htmlspecialchars($_SESSION['username']); ?></p>
            <p>Last Login: <?php echo htmlspecialchars($_SESSION['last_login']); ?></p>
            <p>Last IP: <?php echo htmlspecialchars(inet_ntop( $_SESSION['last_ip'])); ?></p>
        </section>
        
        <div class="login_button_div">
            <a href="logout.php"><button class="button_primary" id="logoutButton">Logout</button></a>
        </div>
    </div>
    <?php include_once '../components/footer.php';?>
</body>
</html>