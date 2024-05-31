<?php
class UserModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function register($username, $password) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $reg_date = date('Y-m-d H:i:s');

        try {
            $stmt = $this->conn->prepare("INSERT INTO users (user, password, reg_date) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $passwordHash, $reg_date);

            if ($stmt->execute()) {
                return true;
            } else {
                echo "Error: " . $stmt->error;
                return false;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function login($username, $password) {
        try {
            $stmt = $this->conn->prepare("SELECT password FROM users WHERE user = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                /** @var string $hashedPassword */
                $stmt->bind_result($hashedPassword);
                $stmt->fetch();
                if (password_verify($password, $hashedPassword)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}
?>
