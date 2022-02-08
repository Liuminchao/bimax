<?php

/**
 * RFI/RFA
 * @author LiuMinchao
 */
class RfRecordAttachment extends CActiveRecord {

    const STATUS_NORMAL = '0'; //已启用
    const STATUS_DISABLE = '1'; //未启用

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'rf_record_attach';
    }

    //状态
    public static function statusText($key = null) {
        $rs = array(
            '0' => 'unsynchronized',
            '1' => 'Synchronized',
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            '0' => 'label-success', //未同步
            '1' => 'label-info', //已同步
        );
        return $key === null ? $rs : $rs[$key];
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ApplyBasicLog the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    //添加
    public static function insertList($args){
        if($args['attachment']){
            foreach($args['attachment'] as $i => $file_src){
                $name = $file_src;
                $formal_src = $file_src;
                $str = substr($file_src,0,18);
                if($str != '/filebase/data/rf/'){
                    $date = date('YmdHis');
                    $rand = rand(0001,9999);
                    $name = substr($file_src,27);
                    $doc_name = substr($file_src,38);
                    $doc = explode('.',$doc_name);
                    $upload_path = Yii::app()->params['upload_data_path'];
                    $contractor_id = Yii::app()->user->getState('contractor_id');
                    $upload = $upload_path . '/rf/draft/';
                    if (!file_exists($upload)) {
                        umask(0000);
                        @mkdir($upload, 0777, true);
                    }
//                    $upload_file = $upload.$name;
                    $upload_file = $upload.$doc[0].'_'.$date.$rand.'.'.$doc[1];
                    //移动文件到指定目录下
                    if (copy($file_src,$upload_file)) {
                        $formal_src = substr($upload_file,18);
                    }else{
                        goto end;
                    }
                }
                $sql = "insert into rf_record_attach (check_id,step,doc_name,doc_path) values (:check_id,:step,:doc_name,:doc_path)";
                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(":check_id", $args['check_id'], PDO::PARAM_STR);
                $command->bindParam(":step", $args['step'], PDO::PARAM_STR);
                $command->bindParam(":doc_name", $name, PDO::PARAM_STR);
                $command->bindParam(":doc_path", $formal_src, PDO::PARAM_STR);
                $rs = $command->execute();
                end:
            }
        }

        if ($rs) {
            $r['msg'] = Yii::t('common', 'success_insert');
            $r['status'] = 1;
            $r['refresh'] = true;
        }else{
//                $trans->rollBack();
            $r['msg'] = Yii::t('common', 'error_insert');
            $r['status'] = -1;
            $r['refresh'] = false;
        }
        return $r;
    }

    public static function movePic($args){
        if($args['attachment']){
            foreach($args['attachment'] as $i => $file_src){
                $date = date('YmdHis');
                $rand = rand(0001,9999);
                $name = $file_src;
                $formal_src = $file_src;
                $str = substr($file_src,0,18);
                if($str != '/filebase/data/rf/'){
                    $name = substr($file_src,27);
                    $doc_name = substr($file_src,38);
                    $doc = explode('.',$doc_name);
                    $upload_path = Yii::app()->params['upload_data_path'];
                    $contractor_id = Yii::app()->user->getState('contractor_id');
                    $upload = $upload_path . '/rf/' . $contractor_id . '/' .$args['program_id'] .'/';
                    if (!file_exists($upload)) {
                        umask(0000);
                        @mkdir($upload, 0777, true);
                    }
//                    $upload_file = $upload.$name;
                    $upload_file = $upload.$doc[0].'_'.$date.$rand.'.'.$doc[1];
                    //移动文件到指定目录下
                    if (copy($file_src,$upload_file)) {
                        $formal_src = substr($upload_file,18);
                    }else{
                        goto end;
                    }
                }else{
                    $name = substr($file_src,24);
                    $doc_name = substr($file_src,35);
                    $doc = explode('.',$doc_name);
                    $upload_path = Yii::app()->params['upload_data_path'];
                    $contractor_id = Yii::app()->user->getState('contractor_id');
                    $upload = $upload_path . '/rf/' . $contractor_id . '/' .$args['program_id'] .'/';
                    if (!file_exists($upload)) {
                        umask(0000);
                        @mkdir($upload, 0777, true);
                    }
                    $upload_file = $upload.$doc[0].'_'.$date.$rand.'.'.$doc[1];
                    //移动文件到指定目录下
                    if (copy('/opt/www-nginx/web'.$file_src,$upload_file)) {
                        $formal_src = substr($upload_file,18);
                    }else{
                        goto end;
                    }
                }
                $sql = "insert into rf_record_attach (check_id,step,doc_name,doc_path) values (:check_id,:step,:doc_name,:doc_path)";
                $command = Yii::app()->db->createCommand($sql);
                $command->bindParam(":check_id", $args['check_id'], PDO::PARAM_STR);
                $command->bindParam(":step", $args['step'], PDO::PARAM_STR);
                $command->bindParam(":doc_name", $doc_name, PDO::PARAM_STR);
                $command->bindParam(":doc_path", $formal_src, PDO::PARAM_STR);
                $rs = $command->execute();
                end:
            }
        }

        if ($rs) {
            $r['msg'] = Yii::t('common', 'success_insert');
            $r['status'] = 1;
            $r['refresh'] = true;
        }else{
//                $trans->rollBack();
            $r['msg'] = Yii::t('common', 'error_insert');
            $r['status'] = -1;
            $r['refresh'] = false;
        }
        return $r;
    }

    /**
     * 按图纸查询RFA记录
     */
    public static function queryList($page, $pageSize, $args) {
        $sql = "select * from rf_record where check_id in (select check_id from rf_record_attach
                 where attach_id=:attach_id) ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":attach_id", $args['attach_id'], PDO::PARAM_STR);
        $data = $command->queryAll();

        $start=$page*$pageSize; #计算每次分页的开始位置
        $count = count($data);
        $pagedata=array();
        if($count>0){
            $pagedata=array_slice($data,$start,$pageSize);
        }else{
            $pagedata = array();
        }

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $count;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $pagedata;

        return $rs;
    }
    /**
     * 详情
     */
    public static function dealList($check_id) {
        $sql = "select * from rf_record_attach
                 where check_id=:check_id ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
        $rows = $command->queryAll();
        return $rows;
    }


    /**
     * 按步骤查询详情
     */
    public static function dealListBystep($check_id,$step) {

        $sql = "select * from rf_record_attach
                 where check_id=:check_id and step=:step";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
        $command->bindParam(":step", $step, PDO::PARAM_STR);
        $rows = $command->queryAll();
        return $rows;
    }

    //删除数据
    public static function deleteFile($attach_id,$doc_path) {
        $sql = "delete from rf_record_attach where attach_id = '".$attach_id."' ";
        $command = Yii::app()->db->createCommand($sql);
        $re = $command->execute();

        if ($re == 1) {
            $path = '/opt/www-nginx/web'.$doc_path;
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

    /**
     * 更新到图纸记录表
     */
    public static function syncDoc($check_id,$attach_id,$status){
        $rf_model = RfList::model()->findByPk($check_id);
        $rfa_type = $rf_model->rfa_type;
        $sql = "update rf_record_attach set deal_status = :deal_status,deal_type=:deal_type where check_id=:check_id and attach_id=:attach_id  order by step desc limit 1";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":deal_status", $status, PDO::PARAM_STR);
        $command->bindParam(":deal_type", $rfa_type, PDO::PARAM_STR);
        $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
        $command->bindParam(":attach_id", $attach_id, PDO::PARAM_STR);
        $rows = $command->execute();
        if ($rows) {
            $r['msg'] = Yii::t('common', 'success_update');
            $r['status'] = 1;
            $r['refresh'] = true;
        }else{
//                $trans->rollBack();
            $r['msg'] = Yii::t('common', 'error_update');
            $r['status'] = -1;
            $r['refresh'] = false;
        }
    }

    /**
     * 更新到图纸记录表
     */
    public static function queryDocStatus($check_id,$attach_id){
        $rf_model = RfList::model()->findByPk($check_id);
        $rfa_type = $rf_model->rfa_type;
        $sql = "select * from rf_record_attach  where check_id=:check_id and attach_id=:attach_id  order by step desc limit 1";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
        $command->bindParam(":attach_id", $attach_id, PDO::PARAM_STR);
        $rows = $command->queryAll();
        return $rows;
    }

    /**
     * 更新到图纸记录表
     */
    public static function queryNewDoc($check_id){
        $rf_model = RfList::model()->findByPk($check_id);
        $rfa_type = $rf_model->rfa_type;
        $sql = "select count(*) as count from rf_record_attach  where check_id=:check_id group by step ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
        $rows = $command->queryAll();
        $sql_1 = "select *  from rf_record_attach  where check_id=:check_id order by step desc limit  ".$rows[0]['count'];
        $command_1 = Yii::app()->db->createCommand($sql_1);
        $command_1->bindParam(":check_id", $check_id, PDO::PARAM_STR);
        $rows_1 = $command_1->queryAll();

        return $rows_1;
    }

}
