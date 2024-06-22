<?php

class FoodModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getFoodData() {
        try {
            $stmt = $this->conn->prepare("SELECT id, aliment AS name, protein, fiber, calories, price FROM foods");
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error: " . $e->getMessage());
            return null;
        }
    }

    public function getFoodsAboveCalories($calories) {
        try {
            $stmt = $this->conn->prepare("SELECT id, aliment AS name, protein, fiber, calories, price FROM foods WHERE calories > ?");
            $stmt->bind_param("i", $calories);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error: " . $e->getMessage());
            return null;
        }
    }

    public function getFoodsBelowCalories($calories) {
        try {
            $stmt = $this->conn->prepare("SELECT id, aliment AS name, protein, fiber, calories, price FROM foods WHERE calories < ?");
            $stmt->bind_param("i", $calories);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error: " . $e->getMessage());
            return null;
        }
    }

    public function getFoodsInRangeCalories($minCalories, $maxCalories) {
        try {
            $stmt = $this->conn->prepare("SELECT id, aliment AS name, protein, fiber, calories, price FROM foods WHERE calories >= ? AND calories <= ?");
            $stmt->bind_param("ii", $minCalories, $maxCalories);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error: " . $e->getMessage());
            return null;
        }
    }

    public function getMostExpensiveFood() {
        try {
            $stmt = $this->conn->prepare("SELECT aliment AS name, protein, fiber, calories, price FROM foods ORDER BY price DESC LIMIT 1");
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        } catch (Exception $e) {
            error_log("Error: " . $e->getMessage());
            return null;
        }
    }

    public function getHighestCaloriesFood() {
        try {
            $stmt = $this->conn->prepare("SELECT aliment AS name, protein, fiber, calories, price FROM foods ORDER BY calories DESC LIMIT 1");
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        } catch (Exception $e) {
            error_log("Error: " . $e->getMessage());
            return null;
        }
    }

    public function getLowestCaloriesFood() {
        try {
            $stmt = $this->conn->prepare("SELECT aliment AS name, protein, fiber, calories, price FROM foods ORDER BY calories ASC LIMIT 1");
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        } catch (Exception $e) {
            error_log("Error: " . $e->getMessage());
            return null;
        }
    }

    public function getHighestProteinFood() {
        try {
            $stmt = $this->conn->prepare("SELECT aliment AS name, protein, fiber, calories, price FROM foods ORDER BY protein DESC LIMIT 1");
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        } catch (Exception $e) {
            error_log("Error: " . $e->getMessage());
            return null;
        }
    }
}

?>