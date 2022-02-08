<?php

/**
 * RFI/RFA
 * @author LiuMinchao
 */
class RfAttachment extends CActiveRecord {

    const STATUS_NORMAL = '0'; //已启用
    const STATUS_DISABLE = '1'; //未启用

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'rf_attach';
    }

    //状态
    public static function statusText($key = null) {
        $rs = array(
            '0' => 'Unpublish',
            '1' => 'Published',
        );
        return $key === null ? $rs : $rs[$key];
    }

    //审批状态
    public static function approveText($key = null) {
        $rs = array(
            '0' => 'NA',
            '1' => 'Approve',
            '2' => 'Approve With Comment',
            '3' => 'Revise and Resubmit',
            '4' => 'Reject',
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态CSS
    public static function approveCss($key = null) {
        $rs = array(
            '0' => 'label-default',
            '1' => 'label-success',
            '2' => 'label-primary',
            '3' => 'label-danger',
            '4' => 'label-warning',

        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            '0' => 'label-default', //未同步
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

        $condition.= ( $condition == '') ? ' status= 1' : ' AND status= 1';

        $user_phone = Yii::app()->user->id;
        $user = Staff::userByPhone($user_phone);

        if ($args['doc_name'] != '') {
            $condition.= ( $condition == '') ? ' doc_name LIKE :doc_name' : ' AND doc_name LIKE :doc_name';
            $params['doc_name'] = '%'.$args['doc_name'].'%';
        }

        if ($args['program_id'] != '') {
            $condition.= ( $condition == '') ? ' project_id=:project_id' : ' AND project_id=:project_id';
            $params['project_id'] = $args['program_id'];
        }

        if ($args['contractor_id'] != '') {
            $condition.= ( $condition == '') ? ' contractor_id=:contractor_id' : ' AND contractor_id=:contractor_id';
            $params['contractor_id'] = $args['contractor_id'];
        }

        if(count($user)>0){
            $user_model = Staff::model()->findByPk($user[0]['user_id']);
            $user_id = $user_model->user_id;
            $condition.= ( $condition == '') ? ' deal_user=:deal_user' : ' OR deal_user=:deal_user';
            $params['deal_user'] = $user_id;
        }

        $total_num = RfAttachment::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'attach_id DESC';
        } else {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $order = substr($_REQUEST['q_order'], 0, -1) . ' DESC';
            else
                $order = $_REQUEST['q_order'] . ' ASC';
        }
        $criteria->order = $order;
        $criteria->condition = $condition;
        $criteria->params = $params;
//        $pages = new CPagination($total_num);
//        $pages->pageSize = $pageSize;
//        $pages->setCurrentPage($page);
//        $pages->applyLimit($criteria);
        $rows = RfAttachment::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }

    //添加
    public static function insertList($args){
        if($args['attachment']){
            foreach($args['attachment'] as $i => $file_src){
                $name = $file_src;
                $formal_src = $file_src;
                if(!strpos($file_src,'/data/rf/')){
                    $name = substr($file_src,38);
                    $upload_path = Yii::app()->params['upload_data_path'];
                    $contractor_id = Yii::app()->user->getState('contractor_id');
                    $upload = $upload_path . '/rf/' . $contractor_id . '/' .$args['program_id'] .'/';
                    if (!file_exists($upload)) {
                        umask(0000);
                        @mkdir($upload, 0777, true);
                    }
                    $upload_file = $upload.$name;
                    //移动文件到指定目录下
                    if (rename($file_src,$upload_file)) {
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

    //移动文件，添加数据
    public static function movePic($args){
//        $name = substr($file_src,35);
        foreach($args['attachment'] as $i => $file_src){
            $name = substr($file_src,38);

            $upload_path = Yii::app()->params['upload_data_path'];
            $contractor_id = Yii::app()->user->getState('contractor_id');
            $upload = $upload_path . '/rf/' . $contractor_id . '/' .$args['program_id'] .'/';
            if (!file_exists($upload)) {
                umask(0000);
                @mkdir($upload, 0777, true);
            }
            $upload_file = $upload.$name;
            $file_name = explode('.',$name);
            //移动文件到指定目录下
            if (rename($file_src,$upload_file)) {
                $r['src'] = substr($upload_file,18);
                $r['status'] = 1;
                $r['refresh'] = true;
            }else{
                $r['msg'] = "Error moving";
                $r['status'] = -1;
                $r['refresh'] = false;
                return $r;
            }
            $model = new RfAttachment('create');
            $record_time = date('Y-m-d H:i:s');
            $model->project_id = $args['program_id'];
            $model->contractor_id = $contractor_id;
            $size = filesize($upload_file)/1024;
            $model->doc_size = sprintf('%.2f',$size);
            $model->doc_path = substr($upload_file,18);
            $model->doc_name = $file_name[0];
            $model->doc_type = $file_name[1];
            $model->project_id = $args['program_id'];
            $operator_id = Yii::app()->user->id;
            $user = Staff::userByPhone($operator_id);
            if(count($user)>0){
                $user_model = Staff::model()->findByPk($user[0]['user_id']);
                $user_id = $user_model->user_id;
                $model->deal_user = $user_id;
            }else{
                $model->deal_user = $operator_id;
            }
            $model->version = 0;
            $model->record_time = $record_time;
            $model->status = self::STATUS_NORMAL;
            $result = $model->save();
            if($result){
                $r['msg'] = Yii::t('common', 'success_insert');
                $r['status'] = 1;
                $r['refresh'] = true;
            }
        }

        return $r;
    }

    //移动文件，添加数据
    public static function moveBatchPic($program_id,$contractor_id,$tags,$label){
//        $name = substr($file_src,35);
        $args = explode("|",$tags);
        $label_list = explode("|",$label);
        foreach($args as $i => $j){
            $rf_attachment_model = RfAttachment::model()->findByPk($j);
            $tag = $rf_attachment_model->tag;
            if($tag == '0'){
                $doc_path = $rf_attachment_model->doc_path;
                $name = substr($doc_path,26);
                $upload_path = Yii::app()->params['upload_program_path'];
                $upload = $upload_path . '/' . $contractor_id . '/' .$program_id .'/';
                if (!file_exists($upload)) {
                    umask(0000);
                    @mkdir($upload, 0777, true);
                }
                $upload_file = $upload.$name;
//            var_dump($name);exit;
                $file_name = explode('.',$name);

                //移动文件到指定目录下
                if (copy('/opt/www-nginx/web'.$doc_path,'/opt/www-nginx/web'.$upload_file)) {
                    $r['src'] = substr($upload_file,18);
                    $r['status'] = 1;
                    $r['refresh'] = true;
                }else{
                    $r['msg'] = "Error moving";
                    $r['status'] = -1;
                    $r['refresh'] = false;
                    return $r;
                }
                $model = new Document('create');
                $model->program_id = $program_id;
                $model->contractor_id = $contractor_id;
                $model->label_id = $label_list[$i];
                $size = filesize($upload_file)/1024;
                $model->doc_size = sprintf('%.2f',$size);
                $model->doc_path = substr($upload_file,18);
                $model->doc_name = $file_name[0];
                $model->doc_type = $file_name[1];
                $model->doc_tag = '01';
                $model->type = 4;
                $result = $model->save();
                $params['label_id'] = $label_list[$i];
                $params['tag'] = 1;
                $json_params = json_encode($params);
                $rf_attachment_model->tag = $json_params;
                $result = $rf_attachment_model->save();
            }
        }
        if($result){
            $r['msg'] = Yii::t('common', 'success_set');
            $r['status'] = 1;
            $r['refresh'] = true;
        }
        return $r;
    }

    //移动文件，添加数据
    public static function publishPic($program_id,$attach_id,$label){

        $contractor_id = Yii::app()->user->getState('contractor_id');
        $rf_attachment = RfAttachment::model()->findByPk($attach_id);
        $tag = $rf_attachment->status;
        if($tag == '0'){
            $doc_path = $rf_attachment->doc_path;
            $name = substr($doc_path,26);
            $upload_path = Yii::app()->params['upload_program_path'];
            $upload = $upload_path . '/' . $contractor_id . '/' .$program_id .'/';
            if (!file_exists($upload)) {
                umask(0000);
                @mkdir($upload, 0777, true);
            }
            $upload_file = $upload.$name;
//            var_dump($name);exit;
            $file_name = explode('.',$name);

            //移动文件到指定目录下
            if (copy('/opt/www-nginx/web'.$doc_path,'/opt/www-nginx/web'.$upload_file)) {
                $r['src'] = substr($upload_file,18);
                $r['status'] = 1;
                $r['refresh'] = true;
            }else{
                $r['msg'] = "Error moving";
                $r['status'] = -1;
                $r['refresh'] = false;
                return $r;
            }
            $model = new Document('create');
            $model->program_id = $program_id;
            $model->contractor_id = $contractor_id;
            $model->label_id = $label;
            $size = filesize($upload_file)/1024;
            $model->doc_size = sprintf('%.2f',$size);
            $model->doc_path = substr($upload_file,18);
            $model->doc_name = $file_name[0];
            $model->doc_type = $file_name[1];
            $model->doc_tag = '01';
            $model->type = 4;
            $result = $model->save();
            $id = $model->doc_id;
            $rf_attachment->version = $id;
            $rf_attachment->status =  '1';
            $rf_attachment->save();
        }
        if($result){
            $r['msg'] = Yii::t('common', 'success_set');
            $r['status'] = 1;
            $r['refresh'] = true;
        }
        return $r;
    }

    /**
     * 详情
     */
    public static function dealList($check_id) {
        $sql = "select * from rf_attachment
                 where check_id=:check_id ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
        $rows = $command->queryAll();
        return $rows;
    }

    //删除数据
    public static function deleteFile($attach_id,$doc_path) {
        $sql = "delete from rf_attach where attach_id = '".$attach_id."' ";
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
    public static function syncDoc($check_id){
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
        if(count($rows_1)>0){
            foreach($rows_1 as $i => $j){
                if($j['attach_id'] != 0){
                    $rf_attach = RfAttachment::model()->findByPk($j['attach_id']);
                    if($j['deal_type'] == '1'){
                        $rf_attach->archi_status = $j['deal_status'];
                    }else if($j['deal_type'] == '2'){
                        $rf_attach->me_status = $j['deal_status'];
                    }else{
                        $rf_attach->cs_status = $j['deal_status'];
                    }
                    $result = $rf_attach->save();
                }
            }
        }
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
        return $r;
    }

}
