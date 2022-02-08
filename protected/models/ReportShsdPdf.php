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
class ReportShsdPdf extends TCPDF {
    const RESULT_YES = 0;
    const RESULT_NO = 1;
    const RESULT_NA = 2;

    //检查条件
    public static function resultText($key = null) {
        $rs = array(
            self::RESULT_YES => '√',
            self::RESULT_NO => '×',
            self::RESULT_NA => 'N/A',
        );
        return $key == null ? $rs : $rs[$key];
    }

    public function  FooterList(){
        $list = array(
            'SHSD001' => 'STEC- OSHE - 001',
            'SHSD002' => 'STEC- OSHE - 002',
            'SHSD003' => 'STEC- OSHE - 003',
            'SHSD004' => 'STEC- OSHE - 004',
            'SHSD005' => 'STEC- OSHE - 005',
            'SHSD006' => 'STEC- OSHE - 006',
            'SHSD007' => 'STEC- OSHE - 007',
            'SHSD008' => 'STEC- OSHE - 008',
            'SHSD009' => 'STEC- OSHE - 009',
            'SHSD010' => 'STEC- OSHE - 010',
            'SHSD011' => 'STEC- OSHE - 011',
            'SHSD012' => 'STEC- OSHE - 012',
        );
        return $list;
    }
    //Page header
    public function Header($logo_pic= null) {
        // Logo
//        $image_file = '/opt/www-nginx/web/filebase/record/2018/04/sign/pic/sign1523249465052_1.jpg';
//        $this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        //设置字体
        $this->SetHeaderMargin(15);
        $logo_pic = '/opt/www-nginx/web/filebase/company/146/shsd.png';
//        $logo_pic = 'http://shell.cmstech.sg/opt/www-nginx/web/filebase/company/146/shsd.png';
//        $this->setHeaderFont(Array('droidsansfallback', '', '15')); //英文OR中文
//        $this->SetFont('droidsansfallback', '', 15, '', true); //英文OR中文
//        $this->Cell(0, 15, $title_html, 0, 1, 'C', 0, '', 0, false, 'M', 'M');
//        $this->Cell(0, 25, $_SESSION['title'], 0, false, 'C', 0, '', 0, false, 'M', 'M');
        if($logo_pic){
            $this->Image($logo_pic, 55, 5, 90, 16, 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        }
        $this->setPrintHeader(true);
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('times', '', 8);
//        $footer_pic = '/opt/www-nginx/web/filebase/company/146/cms_footer.png';
        $this->Cell(0, 10, 'Copyright ©2019', 0, false, 'L', 0, '', 0, false, 'T', 'M');
//        $this->Cell(0, 10, 'Copyright ©2019', 0, false, 'L', 0, '', 0, false, 'T', 'M');
        // Page number
        //STEC-OSHE-003
        $footer_list = self::FooterList();
        $this->Cell(0, 10, $footer_list[$_SESSION['type_id']], 0, false, 'R', 0, '', 0, false, 'T', 'C');

//        $this->writeHTMLCell(0, 0, '170', '', $footer_list[$_SESSION['type_id']], 0, 0, 0, true, 'R', true);
//                $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'C');
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