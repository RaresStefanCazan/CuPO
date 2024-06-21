<?php
class BasketModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
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
