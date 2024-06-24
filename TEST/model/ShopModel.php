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

    public function getFoodsSortedByPrice($order) {
        $sql = "SELECT * FROM foods ORDER BY price $order";
        $result = $this->conn->query($sql);

        $foods = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $foods[] = $row;
            }
        }
        return $foods;
    }

    public function getFoodsByQuery($query) {
        $query = $this->conn->real_escape_string($query);
        
      
        $sql = "(SELECT * FROM foods WHERE aliment LIKE '$query%') 
                UNION 
                (SELECT * FROM foods WHERE aliment LIKE '%$query%' AND aliment NOT LIKE '$query%')";
        
        $result = $this->conn->query($sql);

        $foods = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $foods[] = $row;
            }
        }
        return $foods;
    }

    public function getFoodsByCategory($category) {
        $category = $this->conn->real_escape_string($category);
        
        $sql = "SELECT * FROM foods WHERE category = '$category'";
        
        $result = $this->conn->query($sql);
    
        $foods = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $foods[] = $row;
            }
        }
        return $foods;
    }

    public function deleteFood($id) {
        $stmt = $this->conn->prepare("DELETE FROM foods WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function updateFood($id, $aliment, $category, $price, $restrictions, $perishability, $validity, $availability_season, $availability_region, $specific_restaurants, $weight, $protein, $fiber, $calories) {
        $stmt = $this->conn->prepare("UPDATE foods SET aliment = ?, category = ?, price = ?, restrictions = ?, perishability = ?, validity = ?, availability_season = ?, availability_region = ?, specific_restaurants = ?, weight = ?, protein = ?, fiber = ?, calories = ? WHERE id = ?");
        $stmt->bind_param("ssdsdsssssdssi", $aliment, $category, $price, $restrictions, $perishability, $validity, $availability_season, $availability_region, $specific_restaurants, $weight, $protein, $fiber, $calories, $id);
        
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

}
?>
