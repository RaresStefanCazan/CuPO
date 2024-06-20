<?php
class UserModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function register($username, $password, $role = 'user') {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $reg_date = date('Y-m-d H:i:s');

        try {
            $stmt = $this->conn->prepare("INSERT INTO users (user, password, role, reg_date) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $passwordHash, $role, $reg_date);

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
            $stmt = $this->conn->prepare("SELECT password, role FROM users WHERE user = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();
                                /** @var string $hashedPassword */
                                /** @var string $role */
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($hashedPassword, $role);
                $stmt->fetch();
                if (password_verify($password, $hashedPassword)) {
                    return ['role' => $role];
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
  
    public function getUserRole($username) {
        try {
            $stmt = $this->conn->prepare("SELECT role FROM users WHERE user = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
             /** @var string $role */
            $stmt->bind_result($role);
            $stmt->fetch();
            return $role;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return null;
        }
    }
    


}

?>
