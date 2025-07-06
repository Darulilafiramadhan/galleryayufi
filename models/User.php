<?php
class User {
    private $conn;
    private $table = "users";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function login($username, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        return $user && password_verify($password, $user['password']);
    }

    public function register($username, $password) {
        $check = $this->conn->prepare("SELECT id FROM $this->table WHERE username=?");
        $check->bind_param("s", $username);
        $check->execute();
        if ($check->get_result()->num_rows > 0) return false;

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO $this->table (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hash);
        return $stmt->execute();
    }
}
