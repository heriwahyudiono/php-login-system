<?php

// Fungsi untuk membuka koneksi
function openConnection() {
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $database = "php_login_system";

    $conn = new mysqli($hostname, $username, $password, $database);

    // Jika koneksinya error
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Jika koneksi berhasil
    return $conn;
}