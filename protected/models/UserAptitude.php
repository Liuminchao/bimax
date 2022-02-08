<?php

/**
 * 员工资质证件管理
 * @author LiuMinchao
 */
class UserAptitude extends CActiveRecord {

    const STATUS_NORMAL = '00'; //正常
    const STATUS_STOP = '01'; //结项

    public $subcomp_name; //指派分包公司名
    public $father_model;   //上级节点类
    public $subcomp_sn; //指派分包注册编号
    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_aptitude';
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'certificate_startdate' => Yii::t('comp_staff', 'certificate_startdate'),
            'certificate_enddate' => Yii::t('comp_staff', 'certificate_enddate'),
            'certificate_type' => Yii::t('comp_staff', 'certificate_type'),
            'aptitude_photo' => Yii::t('comp_staff', 'aptitude_photo'),
            'aptitude_content' => Yii::t('comp_staff','aptitude_content'),
            'aptitude_name' => Yii::t('comp_staff','aptitude_remark'),
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Program the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
     /**
     * 查询
     * @param int $page
     * @param int $pageSize
     * @param array $args
     * @return array
     */
    public static function queryList($page, $pageSize, $args = array()) {

        $condition = '';
        $params = array();

        
        if ($args['user_id'] != '') {
            $condition.= ( $condition == '') ? ' user_id=:user_id' : ' AND user_id=:user_id';
            $params['user_id'] = $args['user_id'];
        }
        
        //Contractor
        if ($args['contractor_id'] != '') {
            $condition.= ( $condition == '') ? ' contractor_id=:contractor_id' : ' AND contractor_id=:contractor_id';
            $params['contractor_id'] = $args['contractor_id'];
        }

        //Contractor
        if ($args['aptitude_name'] != '') {
            $condition.= ( $condition == '') ? ' aptitude_name like :aptitude_name' : ' AND aptitude_name like :aptitude_name';
            $params['aptitude_name'] = '%'.$args['aptitude_name'].'%';
        }

        $total_num = UserAptitude::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = "(case WHEN certificate_type IN (30,31,32,33,34,35,36,37,38,39,4,40) then '0' else '1' end),certificate_type desc";
        } else {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $order = substr($_REQUEST['q_order'], 0, -1) . ' ASC';
            else
                $order = $_REQUEST['q_order'] . ' ASC';
        }
        $criteria->order = $order;
        $criteria->condition = $condition;
        $criteria->params = $params;
        $pages = new CPagination($total_num);
        $pages->pageSize = $pageSize;
        $pages->setCurrentPage($page);
        $pages->applyLimit($criteria);
        $rows = UserAptitude::model()->findAll($criteria);
//        var_dump($criteria);
        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }
    //导入人员批量模版
    public static function insertBach($id,$args,$infoargs){
        $passtype = CertificateType::passType();//准证类型
        $prefix = '/opt/www-nginx/web';
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $status = 1;
        if($infoargs['bca_photo'] != ''){
            $bca_args['user_id'] = $id;
            $bca_args['contractor_id'] = $contractor_id;
            $bca_args['aptitude_photo'] = $infoargs['bca_photo'];
            $bca_args['aptitude_content'] = $args['work_no'];
            $src = $prefix.$infoargs['bca_photo'];
            $bca_args['certificate_type'] = $passtype[$args['work_pass_type']];
            $bca_args['permit_startdate'] = Utils::DateToEn($infoargs['bca_issue_date']);
            $bca_args['permit_enddate'] = Utils::DateToEn($infoargs['bca_expire_date']);
            $name = substr($infoargs['bca_photo'],42);
            $file_name = explode('.',$name);
            $bca_args['contractor_id'] = Yii::app()->user->getState('contractor_id');
            $size = filesize($src)/1024;
            $bca_args['aptitude_size'] = sprintf('%.2f',$size);
//            $model->doc_path = substr($upload_file,18);
            $bca_args['aptitude_name'] = $file_name[0];
            $bca_args['aptitude_type'] = $file_name[1];
            $status = self::insertAttach($bca_args);
        }
//        var_dump($infoargs['csoc_photo']);
        if($infoargs['csoc_photo'] != ''){
            $csoc_args['user_id'] = $id;
            $csoc_args['contractor_id'] = $contractor_id;
            $csoc_args['aptitude_photo'] = $infoargs['csoc_photo'];
            $csoc_args['aptitude_content'] = $infoargs['csoc_no'];
            $src = $prefix.$infoargs['csoc_photo'];
            $csoc_args['certificate_type'] = '31';
            $csoc_args['permit_startdate'] = Utils::DateToEn($infoargs['csoc_issue_date']);
            $csoc_args['permit_enddate'] = Utils::DateToEn($infoargs['csoc_expire_date']);
            $name = substr($infoargs['csoc_photo'],42);
            $file_name = explode('.',$name);
            $csoc_args['contractor_id'] = Yii::app()->user->getState('contractor_id');
            $size = filesize($src)/1024;
            $csoc_args['aptitude_size'] = sprintf('%.2f',$size);
//            $model->doc_path = substr($upload_file,18);
            $csoc_args['aptitude_name'] = $file_name[0];
            $csoc_args['aptitude_type'] = $file_name[1];
            $status = self::insertAttach($csoc_args);
        }

        if($infoargs['ppt_photo'] != ''){
            $ppt_args['user_id'] = $id;
            $ppt_args['contractor_id'] = $contractor_id;
            $ppt_args['aptitude_photo'] = $infoargs['ppt_photo'];
            $ppt_args['aptitude_content'] = $infoargs['passport_no'];
            $src = $prefix.$infoargs['ppt_photo'];
            $ppt_args['certificate_type'] = '40';
            $ppt_args['permit_startdate'] = Utils::DateToEn($infoargs['ppt_issue_date']);
            $ppt_args['permit_enddate'] = Utils::DateToEn($infoargs['ppt_expire_date']);
            $name = substr($infoargs['ppt_photo'],43);
            $file_name = explode('.',$name);
            $ppt_args['contractor_id'] = Yii::app()->user->getState('contractor_id');
            $size = filesize($src)/1024;
            $ppt_args['aptitude_size'] = sprintf('%.2f',$size);
//            $model->doc_path = substr($upload_file,18);
            $ppt_args['aptitude_name'] = $file_name[0];
            $ppt_args['aptitude_type'] = $file_name[1];
            $status = self::insertAttach($ppt_args);
        }
        return $status;
    }
    public static function insertAttach($args){

        if ($args['aptitude_content'] == '') {
            $r['msg'] = Yii::t('comp_staff', 'Error Staff_content is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        if ($args['permit_startdate'] == '') {
            $r['msg'] = Yii::t('comp_staff', 'Error issue_date is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        if ($args['permit_enddate'] == '') {
            $r['msg'] = Yii::t('comp_staff', 'Error expiry_date is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        $model = new UserAptitude('create');
        try{
            $model->user_id = $args['user_id'];
            $model->aptitude_name = $args['aptitude_name'];
            $model->aptitude_photo = $args['aptitude_photo'];
            $model->aptitude_content = $args['aptitude_content'];
            $model->contractor_id = $args['contractor_id'];
            $model->permit_startdate = Utils::DateToCn($args['permit_startdate']);
            $model->permit_enddate = Utils::DateToCn($args['permit_enddate']);
            $model->certificate_type = $args['certificate_type'];
            $model->aptitude_type = $args['aptitude_type'];
            $model->aptitude_size = $args['aptitude_size'];
            $result = $model->save();
            if ($result) {
                $r['status'] = 1;
                $r['msg'] = Yii::t('common', 'success_insert');
                $r['refresh'] = true;
            }
            else {
                $r['status'] = (string)-1;
                $r['msg'] = Yii::t('common', 'error_insert');
                $r['refresh'] = false;
            }
        }
        catch(Exception $e){
            //$trans->rollBack();
            $r['status'] = -1;
            $r['msg'] = $e->getmessage();
            $r['refresh'] = false;
        }
        return $r;
    }
    public static function updateAttach($args){
        if ($args['permit_startdate'] == '') {
            $r['msg'] = Yii::t('comp_staff', 'Error issue_date is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        if ($args['permit_enddate'] == '') {
            $r['msg'] = Yii::t('comp_staff', 'Error expiry_date is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        if ($args['aptitude_content'] == '') {
            $r['msg'] = Yii::t('comp_staff', 'Error Staff_content is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        if($args['aptitude_photo']) {
            $model = UserAptitude::model()->findByPk($args['aptitude_id']);
            try {
//                var_dump($args['permit_startdate']);
//                var_dump($args['permit_enddate']);
//                var_dump(Utils::DateToCn($args['permit_startdate']));
//                var_dump(Utils::DateToCn($args['permit_enddate']));
//                exit;
                $old_src = $model->aptitude_photo;
                $src = '/opt/www-nginx/web'.$old_src;
                unlink($src);
                $model->user_id = $args['user_id'];
                $model->aptitude_name = $args['aptitude_name'];
                $model->aptitude_photo = $args['aptitude_photo'];
                $model->aptitude_content = $args['aptitude_content'];
                $model->contractor_id = $args['contractor_id'];
                $model->permit_startdate = Utils::DateToCn($args['permit_startdate']);
                $model->permit_enddate = Utils::DateToCn($args['permit_enddate']);
                $model->certificate_type = $args['certificate_type'];
                $model->aptitude_type = $args['aptitude_type'];
                $model->aptitude_size = $args['aptitude_size'];
                $result = $model->save();
                if ($result) {
                    $r['status'] = 1;
                    $r['msg'] = Yii::t('common', 'success_update');
                    $r['refresh'] = true;
                } else {
                    $r['status'] = (string)-1;
                    $r['msg'] = Yii::t('common', 'error_update');
                    $r['refresh'] = false;
                }
            } catch (Exception $e) {
                //$trans->rollBack();
                $r['status'] = -1;
                $r['msg'] = $e->getmessage();
                $r['refresh'] = false;
            }
        }else{
            $model = UserAptitude::model()->findByPk($args['aptitude_id']);
            try {
//                var_dump($args['permit_startdate']);
//                var_dump($args['permit_enddate']);
//                var_dump(Utils::DateToCn($args['permit_startdate']));
//                var_dump(Utils::DateToCn($args['permit_enddate']));
//                exit;
                $model->aptitude_name = $args['aptitude_name'];
                $model->aptitude_content = $args['aptitude_content'];
                $model->contractor_id = $args['contractor_id'];
                $model->permit_startdate = $args['permit_startdate'];
                $model->permit_enddate = $args['permit_enddate'];
                $model->certificate_type = $args['certificate_type'];
                $result = $model->save();
                if ($result) {
                    $r['status'] = 1;
                    $r['msg'] = Yii::t('common', 'success_update');
                    $r['refresh'] = true;
                } else {
                    $r['status'] = (string)-1;
                    $r['msg'] = Yii::t('common', 'error_update');
                    $r['refresh'] = false;
                }
            } catch (Exception $e) {
                //$trans->rollBack();
                $r['status'] = -1;
                $r['msg'] = $e->getmessage();
                $r['refresh'] = false;
            }
        }
        return $r;
    }
    public static function deleteAttach($args){
        $sql = "DELETE FROM bac_aptitude WHERE user_id =:user_id and aptitude_photo =:aptitude_photo";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":user_id", $args['user_id'], PDO::PARAM_STR);
        $command->bindParam(":aptitude_photo", $args['str'], PDO::PARAM_STR);
                    
        $rs = $command->execute();
        
        if($rs ==2 || $rs == 1){
            $r['msg'] = Yii::t('common', 'success_delete');
            $r['status'] = 1;
            $r['refresh'] = true;
        }else{
            $r['msg'] = Yii::t('common', 'error_delete');
            $r['status'] = -1;
            $r['refresh'] = false;
        }
        return $r;
    }
    public static function deletePic($src){
        $src = '/opt/www-nginx/web'.$src;
        if (!unlink($src))
        {
            $r['msg'] = "Error deleting $src";
            $r['status'] = -1;
            $r['refresh'] = false;
        }
        else
        {
            $r['msg'] = "Deleted $src";
            $r['status'] = 1;
            $r['refresh'] = true;
        }
        return $r;
    }
    public static function movePic($file_src){
//        $name = substr($file_src,24);
        $name = substr($file_src,27);
        $conid = Yii::app()->user->getState('contractor_id');
        $upload_path = Yii::app()->params['upload_data_path'] . '/staff/' .$conid;
        $upload_file = $upload_path.'/'.$name;
//            var_dump($name);exit;
        //创建目录
        if($upload_path == ''){
            return false;
        }
        if(!file_exists($upload_path))
        {
            umask(0000);
            @mkdir($upload_path, 0777, true);
        }
        //移动文件到指定目录下
        if (rename($file_src,$upload_file)) {
            $r['src'] = substr($upload_file,18);
            $r['status'] = 1;
            $r['refresh'] = true;
        }else{
            $r['msg'] = "Error moving";
            $r['status'] = -1;
            $r['refresh'] = false;
        }
        return $r;
    }
    public static function queryAll($user_id){
        $sql = "select * from bac_aptitude where user_id = '".$user_id."'";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }
    //查询人员  wp/ep/sp证书到期日期
    public static function queryExpiryDate($user_id){
        $sql = "select permit_enddate from bac_aptitude where user_id = '".$user_id."' and certificate_type in (36,37,38)";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        $expiry_date = '';
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $permit_enddate = Utils::DateToEn($row['permit_enddate']);
                $expiry_date.=$permit_enddate.' ';
            }
        }
        return $expiry_date;
    }

    //查询人员  wp/ep/sp证书到期日期
    public static function queryCsocDate($user_id){
        $sql = "select permit_enddate from bac_aptitude where user_id = '".$user_id."' and certificate_type in (31,32)";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        $expiry_date = '';
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $permit_enddate = Utils::DateToEn($row['permit_enddate']);
                $expiry_date.=$permit_enddate.' ';
            }
        }
        return $expiry_date;
    }

    //查询数据
    public static function queryFile($aptitude_photo) {
        $sql = "select * from bac_aptitude where  aptitude_photo ='".$aptitude_photo."'";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }

    //设置常用状态
    public static function setUsed($aptitude_id,$aptitude_use){

        if($aptitude_use == 0){
            $sql = "update bac_aptitude set aptitude_use = 1 where aptitude_id = '".$aptitude_id."'";
        }else{
            $sql = "update bac_aptitude set aptitude_use = 0 where aptitude_id = '".$aptitude_id."'";
        }
        $command = Yii::app()->db->createCommand($sql);
        $re = $command->execute();
//        var_dump($re);
//        exit;
        if ($re == 1) {
            $r['msg'] = Yii::t('common', 'success_set');
            $r['status'] = 1;
            $r['refresh'] = true;
        } else {
            $r['msg'] = Yii::t('common', 'error_set');
            $r['status'] = -1;
            $r['refresh'] = false;
        }
        return $r;
    }

    //删除数据
    public static function deleteFile($id) {
        $model = UserAptitude::model()->findByPk($id);
        $aptitude_photo = $model->aptitude_photo;
        $sql = "delete from bac_aptitude where aptitude_id = '".$id."'";
        $command = Yii::app()->db->createCommand($sql);
        $re = $command->execute();
//        var_dump($re);
//        exit;
        if ($re == 1) {
            $path = '/opt/www-nginx/web'.$aptitude_photo;
            if (!unlink($path)){
                $r['msg'] = Yii::t('common', 'error_delete');
                $r['status'] = -1;
                $r['refresh'] = false;
            }else{
                $r['msg'] = Yii::t('common', 'success_delete');
                $r['status'] = 1;
                $r['refresh'] = true;
            }
        } else {
            $r['msg'] = Yii::t('common', 'error_delete');
            $r['status'] = -1;
            $r['refresh'] = false;
        }
        return $r;
    }
    //安全证
    public static function csocInfo($user_id){
        $sql = "select * from bac_aptitude where certificate_type in(30,31) and user_id = '".$user_id."'";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }
    //工作准证
    public static function bcaInfo($user_id){
        $sql = "select * from bac_aptitude where certificate_type in(35,36,37,38,39,4) and user_id = '".$user_id."'";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }
    //护照
    public static function pptInfo($user_id){
        $sql = "select * from bac_aptitude where certificate_type ='40' and user_id = '".$user_id."'";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }

    //人员证书详情
    public static function userDetail($program_id, $contractor_id){
        $sql = "SELECT c.user_id,c.certificate_type,c.aptitude_name FROM bac_aptitude c 
                INNER JOIN  (SELECT a.user_id,b.user_name,b.role_id FROM bac_program_user_q a,bac_staff b  WHERE  a.user_id =b.user_id and a.program_id=:program_id and a.contractor_id=:contractor_id) d 
                ON c.user_id = d.user_id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":program_id", $program_id, PDO::PARAM_STR);
        $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_STR);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['user_id']][$row['certificate_type']] = $row['aptitude_name'];
            }
        }
        return $rs;
    }
}
