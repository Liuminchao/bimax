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
class WshShsdPdf extends TCPDF {
    const RESULT_YES = 0;
    const RESULT_NO = 1;
    const RESULT_NA = 2;

    //Page header
    public function Header() {
        // Logo
//        $image_file = '/opt/www-nginx/web/filebase/record/2018/04/sign/pic/sign1523249465052_1.jpg';
//        $this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        //设置字体
        $logo_pic = '/opt/www-nginx/web/filebase/company/146/shsd.png';
//        $logo_pic = 'http://shell.cmstech.sg/opt/www-nginx/web/filebase/company/146/shsd.png';
//        $this->setHeaderFont(Array('droidsansfallback', '', '15')); //英文OR中文
//        $this->SetFont('droidsansfallback', '', 15, '', true); //英文OR中文
//        $this->Cell(0, 15, $title_html, 0, 1, 'C', 0, '', 0, false, 'M', 'M');
//        $this->Cell(0, 25, $_SESSION['title'], 0, false, 'C', 0, '', 0, false, 'M', 'M');
        if($logo_pic){
            $this->Image($logo_pic, 55, 5, 90, 16, 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        }


        $this->Ln(20);

        // Set font
        $this->SetFont('helvetica', 'B', 14);
        if($_SESSION['month_tag'] == 1){
            $this->Cell(0, 12, 'MONTHLY CORPORATE WSHE INSPECTION REPORT', 0, 1, 'C', 0, '', 0, false, 'M', 'M');
        }else{
            $this->Cell(0, 12, 'WSHE INSPECTION REPORT', 0, 1, 'C', 0, '', 0, false, 'M', 'M');
        }

        // Set font
        $this->SetFont('helvetica', '', 8);



//        $this->Cell(0, 12, 'Project: '.$_SESSION['program_name'].'                     Date / Time: '.Utils::DateToEn(date("Y-m-d H:i:s")), 0, 0, 'L', 0, '', 0, false, 'M', 'M');


//        $this->Cell(0, 0, 'Project: '.$_SESSION['program_name'].'                     Date / Time: '.Utils::DateToEn(date("Y-m-d H:i:s")).'                     Inspected By: '.$_SESSION['apply_user_name'], 0, 1, 'L', 0, '', 0, false, 'M', 'M');
        $txt = 'Project: '.$_SESSION['program_name'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.'Date / Time: '.Utils::DateToEn(date("Y-m-d H:i:s")).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.'Inspected By: '.$_SESSION['apply_user_name'];

        $this->writeHTML($txt, true, false, true, false, '');

        $this->setPrintHeader(true);
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-30);
        // Set font
        $this->SetFont('times', '', 8);
        if($_SESSION['month_tag'] == 1){
            $txt_1 = 'NOTE:';
            $txt_2 = ' - Close the safety issues / violations/ unsafe practices ASAP and submit the photos after rectification.';

            $table = '<table><tr><td width="80%" align="left">'.$txt_1.'</td><td width="20%" align="left"></td></tr><tr><td width="80%" align="left">'.$txt_2.'</td><td width="20%" align="left"></td></tr></table>';
        }else{
            $txt_1 = 'NOTE:';
            $txt_2 = '- Close the safety issues / violations/ unsafe practices ASAP and submit the photos after rectification.';
            $txt_3 = '-  If fail to close within 3 days, STEC will carry out the work and back charge to you (or) impose penalty (or) both.';
            $txt_4 = 'STEC-EHS-030';

            $table = '<table><tr><td width="80%" align="left">'.$txt_1.'</td><td width="20%" align="left"></td></tr><tr><td width="80%" align="left">'.$txt_2.'</td><td width="20%" align="left"></td></tr><tr><td width="80%" align="left">'.$txt_3.'</td><td width="20%" align="left">'.$txt_4.'</td></tr></table>';
        }

        $this->MultiCell(0, 0, $table, 0, 'C', false, 1, '', '', true, 0, true, true, 0, 'T', false);
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