<style type="text/css">
    input{
        font-size: 16px;
    }
</style>
<div id="login_all" class="all">
    <div id="login_sketch">
        <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
            <div>
                <ul class="nav navbar-nav pull-right">
                    <li><a href="#" onclick="changeLanguage('zh_CN')"><p id="lang_zh" <?php if (Yii::app()->language == 'zh_CN') { ?>class="lang_active" <?php } ?>>中文</p></a></li>
                    <li><a href="#"><p class="lang_active">|</p></a></li>
                    <li><a href="#" onclick="changeLanguage('en_US')"><p id="lang_en" <?php if (Yii::app()->language == 'en_US') { ?>class="lang_active" <?php } ?>>English</p></a></li>
                </ul>
            </div>
        </nav>
    <!-- <img src="img/login/gray.png" id="loginBg" class="sketch" /> -->
    </div>
    <div id="main">
        <div class="logo">
            <span class="logoTxt"><?php echo Yii::t('login', 'Website Name'); ?></span>
            <span class="logoVersion" style="font-size: 20px;padding-left: 10px"><?php echo Yii::app()->params['version'];  ?></span>
        </div>
    <div id="wrap">
        <form class="form-horizontal"  action="index.php?r=site/login" id="loginForm" method="post">
<!--            <div class="control-group clearfix infoBox">-->
                <div class="control-group error-msg" name="error-msg" style="padding-top: 40px">
                    <?php echo $message; ?>
                </div>
<!--            </div>-->
            <!--<div class="control-group clearfix">
                <label class="control-label" for="inputUsername"><?php echo Yii::t('login', 'User Type'); ?></label>
                <div class="controls">
                    <select name="LoginForm[usertype]" id="inputType" onchange=javascript:changeType(this.value);>
                        <?php
            $type_list = Operator::typeText();
            foreach($type_list as $typeid => $typename){
                echo "<option value='".$typeid."'>".$typename."</option>";
            }
            ?>
                    </select>
                </div>
            </div>-->
            <div id="user" class="control-group clearfix" >
<!--                <label class="control-label" for="inputUsername">--><?php //echo Yii::t('login', 'User ID'); ?><!--</label>-->
                <i class="ico-user"></i>
                <input class="inpu" type="text" style="padding-left: 40px;background-color: #EAEAEA" name="LoginForm[username]" id="inputUsername" placeholder="<?php echo Yii::t('comp_contractor', 'company_sn'); ?>">
            </div>
            <div id="password" class="control-group" style="padding-top: 10px">
<!--                <label class="control-label" for="inputPassword">--><?php //echo Yii::t('login', 'Password'); ?><!--</label>-->
                <i class="ico-password"></i>
                <input class="inpu"  type="password" style="padding-left: 40px;background-color: #EAEAEA"  name="LoginForm[passwd]" id="inputPassword" placeholder="<?php echo Yii::t('login', 'Password'); ?>">
            </div>
            <div class="control-group" style="padding-top: 10px">
<!--                <label class="control-label" for="inputYzm">--><?php //echo Yii::t('login', 'Code'); ?><!--</label>-->
                    <input style="background-color: #EAEAEA" type="text" name="LoginForm[captcha]" id="inputYzm" placeholder="<?php echo Yii::t('login', 'Code'); ?>" />
                    <img src="index.php?r=site/captcha&<?php echo time(); ?>" class="yzmImg" id="imgyzm"/>
                    <a href="#" class="changeOne" onclick="captcha()"><?php echo Yii::t('login', 'changeOne'); ?></a>
            </div>
            <div class="control-group" style="position:relative;padding-top: 10px">
                <button type="submit" style="height:40px;padding-left: 110px;padding-right: 120px;background-image: linear-gradient(to bottom, #3ABDC7, #3ABDC7);"  class="login_btn" ><?php echo Yii::t('login', 'login'); ?></button>
            </div>
        </form>
    </div>
        </div>
<!--    <img src="img/login/gray.png" id="loginBg" />-->
    <div id="bottom" class="login_bottom">
        
    </div>
    <p class="copyright" >
            <?php echo Yii::t('login', 'copyright'); ?><br/>
            copyright © 2021 CMS Data Technology Pte.Ltd.All rights reserved
    </p>
