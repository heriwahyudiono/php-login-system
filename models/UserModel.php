<?php
require_once '../config/connection.php';

class UserModel
{
    private $conn;

    public function __construct()
    {
        $this->conn = openConnection();
    }

    public function registerUser($name, $gender, $date_of_birth, $email, $phone_number, $password)
    {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO users (name, gender, date_of_birth, email, phone_number, password) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssss", $name, $gender, $date_of_birth, $email, $phone_number, $hashed_password);

        if ($stmt->execute()) {
            header("Location: ../views/count.php");
        } else {
            echo "Error: " . $sql . "<br>" . $this->conn->error;
        }
    }

    public function login($email)
    {
        $sql = "SELECT * FROM users WHERE email=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            return $user;
        } else {
            return null;
        }
    }

    public function getUserById($id)
    {
        $sql = "SELECT * FROM users WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            return $user;
        } else {
            return null;
        }
    }

    public function updateUser($id, $name, $gender, $date_of_birth, $email, $phone_number)
    {
        $sql = "UPDATE users SET name=?, gender=?, date_of_birth=?, email=?, phone_number=? WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssssi", $name, $gender, $date_of_birth, $email, $phone_number, $id);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function updatePassword($id, $password)
    {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $sql = "UPDATE users SET password=? WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $hashed_password, $id);
        $stmt->execute();
    }

    public function updateToken($id, $token)
    {
        $sql = "UPDATE users SET token=? WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $token, $id);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getUserByToken($token)
    {
        $sql = "SELECT * FROM users WHERE token = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            return $user;
        } else {
            return null;
        }
    }

    public function resetToken($email, $token)
    {
        $user = $this->getUserByEmail($email);
        if ($user) {
            $sql = "UPDATE users SET token = ? WHERE email = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ss", $token, $email);
            if ($stmt->execute()) {
                return true;
            }
        }
        return false; 
    }

    public function closeConnection()
    {
        $this->conn->close();
    }
}