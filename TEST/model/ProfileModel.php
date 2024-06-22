<?php
class ProfileModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getUserProfile($username) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE user = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                return $result->fetch_assoc();
            } else {
                return null;
            }
        } catch (Exception $e) {
            error_log("Error: " . $e->getMessage());
            return null;
        }
    }

    public function updateUserProfile($username, $profileData) {
        try {
            $stmt = $this->conn->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ?, weight_kg = ?, height_cm = ?, gender = ?, phone = ?, address = ?, budget_per_week = ? WHERE user = ?");
            $stmt->bind_param("ssssssssds", $profileData['first_name'], $profileData['last_name'], $profileData['email'], $profileData['weight_kg'], $profileData['height_cm'], $profileData['gender'], $profileData['phone'], $profileData['address'], $profileData['budget_per_week'], $username);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error: " . $e->getMessage());
            return false;
        }
    }
}
?>
