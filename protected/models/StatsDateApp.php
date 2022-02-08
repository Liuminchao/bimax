<?php

/**
 * 日统计表
 * @author yaohaiyan
 */
class StatsDateApp extends CActiveRecord
{

    const STATUS_NORMAL = 0; //正常
    const STATUS_DISABLE = 1; //注销

    /**
     * @return string the associated database table name
     */

    public function tableName()
    {
        return 'stats_date_app';
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'contractor_name' => Yii::t('comp_contractor', 'Contractor_name'),
            'attribute_size' => Yii::t('comp_statistics', 'attribute_size'),
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
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    //状态
    public static function statusText($key = null)
    {
        $rs = array(
            self::STATUS_NORMAL => Yii::t('comp_contractor', 'STATUS_NORMAL'),
            self::STATUS_DISABLE => Yii::t('comp_contractor', 'STATUS_DISABLE'),
        );
        return $key === null ? $rs : $rs[$key];
    }

    //状态CSS
    public static function statusCss($key = null)
    {
        $rs = array(
            self::STATUS_NORMAL => 'label-success', //正常
            self::STATUS_DISABLE => ' label-danger', //已注销
        );
        return $key === null ? $rs : $rs[$key];
    }
    //总包项目业务统计
    public static function  BusinessStatistics($page, $pageSize, $args){
        $args['date'] = Utils::DateToCn($args['date']);
        $sql = "SELECT
                  b.contractor_id as con_id,b.program_name,sum(a.ptw_cnt) as ptw_cnt,sum(a.ptw_pcnt) as ptw_pcnt,sum(a.tbm_cnt) as tbm_cnt,sum(a.tbm_pcnt) as tbm_pcnt,sum(a.che_cnt) as che_cnt,sum(a.ins_cnt) as ins_cnt,
                  sum(a.mee_cnt) as mee_cnt,sum(a.mee_pcnt) as mee_pcnt,sum(a.tra_cnt) as tra_cnt,sum(a.tra_pcnt) as tra_pcnt,sum(a.ra_cnt) as ra_cnt,sum(a.inc_cnt) as inc_cnt
                FROM
                  stats_date_app a,bac_program b
                where a.date = '".$args['date']."' and a.root_proid = b.program_id
                group by a.root_proid ";
        $command = Yii::app()->db->createCommand($sql);
        $retdata = $command->queryAll();

//        $start=$page*$pageSize; #计算每次分页的开始位置
        $count = count($retdata);
//        $pagedata=array();
//        $pagedata=array_slice($retdata,$start,$pageSize);

        $rs['status'] = 0;
        $rs['desc'] = '成功';
//        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $count;
//        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $retdata;

        return $rs;
    }
    //流程图片日统计
    public static function DayStatistics()
    {
        $sql = "select program_id from bac_program where program_id = '434'";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        $con_sql = "select * from bac_program";
        $con_command = Yii::app()->db->createCommand($con_sql);
        $con_rows = $con_command->queryAll();
//PTW
        $ptw_sql = "select a.apply_contractor_id,a.root_proid,b.pic from ptw_apply_basic a,bac_check_apply_detail b where a.apply_id = b.apply_id and a.record_time like '2018-01-03%'  ";
        $command = Yii::app()->db->createCommand($ptw_sql);
        $ptw_rows = $command->queryAll();
        $retdata = array();
        foreach ($ptw_rows as $i => $m) {
            $exist = 0;
            foreach ($retdata as $num => $rets) {
                if ($rets['root_proid'] == $m['root_proid']) {
                    if ($rets['apply_contractor_id'] == $m['apply_contractor_id']) {
                        $exist = 1;
                        if ($m['pic'] != '-1' && $m['pic'] != '') {
                            if (file_exists($m['pic'])) {
                                $retdata[$num]['file_size'] += filesize($m['pic']) / 1024 / 1024;
                                $retdata[$num]['file_cnt'] += 1;
                            }
                        }
                    }
                }
            }
            if ($exist == 0) {
                $retdata[count($retdata)]['root_proid'] = $m["root_proid"];
                $retdata[count($retdata) - 1]['apply_contractor_id'] = $m["apply_contractor_id"];
                if ($m['pic'] != '-1' && $m['pic'] != '') {
                    if (file_exists($m['pic'])) {
                        $retdata[count($retdata) - 1]['file_size'] = filesize($m['pic']) / 1024 / 1024;
                        $retdata[count($retdata) - 1]['file_cnt'] = 1;
                    } else {
                        $retdata[count($retdata) - 1]['file_cnt'] = 0;
                    }
                } else {
                    $retdata[count($retdata) - 1]['file_size'] = 0;
                    $retdata[count($retdata) - 1]['file_cnt'] = 0;
                }
            }
        }
//        var_dump($retdata);
//        var_dump('|||||||||||||||||||||');
//        exit;
//TBM
        $tbm_sql = "select a.program_id,a.add_conid,b.pic from tbm_meeting_basic a,bac_check_apply_detail b where a.meeting_id = b.apply_id and a.record_time like '2018-01-03%'";
        $command = Yii::app()->db->createCommand($tbm_sql);
        $tbm_rows = $command->queryAll();
        foreach ($tbm_rows as $j => $m) {
            $exist = 0;
            foreach ($retdata as $num => $rets) {
                if ($rets['root_proid'] == $m['program_id'] && $rets['apply_contractor_id'] == $m['add_conid']) {
//                        var_dump(111111111111111);
                    $exist = 1;
                    if ($m['pic'] != '-1' && $m['pic'] != '') {
                        if (file_exists($m['pic'])) {
                            $retdata[$num]['file_size'] += filesize($m['pic']) / 1024 / 1024;
                            $retdata[$num]['file_cnt'] += 1;
                        }
                    }

                }
            }

            if ($exist == 0) {

                $retdata[count($retdata)]['root_proid'] = $m["root_proid"];
                $retdata[count($retdata) - 1]['apply_contractor_id'] = $m["apply_contractor_id"];
                if ($m['pic'] != '-1' && $m['pic'] != '') {
                    if (file_exists($m['pic'])) {
                        $retdata[count($retdata) - 1]['file_size'] = filesize($m['pic']) / 1024 / 1024;
                        $retdata[count($retdata) - 1]['file_cnt'] = 1;
                    } else {
                        $retdata[count($retdata) - 1]['file_cnt'] = 0;
                    }
                } else {
                    $retdata[count($retdata) - 1]['file_size'] = 0;
                    $retdata[count($retdata) - 1]['file_cnt'] = 0;
                }
            }
//            exit;
        }

//Train
        $train_sql = "select a.pic as train_pic,a.program_id,a.add_conid,b.pic from train_apply_basic a,bac_check_apply_detail b where a.training_id = b.apply_id and a.record_time like '2018-01-03%' ";
        $command = Yii::app()->db->createCommand($train_sql);
        $train_rows = $command->queryAll();
        foreach ($train_rows as $j => $m) {
            $exist = 0;
            foreach ($retdata as $num => $rets) {
                if ($rets['root_proid'] == $m['program_id'] && $rets['apply_contractor_id'] == $m['add_conid']) {
//                        var_dump(111111111111111);
                    $exist = 1;
                    if ($m['pic'] != '-1' && $m['pic'] != '') {
                        if (file_exists($m['pic'])) {
                            $retdata[$num]['file_size'] += filesize($m['pic']) / 1024 / 1024;
                            $retdata[$num]['file_cnt'] += 1;
                        }
                    }
                    if ($m['train_pic'] != '-1' && $m['train_pic'] != '') {
                        if (file_exists($m['train_pic'])) {
                            $retdata[$num]['file_size'] += filesize($m['train_pic']) / 1024 / 1024;
                            $retdata[$num]['file_cnt'] += 1;
                        }
                    }
                }
            }

            if ($exist == 0) {

                $retdata[count($retdata)]['root_proid'] = $m["program_id"];
                $retdata[count($retdata) - 1]['apply_contractor_id'] = $m["add_conid"];
                if ($m['pic'] != '-1' && $m['pic'] != '') {
                    if (file_exists($m['pic'])) {
                        $retdata[count($retdata) - 1]['file_size'] = filesize($m['pic']) / 1024 / 1024;
                        $retdata[count($retdata) - 1]['file_cnt'] = 1;
                    } else {
                        $retdata[count($retdata) - 1]['file_cnt'] = 0;
                    }
                } else {
                    $retdata[count($retdata) - 1]['file_size'] = 0;
                    $retdata[count($retdata) - 1]['file_cnt'] = 0;
                }
                if ($m['train_pic'] != '-1' && $m['train_pic'] != '') {
                    if (file_exists($m['train_pic'])) {
                        $retdata[count($retdata) - 1]['file_size'] = filesize($m['train_pic']) / 1024 / 1024;
                        $retdata[count($retdata) - 1]['file_cnt'] += 1;
                    }
                }
            }
//            exit;
        }
//Inspection
        $inspection_sql = "select a.contractor_id,a.root_proid,b.pic from bac_safety_check a,bac_safety_check_detail b where a.check_id = b.check_id and a.apply_time like '2018-01-03%' ";
        $command = Yii::app()->db->createCommand($inspection_sql);
        $inspection_rows = $command->queryAll();
        foreach ($inspection_rows as $j => $m) {
            $exist = 0;
            foreach ($retdata as $num => $rets) {
                if ($rets['root_proid'] == $m['root_proid'] && $rets['apply_contractor_id'] == $m['contractor_id']) {
//                        var_dump(111111111111111);
                    $exist = 1;
                    if ($m['pic'] != '-1' && $m['pic'] != '') {
                        if (file_exists($m['pic'])) {
                            $retdata[$num]['file_size'] += filesize($m['pic']) / 1024 / 1024;
                            $retdata[$num]['file_cnt'] += 1;
                        }
                    }
                }
            }

            if ($exist == 0) {

                $retdata[count($retdata)]['root_proid'] = $m["root_proid"];
                $retdata[count($retdata) - 1]['apply_contractor_id'] = $m["contractor_id"];
                if ($m['pic'] != '-1' && $m['pic'] != '') {
                    if (file_exists($m['pic'])) {
                        $retdata[count($retdata) - 1]['file_size'] = filesize($m['pic']) / 1024 / 1024;
                        $retdata[count($retdata) - 1]['file_cnt'] = 1;
                    } else {
                        $retdata[count($retdata) - 1]['file_cnt'] = 0;
                    }
                } else {
                    $retdata[count($retdata) - 1]['file_cnt'] = 0;
                }
            }
//            exit;
        }
//Accident
        $acci_sql = "select a.root_proid,a.apply_contractor_id,a.acci_pic,b.pic from accident_basic a,accident_sick_leave b where a.apply_id = b.apply_id and a.record_time like '2018-01-03%' ";
        $command = Yii::app()->db->createCommand($acci_sql);
        $acci_rows = $command->queryAll();
        foreach ($acci_rows as $j => $m) {
            $exist = 0;
            foreach ($retdata as $num => $rets) {
                if ($rets['root_proid'] == $m['root_proid'] && $rets['apply_contractor_id'] == $m['apply_contractor_id']) {
//                        var_dump(111111111111111);
                    $exist = 1;
                    if ($m['pic'] != '-1' && $m['pic'] != '') {
                        if (file_exists($m['pic'])) {
                            $retdata[$num]['file_size'] += filesize($m['pic']) / 1024 / 1024;
                            $retdata[$num]['file_cnt'] += 1;
                        }
                    }
                    if ($m['acci_pic'] != '-1' && $m['acci_pic'] != '') {
                        if (file_exists($m['acci_pic'])) {
                            $retdata[$num]['file_size'] += filesize($m['acci_pic']) / 1024 / 1024;
                            $retdata[$num]['file_cnt'] += 1;
                        }
                    }
                }
            }

            if ($exist == 0) {

                $retdata[count($retdata)]['root_proid'] = $m["root_proid"];
                $retdata[count($retdata) - 1]['apply_contractor_id'] = $m["apply_contractor_id"];
                if ($m['pic'] != '-1' && $m['pic'] != '') {
                    if (file_exists($m['pic'])) {
                        $retdata[count($retdata) - 1]['file_size'] = filesize($m['pic']) / 1024 / 1024;
                        $retdata[count($retdata) - 1]['file_cnt'] = 1;
                    } else {
                        $retdata[count($retdata) - 1]['file_cnt'] = 0;
                    }
                } else {
                    $retdata[count($retdata) - 1]['file_size'] = 0;
                    $retdata[count($retdata) - 1]['file_cnt'] = 0;
                }
                if ($m['acci_pic'] != '-1' && $m['acci_pic'] != '') {
                    if (file_exists($m['acci_pic'])) {
                        $retdata[count($retdata) - 1]['file_size'] += filesize($m['acci_pic']) / 1024 / 1024;
                        $retdata[count($retdata) - 1]['file_cnt'] += 1;
                    }
                }
            }
//            exit;
        }
//QAQC
        $qaqc_sql = "select a.root_proid,a.contractor_id,b.pic from bac_qaqc_basic a,bac_qaqc_detail b where a.check_id = b.check_id and a.apply_time like '2018-01-03%' ";
        $command = Yii::app()->db->createCommand($qaqc_sql);
        $qaqc_rows = $command->queryAll();
        foreach ($qaqc_rows as $j => $m) {
            $exist = 0;
            foreach ($retdata as $num => $rets) {
                if ($rets['root_proid'] == $m['root_proid'] && $rets['contractor_id'] == $m['apply_contractor_id']) {
//                        var_dump(111111111111111);
                    $exist = 1;
                    if ($m['pic'] != '-1' && $m['pic'] != '') {
                        if (file_exists($m['pic'])) {
                            $retdata[$num]['file_size'] += filesize($m['pic']) / 1024 / 1024;
                            $retdata[$num]['file_cnt'] += 1;
                        }
                    }
                }
            }

            if ($exist == 0) {

                $retdata[count($retdata)]['root_proid'] = $m["root_proid"];
                $retdata[count($retdata) - 1]['apply_contractor_id'] = $m["contractor_id"];
                if ($m['pic'] != '-1' && $m['pic'] != '') {
                    if (file_exists($m['pic'])) {
                        $retdata[count($retdata) - 1]['file_size'] = filesize($m['pic']) / 1024 / 1024;
                        $retdata[count($retdata) - 1]['file_cnt'] = 1;
                    } else {
                        $retdata[count($retdata) - 1]['file_cnt'] = 0;
                    }
                } else {
                    $retdata[count($retdata) - 1]['file_cnt'] = 0;
                    $retdata[count($retdata) - 1]['file_size'] = 0;
                }
            }
//            exit;
        }
        $sum = 0;
        foreach ($retdata as $h => $l) {
            $sum += $l['file_cnt'];
        }
        var_dump($sum);
        exit;
    }

    //其他属性统计
    public static function TotalStatistics()
    {
        $file = array(
            'document' => array(
                '0' => '/opt/www-nginx/web/filebase/company/',//公司级文档
                '1' => '/opt/www-nginx/web/filebase/program/',//项目级文档
            ),
            'photo' => array(
                '0' => '/opt/www-nginx/web/filebase/data/staff/',//人员标签页图片，证书
                '1' => '/opt/www-nginx/web/filebase/data/face/',//人员头像
                '2' => '/opt/www-nginx/web/filebase/data/device/',//设备照片，证书
                '3' => '/opt/www-nginx/web/filebase/data/qrcode/',//人员，设备二维码
            ),
            'appupload' => array(
                '0' => '/opt/www-nginx/appupload',
            )
        );
        $contractor_list = Contractor::compList();//承包商列表
        $num = 0;
//        if(Yii::app()->fcache->get('filecache') != 'false'){
//            $data = Yii::app()->fcache->get('filecache');
//        }else {
        foreach ($contractor_list as $contractor_id => $contractor_name) {
            $data[$num]['contractor_id'] = $contractor_id;
            $data[$num]['flow_pic'] = 0;
            //人员各种证书
            $staff_cert_path = $file['photo'][0] . $contractor_id . '/';
            if (file_exists($staff_cert_path)) {
                $staff_cert_str = exec('du -s ' . $staff_cert_path);
                $staff_cert_ar = explode('/', $staff_cert_str);
                $staff_cert_size = $staff_cert_ar[0];
                $data[$num]['attribute'] += $staff_cert_size / 1024;
            }
            //人员头像
            $staff_face_path = $file['photo'][1] . $contractor_id . '/';
            if (file_exists($staff_face_path)) {
                $staff_face_str = exec('du -s ' . $staff_face_path);
                $staff_face_ar = explode('/', $staff_face_str);
                $staff_face_size = $staff_face_ar[0];
                $data[$num]['attribute'] += $staff_face_size / 1024;
            }
            //设备照片+设备证书
            $device_path = $file['photo'][2] . $contractor_id . '/';
            if (file_exists($device_path)) {
                $device_str = exec('du -s ' . $device_path);
                $device_ar = explode('/', $device_str);
                $device_size = $device_ar[0];
                $data[$num]['attribute'] += $device_size / 1024;
            }
            //二维码
            $qrcode_path = $file['photo'][3] . $contractor_id . '/';
            if (file_exists($qrcode_path)) {
                $qrcode_str = exec('du -s ' . $qrcode_path);
                $qrcode_ar = explode('/', $qrcode_str);
                $qrcode_size = $qrcode_ar[0];
                $data[$num]['attribute'] += $qrcode_size / 1024;
            }
            $data[$num]['attribute'] = round($data[$num]['attribute'], 2);
            //公司级文档
            $company_document = $file['document'][0] . $contractor_id . '/';
            if (file_exists($company_document)) {
                $company_str = exec('du -s ' . $company_document);
                $company_ar = explode('/', $company_str);
                $company_size = $company_ar[0];
                $data[$num]['document'] += $company_size / 1024;
            }
            //项目级文档
            $program_document_sql = "select sum(doc_size) as doc_size from bac_document where  type='4' and contractor_id = '" . $contractor_id . "' ";
            $command = Yii::app()->db->createCommand($program_document_sql);
            $program_document_rows = $command->queryAll();
            $program_document_sum = 0;

            $data[$num]['document'] += $program_document_rows[0]['doc_size'] / 1024;
            $data[$num]['document'] = round($data[$num]['document'], 2);
            $data[$num]['total_size'] = $data[$num]['flow_pic'] + $data[$num]['attribute'] + $data[$num]['document'];
            $data[$num]['max_size'] = '2048';
            $num++;
        }
    }

    /**
     * 按日查询
     * @param int $page
     * @param int $pageSize
     * @param array $args
     * @return array
     */
    public static function queryListByDate($page, $pageSize, $args = array())
    {

        $condition = '';
        $params = array();
        if ($args['program_id'] != '') {
            $condition .= ($condition == '') ? ' root_proid=:root_proid' : ' AND root_proid=:root_proid';
            $params['root_proid'] = $args['program_id'];
        } else {
            $condition .= ($condition == '') ? ' root_proid=:root_proid' : ' AND root_proid=:root_proid';
            $params['root_proid'] = '';
        }
//        if ($args['contractor_id'] != '') {
//            $condition.= ( $condition == '') ? ' con_id=:con_id' : ' AND con_id=:con_id';
//            $params['con_id'] = $args['contractor_id'];
//        }
        //操作开始时间
        if ($args['start_date'] != '') {
//            $args['start_date'] = date('Y-m-d',strtotime('+1 day',strtotime(Utils::DateToCn($args['start_date']))));
            $args['start_date'] = Utils::DateToCn($args['start_date']);
            $condition .= ($condition == '') ? ' date >=:start_date' : ' AND date >=:start_date';
            $params['start_date'] = $args['start_date'];
        }
        //操作结束时间
        if ($args['end_date'] != '') {
//            $args['end_date'] = date('Y-m-d',strtotime('+1 day',strtotime(Utils::DateToCn($args['end_date']))));
            $args['end_date'] = Utils::DateToCn($args['end_date']);
            $condition .= ($condition == '') ? ' date <=:end_date' : ' AND date <=:end_date';
            $params['end_date'] = $args['end_date'] . " 23:59:59";
        }
        $total_num = StatsDateApp::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'date desc';
        } else {
            if (substr($_REQUEST['q_order'], -1) == '~')
                $order = substr($_REQUEST['q_order'], 0, -1) . ' DESC';
            else
                $order = $_REQUEST['q_order'] . ' ASC';
        }
        $criteria->order = $order;
        $criteria->condition = $condition;
        $criteria->params = $params;
        $all_rows = StatsDateApp::model()->findAll($criteria);
        $pages = new CPagination($total_num);
        $pages->pageSize = $pageSize;
        $pages->setCurrentPage($page);
        $pages->applyLimit($criteria);
        //var_dump($criteria);
        $rows = StatsDateApp::model()->findAll($criteria);
        //var_dump($rows);
        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $total_num;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $rows;
        $rs['data'] = $rows;
        $rs['all'] = $all_rows;
        return $rs;
    }

