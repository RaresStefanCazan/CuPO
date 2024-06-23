<?php
class UsersAdminModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getUsers() {
        $sql = "SELECT * FROM users";
        $result = $this->conn->query($sql);

        $users = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }
        return $users;
    }

    public function deleteUser($id) {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function updateUser($id, $first_name, $last_name, $email, $weight_kg, $height_cm, $gender, $role, $phone, $address, $budget_per_week) {
        $stmt = $this->conn->prepare("UPDATE users SET first_name = ?, last_name = ?, user = ?, weight_kg = ?, height_cm = ?, gender = ?, role = ?, phone = ?, address = ?, budget_per_week = ? WHERE id = ?");
        $stmt->bind_param("sssdsdssdis", $first_name, $last_name, $email, $weight_kg, $height_cm, $gender, $role, $phone, $address, $budget_per_week, $id);
        return $stmt->execute();
    }
}
?>
