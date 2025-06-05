<?php
$plaintext = 'admin123';
$hash = password_hash($plaintext, PASSWORD_DEFAULT);
echo "Hash baru untuk admin123: " . $hash;
?>