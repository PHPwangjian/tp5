<?php /*a:2:{s:63:"D:\phpstudy_pro\WWW\tp5\application\index\view\index\login.html";i:1587711076;s:63:"D:\phpstudy_pro\WWW\tp5\application\index\view\public\base.html";i:1587694347;}*/ ?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta name="renderer" content="webkit">
<title></title>
<link rel="stylesheet" href="/static/admin/css/pintuer.css">
<link rel="stylesheet" href="/static/admin/css/admin.css">
<link rel="stylesheet" href="/static/lib/validform/css/style.css">
<script src="/static/admin/js/jquery.js"></script>
<script src="/static/lib/validform/js/Validform_v5.3.2_min.js"></script>
<script src="/static/lib/layer/layer.js"></script>
<script src="/static/admin/js/pintuer.js"></script>
</head>
<body>

 
<div class="bg"></div>
<div class="container">
    <div class="line bouncein">
        <div class="xs6 xm4 xs3-move xm4-move">
            <div style="height:150px;"></div>
            <div class="media media-y margin-big-bottom">
            </div>
            <form action="/login" method="post" id="form">
                <div class="panel loginbox">
                    <div class="text-center margin-big padding-big-top"><h1>在线主题管理中心</h1></div>
                    <div class="panel-body" style="padding:30px; padding-bottom:10px; padding-top:10px;">
                        <div class="form-group">
                            <div class="field field-icon-right">
                                <input type="text" class="input input-big" name="user_name" placeholder="登录账号" datatype="s4-10" />
                                <span class="icon icon-user margin-small"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="field field-icon-right">
                                <input type="password" class="input input-big" name="password" placeholder="登录密码" datatype="s6-8" />
                                <span class="icon icon-key margin-small"></span>
                            </div>
                        </div>

                    </div>
                    <div style="padding:30px;"><input type="submit" class="button button-block bg-main text-big input-big" value="登录"></div>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">

    $("#form").Validform({
        tiptype:4,
        ajaxPost:true,
        callback:function(res){
            if(res.status=='success'){
                layer.msg(res.msg,{icon:1});
                location.href='/addTheme';
            }else{
                layer.msg(res.msg,{icon:2});
            }
        }
    });
</script>

</body>
</html>