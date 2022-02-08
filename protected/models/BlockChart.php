<?php

/**
 * 角色管理
 * @author LiuXiaoyuan
 */
class BlockChart extends CActiveRecord {

    //承包商类型
    const CONTRACTOR_TYPE_MC = 'MC'; //总包
    const CONTRACTOR_TYPE_SC = 'SC'; //分包
    const STATUS_NORMAL = '00'; //正常
    const STATUS_STOP = '01'; //停用

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'task_model_statistics';
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

        //project_id
        if ($args['project_id'] != '') {
            $condition.= ( $condition == '') ? ' project_id=:project_id' : ' AND project_id=:project_id';
            $params['project_id'] = $args['project_id'];
        }

        //block
        if ($args['block'] != '') {
            $condition.= ( $condition == '') ? ' block=:block' : ' AND block=:block';
            $params['block'] = $args['block'];
        }


        $total_num = BlockChart::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'id ASC';
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
        $rows = BlockChart::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }

    //插入数据
    public static function insertBlockChart($args) {

        foreach ($args as $key => $value) {
            $args[$key] = trim($value);
        }

        if ($args['project_id'] == '') {
            $r['msg'] = 'Please Select Project';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $exist_data = BlockChart::model()->count('s_val=:s_val and t_val=:t_val and block=:block', array('s_val' => $args['s_val'],'t_val' => $args['t_val'],'block'=> $args['block']));
        if ($exist_data != 0) {
            $r['msg'] = 'The info already exists.';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        try {
            $model = new BlockChart('create');
            $model->project_id = $args['project_id'];
            $model->block = $args['block'];
            $model->type = 'pbu_type';
            $model->s_val = $args['s_val'];
            $model->t_val = $args['t_val'];
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

    //修改数据
    public static function updateBlockChart($args) {

        foreach ($args as $key => $value) {
            $args[$key] = trim($value);
        }

        if ($args['project_id'] == '') {
            $r['msg'] = 'Please Select Project';
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $model = BlockChart::model()->findByPk($args['id']);

        if ($model === null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        try {

            $model->project_id = $args['project_id'];
            $model->block = $args['block'];
            $model->type = $args['type'];
            $model->s_val = $args['s_val'];
            $model->t_val = $args['t_val'];
            $model->record_time = date('Y-m-d H:i:s');
            $result = $model->save();

            if ($result) {
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

    //删除数据
    public static function deleteInfo($id) {

        if ($id == '') {
            $r['msg'] = Yii::t('common', 'error_role_id_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }

        $model = BlockChart::model()->findByPk($id);

        if ($model === null) {
            $r['msg'] = Yii::t('common', 'error_record_is_null');
            $r['status'] = -1;
            $r['refresh'] = false;
            return $r;
        }


        $sql = 'DELETE FROM task_model_statistics WHERE id=:id';
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":id", $id, PDO::PARAM_INT);

        $rs = $command->execute();

        if ($rs == 0) {
            $r['msg'] = Yii::t('common', 'error_record_is_not_exist');
            $r['status'] = -1;
            $r['refresh'] = false;
        } else {
            $r['msg'] = Yii::t('common', 'error_delete');
            $r['status'] = 1;
            $r['refresh'] = true;
        }
        return $r;
    }

    //根据block按pbu_type分组
    public static function pbutypeList($args) {
        //$condition = '';
        $condition = "  project_id =  '".$args['project_id']."'";

        $condition.= " and block =  '".$args['block']."'";

        $sql = "SELECT pbu_type
                 from  pbu_info
                 where ".$condition."
                 group by pbu_type ";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['pbu_type']] = $row['pbu_type'];
            }
        }else{
            $rs = array();
        }
        return $rs;

    }

    //根据项目按block分组
    public static function blockList($args) {
        //$condition = '';
        $condition = "  project_id =  '".$args['project_id']."' and status = '0' ";

        $sql = "SELECT block
                 from  pbu_info
                 where ".$condition."
                 group by block ";
//        var_dump($sql);
//        exit;
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        if (count($rows) > 0) {
            foreach ($rows as $key => $row) {
                $rs[$row['block']] = $row['block'];
            }
        }else{
            $rs = array();
        }
        return $rs;

    }

    public function typeList() {
        return array(
            'pbu_type' => 'pbu_type',
        );
    }
}
