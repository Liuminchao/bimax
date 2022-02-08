<?php

/**
 * This is the model class for table "ptw_condition_list".
 *
 * The followings are the available columns in table 'ptw_condition_list':
 * @property string $condition_name
 * @property string $condition_name_en
 * @property string $status
 * @property string $record_time
 *
 * The followings are the available model relations:
 * @property PtwTypeList[] $ptwTypeLists
 * @author LiuXiaoyuan
 */
class ProgressPlanHis extends CActiveRecord
{

    const STATUS_NORMAL = '0'; //正常
    const STATUS_STOP = '1'; //停用

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'progress_plan_his';
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PtwCondition the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    //状态
    public static function statusText($key = null) {
        $rs = array(
            self::STATUS_NORMAL => Yii::t('sys_role', 'STATUS_NORMAL'),
            self::STATUS_STOP => Yii::t('sys_role', 'STATUS_STOP'),
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::STATUS_NORMAL => 'label-success', //正常
            self::STATUS_STOP => ' label-danger', //停用
        );
        return $key === null ? $rs : $rs[$key];
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
        //Role
        if ($args['version_id'] != '') {
            $condition.= ( $condition == '') ? ' version_id=:version_id' : ' AND version_id=:version_id';
            $params['version_id'] = $args['version_id'];
        }

        //Status
        if ($args['status'] != '') {
            $condition.= ( $condition == '') ? ' status=:status' : ' AND status=:status';
            $params['status'] = $args['status'];
        }

        $total_num = ProgressPlan::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'plan_id ASC';
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
        $rows = ProgressPlan::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }

    //查询安全单
    public static function detailList($list_id){

        $sql = "SELECT * FROM qa_form_data_a WHERE  list_id = '".$list_id."' ";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        return $rows;

    }

    //表单项
    public static function itemAry($form_id) {
        $sql = "select * from qa_form where form_id = '".$form_id."' order by order_id asc ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                if (Yii::app()->language == 'zh_CN') {
                    $rs[$row['item_id']]['item_title'] = $row['item_title'];
                    $rs[$row['item_id']]['group_name'] = $row['group_name'];
                }else if (Yii::app()->language == 'en_US') {
                    $rs[$row['item_id']]['item_title'] = $row['item_title'];
                    $rs[$row['item_id']]['group_name'] = $row['group_name'];
                }
            }
        }
        return $rs;
    }

    //插入区域数据
    public static function InsertForm($args,$version_id){

        $sql = " SELECT
                    *
                FROM
                    progress_plan
                WHERE
                     project_id='".$args['project_id']."' and status='0'";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if(count($rows) > 0){
            foreach($rows as $i => $j){
                $model = new ProgressPlan('create');
                try {
                    $model->version_id = $version_id;
                    $model->unique_id = $args['uniqueID'];
                    $model->plan_name = $args['name'];
                    $model->project_id = $args['project_id'];
                    $model->plan_start = $args['start'];
                    $model->plan_finish = $args['finish'];
                    $model->duration_days = $args['duration'];
                    $model->father_plan = '';
                    $model->level = $args['level'];
                    $model->status = $status;
                    $model->record_time = $record_time;
                    $result = $model->save();
                    $r['msg'] = Yii::t('common','success_insert');
                    $r['status'] = 1;
                    $r['refresh'] = true;

                    $trans->commit();
                } catch (Exception $e) {
                    $r['status'] = -1;
                    $r['msg'] = $e->getMessage();
                    $r['refresh'] = false;
                    $trans->rollback();
                }
            }
        }

        $trans = Yii::app()->db->beginTransaction();
//        $is_required = '0';
        $status = '0';
        $record_time = date('Y-m-d H:i:s');
        $model = new ProgressPlan('create');
        try {
            $model->version_id = $version_id;
            $model->unique_id = $args['uniqueID'];
            $model->plan_name = $args['name'];
            $model->project_id = $args['project_id'];
            $model->plan_start = $args['start'];
            $model->plan_finish = $args['finish'];
            $model->duration_days = $args['duration'];
            $model->father_plan = '';
            $model->level = $args['level'];
            $model->status = $status;
            $model->record_time = $record_time;
            $result = $model->save();
            $r['msg'] = Yii::t('common','success_insert');
            $r['status'] = 1;
            $r['refresh'] = true;

            $trans->commit();
        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getMessage();
            $r['refresh'] = false;
            $trans->rollback();
        }
        return $r;
    }
}
