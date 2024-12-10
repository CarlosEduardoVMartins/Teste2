<?php
require 'db.php';
require 'vendor/autoload.php'; // Inclua o autoload do Composer se estiver usando Composer

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$db = conectar_db();
$produtos = $db->query("SELECT * FROM produtos")->fetchAll(PDO::FETCH_ASSOC);

// Criar um novo Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Definir cabeçalhos das colunas
$sheet->setCellValue('A1', 'ID');
$sheet->setCellValue('B1', 'Nome');
$sheet->setCellValue('C1', 'Quantidade');
$sheet->setCellValue('D1', 'Preço');

// Preencher as linhas com os dados dos produtos
$row = 2;
foreach ($produtos as $produto) {
    $sheet->setCellValue('A' . $row, $produto['id']);
    $sheet->setCellValue('B' . $row, $produto['nome']);
    $sheet->setCellValue('C' . $row, $produto['quantidade']);
    $sheet->setCellValue('D' . $row, $produto['preco']);
    $row++;
}

// Criar um Writer para gerar o arquivo Excel
$writer = new Xlsx($spreadsheet);

// Forçar o download do arquivo Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="estoque_produtos.xlsx"');
header('Cache-Control: max-age=0');
$writer->save('php://output');
exit;
