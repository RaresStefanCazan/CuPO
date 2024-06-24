<?php


class StatisticsModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getUserData($username) {
        try {
            $stmt = $this->conn->prepare("SELECT height_cm, weight_kg FROM users WHERE user = ?");
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

    public function calculateBMI($height_cm, $weight_kg) {
        
        $height_m = $height_cm / 100;
        
        // calculeaza BMI
        if ($height_m > 0) {
            $bmi = $weight_kg / ($height_m * $height_m);
            return $bmi;
        } else {
            return null;
        }
    }

    public function interpretBMI($bmi) {
        if ($bmi === null) {
            return 'Unknown';
        } else if ($bmi < 18.5) {
            return 'Underweight';
        } else if ($bmi < 25) {
            return 'Normal weight';
        } else if ($bmi < 30) {
            return 'Overweight';
        } else {
            return 'Obese';
        }
    }
}
?>
