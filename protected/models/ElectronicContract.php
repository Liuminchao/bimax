<?php

/**
 * 电子合约
 * @author LiuMinchao
 */
class ElectronicContract extends CActiveRecord {

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_electronic_contract';
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Meeting the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'title' => Yii::t('electronic_contract', 'title'),
            'file' => Yii::t('electronic_contract', 'upload_contract'),
            'start_date' => Yii::t('electronic_contract', 'start_date'),
            'end_date' => Yii::t('electronic_contract', 'end_date'),
            'content' => Yii::t('electronic_contract', 'content'),
        );
    }

    //根据承包商查询电子合约
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

        //Operator
        if ($args['contractor_id'] != '') {
            $condition.= ( $condition == '') ? ' contractor_id=:contractor_id' : ' AND contractor_id=:contractor_id';
            $params['contractor_id'] = $args['contractor_id'];
        }

        $total_num = ElectronicContract::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'record_time';
        } else {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $order = substr($_REQUEST['q_order'], 0, -1) . ' ASC';
            else
                $order = $_REQUEST['q_order'] . ' DESC';
        }
        $criteria->order = $order;
        $criteria->condition = $condition;
        $criteria->params = $params;
        $pages = new CPagination($total_num);
        $pages->pageSize = $pageSize;
        $pages->setCurrentPage($page);
        $pages->applyLimit($criteria);
        $rows = ElectronicContract::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }
    
    //插入数据
    public static function insertContract($args) {

        if ($args['title'] == '') {
            $r['msg'] = Yii::t('electronic_contract', 'error_title_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }
       
        try {
            $model = new ElectronicContract('create');
            $model->title = $args['title'];
            $model->file_path = $args['file_path'];
            $model->contractor_id = $args['contractor_id'];
            $model->start_date = $args['start_date'];
            $model->end_date = $args['end_date'];
            $model->content = $args['content'];
            $model->record_time = date('Y-m-d H:i:s');
            $result = $model->save();

            if ($result) {

                $r['msg'] = Yii::t('common', 'success_insert');
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = Yii::t('common', 'error_insert');
                $r['status'] = -1;
                $r['refresh'] = false;
            }
        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getMessage();
            $r['refresh'] = false;
        }
        return $r;
    }
    //删除数据
    public static function deleteContract($id,$path) {
        $sql = "delete from bac_electronic_contract where contractor_id = '".$id."' and file_path = '".$path."'";
        $command = Yii::app()->db->createCommand($sql);
        $re = $command->execute();
        if ($re == 1) {
            $r['msg'] = Yii::t('common', 'success_delete');
            $r['status'] = 1;
            $r['refresh'] = true;
        } else {
            $r['msg'] = Yii::t('common', 'error_delete');
            $r['status'] = -1;
            $r['refresh'] = false;
        }
        return $r;
    }
    //查询数据
    public static function queryContract($path) {
        $sql = "select * from bac_electronic_contract where  file_path ='".$path."'";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }
}