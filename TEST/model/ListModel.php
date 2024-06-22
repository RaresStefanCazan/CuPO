<?php
class ListsModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAllLists($email) {
        $stmt = $this->conn->prepare("SELECT * FROM lists WHERE emails LIKE CONCAT('%', ?, '%')");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        $lists = [];
        while ($row = $result->fetch_assoc()) {
            $lists[] = $row;
        }

        return $lists;
    }

    public function createList($name, $email, $group) {
        $emails = $email;
        $stmt = $this->conn->prepare("INSERT INTO lists (name, emails, `group`) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $emails, $group);
        return $stmt->execute();
    }

    public function addEmailToList($listId, $email) {
        $stmt = $this->conn->prepare("SELECT emails FROM lists WHERE id = ?");
        $stmt->bind_param("i", $listId);
        $stmt->execute();
        $result = $stmt->get_result();
        $list = $result->fetch_assoc();

        if ($list) {
            $emails = $list['emails'];
            $emailsArray = explode(',', $emails);

            if (!in_array($email, $emailsArray)) {
                $emailsArray[] = $email;
                $updatedEmails = implode(',', $emailsArray);

                $stmt = $this->conn->prepare("UPDATE lists SET emails = ? WHERE id = ?");
                $stmt->bind_param("si", $updatedEmails, $listId);
                return $stmt->execute();
            }
        }

        return false;
    }

    public function getLastInsertId() {
        return $this->conn->insert_id;
    }

    public function deleteList($listId) {
        $stmt = $this->conn->prepare("DELETE FROM lists WHERE id = ?");
        $stmt->bind_param("i", $listId);
        return $stmt->execute();
    }

    public function getListById($listId) {
        $stmt = $this->conn->prepare("SELECT * FROM lists WHERE id = ?");
        $stmt->bind_param("i", $listId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
?>