    /**
     * 按月查询
     * @param int $page
     * @param int $pageSize
     * @param array $args
     * @return array
     */
    public static function queryListByMonth($page, $pageSize, $args = array())
    {

        $condition = '';
        $params = array();
        if ($args['program_id'] != '') {
            $condition .= ($condition == '') ? ' root_proid=:root_proid' : ' AND root_proid=:root_proid';
            $params['root_proid'] = $args['program_id'];
        } else {
            $condition .= ($condition == '') ? ' root_proid=:root_proid' : ' AND root_proid=:root_proid';
            $params['root_proid'] = '';
        }
//        if ($args['contractor_id'] != '') {
//            $condition.= ( $condition == '') ? ' con_id=:con_id' : ' AND con_id=:con_id';
//            $params['con_id'] = $args['contractor_id'];
//        }
        if ($args['month'] != '') {
            $month = Utils::MonthToCn($args['month']);
            $condition .= ($condition == '') ? 'date like :month' : ' AND date like :month';
            $params['month'] = $month . '%';
        }

        $total_num = StatsDateApp::model()->count($condition, $params); //总记录数

        $criteria = new CDbCriteria();

        if ($_REQUEST['q_order'] == '') {

            $order = 'date desc';
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
//        $pages->applyLimit($criteria);
        //var_dump($criteria);
        $row = StatsDateApp::model()->findAll($criteria);
//        var_dump($row);
        $retdata = array();
        $i = 0;
        foreach ($row as $cnt => $rows) {
            $exist = 0;
            foreach ($retdata as $num => $rets) {
                if ($rets['con_id'] == $rows['con_id']) {
                    $exist = 1;
                    $retdata[$num]['ptw_cnt'] += $rows['ptw_cnt'];
                    $retdata[$num]['ptw_pcnt'] += $rows['ptw_pcnt'];
                    $retdata[$num]['tbm_cnt'] += $rows['tbm_cnt'];
                    $retdata[$num]['tbm_pcnt'] += $rows['tbm_pcnt'];
                    $retdata[$num]['ins_cnt'] += $rows['ins_cnt'];
                    $retdata[$num]['mee_cnt'] += $rows['mee_cnt'];
                    $retdata[$num]['mee_pcnt'] += $rows['mee_pcnt'];
                    $retdata[$num]['tra_cnt'] += $rows['tra_cnt'];
                    $retdata[$num]['tra_pcnt'] += $rows['tra_pcnt'];
                    $retdata[$num]['ra_cnt'] += $rows['ra_cnt'];
                    $retdata[$num]['che_cnt'] += $rows['che_cnt'];
                    $retdata[$num]['inc_cnt'] += $rows['inc_cnt'];
                }
            }
//            var_dump(3);
            if ($exist == 0) {
//                var_dump(333);
//                var_dump(2);
//                $retdata[count($retdata)] = array("contractor_id"=>$rows[i]["contractor_id"], "record_time"=>$rows[i]["record_time"], "ptw_sum"=>0,  "tbm_sum"=>0, "ptw_staffs"=>0,"tbm_staffs"=>0);
                $retdata[count($retdata)]['con_id'] = $rows["con_id"];
//                var_dump($retdata[count($retdata)-1]['contractor_id']);
                $retdata[count($retdata) - 1]['date'] = $month;
                $retdata[count($retdata) - 1]['ptw_cnt'] = $rows['ptw_cnt'];
                $retdata[count($retdata) - 1]['tbm_cnt'] = $rows['tbm_cnt'];
                $retdata[count($retdata) - 1]['ins_cnt'] = $rows['ins_cnt'];
                $retdata[count($retdata) - 1]['mee_cnt'] = $rows['mee_cnt'];
                $retdata[count($retdata) - 1]['tra_cnt'] = $rows['tra_cnt'];
                $retdata[count($retdata) - 1]['ra_cnt'] = $rows['ra_cnt'];
                $retdata[count($retdata) - 1]['che_cnt'] = $rows['che_cnt'];
                $retdata[count($retdata) - 1]['inc_cnt'] = $rows['inc_cnt'];
                $retdata[count($retdata) - 1]['ptw_pcnt'] = $rows['ptw_pcnt'];
                $retdata[count($retdata) - 1]['tbm_pcnt'] = $rows['tbm_pcnt'];
                $retdata[count($retdata) - 1]['mee_pcnt'] = $rows['mee_pcnt'];
                $retdata[count($retdata) - 1]['tra_pcnt'] = $rows['tra_pcnt'];
            }
            $i++;
//            var_dump($retdata);
        }
//        var_dump($retdata);
//        exit;
        $start=$page*$pageSize; #计算每次分页的开始位置
        $count = count($retdata);
        $pagedata=array();
        $pagedata=array_slice($retdata,$start,$pageSize);


        $rs['status'] = 0;
        $rs['desc'] = '成功';
        $rs['page_num'] = ($page + 1);
        $rs['total_num'] = $count;
        $rs['num_of_page'] = $pageSize;
        $rs['rows'] = $pagedata;
        $rs['data'] = $pagedata;
        $rs['all'] = $retdata;

        return $rs;
    }

    //根据天数统计总包项目使用次数
    public static function cntByDate($args) {
//        $contractor_id = Yii::app()->user->contractor_id;
//        $args['contractor_id'] = $contractor_id;

//        $date = '2018-01-08';
//        $args['program_id'] = '434';
        $i = 0;
        $condition = '';
        $params = array();
        if ($args['program_id'] != '') {
            $condition.= ( $condition == '') ? ' root_proid=:root_proid' : ' AND root_proid=:root_proid';
            $params['root_proid'] = $args['program_id'];
        }
        if ($args['contractor_id'] != '') {
            $condition.= ( $condition == '') ? ' con_id=:con_id' : ' AND con_id=:con_id';
            $params['con_id'] = $args['contractor_id'];
        }
        //操作开始时间
        if ($args['start_date'] != '') {
//            $args['start_date'] = date('Y-m-d',strtotime('+1 day',strtotime(Utils::DateToCn($args['start_date']))));
            $args['start_date'] = Utils::DateToCn($args['start_date']);
            $condition.= ( $condition == '') ? ' date >=:start_date' : ' AND date >=:start_date';
            $params['start_date'] = $args['start_date'];
        }
        //操作结束时间
        if ($args['end_date'] != '') {
//            $args['end_date'] = date('Y-m-d',strtotime('+1 day',strtotime(Utils::DateToCn($args['end_date']))));
            $args['end_date'] = Utils::DateToCn($args['end_date']);
            $condition.= ( $condition == '') ? ' date <=:end_date' : ' AND date <=:end_date';
            $params['end_date'] = $args['end_date'] . " 23:59:59";
        }

        $criteria = new CDbCriteria();
        $criteria->condition = $condition;
        $criteria->params = $params;
        $rows = StatsDateApp::model()->findAll($criteria);
//        $sql = "select * from stats_date_app where root_proid = '" . $args['program_id'] . "' and  date = '".$date ."'";//var_dump($sql);
//        $command = Yii::app()->db->createCommand($sql);
//        $rows = $command->queryAll();

        if ($rows) {
            $j = 0;
            $data = array();
            $data['ptw_cnt'] = 0;
            $data['ptw_pcnt'] = 0;
            $data['tbm_cnt'] = 0;
            $data['tbm_pcnt'] =0;
            $data['che_cnt'] = 0;
            $data['ins_cnt'] = 0;
            $data['mee_cnt'] = 0;
            $data['mee_pcnt'] = 0;
            $data['tra_cnt'] = 0;
            $data['tra_pcnt'] = 0;
            $data['ra_cnt'] = 0;
            $data['inc_cnt'] = 0;
//            $data['file_cnt'] =0;

            foreach ($rows as $cnt => $list) {
                $data['ptw_cnt']+= $list['ptw_cnt'];
                $data['ptw_pcnt']+= $list['ptw_pcnt'];
                $data['tbm_cnt']+= $list['tbm_cnt'];
                $data['tbm_pcnt']+=$list['tbm_pcnt'];
                $data['che_cnt']+= $list['che_cnt'];
                $data['ins_cnt']+= $list['ins_cnt'];
                $data['mee_cnt']+= $list['mee_cnt'];
                $data['mee_pcnt']+= $list['mee_pcnt'];
                $data['tra_cnt']+= $list['tra_cnt'];
                $data['tra_pcnt']+=$list['tra_pcnt'];
                $data['ra_cnt']+= $list['ra_cnt'];
                $data['inc_cnt']+= $list['inc_cnt'];
//                $data['file_cnt']+=$list['file_cnt'];
            }
            $i = 0;
            foreach ($data as $key => $cnt){
                switch ($key)
                {
                    case "ptw_cnt":
                        $rdata[$i]['label'] = 'PTW';
                        $rdata[$i]['label_table'] = 'PTW Cnt';
                        $rdata[$i]['label_id'] = 'ptw_cnt_label';
                        $rdata[$i]['data_id'] = 'ptw_cnt_data';
                        $rdata[$i]['data'] = $cnt;
                        break;
                    case "ptw_pcnt":
                        $rdata[$i]['label'] = 'PTW Participants';
                        $rdata[$i]['label_table'] = 'PTW Participants';
                        $rdata[$i]['label_id'] = 'ptw_pcnt_label';
                        $rdata[$i]['data_id'] = 'ptw_pcnt_data';
                        $rdata[$i]['data'] = $cnt;
                        break;
                    case "tbm_cnt":
                        $rdata[$i]['label'] = 'TBM';
                        $rdata[$i]['label_table'] = 'TBM Cnt';
                        $rdata[$i]['label_id'] = 'tbm_cnt_label';
                        $rdata[$i]['data_id'] = 'tbm_cnt_data';
                        $rdata[$i]['data'] = $cnt;
                        break;
                    case "tbm_pcnt":
                        $rdata[$i]['label'] = 'TBM Participants';
                        $rdata[$i]['label_table'] = 'TBM Participants';
                        $rdata[$i]['label_id'] = 'tbm_pcnt_label';
                        $rdata[$i]['data_id'] = 'tbm_pcnt_data';
                        $rdata[$i]['data'] = $cnt;
                        break;
                    case "che_cnt":
                        $rdata[$i]['label'] = 'Checklist';
                        $rdata[$i]['label_table'] = 'Checklist Cnt';
                        $rdata[$i]['label_id'] = 'che_cnt_label';
                        $rdata[$i]['data_id'] = 'che_cnt_data';
                        $rdata[$i]['data'] = $cnt;
                        break;
                    case "ins_cnt":
                        $rdata[$i]['label'] = 'Inspection';
                        $rdata[$i]['label_table'] = 'Inspection Cnt';
                        $rdata[$i]['label_id'] = 'ins_cnt_label';
                        $rdata[$i]['data_id'] = 'ins_cnt_data';
                        $rdata[$i]['data'] = $cnt;
                        break;
                    case "mee_cnt":
                        $rdata[$i]['label'] = 'Meeting';
                        $rdata[$i]['label_table'] = 'Meeting Cnt';
                        $rdata[$i]['label_id'] = 'mee_cnt_label';
                        $rdata[$i]['data_id'] = 'mee_cnt_data';
                        $rdata[$i]['data'] = $cnt;
                        break;
                    case "mee_pcnt":
                        $rdata[$i]['label'] = 'Meeting Participants';
                        $rdata[$i]['label_table'] = 'Meeting Participants';
                        $rdata[$i]['label_id'] = 'mee_pcnt_label';
                        $rdata[$i]['data_id'] = 'mee_pcnt_data';
                        $rdata[$i]['data'] = $cnt;
                        break;
                    case "tra_cnt":
                        $rdata[$i]['label'] = 'Training';
                        $rdata[$i]['label_table'] = 'Training Cnt';
                        $rdata[$i]['label_id'] = 'tra_cnt_label';
                        $rdata[$i]['data_id'] = 'tra_cnt_data';
                        $rdata[$i]['data'] = $cnt;
                        break;
                    case "tra_pcnt":
                        $rdata[$i]['label'] = 'Training Participants';
                        $rdata[$i]['label_table'] = 'Training Participants';
                        $rdata[$i]['label_id'] = 'tra_pcnt_label';
                        $rdata[$i]['data_id'] = 'tra_pcnt_data';
                        $rdata[$i]['data'] = $cnt;
                        break;
                    case "ra_cnt":
                        $rdata[$i]['label'] = 'RA';
                        $rdata[$i]['label_table'] = 'RA Cnt';
                        $rdata[$i]['label_id'] = 'ra_cnt_label';
                        $rdata[$i]['data_id'] = 'ra_cnt_data';
                        $rdata[$i]['data'] = $cnt;
                        break;
                    case "inc_cnt":
                        $rdata[$i]['label'] = 'Incident';
                        $rdata[$i]['label_table'] = 'Incident Cnt';
                        $rdata[$i]['label_id'] = 'inc_cnt_label';
                        $rdata[$i]['data_id'] = 'inc_cnt_data';
                        $rdata[$i]['data'] = $cnt;
                        break;
//                    case "file_cnt":
//                        $rdata[$i]['label'] = 'File Cnt';
//                        $rdata[$i]['data'] = $cnt;
//                        break;
                    default:
                        break;
                }
                $i++;
            }
        }
//        }
//        var_dump($rdata);
//        exit;
        return $rdata;
    }

    //根据月份统计总包项目使用次数
    public static function cntByMonth($args) {
//        $contractor_id = Yii::app()->user->contractor_id;
//        $args['contractor_id'] = $contractor_id;

//        $date = '2018-01-08';
//        $args['program_id'] = '434';
        $i = 0;
        $condition = '';
        $params = array();
        if ($args['program_id'] != '') {
            $condition.= ( $condition == '') ? ' root_proid=:root_proid' : ' AND root_proid=:root_proid';
            $params['root_proid'] = $args['program_id'];
        }
        if ($args['contractor_id'] != '') {
            $condition.= ( $condition == '') ? ' con_id=:con_id' : ' AND con_id=:con_id';
            $params['con_id'] = $args['contractor_id'];
        }
        if ($args['month'] != '') {
            $month = Utils::MonthToCn($args['month']);
            $condition.= ( $condition == '') ? ' date like :month' : ' AND date like :month';
            $params['month'] = $month.'%';
        }
        $criteria = new CDbCriteria();
        $criteria->condition = $condition;
        $criteria->params = $params;
        $rows = StatsDateApp::model()->findAll($criteria);
//        $sql = "select * from stats_date_app where root_proid = '" . $args['program_id'] . "' and  date = '".$date ."'";//var_dump($sql);
//        $command = Yii::app()->db->createCommand($sql);
//        $rows = $command->queryAll();

        if ($rows) {
            $j = 0;
            $data = array();
            $data['ptw_cnt'] = 0;
            $data['ptw_pcnt'] =0;
            $data['tbm_cnt'] = 0;
            $data['tbm_pcnt'] =0;
            $data['che_cnt'] = 0;
            $data['ins_cnt'] = 0;
            $data['mee_cnt'] = 0;
            $data['mee_pcnt'] = 0;
            $data['tra_cnt'] = 0;
            $data['tra_pcnt'] = 0;
            $data['ra_cnt'] = 0;
            $data['inc_cnt'] = 0;
//            $data['file_cnt'] =0;

            foreach ($rows as $cnt => $list) {
                $data['ptw_cnt']+= $list['ptw_cnt'];
                $data['ptw_pcnt']+= $list['ptw_pcnt'];
                $data['tbm_cnt']+= $list['tbm_cnt'];
                $data['tbm_pcnt']+=$list['tbm_pcnt'];
                $data['che_cnt']+= $list['che_cnt'];
                $data['ins_cnt']+= $list['ins_cnt'];
                $data['mee_cnt']+= $list['mee_cnt'];
                $data['mee_pcnt']+= $list['mee_pcnt'];
                $data['tra_cnt']+= $list['tra_cnt'];
                $data['tra_pcnt']+=$list['tra_pcnt'];
                $data['ra_cnt']+= $list['ra_cnt'];
                $data['inc_cnt']+= $list['inc_cnt'];
//                $data['file_cnt']+=$list['file_cnt'];
            }
            $i = 0;
            foreach ($data as $key => $cnt){
                switch ($key)
                {
                    case "ptw_cnt":
                        $rdata[$i]['label'] = 'PTW';
                        $rdata[$i]['label_table'] = 'PTW Cnt';
                        $rdata[$i]['label_id'] = 'ptw_cnt_label';
                        $rdata[$i]['data_id'] = 'ptw_cnt_data';
                        $rdata[$i]['data'] = $cnt;
                        break;
                    case "ptw_pcnt":
                        $rdata[$i]['label'] = 'PTW Participants';
                        $rdata[$i]['label_table'] = 'PTW Participants';
                        $rdata[$i]['label_id'] = 'ptw_pcnt_label';
                        $rdata[$i]['data_id'] = 'ptw_pcnt_data';
                        $rdata[$i]['data'] = $cnt;
                        break;
                    case "tbm_cnt":
                        $rdata[$i]['label'] = 'TBM';
                        $rdata[$i]['label_table'] = 'TBM Cnt';
                        $rdata[$i]['label_id'] = 'tbm_cnt_label';
                        $rdata[$i]['data_id'] = 'tbm_cnt_data';
                        $rdata[$i]['data'] = $cnt;
                        break;
                    case "tbm_pcnt":
                        $rdata[$i]['label'] = 'TBM Participants';
                        $rdata[$i]['label_table'] = 'TBM Participants';
                        $rdata[$i]['label_id'] = 'tbm_pcnt_label';
                        $rdata[$i]['data_id'] = 'tbm_pcnt_data';
                        $rdata[$i]['data'] = $cnt;
                        break;
                    case "che_cnt":
                        $rdata[$i]['label'] = 'Checklist Cnt';
                        $rdata[$i]['label_table'] = 'Checklist Cnt';
                        $rdata[$i]['label_id'] = 'che_cnt_label';
                        $rdata[$i]['data_id'] = 'che_cnt_data';
                        $rdata[$i]['data'] = $cnt;
                        break;
                    case "ins_cnt":
                        $rdata[$i]['label'] = 'Inspection Cnt';
                        $rdata[$i]['label_table'] = 'Inspection Cnt';
                        $rdata[$i]['label_id'] = 'ins_cnt_label';
                        $rdata[$i]['data_id'] = 'ins_cnt_data';
                        $rdata[$i]['data'] = $cnt;
                        break;
                    case "mee_cnt":
                        $rdata[$i]['label'] = 'Meeting';
                        $rdata[$i]['label_table'] = 'Meeting Cnt';
                        $rdata[$i]['label_id'] = 'mee_cnt_label';
                        $rdata[$i]['data_id'] = 'mee_cnt_data';
                        $rdata[$i]['data'] = $cnt;
                        break;
                    case "mee_pcnt":
                        $rdata[$i]['label'] = 'Meeting Participants';
                        $rdata[$i]['label_table'] = 'Meeting Participants';
                        $rdata[$i]['label_id'] = 'mee_pcnt_label';
                        $rdata[$i]['data_id'] = 'mee_pcnt_data';
                        $rdata[$i]['data'] = $cnt;
                        break;
                    case "tra_cnt":
                        $rdata[$i]['label'] = 'Training';
                        $rdata[$i]['label_table'] = 'Training Cnt';
                        $rdata[$i]['label_id'] = 'tra_cnt_label';
                        $rdata[$i]['data_id'] = 'tra_cnt_data';
                        $rdata[$i]['data'] = $cnt;
                        break;
                    case "tra_pcnt":
                        $rdata[$i]['label'] = 'Training Participants';
                        $rdata[$i]['label_table'] = 'Training Participants';
                        $rdata[$i]['label_id'] = 'tra_pcnt_label';
                        $rdata[$i]['data_id'] = 'tra_pcnt_data';
                        $rdata[$i]['data'] = $cnt;
                        break;
                    case "ra_cnt":
                        $rdata[$i]['label'] = 'RA';
                        $rdata[$i]['label_table'] = 'RA Cnt';
                        $rdata[$i]['label_id'] = 'ra_cnt_label';
                        $rdata[$i]['data_id'] = 'ra_cnt_data';
                        $rdata[$i]['data'] = $cnt;
                        break;
                    case "inc_cnt":
                        $rdata[$i]['label'] = 'Incident';
                        $rdata[$i]['label_table'] = 'Incident Cnt';
                        $rdata[$i]['label_id'] = 'inc_cnt_label';
                        $rdata[$i]['data_id'] = 'inc_cnt_data';
                        $rdata[$i]['data'] = $cnt;
                        break;
//                    case "file_cnt":
//                        $rdata[$i]['label'] = 'File Cnt';
//                        $rdata[$i]['data'] = $cnt;
//                        break;
                    default:
                        break;
                }
                $i++;
            }
        }
//        }
//        var_dump($rdata);
//        exit;
        return $rdata;
    }

    //模块列表
    public static  function ModuleList(){
        if (Yii::app()->language == 'zh_CN') {
            $module = array(
                "ptw_cnt" => "PTW",
                "tbm_cnt" => "TBM",
                "che_cnt" => "Checklist",
                "mee_cnt" => "Meeting",
                "tra_cnt" => "Training",
                "ins_cnt" => "Inspection",
                "ra_cnt" => "RA"
            );
        }else{
            $module = array(
                "ptw_cnt" => "PTW",
                "tbm_cnt" => "TBM",
                "che_cnt" => "Checklist",
                "mee_cnt" => "Meeting",
                "tra_cnt" => "Training",
                "ins_cnt" => "Inspection",
                "ra_cnt" => "RA"
            );
        }
        return $module;
    }

    public static function ModuleByDay($field,$day) {
        $day = Utils::DateToCn($day);
        $sql = "select sum(a.".$field.") as cnt,b.program_name from stats_date_app a,bac_program b where a.date='".$day."' and a.root_proid = b.program_id group by a.root_proid order by cnt asc";
        $command = Yii::app()->db->createCommand($sql);
        $rows = $command->queryAll();
        return $rows;
    }
}

