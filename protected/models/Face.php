<?php

Yii::import('application.extensions.faceall.FaMethod');

/**
 * 人脸识别
 * @author liuxiaoyuan
 */
class Face extends CFormModel {
    
    
    /**
     * 获取图片的face_id
     * @param type $img_path 图片绝对路径 eg.C:\www\a.jpg
     * @return string
     */
    public static function face_id($img_path) {

        if ($img_path== '') {
            $r['errno'] = 1001;
            $r['errstr'] = 'Missing argument:img_path';
            return $r;
        }

        if (!file_exists($img_path)) {
            $r['errno'] = 1006;
            $r['errstr'] = 'File does not exist:'.$img_path;
            return $r;
        }

        $fa_method = new FaMethod();

        //获得上传图片的face_id
        $result = $fa_method->detection_detect($img_path, null);
        
        //记录文件日志
        $log = "get_face_id:detection_detect\n";
        $log .= "input:\n";
        $log .= "img_path:".$img_path."\n";
        $log .= "output:\n";
        $log .= $result;
        Utils::filedump($log, '', 'faceall.log');
        
        $face_id = json_decode($result)->{"faces"}[0]->{"id"};

        if ($face_id != '') {
            //获取face_id后，调用face特征提取接口，但本地不存特征数据
            $fa_method->detection_feature($face_id, $return_feature='false');
            
            $r['errno'] = 0;
            $r['errstr'] = 'face_id success';
            $r['face_id'] = $face_id;
        } else {
            $r['errno'] = -1;
            $r['errstr'] = 'face_id failure';
        }
        return $r;
    }

    /* 创建faceset：创建总包项目时需要创建faceset    */
    public static function FacesetCreate($project_id){
        if($project_id == ''){
            $r['errno'] = 1001;
            $r['errstr'] = 'Missing argument:project_id';
            return $r;
        }
        
        $fa_method = new FaMethod();

        $result = $fa_method->faceset_create($project_id);
        
        //记录文件日志
        $log = "FacesetCreate:\n";
        $log .= "input:\n";
        $log .= "faceset_name(project_id):".$project_id."\n";
        $log .= "output:\n";
        $log .= $result;
        Utils::filedump($log, '', 'faceall.log');
        
        $faceset_id = json_decode($result)->{"id"};

        if ($faceset_id != '') {
            $r['errno'] = 0;
            $r['errstr'] = 'faceset_create success';
            $r['faceset_id'] = $faceset_id;
        } else {
            $r['errno'] = -1;
            $r['errstr'] = 'faceset_create failure';
        }
        return $r;
        
    }
    
    /* 删除faceset：删除总包项目时、总包项目结项时，需要删除faceset    */
    public static function FacesetDelete($faceset_id){
        if($faceset_id == ''){
            $r['errno'] = 1001;
            $r['errstr'] = 'Missing argument:faceset_id';
            return $r;
        }
        
        $fa_method = new FaMethod();

        $result = $fa_method->faceset_delete($faceset_id);
   
        if (json_decode($result)->{"success"} == '1') {
            $r['errno'] = 0;
            $r['errstr'] = 'faceset_delete success';
            $r['faceset_id'] = $faceset_id;
        } else {
            $r['errno'] = -1;
            $r['errstr'] = 'faceset_delete failure';
        }
        return $r;
        
    }
    
    /* 往faceset里编辑脸：项目组编辑成员时，更新faceset里的脸
        $del_list => 需要删除的用户编号集合；
        $add_list => 需要添加的用户编号集合；  */
    public static function FacesetEditFace($faceset_id, $del_list=array(), $add_list=array()){
        //var_dump($del_list);var_dump($add_list);
        if($faceset_id == ''){//var_dump('bb');
            $r['errno'] = 1001;
            $r['errstr'] = 'Missing argument:faceset_id';
            return $r;
        }
        //var_dump($del_list);var_dump($add_list);
        $fa_method = new FaMethod();
        
        //记录文件日志
        $log = "FacesetEditFace:\n\n";
        
        if(!empty($del_list)){
            $del_id = "'".implode("','", $del_list)."'";//var_dump($del_id);
            $rs = Staff::model()->findAll(
                array(
                    'select'=>array('face_id'),
                    'condition' => 'user_id in ('.$del_id.')',
                )
            );
            
            $del_face = '';
            
            foreach((array)$rs as $key => $row){
                if($row['face_id'])
                    $del_face .= $row['face_id'].',';
            }
            $del_rs = $fa_method->faceset_remove_faces($faceset_id, substr($del_face,0,-1));
            //记录文件日志
            $log .= "faceset_remove_faces:\n";
            $log .= "input:\n";
            $log .= "faceset_id:".$faceset_id."\n";
            $log .= "face_id:".substr($del_face,0,-1)."\n";
            $log .= "output:\n";
            $log .= $del_rs."\n\n";
        }
        
        if(!empty($add_list)){

            $add_id = "'".implode("','", $add_list)."'";
            foreach($add_list as $cnt => $user_id){
//                var_dump($user_id);
                $staff_model = Staff::model()->findByPk($user_id);

                $staffinfo_model = StaffInfo::model()->findByPk($user_id);
                $face_id = $staff_model->face_id;
//                var_dump($staffinfo_model);
//                exit;
                $face_img = '/opt/www-nginx/web'.$staffinfo_model->face_img;
//                var_dump($face_img);
                //face_id为空
                if(empty($face_id)){
                    if(!$face_img){
                        $r['errno'] = -4;
                        $r['errstr'] = 'Find not faceimg';
                        $r['faceset_id'] = $faceset_id;
                        return $r;
                    }else{
                        //换取face_id
//                        var_dump($face_img);
//                        exit;
                        $face_rs=Face::face_id($face_img);
                        if($face_rs['errno'] == -1){
                            $r['errno'] = -3;
                            $r['errstr'] = Yii::t('comp_staff', 'Error no face');
                            $r['faceset_id'] = $faceset_id;
                            return $r;
                        }
                        //将faceid保存到数据库
                        $staff_model->face_id=$face_rs['face_id'];
                        $result = $staff_model->save();
                    }

                }
            }
            $rs = Staff::model()->findAll(
                array(
                    'select'=>array('face_id'),
                    'condition' => 'user_id in ('.$add_id.')',
                )
            );
            
            $add_face = '';
            foreach((array)$rs as $key => $row){
                if($row['face_id'])
                    $add_face .= $row['face_id'].',';
            }
            $face_rs = substr($add_face,0,-1);

            $add_rs = $fa_method->faceset_add_faces($faceset_id, substr($add_face,0,-1));
//            var_dump($add_face);
//            exit;
            //记录文件日志
            $log .= "faceset_add_faces:\n";
            $log .= "input:\n";
            $log .= "faceset_id:".$faceset_id."\n";
            $log .= "face_id:".substr($add_face,0,-1)."\n";
            $log .= "output:\n";
            $log .= $add_rs."\n\n";
        }
        if($del_rs || $add_rs){
//            var_dump($add_rs);
            $train_rs = $fa_method->faceset_train($faceset_id, $async='false');
            //记录文件日志
            $log .= "faceset_train:\n";
            $log .= "input:\n";
            $log .= "faceset_id:".$faceset_id."\n";
            $log .= "output:\n";
            $log .= $train_rs;
            Utils::filedump($log, '', 'faceall.log');
//            if($train_rs['code'] == '4002'){
//                $r['errno'] = $train_rs['code'];
//                $r['errstr'] = $train_rs['msg'];
//                $r['faceset_id'] = $faceset_id;
//                return $r;
//            }
        }
        
        
        $r['errno'] = 0;
        $r['errstr'] = 'faceset_edit success';
        $r['faceset_id'] = $faceset_id;
        
        return $r;
        
    }
    
