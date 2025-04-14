<header>
    <!-- Logo & Project Name -->
    <div class="header_left">
        <a href="dashboard.php" class="logo">
            <img src="assets/RomeLogo2.svg" alt="Rome Logo 2">
            <p>Rome-Project</p>
        </a>
    </div>

    <!-- Account Bubble Button -->
    <div class="header_right">
        <?php if ($_SESSION['logged_in']): ?>
            <div class="account_bubble">
                <button class="account_button" id="accountButton">
                    <span class="account_initial"><?php echo strtoupper(substr($_SESSION['account']['username'], 0, 1)); ?></span>
                </button>

                <!-- Dropdown menu -->
                <div class="dropdown_menu" id="accountDropdown">
                    <div class="dropdown_header"><?php echo htmlspecialchars($_SESSION['account']['username']); ?></div>
                    <a href="settings.php" class="dropdown_item">Settings</a>
                    
                    <?php if (isset($_SESSION['account']['role']) && $_SESSION['account']['role'] === 'Developer'): ?>
                        <a href="token.php" class="dropdown_item">Generate Token</a>
                    <?php endif; ?>

                    <a href="backend_router.php?action=logout" class="dropdown_item logout_item">Logout</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</header>

<script>
    // Toggle account dropdown
    document.getElementById('accountButton').addEventListener('click', function() {
        const dropdown = document.getElementById('accountDropdown');
        dropdown.classList.toggle('show');
    });

    // Close dropdown when clicking outside
    window.addEventListener('click', function(event) {
        if (!event.target.closest('.account_button')) {
            const dropdown = document.getElementById('accountDropdown');
            if (dropdown?.classList.contains('show')) {
                dropdown.classList.remove('show');
            }
        }
    });
</script>
