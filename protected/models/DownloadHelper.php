<?php
class DownloadHelper
{
private $fileName;
private $fileSize;
public function __construct($fileName)
{
$this->fileName=$fileName;
if(!file_exists($this->fileName))
{
die("文件不存在");

}
$this->fileSize=filesize($this->fileName);
}
public function fileDownload()
{
$fp=fopen($this->fileName,'r');

//下载文件需要的头
header("Content-type:application/octet-stream");
header("Accept-Ranges:bytes");
header("Accept-Length:$this->fileSize");
header("Content-Disposition:attachment;filename=".$this->fileName);

$fileCount=0;
$fileUnit=1024;
while(!feof($fp)&&$this->fileSize-$fileCount>0)
{
$fileContent=fread($fp,$fileUnit);
echo $fileContent;
$fileCount+=$fileUnit;

}

fclose($fp);

}






}