        /* 编辑用户的头像:更新含有此用户的faceset中的脸  */
    public static function EditUserFace($user_id, $old_face_id='', $new_face_id=''){

        if($user_id == ''){
            $r['errno'] = 1001;
            $r['errstr'] = 'Missing argument:user_id';
            return $r;
        }
        
        /*if($new_face_id == ''){
            $userModel = Staff::model()->findByPk($user_id);
            if ($model === null) {
                $r['errstr'] = 'new_face_id is missing,user is not found';
                $r['errno'] = 1002;
                return $r;
            }
            $new_face_id = $model->face_id;
        }*/
        
        if($old_face_id == '' and $new_face_id == ''){
            $r['errstr'] = 'old_face_id is missing, new_face_id is missing';
            $r['errno'] = 1003;
            return $r;
        }
        
        //记录文件日志
        $log = "EditUserFace:\n\n";
        
        //找到含有此员工的项目faceset_id
        $sql = "SELECT a.faceset_id FROM bac_program a, bac_program_user_q b  WHERE  a.program_id = b.program_id and a.status = 0 and b.check_status =11 and b.user_id = :user_id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $rows = $command->queryAll();
        //var_dump($rows);
        
        if (count($rows) > 0) {
            
            $fa_method = new FaMethod();
            
            //循环更新faceset里的脸
            foreach ($rows as $key => $row) {
                
                if($row['faceset_id'] == ''){
                    continue;
                }
                
                if($old_face_id <> ''){
                    $del_rs = $fa_method->faceset_remove_faces($row['faceset_id'], $old_face_id);
                    //记录文件日志
                    $log .= "faceset_remove_faces:\n";
                    $log .= "input:\n";
                    $log .= "faceset_id:".$row['faceset_id']."\n";
                    $log .= "face_id:".$old_face_id."\n";
                    $log .= "output:\n";
                    $log .= $del_rs."\n\n";
                }
                if($new_face_id <> ''){
                    $add_rs = $fa_method->faceset_add_faces($row['faceset_id'], $new_face_id);
                    //记录文件日志
                    $log .= "faceset_add_faces:\n";
                    $log .= "input:\n";
                    $log .= "faceset_id:".$row['faceset_id']."\n";
                    $log .= "face_id:".$new_face_id."\n";
                    $log .= "output:\n";
                    $log .= $add_rs."\n\n";
                }
                
                if($del_rs || $add_rs){
                    $train_rs = $fa_method->faceset_train($row['faceset_id'], $async='false');
                    //记录文件日志
                    $log .= "faceset_train:\n";
                    $log .= "input:\n";
                    $log .= "faceset_id:".$row['faceset_id']."\n";
                    $log .= "output:\n";
                    $log .= $train_rs."\n\n";
                }
            }
        }
        //var_dump($log);
        Utils::filedump($log, '', 'faceall.log');
//        if($train_rs['code'] == '4002'){
//            $r['errno'] = $train_rs['code'];
//            $r['errstr'] = $train_rs['msg'];
//            return $r;
//        }
        
        $r['errno'] = 0;
        $r['errstr'] = 'EditUserFace success';
        
        return $r;
        
    }

    /* 获取此faceset中所有的脸  */
    public static function GetFacesetInfo($faceset_id){
        $fa_method = new FaMethod();
        $result = $fa_method->faceset_getinfo($faceset_id);
//        self::FacesetDelete($faceset_id);
//        var_dump($result);
//        exit;
        $ar = json_decode($result);
        return $ar;
    }
}
