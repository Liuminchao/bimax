<?php
/**
 * Created by PhpStorm.
 * User: 123456
 * Date: 2018/9/20
 * Time: 11:15
 * @author sunjiaqiang
 * @email 1355049422@qq.com
 */
class Ftp extends CActiveRecord {
    const host='42.61.50.48';//远程服务器地址
    const user='Cmstech';//ftp用户名
    const pass='CMS12345$';//ftp密码
    const port=21;//ftp登录端口
    const success='';
    const error='';

    /**
     * 上传文件到ftp服务器
     * @param string $local_file 本地文件路径
     * @param string $remote_file 服务器文件地址
     * @param bool $permissions 文件夹权限
     * @param string $mode 上传模式(ascii和binary其中之一)
     */
    public static function upload($local_file='',$remote_file='',$file='',$mode='auto',$permissions=NULL){

        $host = self::host;
        $user = self::user;
        $pass = self::pass;
        $port = self::port;
        $success = self::success;
        $error = self::error;
        //设置基础连接
        $conn_id=ftp_connect($host);
        //用指定用户名密码登录到ftp服务器
        $login_result=ftp_login($conn_id,$user,$pass);
        //检查连接是否成功
        if((!$conn_id)||(!$login_result))
        {
            die("ftp connection has failed !");
        }
        echo "current directory:", ftp_pwd($conn_id),"n";

        if ($mode == 'auto'){
            $ext = self::_get_ext($local_file);
            $mode = self::_set_type($ext);
        }
        //创建文件夹
        self::_create_remote_dir($conn_id,$remote_file);
        $mode = ($mode == 'ascii') ? FTP_ASCII : FTP_BINARY;
        $remote_str = $remote_file.'/'.$file;
        $local_file = '/opt/www-nginx/web'.$local_file;
//        var_dump($local_file);
//        exit;
        //开启被动模式
        ftp_pasv($conn_id,TRUE);
        $record_time = date('Y-m-d H:i:s');
        $txt = '['.$record_time.']'.'  '.'local: '.$local_file.' remote: '.$remote_str;
        self::write_log($txt);
        $result = ftp_put($conn_id,$remote_str,$local_file,$mode);//同步上传
        if ($result === FALSE){
            $error = "文件上传失败";
            $record_time = date('Y-m-d H:i:s');
            $txt = '['.$record_time.']'.$error;
            self::write_log($txt);
            echo $error;
        }
        if ($result == 1){
            $success = "文件上传成功";
            $record_time = date('Y-m-d H:i:s');
            $txt = '['.$record_time.']'.$success;
            self::write_log($txt);
            echo $success;
        }
        return TRUE;
    }


    /**
     * ftp创建多级目录
     * @param string $remote_file 要上传的远程图片地址
     */
    private function _create_remote_dir($conn_id,$remote_file='',$permissions=NULL){
//        $remote_dir = dirname($remote_file);
        $path_arr = explode('/',$remote_file); // 取目录数组
        //$file_name = array_pop($path_arr); // 弹出文件名
        $path_div = count($path_arr); // 取层数
        foreach($path_arr as $val) // 创建目录
        {
            if(@ftp_chdir($conn_id,$val) == FALSE)
            {
                $tmp = @ftp_mkdir($conn_id,$val);//此处创建目录时不用使用绝对路径(不要使用:2018-02-20/ceshi/ceshi2，这种路径)，因为下面ftp_chdir已经已经把目录切换成当前目录
                if($tmp == FALSE)
                {
                    echo "目录创建失败，请检查权限及路径是否正确！";
                    exit;
                }
                if ($permissions !== NULL){
                    //修改目录权限
                    $this->_chmod($val,$permissions);
                }
                @ftp_chdir($conn_id,$val);
            }
        }

        for($i=0;$i<$path_div;$i++) // 回退到根,因为上面的目录切换导致当前目录不在根目录
        {
            @ftp_cdup($conn_id);
        }
    }

    private function write_log($data){
        $years = date('Y-m');
        //设置路径目录信息
        $url = '/opt/www-nginx/web/test/ctmgr/ftp_log/'.date('Ymd').'_log.log';
        $dir_name=dirname($url);
        //目录不存在就创建
        if(!file_exists($dir_name))
        {
            //iconv防止中文名乱码
            $res = mkdir(iconv("UTF-8", "GBK", $dir_name),0777,true);
        }
        $fp = fopen($url,"a");//打开文件资源通道 不存在则自动创建
        fwrite($fp,var_export($data,true)."\r\n");//写入文件
        fclose($fp);//关闭资源通道
    }

    /**
     * 递归删除ftp端目录
     * @param string $remote_dir ftp目录地址
     */
    public function delete_dir($remote_dir = null){
        $list = $this->list_file($remote_dir);
        if ( ! empty($list)){
            $count = count($list);
            for ($i=0;$i<$count;$i++){
                if ( ! preg_match('#\.#',$list[$i]) && !@ftp_delete($this->conn,$list[$i])){
                    //这是一个目录，递归删除
                    $this->delete_dir($list[$i]);
                }else{
                    $this->delete_file($list[$i]);
                }
            }
        }
        if (@ftp_rmdir($this->conn,$remote_dir) === FALSE){
            return FALSE;
        }
        return TRUE;
    }

    /**
     * 更改 FTP 服务器上的文件或目录名
     * @param string $old_file 旧文件/文件夹名
     * @param string $new_file 新文件/文件夹名
     */
    public function remane($old_file = null,$new_file = null){
        $result = @ftp_rename($this->conn,$old_file,$new_file);
        if ($result === FALSE){
            $this->error = "移动失败";
            return FALSE;
        }
        return TRUE;
    }

    /**
     * 列出ftp指定目录
     * @param string $remote_path
     */
    public function list_file($remote_path = null){
        $contents = @ftp_nlist($this->conn, $remote_path);
        return $contents;
    }

    /**
     * 获取文件的后缀名
     * @param string $local_file
     */
    private function _get_ext($local_file=''){
        return (($dot = strrpos($local_file,'.'))==FALSE) ? 'txt' : substr($local_file,$dot+1);
    }

    /**
     * 根据文件后缀获取上传编码
     * @param string $ext
     */
    private function _set_type($ext=''){
        //如果传输的文件是文本文件，可以使用ASCII模式，如果不是文本文件，最好使用BINARY模式传输。
        return in_array($ext, ['txt', 'text', 'php', 'phps', 'php4', 'js', 'css', 'htm', 'html', 'phtml', 'shtml', 'log', 'xml'], TRUE) ? 'ascii' : 'binary';
    }

    /**
     * 修改目录权限
     * @param $path 目录路径
     * @param int $mode 权限值
     */
    private function _chmod($path,$mode=0755){
        if (FALSE == @ftp_chmod($this->conn,$path,$mode)){
            return FALSE;
        }
        return TRUE;
    }

    /**
     * 登录Ftp服务器
     */
    private function _login(){
        return @ftp_login($this->conn,$this->user,$this->pass);
    }

    /**
     * 获取上传错误信息
     */
    public function get_error_msg(){
        return $this->error;
    }
    /**
     * 关闭ftp连接
     * @return bool
     */
    public function close(){
        return $this->conn ? @ftp_close($this->conn_id) : FALSE;
    }
}