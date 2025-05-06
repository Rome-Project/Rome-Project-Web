<?php include_once 'components/require.php'; ?>

<!DOCTYPE html>
<html lang="en">
<?php require_once 'components/head.php'; ?>
<body>
    <?php include_once 'components/header.php'; ?>
    <?php include_once 'components/sidebar.php'; ?>

    <div class="ban_container">
        <form class="ban_form" id="banForm" method="POST">
            <div class="ban_form_content">
                <!-- Player ID -->
                <div class="ban_form_group">
                    <label for="player_id">Player ID</label>
                    <input 
                        type="number" 
                        class="ban_form_input" 
                        name="player_id" 
                        id="player_id" 
                        required
                    >
                </div>
                <!-- Reason -->
                <div class="ban_form_group">
                    <label for="reason">Reason</label>
                    <textarea 
                        class="ban_form_input" 
                        name="reason" 
                        id="reason" 
                        maxlength="255" 
                        style="height: 25rem; resize: none;" 
                        rows="10" 
                        required
                    ></textarea>
                </div>
                <!-- Ban Duration -->
                <div class="ban_form_group">
                    <label for="duration">Duration</label>
                    <select class="ban_form_input" name="duration" id="duration" required>
                        <option value="3600">1 Hour</option>
                        <option value="86400">1 Day</option>
                        <option value="604800">1 Week</option>
                        <option value="2592000">1 Month</option>
                        <option value="31536000">1 Year</option>
                        <option value="0">Permanent</option>
                    </select>
                </div>

                <button type="submit" class="button_primary">Ban Player</button>
            </div>
        </form>
        
        <div class="available_bans">
            <!-- TODO -->
            <h2>Banned Players</h2>
            <form class="search_form" method="GET">
                <div class="search_form_content">
                    <div class="search_form_group">
                        <input 
                            type="text" 
                            class="search_form_input" 
                            name="search_id" 
                            id="search_id" 
                            placeholder="Search bans"
                            required
                        >
                    </div>

                    <button type="submit" class="button_primary">Search</button>
                </div>
            </form>

            <div class="bans_data">
                <!-- TODO: Dynamic ban data loading -->
            </div>
        </div>
    </div>

    <?php include_once 'components/footer.php'; ?>
</body>
</html>