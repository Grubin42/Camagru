<?php
$users = [
    ['username' => 'user1', 'email' => 'user1@example.com', 'password' => 'password1', 'notif' => true],
    ['username' => 'user2', 'email' => 'user2@example.com', 'password' => 'password2', 'notif' => true],
    ['username' => 'user3', 'email' => 'user3@example.com', 'password' => 'password3', 'notif' => true],
    ['username' => 'user4', 'email' => 'user4@example.com', 'password' => 'password4', 'notif' => false],
    ['username' => 'user5', 'email' => 'user5@example.com', 'password' => 'password5', 'notif' => true],
];

// Define the path to the shared volume for the output file
$outputFilePath = "/docker-entrypoint-initdb.d/shared-seeds/seed_user_hashed.sql";

// Open the file for writing
$file = fopen($outputFilePath, "w");

// Generate SQL statements with hashed passwords
foreach ($users as $user) {
    $hashedPassword = password_hash($user['password'], PASSWORD_DEFAULT);
    $notif = $user['notif'] ? 'TRUE' : 'FALSE';
    $sql = "INSERT INTO users (username, email, password, notif) VALUES ('{$user['username']}', '{$user['email']}', '$hashedPassword', $notif);\n";
    fwrite($file, $sql);
}

fclose($file);
echo "Hashed SQL seed file generated at $outputFilePath\n";
