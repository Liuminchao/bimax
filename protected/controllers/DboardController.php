<?php

/*
 * 模块编号: M1001
 */

class DboardController extends BaseController {

    const CONTRACTOR_PREFIX = 'CT';   //承包商编号前缀
    const INITIAL_PASSWORD = 123456;  //初始密码

    public function init() {
        parent::init();
        $this->contentHeader = Yii::t('dboard', 'contentHeader');
        $this->bigMenu = Yii::t('dboard', 'Home');
    }

    public function actionIndex() {

//        $app = $_GET['name'];
//        echo $app;
//        var_dump(11111);
//        exit;
        $id = Yii::app()->user->id;
        $ra = Operator::model()->findByPk($id);
        $passwd = $ra->passwd;
        $initial_passwd = md5(self::INITIAL_PASSWORD);
        if (Yii::app()->user->getState('role_id') == Operator::ROLE_SYSTEM) {
            return $this->actionSystem();
        }
        else if (Yii::app()->user->getState('role_id') == Operator::ROLE_COMP) {
            if($passwd == $initial_passwd){
                $this->layout = '//layouts/main_1';
//                return $this->actionPassword();
            }
//            return $this->actionComp();
            $this->redirect('index.php?r=site/list&ptype=MC');
        }
        else{
            if($passwd == $initial_passwd){
                return $this->actionPassword();
            }
//            return $this->actionComp();
            $this->redirect('index.php?r=site/list&ptype=MC');
        }


        /*else if (Yii::app()->user->getState('role_id') == Operator::ROLE_MC) {
            return $this->actionMc();
        } else if (Yii::app()->user->getState('role_id') == Operator::ROLE_SC) {
            return $this->actionSc();
        }*/
        
    }

    /**
     * 系统
     */
    public function actionSystem() {
        $main_cnt = Program::queryMainCount();
        $oper_cnt = Operator::model()->count('operator_type=:operator_type',array('operator_type'=>  Operator::TYPE_SYSTEM));
        $comp_cnt = Contractor::model()->count('status=:status', array('status' => '0'));
        $total_staff_cnt = Staff::model()->count('status=:status', array('status' => Staff::STATUS_NORMAL));
        //$log_cnt = OperatorLog::model()->count();
        $this->smallHeader = Yii::t('dboard', 'Home');
        $this->render('system', array('main_cnt'=> $main_cnt, 'oper_cnt' => $oper_cnt, 'comp_cnt' => $comp_cnt,  'total_staff_cnt' => $total_staff_cnt));
    }


    public function actionComp() {
        $user_cnt = 0;
        $proj_cnt = 0;
        $lice_cnt = 0;
        $meeting_cnt = 0;
        $user_cnt = Staff::model()->count('contractor_id=:contractor_id AND status=:status', array('contractor_id' => Yii::app()->user->getState('contractor_id'),'status'=>Staff::STATUS_NORMAL));
        $main_proj_cnt = Program::model()->count('contractor_id=:contractor_id AND status=:status AND program_id = root_proid', array('contractor_id' => Yii::app()->user->getState('contractor_id'),'status'=>Program::STATUS_NORMAL));
        $proj_cnt = Program::model()->count('contractor_id=:contractor_id AND status=:status', array('contractor_id' => Yii::app()->user->getState('contractor_id'),'status'=>Program::STATUS_NORMAL));
        $lice_cnt = ApplyBasic::model()->count('apply_contractor_id=:apply_contractor_id ', array('apply_contractor_id' => Yii::app()->user->getState('contractor_id')));
        $staff_cnt = Staff::model()->count('contractor_id=:contractor_id', array('contractor_id' => Yii::app()->user->getState('contractor_id')));
//        $program_id = Program::getProgramId();
//        $meeting_cnt = Meeting::model()->count('program_id IN ('.$program_id.') AND status=:status',array('status' => Meeting::STATUS_FINISH));
        $meeting_cnt = Meeting::model()->count('add_conid =:contractor_id or main_conid = :contractor_id and status=:status',array('contractor_id' => Yii::app()->user->getState('contractor_id'),'status' => Meeting::STATUS_FINISH));
//        $r = SafetyCheck::summaryByMonth();
//        print_r(json_encode($r));
        $this->smallHeader = Yii::t('dboard', 'Home');
        $this->render('comp', array('user_cnt' => $user_cnt,'main_proj_cnt' => $main_proj_cnt, 'proj_cnt' => $proj_cnt, 'lice_cnt' => $lice_cnt, 'staff_cnt' => $staff_cnt, 'meeting_cnt' => $meeting_cnt));
    }
    /**
     * 修改WEB端密码
     */
    public function actionPassword(){
        $this->smallHeader = Yii::t('dboard', 'Menu reset');
        $model = new Operator('modify');
        $rs = new Operator('modify');
        $id = Yii::app()->user->id;
        $ra = Operator::model()->findByPk($id);
        $operator_type = $ra->operator_type;
        if($operator_type<>00){
            $contractor_id = Yii::app()->user->contractor_id;
            $seq_id = sprintf('%05s', $contractor_id);
            $operator_id = self::CONTRACTOR_PREFIX . $seq_id;
            $rs->_attributes = Operator::model()->findByPk($operator_id);
        }
        $r = array();
        if (isset($_REQUEST['Operator'])) {
            $args = $_REQUEST['Operator'];
            $r = Operator::updateOperatorPwd($args);
        }
        $model->_attributes = Operator::model()->findByPk($id);
        $this->render('password_form', array('model' => $model,'id'=> $id, 'rs' => $rs, 'msg' => $r));
    }

