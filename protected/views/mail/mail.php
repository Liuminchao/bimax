<style type="text/css">
    li,ul {
        -moz-text-size-adjust: none;
        list-style: outside none none;
        margin: 0;
        padding: 0;
    }
    .formInput {
        padding-top: 60px;
        margin-top: 15px;
        overflow: hidden;
    }
    .formInput li {
        margin-top: 16px;
        position: relative;
        overflow: hidden;
        overflow-x: hidden;
        overflow-y: hidden;
    }
    li {
        display: list-item;
        text-align: -webkit-match-parent;
    }
    .getcode {
        padding-bottom: 30px;;
        border: 0;
        position: absolute;
        right: 39px;
        top: 5px;
        display: block;
        width: 110px;
        height: 26px;
        text-align: center;
        background: #F6F6F6;
        border-radius: 3px;
        font-size: 14px;
        line-height: 34px;
        color: #CFCFCF;
    }
    .formInput li input.passport-login-input {
        width: 200px;
        height: 30px;
        line-height: 50px\0;
        background: #FFF;
        border: 2px solid #EBEBEB;
        border-radius: 2px;
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        font-size: 16px;
        color: #000;
        padding-left: 15px;
        margin-bottom: 14px;
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
        <!--    <img src="img/login/sketch.jpg" id="loginBg" class="sketch" />-->
    </div>
    <div id="main">
        <div class="logo">
            <span class="logoTxt">CMS  <?php echo Yii::t('common', 'send_mail'); ?></span>
        </div>
        <div id="wrap">
            <form class="form-horizontal" action="index.php?r=mail/submit" id="loginForm" method="post">
                <!--            <div class="control-group" style="height: 40px">-->
                <!--<!--                <div class="controls">-->
                <!--                    <input type="text" id="code" name="LoginForm[code]" style="height: 40px" placeholder="--><?php //echo Yii::t('common', 'input code'); ?><!--">-->
                <!--                    <input type="button" id="btn" class="btn btn-primary" onclick="send(this)" value="--><?php //echo Yii::t('common', 'Get verification code'); ?><!--"  />-->
                <!--<!--                </div>-->
                <!--            </div>-->
                <!--            <div class="control-group">-->
                <!--                <label class="control-label"></label>-->
                <!--                <div class="controls" style="text-align: center">-->
                <!--                    <button type="submit" class="btn btn-success" >--><?php //echo Yii::t('common', 'button_post'); ?><!--</button>-->
                <!--                </div>-->
                <!--            </div>-->
                <ul id="userlogin_ul" class="formInput">
                    <li class="mobileLoginLi mobilenumberLi">
                        <input id="loginMobile" class="passport-login-input inputNull" size="20" value="" maxlength="11" name="LoginForm[code]" placeholder="<?php echo Yii::t('common', 'input code'); ?>" type="text">
                        <input id="loginMobilecodeSendBtn" class="getcode"  value="<?php echo Yii::t('common', 'Get verification code'); ?>" onclick="send(this)" type="button">
                    </li>
                    <li class="submit_new">
                        <button type="submit" style="padding-left: 90px;padding-right: 90px;background-image: linear-gradient(to bottom, #3ABDC7, #3ABDC7);" class="login_btn" ><?php echo Yii::t('common', 'button_post'); ?></button>
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <!--    <img src="img/login/gray.png" id="loginBg" />-->
    <div id="bottom" class="login_bottom">
        <p class="copyright" >
            <?php echo Yii::t('login', 'copyright'); ?><br/>
            copyright © 2016 CMS Data Technology Pte.Ltd.All rights reserved
        </p>
    </div>
</div>
<script type="text/javascript">
    var countdown=60;
    function settime(obj) {
        if (countdown == 0) {
            obj.removeAttribute("disabled");
            obj.value="<?php echo Yii::t('common', 'Get verification code'); ?>";
            countdown = 60;
            return;
        } else {
            obj.setAttribute("disabled", true);
            obj.value="<?php echo Yii::t('common', 'resend'); ?>+(" + countdown + ")";
            countdown--;
        }
        setTimeout(function() {
                settime(obj) }
            ,1000)
    }
    var send = function(obj){
        $.ajax({
            type: "POST",
            url: "index.php?r=mail/send",
            data: {tag:1},
            dataType: "json",
            success: function(data){
//                alert(data.num);
                settime(obj);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert(XMLHttpRequest.status);
                alert(XMLHttpRequest.readyState);
                alert(XMLHttpRequest.responseText);
            },
        });
    }
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
</script>