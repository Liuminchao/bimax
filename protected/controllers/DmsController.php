<?php
/*
 * DMS:
 */
class DmsController extends BaseController
{
    public $bigMenu = '';

    public $dms_base_url='';

    public $layout = '//layouts/main_1';

    public $attend_base_url = '';
    
    public function init()
    {
        parent::init();
        $this->bigMenu = 'DMS';
        $this->contentHeader = 'DMS';
        
        $base_url = 'https://roboxz.cmstech.sg/index.php/login';
        $record_time = date("dmY");
        $base_url .= '?sso=true';
        $base_url .= '&user=admin';
//        $user_id = Yii::app()->user->id;
        $user_id = 'admin';
        $token = sha1($record_time);
        $base_url .= '&token='.$token;
        $this->attend_base_url = $base_url;

        //http://roboxz.cmstech.sg/index.php/login/index.php?sso=true&user=HongMi&token=43cf1d2ff918f2773b56a1f372bd57960a5057e2
    }
    /*public function accessRules()
    {
        return array(
            array('allow',
                'users' => array('@'),
            ),
            array('deny',
                'actions' => array(),
            ),
        );
    }*/
    
    //默认
    public function actionIndex()
	{		
        $this -> smallHeader = 'DMS';
            
		$url = $this->attend_base_url;//var_dump($url);
        $this->render('index',array('goUrl'=>$url));
	}



}