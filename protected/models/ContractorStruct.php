<?php

/**
 * 总包组织结构
 * @author LiuXiaoyuan
 */
class ContractorStruct extends CActiveRecord {

    const STATUS_NORMAL = '00'; //正常
    const STATUS_STOP = '01'; //停用

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'bac_contractor_struct';
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'contractor_id' => Yii::t('comp_mcstruct', 'contractor_id'),
            'team_id' => Yii::t('comp_mcstruct', 'team_id'),
            'team_name' => Yii::t('comp_mcstruct', 'team_name'),
            'link_people' => Yii::t('comp_mcstruct', 'link_people'),
            'link_phone' => Yii::t('comp_mcstruct', 'link_phone'),
            'status' => Yii::t('comp_mcstruct', 'status'),
            'record_time' => Yii::t('comp_mcstruct', 'record_time'),
        );
    }

    //状态
    public static function statusText($key = null) {
        $rs = array(
            self::STATUS_NORMAL => Yii::t('comp_mcstruct', 'STATUS_NORMAL'),
            self::STATUS_STOP => Yii::t('comp_mcstruct', 'STATUS_STOP'),
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::STATUS_NORMAL => 'label-success', //正常
            self::STATUS_STOP => 'label-danger', //停用
        );
        return $key === null ? $rs : $rs[$key];
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ContractorStruct the static model class
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

        //Contractor
        if ($args['contractor_id'] != '') {
            $condition.= ( $condition == '') ? ' contractor_id=:contractor_id' : ' AND contractor_id=:contractor_id';
            $params['contractor_id'] = $args['contractor_id'];
        }
        //Team
        if ($args['team_id'] != '') {
            $condition.= ( $condition == '') ? ' team_id=:team_id' : ' AND team_id=:team_id';
            $params['team_id'] = $args['team_id'];
        }
        //Team Name
        if ($args['team_name'] != '') {
            $condition.= ( $condition == '') ? ' team_name LIKE :team_name' : ' AND team_name LIKE :team_name';
            $params['team_name'] = '%'.$args['team_name'].'%';
        }
        //Link People
        if ($args['link_people'] != '') {
            $condition.= ( $condition == '') ? ' link_people LIKE :link_people' : ' AND link_people LIKE :link_people';
            $params['link_people'] = '%'.$args['link_people'].'%';
        }
        //Link Phone
        if ($args['link_phone'] != '') {
            $condition.= ( $condition == '') ? ' link_phone=:link_phone' : ' AND link_phone=:link_phone';
            $params['link_phone'] = $args['link_phone'];
        }
        //Status
        if ($args['status'] != '') {
            $condition.= ( $condition == '') ? ' status=:status' : ' AND status=:status';
            $params['status'] = $args['status'];
        }
        //Record Time
        if ($args['record_time'] != '') {
            $condition.= ( $condition == '') ? ' record_time=:record_time' : ' AND record_time=:record_time';
            $params['record_time'] = $args['record_time'];
        }


        $total_num = ContractorStruct::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'contractor_id';
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
        $rows = ContractorStruct::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }

    //插入日志
    public static function insertLog($model) {

        return array(
            $model->getAttributeLabel('team_id') => Yii::app()->db->lastInsertID,
            $model->getAttributeLabel('team_name') => $model->team_name,
            $model->getAttributeLabel('link_people') => $model->link_people,
            $model->getAttributeLabel('link_phone') => $model->link_phone,
            $model->getAttributeLabel('status') => self::statusText($model->status),
            $model->getAttributeLabel('record_time') => $model->record_time,
        );
    }
    
     //修改日志
    public static function updateLog($model) {

        return array(
            $model->getAttributeLabel('team_id') => $model->team_id,
            $model->getAttributeLabel('team_name') => $model->team_name,
            $model->getAttributeLabel('link_people') => $model->link_people,
            $model->getAttributeLabel('link_phone') => $model->link_phone,
            $model->getAttributeLabel('status') => self::statusText($model->status),
        );
    }
    

    //插入数据
    public static function insertContractorStruct($args) {

        foreach ($args as $key => $value) {
            $args[$key] = trim($value);
        }
        if($args['contractor_id'] == ''){
            $args['contractor_id'] == Yii::app()->user->getState('contractor_id');
        }
        
        if ($args['contractor_id'] == '') {
            $r['msg'] = Yii::t('comp_mcstruct', 'error_contractor_id_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $exist_data = Contractor::model()->count('contractor_id=:contractor_id', array('contractor_id' => $args['contractor_id']));
        if ($exist_data == 0) {
            $r['msg'] = Yii::t('comp_mcstruct', 'error_contractor_id_is_not_exist');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        if ($args['team_name'] == '') {
            $r['msg'] = Yii::t('comp_mcstruct', 'error_team_name_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $exist_data = ContractorStruct::model()->count('contractor_id=:contractor_id AND team_name=:team_name', array('contractor_id' => $args['contractor_id'], 'team_name' => $args['team_name']));
        if ($exist_data == 0) {
            $r['msg'] = Yii::t('comp_mcstruct', 'error_team_name_is_not_exist');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }


        try {
            $model = new ContractorStruct('create');
            $model->contractor_id = $args['contractor_id'];
            $model->team_name = $args['team_name'];
            $model->link_people = $args['link_people'];
            $model->link_phone = $args['link_phone'];
            $model->status = self::STATUS_STOP;
            $model->record_time = date('Y-m-d');
            $result = $model->save();

            if ($result) {
                //记录日志
                OperatorLog::savelog(OperatorLog::MODULE_ID_MAINCOMP, Yii::t('comp_mcstruct', 'Add Mcstruct'), self::insertLog($model));

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

    //修改数据
    public static function updateContractorStruct($args) {

        foreach ($args as $key => $value) {
            $args[$key] = trim($value);
        }

        if ($args['team_id'] == '') {
            $r['msg'] = Yii::t('comp_mcstruct', 'error_contractor_id_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $model = ContractorStruct::model()->findByPk($args['team_id']);

        if ($model === null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        try {

            $model->team_name = $args['team_name'];
            $model->link_people = $args['link_people'];
            $model->link_phone = $args['link_phone'];
            $result = $model->save();

            if ($result) {
                //记录日志
                OperatorLog::savelog(OperatorLog::MODULE_ID_MAINCOMP, Yii::t('comp_mcstruct', 'Edit Mcstruct'), self::updateLog($model));

                $r['msg'] = Yii::t('common', 'success_update');
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = Yii::t('common', 'error_update');
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

    //启用
    public static function startContractorStruct($id) {


        if ($id == '') {
            $r['msg'] = Yii::t('comp_mcstruct', 'error_team_id_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $model = ContractorStruct::model()->findByPk($id);

        if ($model === null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }


        try {

            $model->status = self::STATUS_NORMAL;
            $result = $model->save();

            if ($result) {
                //记录日志
                OperatorLog::savelog(OperatorLog::MODULE_ID_MAINCOMP, Yii::t('comp_mcstruct', 'Start Mcstruct'), self::updateLog($model));

                $r['msg'] = Yii::t('common', 'success_start');
                $r['status'] = 1;
                $r['refresh'] = true;
            } else {
                $r['msg'] = Yii::t('common', 'error_start');
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

    //停用
    public static function stopContractorStruct($id) {


        if ($id == '') {
            $r['msg'] = Yii::t('comp_mcstruct', 'error_team_id_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $model = ContractorStruct::model()->findByPk($id);

        if ($model === null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }


        try {

            $model->status = self::STATUS_STOP;
            $result = $model->save();

            if ($result) {
                //记录日志
                OperatorLog::savelog(OperatorLog::MODULE_ID_MAINCOMP, Yii::t('comp_mcstruct', 'Stop Mcstruct'), self::updateLog($model));

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
