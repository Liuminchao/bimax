<?php

/**
 * 化学物品证书管理
 * @author LiuMinchao
 */
class ChemicalInfo extends CActiveRecord {

    const STATUS_NORMAL = '00'; //正常
    const STATUS_STOP = '01'; //结项

    public $subcomp_name; //指派分包公司名
    public $father_model;   //上级节点类
    public $subcomp_sn; //指派分包注册编号
    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_chemical_info';
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'certificate_startdate' => Yii::t('comp_staff', 'certificate_startdate'),
            'certificate_enddate' => Yii::t('comp_staff', 'certificate_enddate'),
            'certificate_type' => Yii::t('comp_staff', 'certificate_type'),
            'certificate_photo' => Yii::t('comp_staff', 'aptitude_photo'),
            'certificate_content' => Yii::t('comp_staff','aptitude_content'),
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

//        if ($args['chemical_id'] != '') {
//            $condition.= ( $condition == '') ? ' chemical_id=:chemical_id' : ' AND chemical_id=:chemical_id';
//            $params['chemical_id'] = $args['chemical_id'];
//        }

        if ($args['primary_id'] != '') {
            $condition.= ( $condition == '') ? ' primary_id=:primary_id' : ' AND primary_id=:primary_id';
            $params['primary_id'] = $args['primary_id'];
        }

        //Contractor
        if ($args['contractor_id'] != '') {
            $condition.= ( $condition == '') ? ' contractor_id=:contractor_id' : ' AND contractor_id=:contractor_id';
            $params['contractor_id'] = $args['contractor_id'];
        }

        //Contractor
        if ($args['certificate_title'] != '') {
            $condition.= ( $condition == '') ? ' certificate_title like :certificate_title' : ' AND certificate_title like :certificate_title';
            $params['certificate_title'] = '%'.$args['certificate_title'].'%';
        }

