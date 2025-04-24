<?php include_once 'components/require.php'; ?>

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
            <p class="subtitle">Welcome, <?php echo $user->getUsername(); ?>!</p>
        </div>
        
        <!-- Database Connection Status -->
        <section>
            <h3>Database Status</h3>
            <p class="<?php echo $pdo ? 'success' : 'error'; ?>">
                <?php echo $pdo ? 'Database connected' : 'Database failed to connect'; ?>
            </p>
        </section>
        
        <!-- Account info -->
        <section>
            <h3>Your Account</h3>
            <p>User ID: <?php echo $user->getUserID(); ?></p>
            <p>Username: <?php echo $user->getUsername(); ?></p>
            <p>Role: <?php echo $user->getRole(); ?></p>
            <p>Created At: <?php echo $user->getCreatedAt(); ?></p>
            <p>Last Login: <?php echo $user->getLastLogin(); ?></p>
            <p>Account Enabled: <?php 
                if ($user->getIsEnabled()) {
                    echo '<span class="success">True</span>';
                } else {
                    echo '<span class="error">False</span>';
                }
            ?></p>
        </section>
    </div>
    
    <?php include_once 'components/footer.php'; ?>
</body>
</html>