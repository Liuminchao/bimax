<?php

/**
 * 项目分解附件管理
 * @author LiuMinchao
 */
class TaskAttach extends CActiveRecord {

    const STATUS_NORMAL = '00'; //正常
    const STATUS_STOP = '01'; //结项

    public $subcomp_name; //指派分包公司名
    public $father_model;   //上级节点类
    public $subcomp_sn; //指派分包注册编号
    public $TYPE;   //项目类型
    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_task_attach';
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'attach_content' => Yii::t('task', 'attach_content'),
            'task_attach' => Yii::t('task','attach_ment'),
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
//        var_dump($args['program_id']);
//        exit;
        //Task_id
        if ($args['task_id'] != '') {
            $condition.= ( $condition == '') ? ' task_id LIKE :task_id' : ' AND task_id LIKE :task_id';
            $params['task_id'] = '%' .$args['task_id'] . '%';
        }
        
        if ($args['program_id'] != '') {
            $condition.= ( $condition == '') ? ' program_id=:program_id' : ' AND program_id=:program_id';
            $params['program_id'] = $args['program_id'];
        }
        
        //Contractor
//        if ($args['contractor_id'] != '') {
//            $condition.= ( $condition == '') ? ' contractor_id=:contractor_id' : ' AND contractor_id=:contractor_id';
//            $params['contractor_id'] = $args['contractor_id'];
//        }

        $total_num = TaskAttach::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'task_id asc';
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
        $rows = TaskAttach::model()->findAll($criteria);

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
        $model = new TaskAttach('create');
        try{
            $model->program_id = $args['program_id'];
            $model->task_id = $args['task_id'];
            $model->task_attach = $args['task_attach'];
            $model->attach_content = $args['attach_content'];
            $model->contractor_id = $args['contractor_id'];
            $model->status = self::STATUS_NORMAL;
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
    public static function deleteAttach($args){
        $sql = "DELETE FROM bac_task_attach WHERE task_id =:task_id and task_attach =:task_attach";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":task_id", $args['task_id'], PDO::PARAM_STR);
        $command->bindParam(":task_attach", $args['str'], PDO::PARAM_STR);
                    
        $rs = $command->execute();
        
        if($rs ==2 || $rs == 1){
            $r['msg'] = '删除成功';
            $r['status'] = 1;
            $r['refresh'] = true;
        }else{
            $r['msg'] = '删除失败';
            $r['status'] = -1;
            $r['refresh'] = false;
        }
        return $r;
    }
}
