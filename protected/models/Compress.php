<?php

/**
 * 图片压缩&&目录类
 * @author liu minchao
 */
class Compress extends CFormModel {

    //图片对应的小目录文件路径
    private static function TypePath(){
        return array(
            'face_src'  =>  'face',
            'ppt_src' =>  'ppt',
            'home_id_src' =>  'homeid',
            'bca_src' =>  'bca',
            'csoc_src'=>  'csoc',
        );
    }

    //图片对应的大目录文件路径
    private static function FilePath(){
        return array(
            'face_src'  =>  'face',
            'ppt_src' =>  'staff',
            'home_id_src' => 'staff',
            'bca_src' =>  'cert',
            'csoc_src'=>  'cert',
            'certificate_src'=> 'staff',
            'device_src'=> 'device',
            'device_certificate_src'=> 'device',
            'contract_src'=> 'doc',
            'ra_src' => 'cert',
            'chemical_src' => 'chemical'
        );
    }

    //允许的图片类型
    private static function fileType(){
        return array("jpg","jpeg","png","bmp", "JPG");
    }

    //创建目录
    private static function createPath($path){
        if($path == ''){
            return false;
        }
        if(!file_exists($path))
        {
            umask(0000);
            @mkdir($path, 0777, true);
        }
        return true;
    }


    /**
     * 文件上传：$files ＝ $_FILES["StaffInfo"];
     */
    public static function uploadPicture($files,$conid){

        $rs = array();  //返回结果
        $type_path = self::TypePath();
        $file_path = self::FilePath();
        //循环上传的文件
        foreach($files as $type  =>  $filename){

//            $name = substr($filename,24);
            $name = substr($filename,27);
//            var_dump($filename);
//            exit;
            $upload_path = Yii::app()->params['upload_data_path'] . '/' . ($file_path[$type] == '' ? 'tmp' : $file_path[$type]) . '/' .$conid;
            if($type == 'contract_src'){
                $upload_path = $upload_path.'/contract';
            }else if($type == 'ra_src'){
                $upload_path = $upload_path.'/ra';
                $name = substr($name,5511);
            }
            $upload_file = $upload_path.'/'.$name;
//            var_dump($name);exit;
            //创建目录
            self::createPath($upload_path);
            //移动文件到指定目录下
            if (rename($filename,$upload_file)) {
                $rs[$type]['code'] = 0;
                $rs[$type]['upload_file'] = $upload_file;
            }else{
                $rs[$type]['code'] = -2;
                $rs[$type]['msg'] = $type.': file move error';
            }
        }
        return $rs;
    }
    /**
     * 图片压缩(PHP后台)
     */
    public static function compressPic(&$files,$conid){
        if(empty($files)){
            return array();
        }

        $rs = array();  //返回结果
        $size = Yii::app()->params['face_img_size'];
        $type_path = self::TypePath();
        foreach($files as $type  =>  $filename) {
            $size = getimagesize($filename);
            $width = $size[0];//原宽度
            $height = $size[1];//原高度
            $resize_width = 400;//压缩后宽度
            $resize_height = 240;//压缩后高度
            //判断文件大小
            $size = filesize($filename)/1000;
            if($size > $size*1024) {
                $rs[$type]['code'] = -1;
                $rs[$type]['msg'] = $type.': '.Yii::t('comp_staff', 'Upload file greater');
                return $rs;
            }
            $new_name = date('YmdHis').'_'.rand(10, 99).'.'.png;
            $upload_path = Yii::app()->params['upload_file_path'].'/'.($type_path[$type]==''?'tmp':$type_path[$type]).'/'.$conid;
            $upload_file = $upload_path.'/'.$new_name;
            //var_dump($upload_file );exit;
            //创建目录
            self::createPath($upload_path);
            $temp_img = imagecreatetruecolor($resize_width, $resize_height);//创建画布
//            $im=imagecreatefromjpeg($_FILES['StaffInfo']['tmp_name']['face_img']);
            $im = imagecreatefrompng($filename);
            imagecopyresampled($temp_img, $im, 0, 0, 0, 0, $resize_width, $resize_height, $width, $height);//将图片复制到画布中
//            imagejpeg($temp_img,$full_dir, 80);
            imagepng($temp_img, $upload_file, 9);
            imagedestroy($im);
            imagedestroy($temp_img);
        }
    }

}
