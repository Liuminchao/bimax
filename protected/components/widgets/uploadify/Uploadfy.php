<?php
/**
 *@Name Uploadfy.php
 *@Author Connor <caokang@foxmail.com>
 *@Copyright Copyright &copy;  2012 
 *@Since 2012-7-27
 *@Todo 多文件上传插件
 */
class Uploadfy extends CWidget{
	public $uploader;
	public $statics;
	public $maxsize = '2MB';
	public $type;
	public $wid;
	public function init(){
		if($this->statics==null){
			$path=dirname(__FILE__).DIRECTORY_SEPARATOR.'static';
			$this->statics=Yii::app()->getAssetManager()->publish($path);
		}
		
		parent::init();
	}
	public function run(){
// 	echo $this->wid;
		$this->render('add',array('maxsize'=>$this->maxsize,));
		
	}
}