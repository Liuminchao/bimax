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
class RfPdf extends TCPDF {

    //Page header
    public function Header($logo_pic= null) {
        // Logo
//        $image_file = '/opt/www-nginx/web/filebase/record/2018/04/sign/pic/sign1523249465052_1.jpg';
//        $this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        //设置字体
        $this->SetHeaderMargin(15);
//        $logo_pic = '/opt/www-nginx/web/filebase/company/146/图片1.png';
        $this->setHeaderFont(Array('droidsansfallback', '', '15')); //英文OR中文
        $this->SetFont('droidsansfallback', '', 15, '', true); //英文OR中文
//        $this->Cell(0, 15, $title_html, 0, 1, 'C', 0, '', 0, false, 'M', 'M');
//        $this->Cell(0, 25, $_SESSION['title'], 0, false, 'C', 0, '', 0, false, 'M', 'M');
        if($logo_pic){
            $this->Image($logo_pic, 15, 0, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        }
        $txt = '[LEFT] Lorem ipsum dolor sit amet,
consectetur adipisicing elit, sed do
eiusmod tempor incididunt ut labore
et dolore magna aliqua.';
//        $this->MultiCell(55, 5, '[LEFT] '.$txt, 1, 'L', 1, 0, '', '', true);
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('droidsansfallback', 'I', 8);
        $footer_pic = '/opt/www-nginx/web/filebase/company/146/cms_footer.png';
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'C');
//        $this->Image($footer_pic, '115', '', 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
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