<?php
/**
 *@Name add.php
 *@Author Connor <caokang@foxmail.com>
 *@Copyright Copyright &copy;  2012 
 *@Since 2012-7-27
 */
 ?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="<?php echo $this->statics?>/uploadify.css">
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo $this->statics?>/jquery.uploadify-3.1.min.js"></script>
	<script type="text/javascript">
	$(function() {
		$('#uploadify').uploadify({
			'auto'		:false,
			'swf'      : '<?php echo $this->statics?>/uploadify.swf',
			'uploader' : '<?php echo $this->uploader?>',
			//'height'   : 16,
			//'width':16,
			 'fileSizeLimit' : '<?php echo $this->maxsize;?>',
			'onUploadError' : function(file, errorCode, errorMsg, errorString,data) {
            alert('文件上传失败: ' + errorMsg);},
			//'buttonClass':'replacement-1',
			'buttonText':'选择文件',
			//'buttonImage' : 'b_snewtbl.png',
		
            'multi': true,
            'formData':{ '<?php echo session_name();?>':'<?php echo session_id();?>','type':'<?php echo $this->type;?>','wid':'<?php echo $this->wid;?>'},
			'onUploadSuccess' : function(file, data, response) {
				if(response=='1'){
					alert('文件上传成功');
				}else
            alert('上传失败 ：' + data);
			},
			// Your options here
		});
	});
	</script>

</head>
<body>
<div class="photoup">
    <input  type="file" name="file_upload" id="uploadify" />
    <span class="sBtn">
		<a href="javascript:$('#uploadify').uploadify('upload','*')" class="left">上传</a><a class="right"></a>
	</span>    
    <span class="sBtn-cancel">
		<a href="javascript:$('#uploadify').uploadify('cancel')" class="left">取消上传</a><a class="right"></a>
	</span>    
</div>
</body>
</html>