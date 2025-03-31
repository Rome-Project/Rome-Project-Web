USE rome_project_db;
INSERT INTO users (username, password_hash, last_ip)
VALUES('USERNAME', 'HASHED PASSWORD', INET6_ATON('192.168.1.100'));

/*
<?php
$password = 'PASSWORD';
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Password Hash is: " . $hash;
?>
*/