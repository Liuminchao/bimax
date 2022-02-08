<?php

/**
 * 图片处理类
 * @author weijuan
 */
class UploadFiles extends CFormModel {
    
    //图片对应的存储文件路径
    private static function TypePath(){
        return array(
            'face_img'  =>  'face',
            'ppt_photo' =>  'ppt',
            'home_id_photo' =>  'homeid',
            'bca_photo' =>  'bca',
            'csoc_photo'=>  'csoc',
            'task_attach'=> 'proj',
            'device_img'=> 'device',
            'permit_img'=> 'permit',
            'aptitude_photo'=> 'aptitude',
            'certificate_photo'=> 'device_certificate'
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
    public static function uploadPicture(&$files,$conid){
        if(empty($files)){
            return array();
        }
        
        $rs = array();  //返回结果
        $size = Yii::app()->params['face_img_size'];
        $type_path = self::TypePath();
        
        //循环上传的图片
        foreach($files['name'] as $type  =>  $filename){
            
            //上传错误
            if($files['error'][$type] <> 0){
                $rs[$type]['code'] = $files['error'][$type];
                $rs[$type]['msg'] = $type.': file upload error';
                return $rs;
            }
            
            //判断文件大小
            if($files['size'][$type] > $size*1024) {
                $rs[$type]['code'] = -1;
                $rs[$type]['msg'] = $type.': '.Yii::t('comp_staff', 'Upload file greater');
                return $rs;
            }
            
            $suffix = substr($filename, strrpos($filename, '.')+1); 
            //判断文件类型
            if (!in_array(strval($suffix), self::fileType())) {
                $rs[$type]['code'] = -2;
                $rs[$type]['msg'] = $type.': '.Yii::t('comp_staff', 'Upload format wrong');
                return $rs;
            }
            
            
            $new_name = date('YmdHis').'_'.rand(10, 99).'.'.$suffix;
            $upload_path = Yii::app()->params['upload_file_path'].'/'.($type_path[$type]==''?'tmp':$type_path[$type]).'/'.$conid;
            $upload_file = $upload_path.'/'.$new_name;
            //var_dump($upload_file );exit;
            //创建目录
            self::createPath($upload_path);
            
            if (move_uploaded_file($files['tmp_name'][$type], $upload_file)) {
                $rs[$type]['code'] = 0;
                $rs[$type]['upload_file'] = $upload_file;
            }else{
                $rs[$type]['code'] = -3;
                $rs[$type]['msg'] = $type.': file upload error';
            }
        }
        
        return $rs;

    }
	
	/**
     * @param array $file $_FILE数组
     * @param array $type 允许上传的文件格式
     * @param string $dir 文件存储路径  eg:../files/coupon
     *
     * @return array 上传文件处理返回说明：
     * status:文件上传状态 1:成功上传，-1:上传失败或者格式、大小错误
     * desc:上传结果描述
     * path:文件存储路径(要存到DB的相对路径)
     * upname:上传文件原名称
     * savename:存储文件名称
     */

    public static function fileUpload($file, $type, $dir) {
        $return = array ();

//        var_dump($file);
//        var_dump($type);
//        var_dump($dir);
//        exit;

//        var_dump(Yii::app ()->params ['upload_file_path']);
//        exit;
        //string(7) "./files"
        $full_dir = $dir;
        $fname = $file ['name'];
        $ftype = substr ( strrchr ( $fname, '.' ), 1 );
        if (empty ( $fname )) {
            $return ['status'] = '-1';
            $return ['desc'] = '没有上传任何文件';
        } elseif (! in_array ( $ftype, $type )) {
            $return ['status'] = '-1';
            $return ['desc'] = '上传文件格式不符合要求';
        } elseif ($file ['size'] == 0) {
            $return ['status'] = '-1';
            $return ['desc'] = '上传文件不能为空';
        } else {

//            var_dump($full_dir);
//            exit;
            $rname = time().rand(0,1000);
            $dname = $rname . '.' . $ftype;
            if (! is_dir ( $full_dir )) {
                umask ( 0000 );
                mkdir ( $full_dir, 0777, true);
            }
            if (file_exists ( $full_dir )) {
                $path = $full_dir . '/' . $dname;
                if (move_uploaded_file ( $file ['tmp_name'], $path )) {
                    $return ['status'] = '1';
                    $return ['desc'] = '文件上传成功';
                    $return ['path'] = $dir . '/' . $dname;
                    $return ['upname'] = $fname;
                    $return ['savename'] = $dname;
                } else {
                    $return ['status'] = '-1';
                    $return ['desc'] = '服务器繁忙，上传失败';
                }

            } else {
                $return ['status'] = '-1';
                $return ['desc'] = '没有找到相应的上传目录';
            }
        }
        return $return;
    }

}