        $total_num = ChemicalInfo::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'chemical_id asc';
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
        $rows = ChemicalInfo::model()->findAll($criteria);
        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }
    
    public static function insertAttach($args){
//        var_dump($args);
//        exit;
        //array(1) { ["task_attach"]=> array(2) { ["code"]=> int(0) ["upload_file"]=> string(38) "./files/proj/135/20160107140555_17.jpg" } }
        $model = new ChemicalInfo('create');
        if ($args['certificate_content'] == '') {
            $r['msg'] = Yii::t('chemical', 'Error Chemical_content is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        if ($args['permit_startdate'] == '') {
            $r['msg'] = Yii::t('chemical', 'Error issue_date is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        if ($args['permit_enddate'] == '') {
            $r['msg'] = Yii::t('chemical', 'Error expiry_date is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        try{
            $model->chemical_id = $args['chemical_id'];
            $model->primary_id = $args['primary_id'];
            $model->certificate_photo = $args['certificate_photo'];
            $model->certificate_title = $args['certificate_content'];
            $model->certificate_content = $args['certificate_content'];
            $model->contractor_id = $args['contractor_id'];
            $model->permit_startdate = Utils::DateToCn($args['permit_startdate']);
            $model->permit_enddate = Utils::DateToCn($args['permit_enddate']);
            $model->certificate_type = $args['certificate_type'];
            $model->certificate_size = $args['certificate_size'];
            $model->type = $args['type'];
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
//        var_dump($args);
//        exit;
        if ($args['certificate_content'] == '') {
            $r['msg'] = Yii::t('chemical', 'Error Chemical_content is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        if ($args['permit_startdate'] == '') {
            $r['msg'] = Yii::t('chemical', 'Error issue_date is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        if ($args['permit_enddate'] == '') {
            $r['msg'] = Yii::t('chemical', 'Error expiry_date is null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
        if ($args['certificate_photo']) {
            $model = ChemicalInfo::model()->findByPk($args['id']);
//            var_dump($model);
//            exit;
            try{
                $model->chemical_id = $args['chemical_id'];
                $model->primary_id = $args['primary_id'];
                $model->certificate_photo = $args['certificate_photo'];
                $model->certificate_title = $args['certificate_content'];
                $model->certificate_content = $args['certificate_content'];
                $model->contractor_id = $args['contractor_id'];
                $model->permit_startdate = Utils::DateToCn($args['permit_startdate']);
                $model->permit_enddate = Utils::DateToCn($args['permit_enddate']);
                $model->certificate_type = $args['certificate_type'];
                $model->certificate_size = $args['certificate_size'];
                $model->type = $args['type'];
                $result = $model->save();
                if ($result) {
                    $r['status'] = 1;
                    $r['msg'] = Yii::t('common', 'success_update');
                    $r['refresh'] = true;
                }
                else {
                    $r['status'] = (string)-1;
                    $r['msg'] = Yii::t('common', 'error_update');
                    $r['refresh'] = false;
                }
            }
            catch(Exception $e){
                //$trans->rollBack();
                $r['status'] = -1;
                $r['msg'] = $e->getmessage();
                $r['refresh'] = false;
            }
        }else{
            $model = ChemicalInfo::model()->findByPk($args['id']);
            try{
                $model->chemical_id = $args['chemical_id'];
                $model->primary_id = $args['primary_id'];
                $model->certificate_title = $args['certificate_content'];
                $model->certificate_content = $args['certificate_content'];
                $model->contractor_id = $args['contractor_id'];
                $model->permit_startdate = $args['permit_startdate'];
                $model->permit_enddate = $args['permit_enddate'];
                $model->certificate_type = $args['certificate_type'];
                $result = $model->save();
                if ($result) {
                    $r['status'] = 1;
                    $r['msg'] = Yii::t('common', 'success_update');
                    $r['refresh'] = true;
                }
                else {
                    $r['status'] = (string)-1;
                    $r['msg'] = Yii::t('common', 'error_update');
                    $r['refresh'] = false;
                }
            }
            catch(Exception $e){
                //$trans->rollBack();
                $r['status'] = -1;
                $r['msg'] = $e->getmessage();
                $r['refresh'] = false;
            }

        }
        return $r;
    }
    public static function deleteAttach($args){
        $sql = "DELETE FROM bac_chemical_info WHERE chemical_id =:chemical_id and certificate_photo =:certificate_photo";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":chemical_id", $args['chemical_id'], PDO::PARAM_STR);
        $command->bindParam(":certificate_photo", $args['str'], PDO::PARAM_STR);
                    
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
        $upload_path = Yii::app()->params['upload_data_path'] . '/chemical/' .$conid;
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

    public static function queryAll($chemical_id){
        $sql = "select * from bac_chemical_info where chemical_id = '".$chemical_id."'";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }

    //设置常用状态
    public static function setUsed($id,$certificate_use){

        if($certificate_use == 0){
            $sql = "update bac_chemical_info set certificate_use = 1 where id = '".$id."'";
        }else{
            $sql = "update bac_chemical_info set certificate_use = 0 where id = '".$id."'";
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
        $model = ChemicalInfo::model()->findByPk($id);
        $certificate_photo = $model->certificate_photo;
        $sql = "delete from bac_chemical_info where id = '".$id."'";
        $command = Yii::app()->db->createCommand($sql);
        $re = $command->execute();
//        var_dump($re);
//        exit;
        if ($re == 1) {
            $path = '/opt/www-nginx/web'.$certificate_photo;
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
    //得到一个企业下所有设备的资质信息
    public static function chemicalinfoExport($rs){
        $params = array();
        $connection = Yii::app()->db;
        foreach ($rs as $index => $v) {
//            var_dump($v);
//            exit;
            $sql = "select * from bac_chemical_info where primary_id = '".$v."'";
            $command = $connection->createCommand($sql);
            $data = $command->queryAll();
            foreach($data as $num => $m){
                $rows[$v][] = $m;
            }
        }
        return $rows;
    }
}
