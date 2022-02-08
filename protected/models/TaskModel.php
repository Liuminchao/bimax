<?php

/**
 * 任务列表
 * @author LiuMinchao
 */
class TaskModel extends CActiveRecord {

    const STATUS_DRAFT = '-1'; //草稿
    const STATUS_ONGOING = '0'; //进行中
    const STATUS_COMPLETED = '1'; //已完成
    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'task_model_list';
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(

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

    //状态
    public static function statusText($key = null) {
        $rs = array(
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_ONGOING => 'Ongoing',
            self::STATUS_COMPLETED => 'Completed',
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::STATUS_DRAFT => 'bg-primary', //草稿
            self::STATUS_ONGOING => 'bg-info', //进行中
            self::STATUS_COMPLETED => 'bg-success', //已完成
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

        //Program
        if ($args['program_id'] != '') {
            $condition.= ( $condition == '') ? ' program_id=:program_id' : ' AND program_id=:program_id';
            $params['program_id'] = $args['program_id'];
        }
        //Record Time
        if ($args['record_time'] != '') {
            $condition.= ( $condition == '') ? ' record_time=:record_time' : ' AND record_time=:record_time';
            $params['record_time'] = $args['record_time'];
        }

        $args['status'] = '0';
        if ($args['status'] != '') {
            $condition.= ( $condition == '') ? ' status=:status' : ' AND status=:status';
            $params['status'] = $args['status'];
        }

        $total_num = TaskModel::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'template_id';
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
        $rows = TaskList::model()->findAll($criteria);
        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }

    /**
     * 添加记录
     */
    public  static function insertBasic($task) {

        $model = new TaskModel('create');
        $trans = $model->dbConnection->beginTransaction();
        $operator_id = Yii::app()->user->id;
        $user = Staff::userByPhone($operator_id);
        if($user){
            $operator_id = $user[0]['user_id'];
        }
        try {
            $model->task_name = $task['task_name'];
            $model->program_id = $task['program_id'];
//            $model->path ='/opt/www-nginx/web/filebase/tmp/Model_component_template-5efc069363f9e300118f65ac.xlsx';
            $model->operator_id = $operator_id;
            $model->status = self::STATUS_ONGOING;
            $model->record_time = date('Y-m-d H:i:s', time());
            $result = $model->save();
            if ($result) {
                $trans->commit();
                $r['id'] = $model->id;
                $r['msg'] = Yii::t('common', 'success_insert');
                $r['status'] = 1;
                $r['refresh'] = true;
            }
            else {
                $trans->rollBack();
                $r['msg'] = Yii::t('common', 'error_insert');
                $r['status'] = -1;
                $r['refresh'] = false;
            }
        }catch(Exception $e){
            $trans->rollBack();
            $r['status'] = -1;
            $r['msg'] = $e->getmessage();
            $r['refresh'] = false;
        }
        return $r;
    }

    //搜索前十条记录
    public static function detailList($program_id){
        $operator_id = Yii::app()->user->id;
        $user = Staff::userByPhone($operator_id);
        if($user){
            $operator_id = $user[0]['user_id'];
        }
        $sql = "SELECT * FROM task_model_list WHERE  program_id = '".$program_id."' and operator_id = '".$operator_id."' order by record_time desc limit 10";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        return $rows;
    }
}
