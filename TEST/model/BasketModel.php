<?php
class BasketModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getBasketItemsByListId($listId) {
        $stmt = $this->conn->prepare("SELECT items FROM lists WHERE id = ?");
        $stmt->bind_param("i", $listId);
        $stmt->execute();
        $result = $stmt->get_result();
        $list = $result->fetch_assoc();

        error_log("List data: " . json_encode($list)); // Debugging: afișăm datele listei

        if ($list && !empty($list['items'])) {
            $items = $list['items'];
            $itemsArray = explode(',', $items);

            if (!empty($itemsArray)) {
                $ids = implode(',', array_map('intval', $itemsArray));
                $sql = "SELECT * FROM foods WHERE id IN ($ids)";
                $result = $this->conn->query($sql);

                $basketItems = [];
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $basketItems[] = $row;
                    }
                }

                error_log("Basket items: " . json_encode($basketItems)); // Debugging: afișăm produsele din coș
                return $basketItems;
            }
        }
        
        return [];
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
