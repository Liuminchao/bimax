<?php

class Drawing extends CActiveRecord {

    public static function createDrawing($drawing_path,$position_list){

        $file = explode('.',$drawing_path);
        $file_type = $file[1];
        $file_name = $file[0];
        if($file_type == 'JPG'){
            $image_1 = imagecreatefromjpeg($drawing_path);
        }

        if($file_type == 'png'){
            $image_1 = imagecreatefrompng($drawing_path);
        }

        $path_2 = '/opt/www-nginx/web/test/bimax/img/pin.png';
        $image_2 = imagecreatefrompng($path_2);
        $image_3 = imageCreatetruecolor(imagesx($image_1),imagesy($image_1));
        $color = imagecolorallocate($image_3, 255, 255, 255);

        //获取图片的属性，第一个宽度，第二个高度，类型1=>gif，2=>jpeg,3=>png
        list($width,$height,$type) = getimagesize($drawing_path);
        list($width_2,$height_2,$type_2) = getimagesize($path_2);

        //设置居中图片的X轴坐标位置
        $x = 1885.74;
        //设置居中图片的Y轴坐标位置
        $y = 1631.42-$height_2;

        $x_1 = 2811.81;
        $y_1 = 1252.35-$height_2;
        imagefill($image_3, 0, 0, $color);
        imagefill($image_2, 0, 0, $color);
//        imageColorTransparent($image_3, $color);
        imagecopyresampled($image_3,$image_1,0,0,0,0,imagesx($image_1),
            imagesy($image_1),imagesx($image_1),imagesy($image_1));
        //图片在背景上的位置 $x横坐标，$y纵坐标
        $index = 1;
        foreach ($position_list as $i => $j){
            if($index % 2 == 1){
                $x = $j;
            }
            if($index % 2 == 0){
                $y = $j;
                imagecopymerge($image_3,$image_2, $x,$y,0,0,imagesx($image_2),imagesy($image_2), 100);
            }
            $index++;
        }

        //将画布保存到指定的文件
        $path_3 = $file_name.'_position.'.$file_type;
        imagepng($image_3, $path_3);
//        $title = 'demo';
//        $_SESSION['title'] = 'demo';
//        $tcpdfPath = Yii::app()->basePath.DIRECTORY_SEPARATOR.'extensions'.DIRECTORY_SEPARATOR.'tcpdf'.DIRECTORY_SEPARATOR.'tcpdf.php';
//        require_once($tcpdfPath);
////        $pdf = new ReportPdf('L', 'mm', 'A0', true, 'UTF-8', false);
//        $pdf = new TCPDF('L', 'mm', 'A0', true, 'UTF-8', false);
//        // 设置文档信息
//        $pdf->SetCreator(Yii::t('login', 'Website Name'));
//        $pdf->SetAuthor(Yii::t('login', 'Website Name'));
//        $pdf->SetTitle($title);
//        $pdf->SetSubject($title);
//        $pdf->setImageScale(1);
//        $y_new = $pdf->GetY();
//        $x_new = $pdf->GetX();
//        $image_4 = 'https://shell.cmstech.sg/test/bimax/new.jpg';
//        $img_info = getimagesize($image_4);
//        $pdf->AddPage();//再加一页
////        $pdf->AddPage();//再加一页
//        $pdf->Image($image_4, $x_new, $y_new, $img_info[0], $img_info[1], 'PNG', '', '', true, 300, '', false, false, 0, true, false, false);
//        $filepath = '/opt/www-nginx/web/test/bimax/new.pdf';
//        $pdf->Output($filepath, 'F'); //保存到指定目录
        return $path_3;
    }
}
