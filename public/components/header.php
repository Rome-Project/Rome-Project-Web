<header>
    <!-- Logo & Project Name -->
    <div class="header_left">
        <a href="dashboard.php" class="logo">
            <img src="assets/RomeLogo_Small.ico" alt="Rome Logo 2">
            <p>Rome-Project</p>
        </a>
    </div>

    <!-- Account Bubble Button -->
    <div class="header_right">
        <?php if ($_SESSION['logged_in']): ?>
            <div class="account_bubble">
                <button class="account_button" id="accountButton">
                    <span class="account_initial"><?php echo strtoupper(substr($user->getUsername(), 0, 1)); ?></span>
                </button>

                <!-- Dropdown menu -->
                <div class="dropdown_menu" id="accountDropdown">
                    <div class="dropdown_header"><?php echo $user->getUsername(); ?></div>
                    <a href="settings.php" class="dropdown_item">Settings</a>
                    
                    <?php if ($user->getRole() === 'Developer'): ?>
                        <a href="token.php" class="dropdown_item">Generate Token</a>
                    <?php endif; ?>

                    <a href="backend_router.php?action=logout" class="dropdown_item logout_item">Logout</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</header>

<!-- Antarux NOTE: I know it's not really ideal to load it here, but it's a small file so it should not cause optimization trouble in da end -->
<script src="app/TopBarNav.js"></script> 