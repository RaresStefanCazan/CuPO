<?php
class StatisticUserModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getUserStatistics($username) {
        try {
            $stmt = $this->conn->prepare("SELECT height, weight FROM users WHERE user = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($height, $weight);

            if ($stmt->num_rows > 0) {
                $stmt->fetch();
                return ['height' => $height, 'weight' => $weight];
            } else {
                return null;
            }
        } catch (Exception $e) {
            error_log("Error: " . $e->getMessage());
            return null;
        }
    }

    public function calculateBMI($height, $weight) {
        // Convert height to meters from cm
        $heightInMeters = $height;
        
        // Calculate BMI
        if ($heightInMeters > 0) {
            $bmi = $weight / ($heightInMeters * $heightInMeters);
            return $bmi;
        } else {
            return null;
        }
    }

    public function interpretBMI($bmi) {
        // Define BMI thresholds for interpretation
        $bmiCategories = [
            ['label' => 'Underweight', 'min' => 0, 'max' => 18.5],
            ['label' => 'Normal weight', 'min' => 18.5, 'max' => 24.9],
            ['label' => 'Overweight', 'min' => 25, 'max' => 29.9],
            ['label' => 'Obese', 'min' => 30, 'max' => 100],
        ];

        // Determine BMI category
        foreach ($bmiCategories as $category) {
            if ($bmi >= $category['min'] && $bmi <= $category['max']) {
                return $category['label'];
            }
        }

        return 'Unknown';
    }
}
?>