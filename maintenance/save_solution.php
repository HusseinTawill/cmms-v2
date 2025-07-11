<?php
session_start();
require 'vendor/autoload.php'; // If using Composer

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$problem = $_POST['problem_description'] ?? '';
$solution = $_POST['solution'] ?? '';

// Excel file path
$filePath = 'ticket_solutions.xlsx';

// Load or create file
if (file_exists($filePath)) {
    $spreadsheet = IOFactory::load($filePath);
    $sheet = $spreadsheet->getActiveSheet();
} else {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A1', 'Problem');
    $sheet->setCellValue('B1', 'Solution');
}

// Add new row
$lastRow = $sheet->getHighestRow() + 1;
$sheet->setCellValue("A$lastRow", $problem);
$sheet->setCellValue("B$lastRow", $solution);

// Save file
$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save($filePath);

// Optional: feedback
$_SESSION['success'] = "Solution saved to Excel.";
header("Location: dashboard.php");
exit();
?>
