<?php

Yii::import('application.controllers.site.*');

class ReportController extends BaseController {

    /**
     * Declares class-based actions.
     */
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

//    public function init() {
//
//       if (isset($_REQUEST['lang']) && $_REQUEST['lang'] != "") {   //通过lang参数识别语言
//            Yii::app()->language = $_REQUEST['lang'];
//            setcookie('lang', $_REQUEST['lang']);
//        } else if (isset($_COOKIE['lang']) && $_COOKIE['lang'] != "") {   //通过$_COOKIE['lang']识别语言
//            Yii::app()->language = $_COOKIE['lang'];
//        } else {   //通过系统或浏览器识别语言
//            $lang = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
//            Yii::app()->language = strtolower(str_replace('-', '_', $lang[0]));
//        }
//    }

    public function accessRules() {
        return array(
            array('allow',
                'actions' => array('captcha', 'index', 'login', '401', 'error','lang'),
                'users' => array('*'),
            ),
            array('allow',
                'users' => array('@'),
            ),
            array('deny',
                'actions' => array(),
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
//    public function actionIndex() {
////        $app = $_GET['name'];
////        echo $app;
////        var_dump(11111);
////        exit;
//        if (Yii::app()->user->isGuest) {
//            return $this->actionLogin();
//        }
//        $this->redirect('index.php?r=dboard');
//    }

    public function actionIndex() {
        $id = $_REQUEST['id'];
        $params['id'] = $id;
        $app_id = $_REQUEST['app_id'];

        $filepath = DownloadPdf::transferDownload($params,$app_id);

//        Utils::Download($filepath, $title, 'pdf');
        return $filepath;
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->renderPartial('error', $error);
        }
    }


    /**
     * Displays the login page
     */
    public function actionLogin() {

        if (isset($_POST['LoginForm'])) {

            $form = $_POST['LoginForm'];
            $hasErrors = false;
            if ($form['username'] == '' || $form['passwd'] == '') {
                $message = Yii::t('sys_operator', 'Error User is empty');
                $hasErrors = true;
            } elseif ($form['captcha'] != $this->createAction('captcha')->getVerifyCode()) {
                $message = Yii::t('sys_operator', 'Error Code is wrong');
                $hasErrors = true;
            }
            if (!$hasErrors) {

                $identity = new UserIdentity(trim($form['username']), trim($form['passwd']));

                $identity->usertype = $form['usertype'];

                $identity->authenticate();

                switch ($identity->errorCode) {
                    case UserIdentity::ERROR_NONE:
                        $duration = isset($form['rememberMe']) ? 3600 * 24 * 1 : 0; // 1 day
                        $duration = 0;
                        Yii::app()->user->login($identity, $duration);
//                        if ($duration) {
//                            setcookie('login_id',$form['username'], time() + $duration, Yii::app()->request->baseUrl);
//                        } else {
//                            unset($_COOKIE['login_id']);
//                            setcookie('login_id', NULL, -1);
//                        }
                        //修改登录时间和IP
                        //var_dump(Yii::app()->user->id);exit;
                        Operator::updateOperatorLogin(trim($form['username']));
                        $this->redirect(Yii::app()->user->returnUrl);
                        return ;
                        break;
                    case UserIdentity::ERROR_USERNAME_INVALID:
                        $message = Yii::t('common', 'Error User is wrong'); //用户名或密码错误！
                        break;
                    case UserIdentity::ERROR_PASSWORD_INVALID:
                        $message = Yii::t('common', 'Error User is wrong'); //用户名或密码错误！
                        break;
                    case 203:
                        $message = Yii::t('common', 'Error Without permission'); //没有权限
                        break;
                    case 201:
                        $message = Yii::t('common', 'Error User is freeze'); //用户已被冻结！
                        break;
                    default: // UserIdentity::ERROR_PASSWORD_INVALID
                        $message = Yii::t('common', 'Error User is wrong'); //用户名或密码错误！
                        break;
                }
            }
        }
        $this->layout = '//layouts/login';
        $this->render('login', array('form' => $form, 'message' => $message));
    }

    /**
     * 中英语言切换
     */
    public function actionLang() {

        if ($_REQUEST['confirm']) {
            $lang = trim($_REQUEST['_lang']);
            Yii::app()->language = $lang;
            setcookie('lang', $lang);
        }

        echo json_encode(true);
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect("./index.php");
    }

}
