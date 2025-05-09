<?php
// Validates password strength
function validatePasswordStrength(string $password): bool {
    if (strlen($password) < 15) {
        $_SESSION['register_error'] = "Password must be at least 15 characters long";
        return false;
    }
    
    if (!preg_match('/[0-9]/', $password)) {
        $_SESSION['register_error'] = "Password must include numbers";
        return false;
    }

    if (!preg_match('/[\W]/', $password)) {
        $_SESSION['register_error'] = "Password must include special characters";
        return false;
    }
    
    return true;
}

/*
    - Hashes the password using blowfish alogithm
    - https://www.php.net/manual/en/function.password-hash.php 
*/
function hashPassword(string $password): string {
    return password_hash($password, PASSWORD_BCRYPT);
}

/*
    - Verifies the password with the hashed version
    - https://www.php.net/manual/en/function.password-verify.php
*/
function verifyPassword(string $password, string $hash): bool {
    return password_verify($password, $hash);
}

/*
    - Validates the email address, to make sure, well, that it is a valid email
    - https://www.php.net/manual/en/function.filter-var.php
*/
function validateEmail(string $email): mixed {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/* 
    - Generates a random secured token for registration
    - Uses 128 bits for hexa string security basically lol
    - https://www.php.net/manual/en/function.bin2hex.php
*/
function generateToken(): string {
    return bin2hex(random_bytes(16));
}   
?>