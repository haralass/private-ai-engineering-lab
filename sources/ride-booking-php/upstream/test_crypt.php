<?php
function verify_bcrypt($password, $hash) {
    return crypt($password, $hash) === $hash;
}

$hash = '$2y$10$8xXaOXjYjt7gEEVJaRpjTeAtW6Tk2x9l71s6OcFkQYMJQj0VlJOYC';

if (verify_bcrypt("123456", $hash)) {
    echo "OK";
} else {
    echo "FAIL";
}
?>
