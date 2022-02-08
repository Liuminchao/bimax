<?php

/**
 * This is the model class for table "ptw_condition_list".
 *
 * The followings are the available columns in table 'ptw_condition_list':
 * @property string $condition_id
 * @property string $condition_name
 * @property string $condition_name_en
 * @property string $status
 * @property string $record_time
 *
 * The followings are the available model relations:
 * @property PtwTypeList[] $ptwTypeLists
 * @author LiuXiaoyuan
 */
class QaForm extends CActiveRecord
{

    const STATUS_NORMAL = '0'; //正常
    const STATUS_STOP = '1'; //停用

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'qa_form';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('condition_id, condition_name, condition_name_en, record_time', 'required'),
            array('condition_id', 'length', 'max'=>64),
            array('condition_name, condition_name_en', 'length', 'max'=>4000),
            array('status', 'length', 'max'=>2),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('condition_id, condition_name, condition_name_en, status, record_time', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'ptwTypeLists' => array(self::MANY_MANY, 'PtwTypeList', 'ptw_type_condition(condition_id, type_id)'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'condition_id' => 'Condition',
            'condition_name' => 'Condition Name',
            'condition_name_en' => 'Condition Name En',
            'status' => 'Status',
            'record_time' => 'Record Time',
        );
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

        //Status
        if ($args['status'] != '') {
            $condition.= ( $condition == '') ? ' status=:status' : ' AND status=:status';
            $params['status'] = $args['status'];
        }

        $total_num = QaForm::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'order_id ASC';
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
        $rows = QaForm::model()->findAll($criteria);

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
    public static function InsertForm($qa){

        $exist_data = QaForm::model()->count('item_id=:item_id and form_id=:form_id ', array('item_id' => $qa['item_id'],'form_id'=>$qa['form_id']));
//        if ($exist_data != 0) {
//            $r['msg'] = 'Already Exist';
//            $r['status'] = -1;
//            $r['refresh'] = false;
//            return $r;
//        }
        $trans = Yii::app()->db->beginTransaction();
//        $is_required = '0';
        $status = '0';
        $record_time = date('Y-m-d H:i:s');
        try {
//            if ($exist_data != 0) {
//                $sub_sql = 'UPDATE qa_form SET order_id = :order_id,item_title = :item_title,item_title_en = :item_title_en,group_name = :group_name,status = :status,item_type = :item_type,item_data = :item_data,is_required = :is_required,record_time = :record_time WHERE  item_id = :item_id  and form_id = :form_id';
//                $command = Yii::app()->db->createCommand($sub_sql);
//                $command->bindParam(":item_id", $qa['item_id'], PDO::PARAM_STR);
//                $command->bindParam(":form_id", $qa['form_id'], PDO::PARAM_STR);
//                $command->bindParam(":order_id", $qa['order_id'], PDO::PARAM_STR);
//                $command->bindParam(":item_title", $qa['item_title'], PDO::PARAM_STR);
//                $command->bindParam(":item_title_en", $qa['item_title_en'], PDO::PARAM_STR);
//                $command->bindParam(":group_name", $qa['group_name'], PDO::PARAM_STR);
//                $command->bindParam(":status", $status, PDO::PARAM_STR);
//                $command->bindParam(":item_type", $qa['item_type'], PDO::PARAM_STR);
//                $command->bindParam(":item_data", $qa['item_data'], PDO::PARAM_STR);
//                $command->bindParam(":is_required", $is_required, PDO::PARAM_STR);
//                $command->bindParam(":record_time", $record_time, PDO::PARAM_STR);
//                $rs = $command->execute();
//                $r['msg'] = Yii::t('common','success_update');
//                $r['status'] = 1;
//                $r['refresh'] = true;
//            }else{
//
//            }
            $sub_sql = 'INSERT INTO qa_form(item_id,form_id,order_id,item_title,item_title_en,group_name,status,item_type,item_data,is_required,record_time) VALUES(:item_id,:form_id,:order_id,:item_title,:item_title_en,:group_name,:status,:item_type,:item_data,:is_required,:record_time)';
            $command = Yii::app()->db->createCommand($sub_sql);
            $command->bindParam(":item_id", $qa['item_id'], PDO::PARAM_STR);
            $command->bindParam(":form_id", $qa['form_id'], PDO::PARAM_STR);
            $command->bindParam(":order_id", $qa['order_id'], PDO::PARAM_STR);
            $command->bindParam(":item_title", $qa['item_title'], PDO::PARAM_STR);
            $command->bindParam(":item_title_en", $qa['item_title_en'], PDO::PARAM_STR);
            $command->bindParam(":group_name", $qa['group_name'], PDO::PARAM_STR);
            $command->bindParam(":status", $status, PDO::PARAM_STR);
            $command->bindParam(":item_type", $qa['item_type'], PDO::PARAM_STR);
            $command->bindParam(":item_data", $qa['item_data'], PDO::PARAM_STR);
            $command->bindParam(":is_required", $qa['is_required'], PDO::PARAM_STR);
            $command->bindParam(":record_time", $record_time, PDO::PARAM_STR);
            $rs = $command->execute();
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

    //查询详情
    public static function groupDetail($form_id,$group_name){

        $sql = "SELECT item_id FROM qa_form WHERE  form_id = '".$form_id."' and  group_name = '".$group_name."'";//var_dump($sql);
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();

        return $rows;

    }
}
