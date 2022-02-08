<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */ 
class UserIdentity extends CUserIdentity {

    public $usertype;
    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changecontractor_idd to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
     */
    public function authenticate() {
        $session = Yii::app()->session;
        $session->open();

        return $this->auth_system();
    }

    public function auth_system() {
//        if($this->usertype == '01'){
//            $comp = Contractor::model()->find('company_sn=:company_sn', array(':company_sn' => $this->username));                
//            if ($comp == null) {
//                $this->errorCode = self::ERROR_USERNAME_INVALID;
//                return false;
//            }
//        }    
        //$user = Operator::model()->find('operator_id=:id AND operator_type=:type', array(':id' => $this->username,':type'=>$this->usertype));

//        if ($user == null) {
//            //承包商管理员也可以用企业注册号登录
//            if($this->usertype == '01'){
//                $comp = Contractor::model()->find('company_sn=:company_sn', array(':company_sn' => $this->username));
//                
//                if ($comp == null) {
//                    $this->errorCode = self::ERROR_USERNAME_INVALID;
//                    return false;
//                }
//                $user = Operator::model()->find('contractor_id=:contractor_id AND operator_type=:type', array(':contractor_id' => $comp->contractor_id,':type'=>$this->usertype));
//                if ($user == null) {
//                    $this->errorCode = self::ERROR_USERNAME_INVALID;
//                    return false; 
//                }
//                
//                $this->username = $user->operator_id;
//            }
//        }
        $user = Operator::model()->find('operator_id=:id AND operator_type=01', array(':id' => $this->username));
        if(empty($user)){
            $user = Operator::model()->find('operator_id=:id AND operator_type=00', array(':id' => $this->username));
        }
        if ($user->status != Operator::STATUS_NORMAL) {
            $this->errorCode = 201;
            return false;
        }
//        var_dump(md5($this->password));
//        var_dump($user->passwd);
//        exit;
//        if (md5($this->password) != $user->passwd) {
//            $this->errorCode = self::ERROR_PASSWORD_INVALID;
//            return false;
//        }
//        $auths = Auth::GetMyAuth($user->role_id);
        $auths = Auth::GetMyAuth($this->username);
        if ($auths == null) {
            $this->errorCode = 200;
            return false;
        }
        $coninfo = Contractor::model()->findByPk($user->contractor_id);
//        var_dump($user);exit;
        Yii::app()->user->id = $this->username;
        Yii::app()->user->setState('name', $user->name);
        Yii::app()->user->setState('operator_type', $user->operator_type);
        Yii::app()->user->setState('operator_role', $user->operator_role);
        Yii::app()->user->setState('contractor_id', $user->contractor_id);
        Yii::app()->user->setState('contractor_name', $coninfo->contractor_name);
        Yii::app()->user->setState('contractor_info', $coninfo);
        Yii::app()->user->setState('role_id',$user->role_id);
        Yii::app()->user->setState('auths', $auths);
        //var_dump(Yii::app()->user->id);exit;
        $this->errorCode = self::ERROR_NONE;
        
        return !$this->errorCode;
    }

}
