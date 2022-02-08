<?php
class Dms {

    /**
     * 公司加入dms
     * @return bool|resource
     */
    public static function NewContractor($contractor_id){
        //{“orgid”:”1001”,”orgname”:”orgtest”}
        $con_model =Contractor::model()->findByPk($contractor_id);
        $contractor_name = str_replace(' ','', $con_model->contractor_name);
//        $contractor_name = $con_model->contractor_name;
        $contractor_name = substr($contractor_name,0,10);
        $data = array(
            'orgid' => $contractor_id,
            'orgname' => $contractor_name,
            'folder'  => $contractor_name
        );
        foreach ($data as $key => $value) {
            $post_data[$key] = $value;
        }

        $post_data = json_encode($post_data);

        $publicEncrypt =  Rsa::publicEncrypt($post_data);
        $post['orginfo'] = $publicEncrypt;
        $url = "https://roboxz.cmstech.sg/dmsapi/api/neworg";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true); //post提交
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
        // 3. 执行并获取HTML文档内容
        $output = curl_exec($ch);
        $rs = json_decode($output,true);
        return $rs;
    }

    /**
     * 编辑公司
     * @return bool|resource
     */
    public static function EditContractor($contractor_id,$contractor_name){
        //{“orgid”:”1001”,”orgname”:”orgtest”}
        $con_model =Contractor::model()->findByPk($contractor_id);
        $contractor_name = str_replace(' ','', $con_model->contractor_name);
        $contractor_name = $con_model->contractor_name;
        $contractor_name = substr($contractor_name,0,10);
        $data = array(
            'orgid' => $contractor_id,
            'orgname' => $contractor_name
        );
        foreach ($data as $key => $value) {
            $post_data[$key] = $value;
        }
        $post_data = json_encode($post_data);
        $publicEncrypt =  Rsa::publicEncrypt($post_data);
        $post['orginfo'] = $publicEncrypt;
        $url = "https://roboxz.cmstech.sg/dmsapi/api/editorg";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true); //post提交
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
        // 3. 执行并获取HTML文档内容
        $output = curl_exec($ch);
        $rs = json_decode($output,true);
        return $rs;
    }

    /**
     * 项目加入dms
     * @return bool|resource
     */
    public static function NewProgram($program_id,$program_name){
        //{“orgid”:”1001”,”orgname”:”orgtest”}
        $pro_model =Program::model()->findByPk($program_id);
        $program_name = str_replace(' ','', $pro_model->program_name);
        $program_name = substr($program_name,0,10);
        $program_len = strlen($program_name);
        $data = array(
            'groupid' => $program_id,
//            'folder'  => $program_name,
        );
        foreach($data as $key => $value){
            if(is_string($value)){
                $encode = mb_detect_encoding($value,array('ASCII','UTF-8','GB2312','GBK','BIG5'));
                if($encode != 'UTF-8'){
                    $data[$key] = mb_convert_encoding($value ,'utf-8' , $encode);
                }
            }
        }
        $post_data = json_encode($data,JSON_UNESCAPED_UNICODE);
        $publicEncrypt =  Rsa::publicEncrypt($post_data);
        $post['groupinfo'] = $publicEncrypt;
        $url = "https://roboxz.cmstech.sg/dmsapi/api/newgroup";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true); //post提交
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
        // 3. 执行并获取HTML文档内容
        $output = curl_exec($ch);
        $rs = json_decode($output,true);
        return $rs;
    }

    /**
     * 人员加入dms
     * @return bool|resource
     */
    public static function NewUser($user_id,$program_id= null){
        //{“userid”:”1001”,”password”:”orgtest”,”email”:abc@126.com,”,”groupid”:”Team A”,”orgid”:”1001”}
        $staff_model =Staff::model()->findByPk($user_id);
        $user_name = $staff_model->user_name;
        $email = $staff_model->primary_email;
        $contractor_id = $staff_model->contractor_id;
        $con_model =Contractor::model()->findByPk($contractor_id);
        $contractor_name = $con_model->contractor_name;
        $group_list[] = $contractor_id;
        if($program_id){
            $group_list[] = $program_id;
        }
        $data = array(
            'userid' => $user_id,
            'password' => 'dms123456',
            'email' => $email,
            'groups' => $group_list,
            'orgid' => $contractor_id,
//            'displayName' => $user_name.'-'.$contractor_name,
            'displayName' => $user_name,
            'quota' => '5GB'
        );
        $post_data = json_encode($data);

        $publicEncrypt =  Rsa::publicEncrypt($post_data);

        $post['userinfo'] = $publicEncrypt;
        $url = "https://roboxz.cmstech.sg/dmsapi/api/newuser";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true); //post提交
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
        // 3. 执行并获取HTML文档内容
        $output = curl_exec($ch);
        $rs = json_decode($output,true);
        return $rs;
    }

    /**
     * 人员加入Group
     * @return bool|resource
     */
    public static function AddGroup($program_id,$user_id){
        //{“userid”:”1001”,”password”:”orgtest”,”email”:abc@126.com,”,”groupid”:”Team A”,”orgid”:”1001”}
        $data = array(
            'userid' => $user_id,
            'groupid' => $program_id
        );
        foreach ($data as $key => $value) {
            $post_data[$key] = $value;
        }
        $post_data = json_encode($post_data);
//        var_dump($post_data);
        $publicEncrypt =  Rsa::publicEncrypt($post_data);

        $post['userinfo'] = $publicEncrypt;
        $url = "https://roboxz.cmstech.sg/dmsapi/api/addgroup";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true); //post提交
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
        // 3. 执行并获取HTML文档内容
        $output = curl_exec($ch);
        $rs = json_decode($output,true);
        return $rs;
    }

    /**
     * 把人员从Group移除
     * @return bool|resource
     */
    public static function RemoveGroup($program_id,$user_id){
        //{“userid”:”1001”,”password”:”orgtest”,”email”:abc@126.com,”,”groupid”:”Team A”,”orgid”:”1001”}
        $data = array(
            'userid' => $user_id,
            'groupid' => $program_id
        );
        foreach ($data as $key => $value) {
            $post_data[$key] = $value;
        }
        $post_data = json_encode($post_data);

        $publicEncrypt =  Rsa::publicEncrypt($post_data);

        $post['userinfo'] = $publicEncrypt;
        $url = "https://roboxz.cmstech.sg/dmsapi/api/removegroup";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true); //post提交
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
        // 3. 执行并获取HTML文档内容
        $output = curl_exec($ch);
        $rs = json_decode($output,true);
        return $rs;
    }

    /**
     * 人员单点登录
     * @return bool|resource
     */
    public static function login($user_id){
        //{“userid”:”1001”,”password”:”orgtest”,”email”:abc@126.com,”,”groupid”:”Team A”,”orgid”:”1001”}
//        $user_id = '21634';
//        $user_id = '212_admin';
        $publicEncrypt =  Rsa::publicEncrypt($user_id);

        $post['userid'] = $publicEncrypt;
        $url = "https://roboxz.cmstech.sg/dmsapi/api/login";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true); //post提交
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
        // 3. 执行并获取HTML文档内容
        $output = curl_exec($ch);
        $rs = json_decode($output,true);
        $path = Rsa::publicDecrypt($rs['login']);
        return $path;
    }

    /**
     * 文件上传
     * @return bool|resource
     */
    public static function publish($file){

        $data = array(
            'userid' => $file['user_id'],
            'upload_file_url' => $file['path'],
            'upload_path' => $file['upload_path'],
            'groupid' => $file['program_id'],
            'file_name'=>$file['name']
        );
        $post_data = json_encode($data);

        $publicEncrypt =  Rsa::publicEncrypt($post_data);

        $post['fileinfo'] = $publicEncrypt;
        $url = "https://roboxz.cmstech.sg/dmsapi/api/upload";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true); //post提交
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
        // 3. 执行并获取HTML文档内容
        $output = curl_exec($ch);
        $rs = json_decode($output,true);
        return $rs;
    }

    /**
     * 文件目录
     * @return bool|resource
     */
    public static function Filepath($program_id,$user_id){

        $data = array(
            'userid' => $user_id,
            'groupid' => $program_id
        );
        foreach ($data as $key => $value) {
            $post_data[$key] = $value;
        }
        $post_data = json_encode($post_data);
        $publicEncrypt =  Rsa::publicEncrypt($post_data);
        $post['userinfo'] = $publicEncrypt;
        $url = "https://roboxz.cmstech.sg/dmsapi/api/userfolders";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true); //post提交
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
        // 3. 执行并获取HTML文档内容
        $output = curl_exec($ch);
        $rs = json_decode($output,true);
        var_dump($output);
        exit;
        return $rs;
    }
}