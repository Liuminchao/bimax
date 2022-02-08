<?php

/**
 * 质量表单类型
 * @author LiuMinchao
 */
class ProgressPlanVersion extends CActiveRecord {

    const STATUS_NORMAL = '0'; //正常
    const STATUS_STOP = '1'; //停用

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'progress_plan_version';
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
        if ($args['version'] != '') {
            $condition.= ( $condition == '') ? ' version=:version' : ' AND version=:version';
            $params['version'] = $args['version'];
        }
        //Contractor Type
        if ($args['project_id'] != '') {
            $condition.= ( $condition == '') ? ' project_id=:project_id' : ' AND project_id=:project_id';
            $params['project_id'] = $args['project_id'];
        }
        //Form Name En
        if ($args['version_name'] != '') {
            $condition.= ( $condition == '') ? ' version_name LIKE :version_name' : ' AND version_name LIKE :version_name';
            $params['version_name'] = '%' . $args['version_name'] . '%';
        }

        $args['status'] = '0';
        //Status
        if ($args['status'] != '') {
            $condition.= ( $condition == '') ? ' status=:status' : ' AND status=:status';
            $params['status'] = $args['status'];
        }

        $total_num = ProgressPlanVersion::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'version_id ASC';
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
        $rows = ProgressPlanVersion::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }

    //QA按类型选择表单
    public static function formByType($type_id) {
        $sql = " SELECT
                    a.form_id, a.form_name, a.form_name_en
                FROM
                    qa_checklist a
                WHERE
                     a.type_id ='".$type_id."' and a.status='00'
                order by a.type_id";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if(count($rows) > 0){
            foreach ($rows as $key => $row) {
                if (Yii::app()->language == 'zh_CN') {
                    $rs[$row['form_id']] = $row['form_name'];
                }else if (Yii::app()->language == 'en_US') {
                    $rs[$row['form_id']] = $row['form_name_en'];
                }
            }
        }

        return $rs;
    }

    //QA按类型选择表单
    public static function formList() {
        $sql = " SELECT
                    a.form_id, a.form_name, a.form_name_en
                FROM
                    qa_checklist a
                WHERE
                     a.status='00'
                order by a.type_id";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if(count($rows) > 0){
            foreach ($rows as $key => $row) {
                if (Yii::app()->language == 'zh_CN') {
                    $rs[$row['form_id']] = $row['form_name'];
                }else if (Yii::app()->language == 'en_US') {
                    $rs[$row['form_id']] = $row['form_name_en'];
                }
            }
        }

        return $rs;
    }

    //添加表单
    public static function saveForm($args){
        $sql = " SELECT
                    max(version) as version
                FROM
                    progress_plan_version
                WHERE
                     status='0' and project_id = '".$args['project_id']."' ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if(count($rows) > 0){
            $version = $rows[0]['version']+1;
        }else{
            $version = 0;
        }
        $model = new ProgressPlanVersion('create');
        $trans = Yii::app()->db->beginTransaction();
        try{
            $record_time = date("Y-m-d H:i:s");
            $operator_id = Yii::app()->user->id;
            $status = self::STATUS_NORMAL;
            $model->version_name = $args['version_name'];
            $model->project_id = $args['project_id'];
            $model->version = $version;
            $model->user_id = $operator_id;
            $model->status = $status;
            $model->record_time = $record_time;
            $result = $model->save();
            $version_id = $model->version_id;
            if ($result) {
                $r['msg'] = '添加成功！';
                $r['status'] = 1;
                $r['refresh'] = true;
                $r['version_id'] = $version_id;

//                $sql = "update progress_plan_version set status = '1' where project_id = '".$args['project_id']."' and version ='".$rows[0]['version']."' ";
//                $command = Yii::app()->db->createCommand($sql);
//                $re = $command->execute();

                $trans->commit();
            } else {
                $r['msg'] = '添加失败！';
                $r['status'] = -1;
                $r['refresh'] = false;
                $trans->rollback();
            }
        }
        catch(Exception $e){
            $r['status'] = -1;
            $r['msg'] = $e->getmessage();
            $r['refresh'] = false;
        }


        return $r;
    }

    //停用表单
    public static function stopForm($id) {

        if ($id == '') {
            $r['msg'] = '请选择表单';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $model = QaChecklist::model()->findByPk($id);

        if ($model === null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        try {

            $model->status = QaChecklist::STATUS_STOP;
            $result = $model->save();

            if ($result) {
                $r['msg'] = Yii::t('common', 'success_stop');
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = Yii::t('common', 'error_stop');
                $r['status'] = -1;
                $r['refresh'] = false;
            }
        } catch (Exception $e) {
            $r['status'] = -1;
            $r['msg'] = $e->getmessage();
            $r['refresh'] = false;
        }
        return $r;
    }

}