    /**
     * 修改登录密码
     */
    public function actionPwd() {
        $r = array();
        if (isset($_REQUEST['Operator'])) {
            $args = $_REQUEST['Operator'];
            $r = Operator::updateOperatorPwd($args);
            $class = Utils::getMessageType($r['status']);
            $r['class'] = $class[0];
        }
        print_r(json_encode($r));
    }
    /**
     *查询项目成员人数
     */
    public function actionCnt() {
        $r = RfList::RecordCntList();
        print_r(json_encode($r));
    }
    /**
     *按月统计承包商下各项目的违规次数
     */
    public function actionViolationsCnt() {
        $r = SafetyCheck::summaryByMonth();
//        var_dump($r);
//        exit;
        print_r(json_encode($r));
    }
    /**
     *按月统计承包商下各项目的违规次数
     */
    public function actionAccidentsCnt() {
        $r = AccidentBasic::summaryByMonth();
        print_r(json_encode($r));
    }
    /**
     * 统计公司文件使用空间大小
     */
    public function actionFileSpace() {
        $r = StatsContractorStore::summaryByContractor();
        print_r(json_encode($r));
    }
    /**
     * 承包商下总包项目的位置信息
     */
    public function actionProgramAddress() {
        $contractor_id = Yii::app()->user->contractor_id;
        $args['contractor_id'] = $contractor_id;
        $r = Program::McProgramAddress($args);
        print_r(json_encode($r));
    }
    /**
     * 总包
     */
    /*public function actionMc() {

        $user_cnt = User::model()->count('contractor_id=:contractor_id', array('contractor_id' => Yii::app()->user->getState('contractor_id')));
        $proj_cnt = Program::model()->count('contractor_id=:contractor_id', array('contractor_id' => Yii::app()->user->getState('contractor_id')));
        $lice_cnt = ApplyBasicLog::model()->count('contractor_id=:contractor_id AND status=:status', array('contractor_id' => Yii::app()->user->getState('contractor_id'),'status'=>ApplyBasicLog::STATUS_FINISH));
        $program_id = Program::getProgramId();
        $meeting_cnt = Meeting::model()->count('program_id IN ('.$program_id.') AND status=:status',array('status' => Meeting::STATUS_FINISH));
        $this->smallHeader = Yii::t('dboard', 'Home');
        $this->render('mc', array('user_cnt' => $user_cnt, 'proj_cnt' => $proj_cnt, 'lice_cnt' => $lice_cnt, 'meeting_cnt' => $meeting_cnt));
    }*/

    /**
     * 分包
     */
    /*public function actionSc() {
        
        $user_cnt = User::model()->count('contractor_id=:contractor_id', array('contractor_id' => Yii::app()->user->getState('contractor_id')));
        $worker_cnt = Worker::model()->count('contractor_id=:contractor_id', array('contractor_id' => Yii::app()->user->getState('contractor_id')));
        $staff_cnt = Staff::model()->count('contractor_id=:contractor_id', array('contractor_id' => Yii::app()->user->getState('contractor_id')));
        $lice_cnt = ApplyBasicLog::model()->count('program_id=:program_id AND status=:status', array('program_id' => ProgramContractor::getProgramId(),'status'=>ApplyBasicLog::STATUS_FINISH));
        
        
        $assign_cnt = ProgramContractor::model()->count('contractor_id=:contractor_id', array('contractor_id' => Yii::app()->user->getState('contractor_id')));
        $this->smallHeader = Yii::t('dboard', 'Home');
        $this->render('sc',array('user_cnt'=>$user_cnt,'worker_cnt'=>$worker_cnt,'staff_cnt'=>$staff_cnt,'lice_cnt'=>$lice_cnt,'assign_cnt'=>$assign_cnt));
    }*/

}
