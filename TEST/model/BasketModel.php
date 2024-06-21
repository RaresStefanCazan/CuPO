<?php
class BasketModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getBasketItems($item_ids) {
        if (empty($item_ids)) {
            return [];
        }

        $ids = implode(',', array_map('intval', $item_ids));
        $sql = "SELECT * FROM foods WHERE id IN ($ids)";
        $result = $this->conn->query($sql);

        $items = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $items[] = $row;
            }
        }
        return $items;
    }

    public function addToBasket($listId, $foodId) {
        $stmt = $this->conn->prepare("SELECT items FROM lists WHERE id = ?");
        $stmt->bind_param("i", $listId);
        $stmt->execute();
        $result = $stmt->get_result();
        $list = $result->fetch_assoc();
        
        if ($list) {
            $items = $list['items'];
            $itemsArray = explode(',', $items);
    
            if (!in_array($foodId, $itemsArray)) {
                $itemsArray[] = $foodId;
                $updatedItems = implode(',', $itemsArray);
    
                $stmt = $this->conn->prepare("UPDATE lists SET items = ? WHERE id = ?");
                $stmt->bind_param("si", $updatedItems, $listId);
                return $stmt->execute();
            }
        }
        
        return false;
    }
}
?>
