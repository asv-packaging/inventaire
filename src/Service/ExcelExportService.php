<?php
namespace App\Service;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelExportService
{
    public function exportToExcel(array $headers, array $data, string $filePath)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Ajouter les en-têtes
        $sheet->fromArray($headers, null, 'A1');

        // Ajouter les données au fichier Excel
        $sheet->fromArray($data, null, 'A2');

        // Sauvegarde du fichier Excel
        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);
    }
}