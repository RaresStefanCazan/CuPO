<?php 
class BasketModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getBasketItemsByListId($listId) {
        $stmt = $this->conn->prepare("SELECT items, quantity FROM lists WHERE id = ?");
        $stmt->bind_param("i", $listId);
        $stmt->execute();
        $result = $stmt->get_result();
        $list = $result->fetch_assoc();

        error_log("List data: " . json_encode($list)); 

        if ($list && !empty($list['items']) && !empty($list['quantity'])) {
            $items = explode(',', $list['items']);
            $quantities = explode(',', $list['quantity']);

            if (count($items) == count($quantities)) {
                $ids = implode(',', array_map('intval', $items));
                $sql = "SELECT * FROM foods WHERE id IN ($ids)";
                $result = $this->conn->query($sql);

                $basketItems = [];
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $index = array_search($row['id'], $items);
                        $quantity = $quantities[$index];
                        $row['quantity'] = $quantity;
                        $row['total_price'] = $row['price'] * $quantity;
                        $basketItems[] = $row;
                    }
                }

                error_log("Basket items: " . json_encode($basketItems)); 
                return $basketItems;
            }
        }
        
        return [];
    }

    public function addToBasket($listId, $foodId, $quantity) {
        $stmt = $this->conn->prepare("SELECT items, quantity FROM lists WHERE id = ?");
        $stmt->bind_param("i", $listId);
        $stmt->execute();
        $result = $stmt->get_result();
        $list = $result->fetch_assoc();
        
        if ($list) {
            $items = $list['items'] ? explode(',', $list['items']) : [];
            $quantities = $list['quantity'] ? explode(',', $list['quantity']) : [];
    
            $index = array_search($foodId, $items);
            if ($index === false) {
                $items[] = $foodId;
                $quantities[] = $quantity;
            } else {
                $quantities[$index] += $quantity;
            }
    
            $updatedItems = implode(',', $items);
            $updatedQuantities = implode(',', $quantities);
    
            $stmt = $this->conn->prepare("UPDATE lists SET items = ?, quantity = ? WHERE id = ?");
            $stmt->bind_param("ssi", $updatedItems, $updatedQuantities, $listId);
            return $stmt->execute();
        }
        
        return false;
    }

    public function removeFromBasket($listId, $foodId) {
        $stmt = $this->conn->prepare("SELECT items, quantity FROM lists WHERE id = ?");
        $stmt->bind_param("i", $listId);
        $stmt->execute();
        $result = $stmt->get_result();
        $list = $result->fetch_assoc();
        
        if ($list) {
            $items = explode(',', $list['items']);
            $quantities = explode(',', $list['quantity']);
    
            $index = array_search($foodId, $items);
            if ($index !== false) {
                array_splice($items, $index, 1);
                array_splice($quantities, $index, 1);
    
                $updatedItems = implode(',', $items);
                $updatedQuantities = implode(',', $quantities);
    
                $stmt = $this->conn->prepare("UPDATE lists SET items = ?, quantity = ? WHERE id = ?");
                $stmt->bind_param("ssi", $updatedItems, $updatedQuantities, $listId);
                return $stmt->execute();
            }
        }
        
        return false;
    }
}
