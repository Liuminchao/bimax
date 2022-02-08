<?php

/**
 * 质量表单类型
 * @author LiuMinchao
 */
class QaChecklist extends CActiveRecord {

    const STATUS_NORMAL = '00'; //正常
    const STATUS_STOP = '01'; //停用

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'qa_checklist';
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
            self::STATUS_NORMAL => 'bg-success', //正常
            self::STATUS_STOP => ' bg-danger', //停用
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
        if ($args['form_id'] != '') {
            $condition.= ( $condition == '') ? ' form_id=:form_id' : ' AND form_id=:form_id';
            $params['form_id'] = $args['form_id'];
        }
        //Contractor Type
        if ($args['project_id'] != '') {
            $condition.= ( $condition == '') ? ' project_id=:project_id' : ' AND project_id=:project_id';
            $params['project_id'] = $args['project_id'];
        }
        //Form Name En
        if ($args['form_name'] != '') {
            $condition.= ( $condition == '') ? ' form_name_en LIKE :form_name_en' : ' AND form_name_en LIKE :form_name_en';
            $params['form_name_en'] = '%' . $args['form_name'] . '%';
        }
        //Teamid
        if ($args['type_id'] != '') {
            $condition.= ( $condition == '') ? ' type_id=:type_id' : ' AND type_id=:type_id';
            $params['type_id'] = $args['type_id'];
        }
        $args['status'] = '00';
        //Status
        if ($args['status'] != '') {
            $condition.= ( $condition == '') ? ' status=:status' : ' AND status=:status';
            $params['status'] = $args['status'];
        }
        $total_num = QaChecklist::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'type_id ASC';
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
        $rows = QaChecklist::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }

    //QA按类型选择表单
    public static function formByType($type_id,$program_id) {
        $sql = " SELECT
                    a.form_id, a.form_name, a.form_name_en
                FROM
                    qa_checklist a
                WHERE
                     a.type_id ='".$type_id."' and a.status='00' and a.project_id = '".$program_id."'
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

    //添加QA表单
    public static function saveForm($args){
        $model = new QaChecklist('create');
        $exist_data = QaChecklist::model()->count('form_id=:form_id ', array('form_id'=>$args['form_id']));
        try{
            if ($exist_data != 0) {
                $sub_sql = 'update qa_checklist  set form_type = :form_type,form_name = :form_name,form_name_en = :form_name_en,type_id = :type_id,status=:status  where form_id = :form_id ';
                $command = Yii::app()->db->createCommand($sub_sql);
                $command->bindParam(":form_id", $args['form_id'], PDO::PARAM_STR);
                $command->bindParam(":form_type", $args['form_type'], PDO::PARAM_STR);
                $command->bindParam(":form_name", $args['form_name'], PDO::PARAM_STR);
                $command->bindParam(":form_name_en", $args['form_name_en'], PDO::PARAM_STR);
                $command->bindParam(":type_id", $args['type_id'], PDO::PARAM_STR);
                $command->bindParam(":status", $args['status'], PDO::PARAM_STR);
                $rs = $command->execute();

                $del_sql = 'delete from qa_form where form_id = :form_id';
                $del_command = Yii::app()->db->createCommand($del_sql);
                $del_command->bindParam(":form_id", $args['form_id'], PDO::PARAM_STR);
                $s = $del_command->execute();

            }else{
                $sub_sql = 'INSERT INTO qa_checklist(form_id,form_type,form_name,form_name_en,type_id,status,project_id) VALUES(:form_id,:form_type,:form_name,:form_name_en,:type_id,:status,:project_id)';
                $command = Yii::app()->db->createCommand($sub_sql);
                $command->bindParam(":form_id", $args['form_id'], PDO::PARAM_STR);
                $command->bindParam(":form_type", $args['form_type'], PDO::PARAM_STR);
                $command->bindParam(":form_name", $args['form_name'], PDO::PARAM_STR);
                $command->bindParam(":form_name_en", $args['form_name_en'], PDO::PARAM_STR);
                $command->bindParam(":type_id", $args['type_id'], PDO::PARAM_STR);
                $command->bindParam(":status", $args['status'], PDO::PARAM_STR);
                $command->bindParam(":project_id", $args['program_id'], PDO::PARAM_STR);
                $rs = $command->execute();
            }
//            if ($exist_data != 0) {
//                $r['msg'] = 'Form Id Already Exist';
//                $r['status'] = -2;
//                $r['refresh'] = false;
//                return $r;
//            }
            if ($rs) {
                $r['msg'] = Yii::t('common', 'success_insert');
                $r['status'] = 1;
                $r['refresh'] = true;
            }
            else {
                $r['msg'] = Yii::t('common', 'error_insert');
                $r['status'] = -1;
                $r['refresh'] = false;
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

    public static function formType($key = null) {
        $rs = array(
            '01' => 'Inspection',
            '02' => 'Inspection',
            '03' => 'Inspection',
            '04' => 'Inspection',
            'A' => 'Site',
            'B' => 'Fitout',
            'C' => 'Carcass',
        );
        return $key === null ? $rs : $rs[$key];
    }

}
