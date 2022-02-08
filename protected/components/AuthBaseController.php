<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class AuthBaseController extends CController {

    public $gridId = 'list';
    public $pageSize = 20;

    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/main_new';

    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $menu = array();

    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();
    public static $authInited = false;
    //页头
    public $contentHeader = '';
    public $smallHeader = '';
    public $bigMenu = '';

    public function init() {

//        $sql = "SELECT * FROM `bac_program` where father_proid <> root_proid and status = '00'";
//        $command = Yii::app()->db->createCommand($sql);
//        $rows = $command->queryAll();
//        foreach($rows as $i => $j){
//            $program_id = $j['program_id'];
//            $sql_1 = "SELECT * FROM `bac_program` where root_proid = '$program_id' and father_proid = root_proid and status = '00'";
//            $command = Yii::app()->db->createCommand($sql_1);
//            $rows_1 = $command->queryAll();
//            if(count($rows_1)>0){
//                foreach($rows_1 as $x => $y){
//                    $update_sql = "update bac_program set program_name =:program_name where program_id=:program_id ";
//                    $command = Yii::app()->db->createCommand($update_sql);
//                    $command->bindParam(":program_name", $j['program_name']);
//                    $command->bindParam(":program_id", $y['program_id']);
//                    $rs = $command->execute();
//                }
//            }
//        }
        $tag = self::beforeCall();
        parent::init();
        if(!$tag){
            if (isset(Yii::app()->user->auths) && count(Yii::app()->user->auths) > 0) {
                $authManager = Yii::app()->authManager;
                $userId = Yii::app()->user->id;
                foreach (Yii::app()->user->auths as $authid) {
                    try {
                        $authManager->assign($authid, $userId);
                    } catch (Exception $e) {
                        continue;
                    }
                }
            } else {
                $this->redirect('index.php?r=site/login');
            }
            $this->pageTitle = Yii::app()->name;
        }

        //国际化
        if (isset($_REQUEST['lang']) && $_REQUEST['lang'] != "") {   //通过lang参数识别语言 
//            var_dump('if');
            Yii::app()->language = $_REQUEST['lang'];
            setcookie('lang', $_REQUEST['lang']);
        } else if (isset($_COOKIE['lang']) && $_COOKIE['lang'] != "") {   //通过$_COOKIE['lang']识别语言   
//            var_dump('else if');
            Yii::app()->language = $_COOKIE['lang'];
        } else {   //通过系统或浏览器识别语言 
//            var_dump('else');
//            $lang = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
//            Yii::app()->language = str_replace('-', '_', $lang[0]);
            Yii::app()->language = 'en_US';
        }

    }

    protected function beforeCall() {
        $mod = $this->module !== null ? $this->module->id . '/' : "";
        $access = $mod . $this->id . '/' . $this->action->id;
        $str = Yii::app()->request->getUrl();
        $index_1 = strpos($str, '=')+1;
        $index_2 = strpos($str, '&');
        $length = $index_2 -$index_1;
        $str = substr($str,$index_1,$length);
        $action_array_1 = array(
            'qa/qainspection/qaexport',
            'rf/rf/info',
        );
        if(in_array($str, $action_array_1)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Checks if rbac access is granted for the current user
     * @param String $action . The current action
     * @return boolean true if access is granted else false
     */
    protected function beforeAction($action) {
        //return true;
        //rbac access
        $mod = $this->module !== null ? $this->module->id . '/' : "";
        $access = $mod . $this->id . '/' . $this->action->id;
        //Always allow access if $access is in the allowedAccess array
        if (in_array($access, $this->allowedAccess())) {
            return true;
        }
        //Always allow access if $access is in the allowedAccess array
        if (Yii::app()->user->checkAccess("")) {
            return true;
        }
        // Check for rbac access
        if (Yii::app()->user->isGuest) {
            $this->renderPartial('//site/login');
        } else if (!Yii::app()->user->checkAccess($access)) {
            // You may change this messages
            //
            //($access);exit;
            $error["code"] = "403";
            $error["title"] = ''; //"您没有权限执行该操作！".($mod==''?'':$mod."/").$this->id."/".$this->action->id."。</div>";
            $error["message"] = Yii::t('dboard','no auth');
            //You may change the view for unauthorized access
            if (Yii::app()->request->isAjaxRequest) {
                $this->renderPartial('//site/error', array("code" => $error["code"] . ' ' . $error["title"], "message" => $error["message"]));
            } else {
                $this->render('//site/error', array("code" => $error["code"] . ' ' . $error["title"], "message" => $error["message"]));
            }
            return false;
        } else {
            //self::log();
            return true;
        }
    }

    /**
     * The auth items that access is always  allowed. Configured in rbac module's
     * configuration
     * @return The always allowed auth items
     */
    protected function allowedAccess() {
        return array(
            'dboard/index', 'site/s', 'site/index', 'site/error', 'site/contact', 'site/login', 'site/logout', 'site/switchwidth', 'site/updateoperation', 'chart/report', 'chart/rf', 'rf/rf/info', 'qa/qainspection/qaexport'
        );
    }

    protected function log($access = "") {
        if (Yii::app()->user->isGuest) {
            return;
        }
        if ($access != "") {
            $operation = $access;
        } else {
            $mod = $this->module !== null ? $this->module->id . '/' : "";
            $operation = $mod . $this->id . '/' . $this->action->id;
        }
        $unLog = self::unLogAccess();
        if (in_array($operation, $unLog)) {
            return;
        }
        $user_id = Yii::app()->user->id;
        $user_name = Yii::app()->user->name;
        $user_type = 1;
        $ip_add = $_SERVER['REMOTE_ADDR'];

        $request = json_encode($_REQUEST);
        //print_r($request);
        $split = "\u01";
        $message = $user_id . $split . $user_name . $split . $user_type . $split . $ip_add . $split . $operation . $split . $request;
        //Yii::app()->redis->getClient()->publish(Yii::app()->params['logChannel'],$message);
    }

    protected function unLogAccess() {
        return array(
            'log/systemlog/list',
            'log/systemlog/grid',
            'log/systemlog/detail'
        );
    }


}
