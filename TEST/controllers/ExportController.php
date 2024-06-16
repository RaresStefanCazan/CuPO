<?php
require_once __DIR__ . '/../model/database.php';
require_once __DIR__ . '/../model/StatisticsModel.php';

class ExportController {
    private $conn;
    private $statisticsModel;

    public function __construct($conn) {
        $this->conn = $conn;
        $this->statisticsModel = new StatisticsModel($conn);
    }

    public function exportCSV() {
        $data = $this->statisticsModel->getExpensiveProducts();

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="expensive_products.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, array('Product', 'Price'));

        foreach ($data as $row) {
            fputcsv($output, $row);
        }
        fclose($output);
        exit();
    }
}
?>
