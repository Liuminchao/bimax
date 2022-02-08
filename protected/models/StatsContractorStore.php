<?php

/**
 * 总包公司管理
 * @author yaohaiyan
 */
class StatsContractorStore extends CActiveRecord {

    const STATUS_NORMAL = 0; //正常
    const STATUS_DISABLE = 1; //注销

    /**
     * @return string the associated database table name
     */

    public function tableName() {
        return 'stats_contractor_store';
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'contractor_name' => Yii::t('comp_contractor', 'Contractor_name'),
            'attribute_size' => Yii::t('comp_statistics','attribute_size'),
            'flow_pic_size' => Yii::t('comp_statistics', 'flow_pic_size'),
            'documnet_size' => Yii::t('comp_statistics', 'documnet_size'),
            'total_size' => Yii::t('comp_statistics', 'total_size'),
            'max_size' => Yii::t('comp_statistics', 'max_size'),
            'record_time' => Yii::t('common', 'record_time'),
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Operator the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    //状态
    public static function statusText($key = null) {
        $rs = array(
            self::STATUS_NORMAL => Yii::t('comp_contractor', 'STATUS_NORMAL'),
            self::STATUS_DISABLE => Yii::t('comp_contractor', 'STATUS_DISABLE'),
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态CSS
    public static function statusCss($key = null) {
        $rs = array(
            self::STATUS_NORMAL => 'label-success', //正常
            self::STATUS_DISABLE => ' label-danger', //已注销
        );
        return $key === null ? $rs : $rs[$key];
    }

    /**
     * 管理员查询
     * @param int $page
     * @param int $pageSize
     * @param array $args
     * @return array
     */
    public static function queryAllList($page, $pageSize, $args = array()) {

        $condition = '';
        $params = array();

        $total_num = StatsContractorStore::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'contractor_id asc';
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
        //var_dump($criteria);
        $rows = StatsContractorStore::model()->findAll($criteria);
        //var_dump($rows);
        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
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
        //contractor_name
        if($args['contractor_name'] != ''){
            $sql = "select contractor_id from bac_contractor where contractor_name like '%".$args['contractor_name']."%' ";
//            var_dump($sql);
//            exit;
            $command = Yii::app()->db->createCommand($sql);
            $rows = $command->queryAll();
            $i = '';
            if (count($rows) > 0) {
                foreach ($rows as $key => $row) {
                    $i.=$row['contractor_id'].',';
                }
            }
            if ($i != '')
                $contractor_id = substr($i, 0, strlen($i) - 1);
            $condition.= ( $condition == '') ? ' t.contractor_id IN ('.$contractor_id.') ' : ' AND t.contractor_id IN ('.$contractor_id.')';

        }

        $total_num = StatsContractorStore::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'total_size desc';
        } else {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $order = substr($_REQUEST['q_order'], 0, -1) . ' DESC';
            else
                $order = $_REQUEST['q_order'] . ' ASC';
        }

        $criteria->condition = $condition;
        $criteria->params = $params;
        $criteria->order = $order;
        $pages = new CPagination($total_num);
        $pages->pageSize = $pageSize;
        $pages->setCurrentPage($page);
        $pages->applyLimit($criteria);

        $rows = StatsContractorStore::model()->findAll($criteria);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;

        return $rs;
    }
    //查询公司下文件使用大小
    public static function summaryByContractor(){
        $contractor_id = Yii::app()->user->getState('contractor_id');
        $sql = "select * from stats_contractor_store where contractor_id = :contractor_id ";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindParam(":contractor_id", $contractor_id, PDO::PARAM_INT);
        $rows = $command->queryAll();
//        var_dump($rows);
//        exit;
        $i = 0;
        if(!empty($rows)){
            $r = $rows[0];
            $r['no_use_size'] = $r['max_size'] - $r['total_size'];
            foreach($r as $key => $data){
                switch ($key)
                {
                    case "flow_pic_size":
                        $rdata[$i]['label'] = Yii::t('common','Flow_Pic');
                        $rdata[$i]['data'] = $data;
                        break;
                    case "attribute_size":
                        $rdata[$i]['label'] = Yii::t('common','Attribute_Size');
                        $rdata[$i]['data'] = $data;
                        break;
                    case "document_size":
                        $rdata[$i]['label'] = Yii::t('common','Document_Size');
                        $rdata[$i]['data'] = $data;
                        break;
                    case "no_use_size":
                        $rdata[$i]['label'] = Yii::t('common','Remaining_Size');
                        $rdata[$i]['data'] = $data;
                        break;
                    default:
                        break;
                }
                $i++;
            }
        }
        return $rdata;
//        var_dump($r);
//        exit;
    }

    public static function saveMaxSzie($contractor_id,$max_size){
        $normal = 0;
        $max_size = 1024*$max_size;
        $statistics_date = date('Y-m-d H:i:s',time());//获取精确时间
        $insert_sql = 'INSERT INTO stats_contractor_store (contractor_id,flow_pic_size,attribute_size,document_size,total_size,max_size,statistics_date,record_time) VALUES (:contractor_id,:flow_pic_size,:attribute_size,:document_size,:total_size,:max_size,:statistics_date,:record_time)';
        $insert_command = Yii::app()->db->createCommand($insert_sql);
        $insert_command->bindParam(":contractor_id",$contractor_id, PDO::PARAM_STR);
        $insert_command->bindParam(":flow_pic_size",$normal, PDO::PARAM_STR);
        $insert_command->bindParam(":attribute_size", $normal, PDO::PARAM_STR);
        $insert_command->bindParam(":document_size",$normal, PDO::PARAM_STR);
        $insert_command->bindParam(":total_size",$normal, PDO::PARAM_STR);
        $insert_command->bindParam(":max_size",$max_size, PDO::PARAM_INT);
        $insert_command->bindParam(":statistics_date",$statistics_date, PDO::PARAM_STR);
        $insert_command->bindParam(":record_time",$statistics_date, PDO::PARAM_STR);
        $rs = $insert_command->execute();
    }
}
