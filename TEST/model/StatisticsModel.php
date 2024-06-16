<?php
class StatisticsModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getExpensiveProducts() {
        $sql = "SELECT aliment, price FROM foods ORDER BY price DESC";
        $result = $this->conn->query($sql);

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function getFavouriteProducts() {
        $sql = "SELECT aliment, price FROM foods WHERE favourite = 1";
        $result = $this->conn->query($sql);

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function getVeganProducts() {
        $sql = "SELECT aliment, price FROM foods WHERE vegan = 1";
        $result = $this->conn->query($sql);

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function getLactoseFreeProducts() {
        $sql = "SELECT aliment, price FROM foods WHERE lactose_free = 1";
        $result = $this->conn->query($sql);

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    // Adaugă alte funcții pentru generarea altor statistici...
}
?>
