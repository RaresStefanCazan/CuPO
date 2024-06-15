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
}
?>
