<?php

/**
 * 文档管理
 * @author LiuMinchao
 */
class ProgramDocument extends CActiveRecord {

    //承包商类型
    const CONTRACTOR_TYPE_MC = 'MC'; //总包
    const CONTRACTOR_TYPE_SC = 'SC'; //分包
    const STATUS_NORMAL = '00'; //正常
    const STATUS_STOP = '01'; //停用

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_program_document';
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'role_id' => Yii::t('sys_role', 'role_id'),
            'contractor_type' => Yii::t('sys_role', 'contractor_type'),
            'role_name' => Yii::t('sys_role', 'role_name'),
            'role_name_en' => Yii::t('sys_role', 'role_name_en'),
            'team_name' => Yii::t('sys_role', 'team_name'),
            'team_name_en' => Yii::t('sys_role', 'team_name_en'),
            'sort_id' => Yii::t('sys_role', 'order'),
            'status' => Yii::t('sys_role', 'status'),
            'record_time' => Yii::t('sys_role', 'record_time'),
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Role the static model class
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

        $total_num = Document::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'doc_id ASC';
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
        $rows = Document::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }

    //移动文件，添加数据
    public static function movePic($file_src,$args){
//        $name = substr($file_src,35);
        $name = substr($file_src,38);
//        var_dump($file_src);
//        exit;
//        $conid = Yii::app()->user->getState('contractor_id');
        if($args['type'] == 2) {
            $upload_path = Yii::app()->params['upload_company_path'];
            $contractor_id = Yii::app()->user->getState('contractor_id');
            $upload = $upload_path . '/' . $contractor_id . '/';
            if (!file_exists($upload)) {
                umask(0000);
                @mkdir($upload, 0777, true);
            }
            $upload_file = $upload.$name;
        }else{
            $upload_path = Yii::app()->params['upload_platform_path'];
            $upload_file = $upload_path . '/' . $name;
        }
//            var_dump($name);exit;
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
        $model = new Document('create');
        $model->doc_path = substr($upload_file,18);
        $model->doc_name = $file_name[0];
        $model->doc_type = $file_name[1];
        $model->label_id = 5;
        if($args['type'] == 2){
            $model->contractor_id = $contractor_id;
        }
        $model->type = $args['type'];
        $result = $model->save();
        if($result){
            $r['msg'] = Yii::t('common', 'success_insert');
            $r['status'] = 1;
            $r['refresh'] = true;
        }
        return $r;
    }

    //设置常用状态
    public static function setUsed($doc_id,$doc_use){

        if($doc_use == 0){
            $sql = "update bac_document set doc_use = 1 where doc_id = '".$doc_id."'";
        }else{
            $sql = "update bac_document set doc_use = 0 where doc_id = '".$doc_id."'";
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
    public static function deleteFile($doc_id,$doc_path) {
        $sql = "delete from bac_document where doc_id = '".$doc_id."' and doc_path = '".$doc_path."'";
        $command = Yii::app()->db->createCommand($sql);
        $re = $command->execute();
//        var_dump($re);
//        exit;
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
    //查询数据
    public static function queryFile($doc_path) {
        $sql = "select * from bac_document where  doc_path ='".$doc_path."'";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }
}
