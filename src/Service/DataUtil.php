<?php
namespace App\Service;

class DataUtil
{
    public static function readExcelArray($xls_file, $first_row_key = false)
    {
        $x = self::readExcelSheets($xls_file, $first_row_key);
        $ret = array_shift($x);
        return $ret;
    }

    public static function readExcelSheets($xls_file, $first_row_key = false)
    {
        if (!file_exists($xls_file)) {
            throw new \Exception('File ' . $xls_file . ' not found');
        }
        $objPHPExcel = \PhpOffice\PhpSpreadsheet\IOFactory::load($xls_file);
        $sheets_names = $objPHPExcel->getSheetNames();
        $sheets_index = array_flip($sheets_names);

        $ret = [];
        if ($first_row_key) {
            foreach ($sheets_index as $k => $i) {
                $ret[$k]['data'] = $objPHPExcel->getSheet($sheets_index[$k])->toArray(null, true, false, false);
                $ret[$k]['keys'] = @array_flip(array_shift($ret[$k]['data']));
            }
        }
        else {
            foreach ($sheets_index as $k => $i) {
                $ret[$k] = $objPHPExcel->getSheet($sheets_index[$k])->toArray(null, true, false, false);
            }
        }
        return $ret;
    }

    public static function writeExcelSheets($xls_file, $data) {
        $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheetnames = array_keys($data);
        $sheets = array_values($data);
        foreach ($sheets as $i => $sheet) {
            if ($i === 0) {
                $worksheet = $objPHPExcel->getActiveSheet();
            }
            else {
                $worksheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($objPHPExcel);
                $objPHPExcel->addSheet($worksheet);
            }
            $worksheet->setTitle($sheetnames[$i]);
            $worksheet->fromArray($sheet, '', 'A1', true);
        }
        $objWriter = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($objPHPExcel);
        $objWriter->setOffice2003Compatibility(true);
        if (file_exists($xls_file)) {
            unlink($xls_file);
        }
        $objWriter->save($xls_file);
        $objPHPExcel->disconnectWorksheets();
        unset($objWriter, $objPHPExcel);
    }

    public static function writeCsvSheet($csv_file, $data) {
        $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $worksheet = $objPHPExcel->getActiveSheet();
        $worksheet->fromArray($data, '', 'A1', true);
        $objWriter = new \PhpOffice\PhpSpreadsheet\Writer\Csv($objPHPExcel);
        $objWriter->setUseBOM(true);
        if (file_exists($csv_file)) {
            unlink($csv_file);
        }
        $objWriter->save($csv_file);
        $objPHPExcel->disconnectWorksheets();
        unset($objWriter, $objPHPExcel);
    }

    public static function readCsvArray($csv_file, $first_row_key = false) {
        if (!file_exists($csv_file)) {
            throw new \Exception('File ' . $csv_file . ' not found');
        }
        if (($fp = fopen($csv_file, 'r')) === false) {
            throw new \Exception('Unable to read data file "' . $csv_file . '".');
        }
        $convert = function($str) {
            return iconv('Windows-1252', 'UTF-8', $str);
        };
        $ret = [];
        $i = 0;
        while (($row = fgetcsv($fp)) !== false) {
            $row = array_map($convert, $row);
            if ($first_row_key) {
                if ($i === 0) {
                    $keys = $row;
                } else {
                    $ret[] = array_combine($keys, $row);
                }
            } else {
                $ret[] = $row;
            }
            $i++;
        }
        return $ret;
    }
}