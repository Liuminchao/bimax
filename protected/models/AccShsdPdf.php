<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/1/7
 * Time: 9:20
 */
$tcpdfPath = Yii::app()->basePath . DIRECTORY_SEPARATOR . 'extensions' . DIRECTORY_SEPARATOR . 'tcpdf' . DIRECTORY_SEPARATOR . 'tcpdf.php';
require_once($tcpdfPath);

// Extend the TCPDF class to create custom Header and Footer
class AccShsdPdf extends TCPDF {
    const RESULT_YES = 0;
    const RESULT_NO = 1;
    const RESULT_NA = 2;

    //Page header
    public function Header() {
        // Logo
//        $image_file = '/opt/www-nginx/web/filebase/record/2018/04/sign/pic/sign1523249465052_1.jpg';
//        $this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        //设置字体
//        $this->SetHeaderMargin(15);
//        $this->Cell(0, 5, $no, 0, false, 'L', 0, '', 0, false, 'T', 'M');
//        $this->setPrintHeader(true);

        // Set font
        $this->Ln(5);
        $this->SetFont('helvetica', '', 10);
        // Title
        $this->Cell(0, 12, $_SESSION['acci_type'], 0, false, 'R', 0, '', 0, false, 'M', 'M');
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('times', '', 8);
//        $footer_pic = '/opt/www-nginx/web/filebase/company/146/cms_footer.png';
    }

    // Load table data from file
    public function LoadData($file) {
//        $data = array();
//        foreach($file as $line) {
        $data = explode(',', $file);
//        }
        return $data;
    }

    // Colored table
    public function ColoredTable($header,$data) {
        // Colors, line width and bold font
        $this->SetFillColor(229, 229, 229);
        $this->SetTextColor(255);
        $this->SetDrawColor(170, 170, 170);
        $this->SetLineWidth(0.1);
        // Header
        $w = array(40, 35, 40, 45);
        $num_headers = count($header);
        for($i = 0; $i < $num_headers; ++$i) {
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
        }
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        // Data
        $fill = 0;
        foreach($data as $row) {
            $this->Cell($w[0], 6, $row[0], 'LR', 0, 'C', $fill);
            $this->Cell($w[1], 6, $row[1], 'LR', 0, 'C', $fill);
            $this->Cell($w[2], 6, $row[2], 'LR', 0, 'C', $fill);
            $this->Cell($w[3], 6, $row[3], 'LR', 0, 'C', $fill);
            $this->Ln();
            $fill=!$fill;
        }
        $this->Cell(array_sum($w), 0, '', 'T');
    }
}