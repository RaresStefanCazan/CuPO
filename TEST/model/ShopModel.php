<?php
class ShopModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getFoods() {
        $sql = "SELECT * FROM foods";
        $result = $this->conn->query($sql);

        $foods = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $foods[] = $row;
            }
        }
        return $foods;
    }
}
?>
