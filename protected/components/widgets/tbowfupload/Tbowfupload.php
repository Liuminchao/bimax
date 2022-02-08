<?php
/**
 *@Name Tbowfupload.php
 *@Author Connor <sudunkuai@gmail.com>
 *@Copyright Copyright &copy;  2012 
 *@Since 2012-7-27
 *@Todo 文件拍照上传
 */
class Tbowfupload extends CWidget{
	public $url;
	public $statics;
	public $maxsize = '2MB';
	public function init(){
		if($this->statics==null){
			$path=dirname(__FILE__).DIRECTORY_SEPARATOR.'static';
			$this->statics=Yii::app()->getAssetManager()->publish($path);
		}
		parent::init();
	}
	public function run(){
		$this->render('fupload',array());
	}
}