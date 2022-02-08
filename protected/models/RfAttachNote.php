<?php

/**
 * RFI/RFA
 * @author LiuMinchao
 */
class RfAttachNote extends CActiveRecord {

    const STATUS_NORMAL = '0'; //已启用
    const STATUS_DISABLE = '1'; //未启用

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'rf_attach_note';
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
        $pages = new CPagination($total_num);
        $pages->pageSize = $pageSize;
        $pages->setCurrentPage($page);
        $pages->applyLimit($criteria);
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
    public static function saveNote($args){
        $sql = "select * from rf_record_detail
                 where check_id=:check_id order by step DESC LIMIT 1";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_id", $args['check_id'], PDO::PARAM_STR);
        $rows = $command->queryAll();
        $id = $rows[0]['id'];
        $step = $rows[0]['step'];
        $check_id = $rows[0]['check_id'];
        $check_model = RfList::model()->findByPk($check_id);
        $user_phone = Yii::app()->user->id;
        $user = Staff::userByPhone($user_phone);
        if(count($user)>0){
            $user_model = Staff::model()->findByPk($user[0]['user_id']);
            $operator_id = $user_model->user_id;
        }else{
            $operator_id = Yii::app()->user->id;
        }
        if($rows[0]['deal_type'] == '9'){
            $contractor_id = Yii::app()->user->getState('contractor_id');
            $status ='1';
            $deal_type = RfDetail::STATUS_NOTE;
            $record_time = date("Y-m-d H:i:s");
            $model = new RfDetail('create');
            $model->check_id = $args['check_id'];
            $model->step = $step+1;
            $model->user_id = $operator_id;
            $model->deal_type = $deal_type;
            $model->remark = '';
            $model->status = $status;
            $model->record_time = $record_time;
            $result = $model->save();//var_dump($result);exit;
            $id = $model->id;
            $check_model->step = $step+1;
            $check_model->save();
        }

        $record_time = date("Y-m-d H:i:s");
        $model = new RfAttachNote('create');
        $model->check_id = $args['check_id'];
        $model->attach_id = $args['attach_id'];
        $model->detail_id = $id;
        $model->pic = $args['pic'];
        $model->remark = $args['remark'];
        $model->page = $args['pagenumber'];
        $model->record_time = $record_time;
        $result = $model->save();//var_dump($result);exit;

        if($result){
            $r['msg'] = Yii::t('common', 'success_insert');
            $r['status'] = 1;
            $r['refresh'] = true;
        }
        return $r;
    }

    //移动文件，添加数据
    public static function movePic($file_src,$args){
//        $name = substr($file_src,35);
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
        $model->version = 0;
        $model->deal_user = $operator_id;
        $model->record_time = $record_time;
        $model->status = self::STATUS_NORMAL;
        $result = $model->save();
        if($result){
            $r['msg'] = Yii::t('common', 'success_insert');
            $r['status'] = 1;
            $r['refresh'] = true;
        }
        return $r;
    }

    /**
     * 详情
     */
    public static function dealList($check_id,$attach_id) {
        $sql = "select * from rf_attach_note
                 where check_id=:check_id and attach_id=:attach_id";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
        $command->bindParam(":attach_id", $attach_id, PDO::PARAM_STR);
        $rows = $command->queryAll();
        return $rows;
    }

    /**
     * 详情
     */
    public static function dealListBystep($check_id,$step) {
        $sql = "select * from rf_attachment
                 where check_id=:check_id and step=:step";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":check_id", $check_id, PDO::PARAM_STR);
        $command->bindParam(":step", $step, PDO::PARAM_STR);
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

}
