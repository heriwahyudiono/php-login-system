<?php
// Untuk memulai sesi/session
session_start();

// Untuk memanggil model user
require_once '../models/UserModel.php';

// Jika request method == post 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Tangkap input registrasi user
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $date_of_birth = $_POST['date_of_birth'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Jika password yang di masukkan tidak sama dengan konfirmasi password
    if ($password != $confirm_password) {
        echo "Konfirmasi password tidak sesuai";
        exit;
    }

    // Panggil kelas UserModel
    $userModel = new UserModel();

    // Panggil fungsi registerUser
    $userModel->registerUser($name, $gender, $date_of_birth, $email, $phone_number, $password);

    // Menyimpan sesi user berdasarkan id
    $_SESSION['id'] = $userModel->login($email)['id'];

    // Menutup koneksi ke database
    $userModel->closeConnection();

    // Jika user berhasil registrasi, arahkan ke halaman home
    header("Location: ../views/home.php");
}
