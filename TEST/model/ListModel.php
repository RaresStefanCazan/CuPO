<?php
class ListsModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getListsByUserId($userId) {
        $stmt = $this->conn->prepare("SELECT * FROM lists WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $lists = [];
        while ($row = $result->fetch_assoc()) {
            $lists[] = $row;
        }

        return $lists;
    }

    public function createList($name, $userId) {
        $stmt = $this->conn->prepare("INSERT INTO lists (name, created_by) VALUES (?, ?)");
        $stmt->bind_param("si", $name, $userId);
        return $stmt->execute();
    }
}
?>