</div>
<!--<div class="loginBoxBg"></div>-->
<!--<div id="loginBox" class="container clearfix">-->
<!--    <div class="loginMain">-->
<!--        <div class="logo">-->
<!--            <span class="logoTxt">--><?php //echo Yii::t('login', 'Website Name'); ?><!--</span>-->
<!--            <span class="copyright" style="font-size: 16px;">--><?php //echo Yii::app()->params['version'];  ?><!--</span>-->
<!--        </div>-->
<!--        <form class="form-horizontal" action="index.php?r=site/login" id="loginForm" method="post">-->
<!--            <div class="control-group clearfix infoBox">-->
<!--                <label class="control-label"></label>-->
<!--                <div class="info controls" style="line-height:0px;" name="error-msg">-->
<!--                    --><?php //echo $message; ?>
<!--                </div>-->
<!--            </div>-->
<!--            <!--<div class="control-group clearfix">-->
<!--                <label class="control-label" for="inputUsername">--><?php //echo Yii::t('login', 'User Type'); ?><!--</label>-->
<!--                <div class="controls">-->
<!--                    <select name="LoginForm[usertype]" id="inputType" onchange=javascript:changeType(this.value);>-->
<!--                        --><?php
//                        $type_list = Operator::typeText();
//                        foreach($type_list as $typeid => $typename){
//                            echo "<option value='".$typeid."'>".$typename."</option>";
//                        }
//                        ?>
<!--                    </select>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="control-group clearfix">-->
<!--                <label class="control-label" for="inputUsername">--><?php //echo Yii::t('login', 'User ID'); ?><!--</label>-->
<!--                <div class="controls">-->
<!--                    <input type="text" name="LoginForm[username]" id="inputUsername" placeholder="--><?php //echo Yii::t('comp_contractor', 'company_sn'); ?><!--">-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="control-group">-->
<!--                <label class="control-label" for="inputPassword">--><?php //echo Yii::t('login', 'Password'); ?><!--</label>-->
<!--                <div class="controls">-->
<!--                    <input type="password" name="LoginForm[passwd]" id="inputPassword" placeholder="--><?php //echo Yii::t('login', 'Password'); ?><!--">-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="control-group">-->
<!--                <label class="control-label" for="inputYzm">--><?php //echo Yii::t('login', 'Code'); ?><!--</label>-->
<!--                <div class="controls">-->
<!--                    <input type="text" name="LoginForm[captcha]" id="inputYzm" placeholder="--><?php //echo Yii::t('login', 'Code'); ?><!--" />-->
<!--                    <img src="index.php?r=site/captcha&--><?php //echo time(); ?><!--" class="yzmImg" id="imgyzm"/>-->
<!--                    <a href="#" class="changeOne" onclick="captcha()"><span class="icon-repeat icon-white"></span> --><?php //echo Yii::t('login', 'changeOne'); ?><!--</a>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="control-group">-->
<!--                <label class="control-label"></label>-->
<!--                <div class="controls">-->
<!--                    <button type="submit" class="btn btn-success" >--><?php //echo Yii::t('login', 'login'); ?><!--</button>-->
<!--                </div>-->
<!--            </div>-->
<!--        </form>   -->
<!--        <p class="copyright">-->
<?php //echo Yii::t('login', 'copyright'); ?><!--<br/>-->
<!--            copyright © 2016 CMS Data Technology Pte.Ltd.All rights reserved-->
<!--        </p> -->
<!--    </div>-->
<!--</div>-->

<script type="text/javascript">
    var captcha = function () {
        $("#imgyzm").attr("src", "index.php?r=site/captcha&" + new Date().getTime());
        $("input[name='LoginForm[captcha]']").val('');
    }

    $(document).ready(function () {
        if (!!window.ActiveXObject || "ActiveXObject" in window){
            var user_i = document.getElementById("user").children[0];
            user_i.style.setProperty('margin-left','42px');
            var password_i = document.getElementById("password").children[0];
            password_i.style.setProperty('margin-left','42px');
        }
        $('input:text:first').focus();
        $('input').bind("keypress", function (e) {
            /* ENTER PRESSED*/
            if (e.keyCode == 13) {
                /* FOCUS ELEMENT */
                var inputs = $(this).parents("form").find(":input");
                var idx = inputs.index(this);

                if ($(this).attr("name") == 'LoginForm[captcha]') {
                    doLogin();
                    return true;
                } else {
                    inputs[idx + 1].focus(); //  handles submit buttons
                    inputs[idx + 1].select();
                }
                return false;
            }
        });
    });
    var changeLanguage = function (lang) {
        if (lang == 'zh_CN') {
            $("#lang_zh").addClass("lang_active");
            $("#lang_en").removeClass("lang_active");
        }
        else if (lang == 'en_US') {
            $("#lang_en").addClass("lang_active");
            $("#lang_zh").removeClass("lang_active");
        }
        $.ajax({
            data: {_lang: lang, confirm: 1},
            url: "index.php?r=site/lang",
            dataType: "json",
            type: "POST",
            success: function (data) {
                location.reload();
            }
        });
    }
    
    var changeType = function(type){
        //alert(type);
        if (type == '01') {
            $("#inputUsername").attr('placeholder', "<?php echo Yii::t('login', 'User ID').'/'.Yii::t('comp_contractor', 'company_sn'); ?>");
        }else{
            $("#inputUsername").attr('placeholder', "<?php echo Yii::t('login', 'User ID'); ?>");
        }
    }
    
    
</script>
