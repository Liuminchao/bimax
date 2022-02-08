<?php
/**
 *@Name HighCharts.php
 *@Author Connor <caokang@foxmail.com>
 *@Copyright Copyright &copy;  2012 
 *@Since 2012-7-17
 */
 class HighCharts extends CWidget{
 	
 	public $jspath;
 	public $type='line_basic';
 	public $unit;
 	public $data;
//  	public $title;
 	
 	public function init(){
 		if($this->jspath==null){
 			$path = dirname(__FILE__).DIRECTORY_SEPARATOR.'js';
 			$this->jspath=Yii::app()->getAssetManager()->publish($path);
 		}
 		
 		parent::init();
 	}
 	
 	public function run(){
//  		print_r($this->data);
		$this->data['unit']=$this->unit;
 		$this->render($this->type,$this->data);
 	}
 	
 }