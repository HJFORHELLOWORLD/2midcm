<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=1280, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <title>在线进销存</title>
    <link href="<?=skin_url()?>/css/common.css?ver=20140815" rel="stylesheet" type="text/css">
    <link href="<?=skin_url()?>/css/<?=skin()?>/ui.min.css?ver=20140815" rel="stylesheet">
    <script src="<?=skin_url()?>/js/common/libs/jquery/jquery-1.10.2.min.js"></script>
    <script src="<?=skin_url()?>/js/common/libs/json2.js"></script>
    <script src="<?=skin_url()?>/js/common/common.js?ver=20140815"></script>
    <script src="<?=skin_url()?>/js/common/grid.js?ver=20140815"></script>
    <script src="<?=skin_url()?>/js/common/plugins.js?ver=20140815"></script>
    <script src="<?=skin_url()?>/js/common/plugins/jquery.dialog.js?self=true&ver=20140815"></script>
    <script type="text/javascript">
        try{
            document.domain = '<?=base_url()?>';
        }catch(e){
            //console.log(e);
        }
    </script>

    <script type="text/javascript">
        var WDURL = "";
        var SCHEME= "<?=skin()?>";
        var icon_url = "<?=skin_url()?>/css/base/dialog/icons/";
        var settings_customer_manage = "<?=site_url('settings/customer_manage')?>";
        var settings_vendor_manage = "<?=site_url('settings/vendor_manage')?>";
        var settings_vendor_batch = "<?=site_url('settings/vendor_batch')?>";
        var settings_customer_batch = "<?=site_url('settings/customer_batch')?>";
        var basedata_settlement = "<?=site_url('basedata/settlement')?>";
        var settings_settlement_manage = "<?=site_url('settings/settlement_manage')?>";
        var basedata_category = "<?=site_url('basedata/category')?>";
        var basedata_category_type= "<?=site_url('basedata/category_type')?>";
        var settings_goods_manage = "<?=site_url('settings/goods_manage')?>";
        var settings_goods_batch  = "<?=site_url('settings/goods_batch')?>";
        var basedata_goods = "<?=site_url('basedata/goods')?>";
        var basedata_unit  = "<?=site_url('basedata/unit')?>";
        var settings_unit_manage = "<?=site_url('settings/unit_manage')?>";
        var basedata_contact  = "<?=site_url('basedata/contact')?>";
        var settings_inventory =  "<?=site_url('settings/inventory')?>";
        var settings_skins =  "<?=site_url('settings/skins')?>";

        var admin_add = "<?=site_url('admin/add')?>";
        var admin_authority = "<?=site_url('admin/authority')?>";
        var basedata_admin_checkname = "<?=site_url('basedata/admin_checkname')?>";
        var area_add = "<?=site_url('area/add')?>";
        var area_save = "<?=site_url('area/save')?>"
    </script>
    <link href="<?=skin_url()?>/css/authority.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="wrapper authority-wrap">
    <div class="mod-inner">
        <!--<ul class="mod-steps" id="import-steps">
          <li><span class="current">1.新建用户</span>&gt;</li>
          <li><span>2.分配权限</span>&gt;</li>
          <li><span>3.邀请成功</span></li>
        </ul>-->
        <div class="authority-ctn-wrap">
            <div class="register-wrap">
                <h3>新建地区编号</h3>
                <form action="#" id="registerForm" class="register-form">
                    <ul class="mod-form-rows">
<!--                        <li class="row-item">
                            <div class="label-wrap">
                                <label >地区编码</label>
                            </div>
                            <div class="ctn-wrap">
                                <input type="text" class="ui-input" id="number" style="ime-mode:disabled;" onpaste="return false;"/>
                            </div>
                        </li>-->
                        <li class="row-item">
                            <div class="label-wrap">
                                <label >地区名称</label>
                            </div>
                            <div>
                                <input type="text" id="id" class="ui-input" />
                            </div>
                        </li>
                        <li class="row-item">
                            <div class="label-wrap">
                                <label >上级区域</label>
                            </div>
                            <div>
                                <input type="text" id="id" class="ui-input" />
                            </div>
                        </li>
                        <li class="row-item">
                            <div class="label-wrap">
                                <label >创建人</label>
                            </div>
                            <div class="ctn-wrap">
                                <input type="text" id="founder" class="ui-input" />
                            </div>
                        </li>
                        <li class="row-item">
                            <div class="label-wrap">
                                <label for=""> &nbsp;&nbsp;&nbsp;</label>
                            </div>
                            <div class="ctn-wrap">
                                <a href="#" class="ui-btn ui-btn-sp" id="registerBtn">提交</a>
                            </div>
                        </li>
                    </ul>
                    <!--<div class="btn-row">
                      <a href="authority.jsp" class="ui-btn mrb">返回列表</a><a href="#" class="ui-btn ui-btn-sp" id="registerBtn">下一步</a>
                    </div>-->
                </form>
            </div>
            <div>
            </div>
        </div>
        <script src="<?=skin_url()?>/js/dist/arealistadd.js?199"></script>
</body>
</html>