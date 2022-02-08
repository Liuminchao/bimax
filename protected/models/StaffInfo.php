<?php

class StaffInfo extends CActiveRecord {

    const STATUS_NORMAL = 0; //正常
    const STATUS_DISABLE = 1; //注销
    const CONTRACTOR_TYPE_MC = 'MC'; //总包
    const CONTRACTOR_TYPE_SC = 'SC'; //分包

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_staff_info';
    }
    
    public function attributeLabels() {
        return array(
            'issue_date' => Yii::t('comp_staff', 'issue_date'),
            'expire_date' => Yii::t('comp_staff', 'expire_date'),
            'name_cn' => Yii::t('comp_staff', 'name_cn'),
            'family_name'=> Yii::t('comp_staff', 'family_name'),
            'first_name'=> Yii::t('comp_staff', 'first_name'),
            'gender'=> Yii::t('comp_staff', 'gender'),
            'nationality'=> Yii::t('comp_staff', 'nationality'),
            'birth_date'=> Yii::t('comp_staff', 'birth_date'),
            'home_address'=> Yii::t('comp_staff', 'home_address'),
            'home_contact'=> Yii::t('comp_staff', 'home_contact'),
            'relationship'=> Yii::t('comp_staff', 'relationship'),
            'home_phone'=> Yii::t('comp_staff', 'home_phone'),
            'sg_address'=> Yii::t('comp_staff', 'sg_address'),
            'sg_phone'=> Yii::t('comp_staff', 'sg_phone'),
            'sg_postal_code'=> Yii::t('comp_staff', 'sg_postal_code'),
            'home_id'=>Yii::t('comp_staff', 'home_id'),
            'home_id_photo'=>Yii::t('comp_staff', 'home_id_photo'),
            'ppt_photo' => Yii::t('comp_staff', 'ppt_photo'),
            'csoc_no'=>Yii::t('comp_staff', 'csoc_no'),
            'csoc_photo' => Yii::t('comp_staff', 'csoc_photo'),
            'bca_company' => Yii::t('comp_staff', 'bca_company'),
            'bca_company_uen' => Yii::t('comp_staff', 'bca_company_uen'),
            'bca_pass_type' => Yii::t('comp_staff', 'bca_pass_type'),
            'bca_pass_no' => Yii::t('comp_staff', 'bca_pass_no'),
            'bca_apply_date' => Yii::t('comp_staff', 'bca_apply_date'),
            'bca_levy_rate' => Yii::t('comp_staff', 'bca_levy_rate'),
            'bca_trade' => Yii::t('comp_staff', 'bca_trade'),
            'bca_photo' => Yii::t('comp_staff', 'bca_photo'),
            'ins_scy_no' => Yii::t('comp_staff', 'ins_scy_no'),
            'ins_med_no' => Yii::t('comp_staff', 'ins_med_no'),
            'ins_adt_no' => Yii::t('comp_staff', 'ins_adt_no'),
            'passport_no'=>Yii::t('comp_staff', 'passport_no'),
            'Joined Date' => Yii::t('comp_staff', 'Joined Date'),
            'Race' => Yii::t('comp_staff', 'Race'),
            'Previous Industry Experience & Designation' => Yii::t('comp_staff', 'Previous Industry Experience & Designation'),
            'Marital Status'=>Yii::t('comp_staff', 'Marital Status'));
    }
    
     /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Operator the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    //插入数据
    public static function insertStaffInfo($id,$infoargs) {
        if(empty($infoargs)){
            return;
        }
//        if (array_key_exists("bca_photo",$infoargs) && $infoargs['bca_photo']==''){
//            $r['msg'] = Yii::t('comp_staff', 'Error bca_pic is null');
//            $r['status'] = -1;
//            $r['refersh'] = false;
//            return $r;
//        }
//        if (array_key_exists("csoc_photo",$infoargs) && $infoargs['csoc_photo']==''){
//            $r['msg'] = Yii::t('comp_staff', 'Error csoc_pic is null');
//            $r['status'] = -1;
//            $r['refersh'] = false;
//            return $r;
//        }
//        var_dump($infoargs);
        if (array_key_exists("bca_issue_date",$infoargs) && $infoargs['bca_issue_date']==''){
            $r['msg'] = Yii::t('comp_staff', 'Error issue_date is null');
            $r['status'] = -1;
            $r['refersh'] = false;
            return $r;
        }
        if (array_key_exists("bca_expire_date",$infoargs) && $infoargs['bca_expire_date']==''){
            $r['msg'] = Yii::t('comp_staff', 'Error expiry_date is null');
            $r['status'] = -1;
            $r['refersh'] = false;
            return $r;
        }

        if (array_key_exists("csoc_issue_date",$infoargs) && $infoargs['csoc_issue_date']==''){
            $r['msg'] = Yii::t('comp_staff', 'Error issue_date is null');
            $r['status'] = -1;
            $r['refersh'] = false;
            return $r;
        }
        if (array_key_exists("csoc_expire_date",$infoargs) && $infoargs['csoc_expire_date']==''){
            $r['msg'] = Yii::t('comp_staff', 'Error expiry_date is null');
            $r['status'] = -1;
            $r['refersh'] = false;
            return $r;
        }
        if (array_key_exists("csoc_no",$infoargs) && $infoargs['csoc_no']==''){
            $r['msg'] = Yii::t('comp_staff', 'Error Csoc_no is null');
            $r['status'] = -1;
            $r['refersh'] = false;
            return $r;
        }
        $infomodel = new StaffInfo('create');
        //$transaction=$infomodel->dbConnection->beginTransaction();
        //图片转二进制
        /*if ($infoargs['face_img'] <> '') {
            $attach1 = $infoargs['face_img'];
            $fsize = filesize($attach1);
            $handle = fopen($attach1, "r");
            $infomodel->face_img = fread($handle, $fsize);
            //var_dump($infomodel->face_img);
            fclose($handle);
        }*/
       
        try{
            $infomodel->user_id = $id;
            foreach ($infoargs as $key => $v) {
                if($v != ''&& $key != 'tag'){
                    $infomodel->$key = $v;
                }
            }
             $infomodel->face_img = $infoargs['face_img'];
//            $infomodel->bca_issue_date = Utils::DateToCn($infoargs['bca_issue_date']);
//            $infomodel->bca_expire_date = Utils::DateToCn($infoargs['bca_expire_date']);
//            $infomodel->csoc_issue_date = Utils::DateToCn($infoargs['csoc_issue_date']);
//            $infomodel->csoc_expire_date = Utils::DateToCn($infoargs['csoc_expire_date']);
//            $infomodel->ins_scy_issue_date = Utils::DateToCn($infoargs['ins_scy_issue_date']);
//            $infomodel->ins_scy_expire_date = Utils::DateToCn($infoargs['ins_scy_expire_date']);
//            $infomodel->ins_med_issue_date = Utils::DateToCn($infoargs['ins_med_issue_date']);
//            $infomodel->ins_med_expire_date = Utils::DateToCn($infoargs['ins_med_expire_date']);
//            $infomodel->ins_adt_issue_date = Utils::DateToCn($infoargs['ins_adt_issue_date']);
//            $infomodel->ins_adt_expire_date = Utils::DateToCn($infoargs['ins_adt_expire_date']);
            $res = $infomodel->save();
            if(!$res){
                $r['msg'] = Yii::t('common', 'error_insert');
                $r['status'] = -1;
                $r['refresh'] = false;
            }
        }
        catch(Exception $e){
                //$transaction->rollBack();
                $r['status'] = -1;
                $r['msg'] = $e->getmessage();
                $r['refresh'] = false;
            }
        return $r;
    }
    //修改数据
    public static function updateStaffInfo($args,$infoargs) {
        //判断是否有此user_id的一条记录
        $connection = Yii::app()->db;
        $sql = "select count(*) from bac_staff_info where  user_id = :user_id";
        $command = $connection->createCommand($sql);
        $command->bindParam(":user_id", $args['user_id'], PDO::PARAM_STR);
        $exist_data = $command->queryAll();
        $data = $exist_data[0]["count(*)"];
//        var_dump($data);
//        exit;
        if ($data ==0) {
            //插入一条此user_id的记录
            $usermodel = new StaffInfo('create');
            $usermodel->user_id = $args['user_id'];
            $res = $usermodel->save();
        }

        $infomodel = StaffInfo::model()->findByPk($args['user_id']);
//        var_dump($infomodel);
//        var_dump($infoargs);
//        exit;
        //图片转二进制
        if ($infoargs['face_img'] <> '') {
//            var_dump(1111111111111);
//            exit;
//            $old_face_id = $infomodel->face_id;
//                
//            $face_rs=Face::face_id($infoargs['face_img']);
//            if($face_rs['errno'] == -1){
//                $r['msg'] = Yii::t('comp_staff', 'Error no face');
//                $r['status'] = -1;
//                $r['refresh'] = false;
//                return $r;
//            }
//            $infomodel->face_id=$face_rs['face_id'];
//               
//            $new_face_id = $face_rs['face_id'];
               
            /*$attach1 = $infoargs['face_img'];
            $fsize = filesize($attach1);
            $handle = fopen($attach1, "r");
            $infomodel->face_img = fread($handle, $fsize);
            fclose($handle);*/
//         var_dump($infoargs['face_img']);
//         exit;
            $infomodel->face_img = $infoargs['face_img'];  //图片路径
        }
//        var_dump($infoargs);
//        exit;
        if(array_key_exists("ppt_photo",$infoargs) && $infoargs['ppt_photo']=='') {
            if ($infomodel->ppt_photo) {
                $infoargs['ppt_photo'] = $infomodel->ppt_photo;
            }
        }
        if(array_key_exists("home_id_photo",$infoargs) && $infoargs['home_id_photo']==''){
            if($infomodel->home_id_photo){
                $infoargs['home_id_photo'] = $infomodel->home_id_photo;
            }
        }
        if(array_key_exists("bca_photo",$infoargs) && $infoargs['bca_photo']=='') {
            if ($infomodel->bca_photo) {
                $infoargs['bca_photo'] = $infomodel->bca_photo;
            }
        }
        if(array_key_exists("csoc_photo",$infoargs) && $infoargs['csoc_photo']=='') {
            if ($infomodel->csoc_photo) {
                $infoargs['csoc_photo'] = $infomodel->csoc_photo;
            }
        }
//        if(array_key_exists("bca_photo",$infoargs) && $infoargs['bca_photo']=='') {
//            if ($infomodel->bca_photo) {
//                $infoargs['bca_photo'] = $infomodel->bca_photo;
//            }else{
//                $r['msg'] = Yii::t('comp_staff', 'Error bca_pic is null');
//                $r['status'] = -1;
//                $r['refersh'] = false;
//                return $r;
//            }
//        }
//        if(array_key_exists("csoc_photo",$infoargs) && $infoargs['csoc_photo']=='') {
//            if ($infomodel->csoc_photo) {
//                $infoargs['csoc_photo'] = $infomodel->csoc_photo;
//            }else{
//                $r['msg'] = Yii::t('comp_staff', 'Error csoc_pic is null');
//                $r['status'] = -1;
//                $r['refersh'] = false;
//                return $r;
//            }
//        }
        if (array_key_exists("bca_issue_date",$infoargs) && $infoargs['bca_issue_date']==''){
            $r['msg'] = Yii::t('comp_staff', 'Error issue_date is null');
            $r['status'] = -1;
            $r['refersh'] = false;
            return $r;
        }
        if (array_key_exists("bca_expire_date",$infoargs) && $infoargs['bca_expire_date']==''){
            $r['msg'] = Yii::t('comp_staff', 'Error expiry_date is null');
            $r['status'] = -1;
            $r['refersh'] = false;
            return $r;
        }
        if (array_key_exists("bca_company",$infoargs) && $infoargs['bca_company']==''){
            $r['msg'] = Yii::t('comp_staff', 'Error company_name is null');
            $r['status'] = -1;
            $r['refersh'] = false;
            return $r;
        }
        if (array_key_exists("csoc_issue_date",$infoargs) && $infoargs['csoc_issue_date']==''){
            $r['msg'] = Yii::t('comp_staff', 'Error issue_date is null');
            $r['status'] = -1;
            $r['refersh'] = false;
            return $r;
        }
        if (array_key_exists("csoc_expire_date",$infoargs) && $infoargs['csoc_expire_date']==''){
            $r['msg'] = Yii::t('comp_staff', 'Error expiry_date is null');
            $r['status'] = -1;
            $r['refersh'] = false;
            return $r;
        }
        if (array_key_exists("csoc_no",$infoargs) && $infoargs['csoc_no']==''){
            $r['msg'] = Yii::t('comp_staff', 'Error Csoc_no is null');
            $r['status'] = -1;
            $r['refersh'] = false;
            return $r;
        }
        try{
            foreach ($infoargs as $key => $v) {
//                if($v != ''){
                    //$infomodel->$key = Utils::DateToCn($v);
                    $infomodel->$key = $v;
//                }
            }
//            $infomodel->bca_issue_date = Utils::DateToCn($infoargs['bca_issue_date']);
//            $infomodel->bca_expire_date = Utils::DateToCn($infoargs['bca_expire_date']);
//            $infomodel->csoc_issue_date = Utils::DateToCn($infoargs['csoc_issue_date']);
//            $infomodel->csoc_expire_date = Utils::DateToCn($infoargs['csoc_expire_date']);
//            $infomodel->ins_scy_issue_date = Utils::DateToCn($infoargs['ins_scy_issue_date']);
//            $infomodel->ins_scy_expire_date = Utils::DateToCn($infoargs['ins_scy_expire_date']);
//            $infomodel->ins_med_issue_date = Utils::DateToCn($infoargs['ins_med_issue_date']);
//            $infomodel->ins_med_expire_date = Utils::DateToCn($infoargs['ins_med_expire_date']);
//            $infomodel->ins_adt_issue_date = Utils::DateToCn($infoargs['ins_adt_issue_date']);
//            $infomodel->ins_adt_expire_date = Utils::DateToCn($infoargs['ins_adt_expire_date']);
            $res = $infomodel->save();
            if(!$res){
                $r['msg'] = Yii::t('common', 'error_update');
                $r['status'] = -1;
                $r['refresh'] = false;
            }else{
                $r['msg'] = Yii::t('common', 'success_update');
                $r['status'] = 1;
                $r['refresh'] = true;
                $r['user_id'] = $args['user_id'];
            }
        }
        catch(Exception $e){
//            $transaction->rollBack();
            $r['status'] = -1;
            $r['msg'] = $e->getmessage();
            $r['refresh'] = false;
        }
        return $r;
    }
    
    //得到一个企业下所有员工的资质信息
    public static function staffinfoExport($rs){
        $params = array();
        $connection = Yii::app()->db;
        foreach ($rs as $index => $v) {
//            var_dump($v);
//            exit;
            $sql = "select * from bac_staff_info where user_id = '".$v."'";
            $command = $connection->createCommand($sql);
            $data = $command->queryAll();
            $rows[] = $data[0];
        }
        return $rows;
    }
    
    //得到一个员工的资质信息
    public static function staffinfoPhoto($user_id){
        $params = array();
        $connection = Yii::app()->db;
        $sql = "select ppt_photo,bca_photo,csoc_photo from bac_staff_info where user_id = '".$user_id."'";
        $command = $connection->createCommand($sql);
        $rows = $command->queryAll();
       
        return $rows;
    }
    
}