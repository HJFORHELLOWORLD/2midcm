<?php if(!defined('BASEPATH')) exit('No direct script access allowed');?>
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
        var basedata_category_type= "<?=site_url('basedata/category_type')?>";
        var settings_goods_manage = "<?=site_url('settings/goods_manage')?>";
        var settings_goods_batch  = "<?=site_url('settings/goods_batch')?>";
        var basedata_goods = "<?=site_url('basedata/goods')?>";
        var basedata_unit  = "<?=site_url('basedata/unit')?>";
        var settings_unit_manage = "<?=site_url('settings/unit_manage')?>";
        var basedata_contact  = "<?=site_url('basedata/contact')?>";
        var settings_inventory =  "<?=site_url('settings/inventory')?>";
        var settings_skins =  "<?=site_url('settings/skins')?>";
        var category_save = "<?=site_url('category/save')?>";
        var basedata_category = "<?=site_url('basedata/category')?>";  //分类接口
        var basedata_goods_query = "<?=site_url('basedata/goods_query')?>";
        var basedata_goods_checkname = "<?=site_url('basedata/goods_checkname')?>";
        var basedata_goods_getnextno = "<?=site_url('basedata/goods_getnextno')?>";
        var bom_save = "<?=site_url('bom/save')?>";
        var basedata_cat1List = "<?=site_url('basedata/cat1List')?>";
        var basedata_cat2List = "<?=site_url('basedata/cat2List')?>";

    </script>
<!--    <link rel="stylesheet" href="<?/*=skin_url()*/?>/js/common/plugins/validator/jquery.validator.css">
    <script type="text/javascript" src="<?/*=skin_url()*/?>/js/common/plugins/validator/jquery.validator.js"></script>
    <script type="text/javascript" src="<?/*=skin_url()*/?>/js/common/plugins/validator/local/zh_CN.js"></script>-->
    <style>
        body{background: #fff;}
        .ui-combo-wrap{position:static;}
        .mod-form-rows .label-wrap{font-size:12px;}
        .manage-wrapper{margin:20px auto 0;width:600px;}
        .manage-wrap .ui-input{width: 198px;}
        .base-form{*zoom: 1;margin:0 -10px;}
        .base-form:after{content: '.';display: block;clear: both;height: 0;overflow: hidden;}
        .base-form .row-item{float: left;width: 290px;height: 31px;margin: 0 10px;overflow: visible;padding-bottom:15px;}
        .manage-wrap textarea.ui-input{width: 588px;height: 60px;*vertical-align:auto;overflow: hidden;}

        .contacters h3{margin-bottom: 10px;font-weight: normal;}
        .ui-jqgrid-bdiv .ui-state-highlight { background: none; }
        .operating .ui-icon{ margin:0; }
        .ui-icon-plus { background-position:-80px 0; }
        .ui-icon-trash { background-position:-64px 0; }
        .mod-form-rows .ctn-wrap{overflow: visible;;}
        .mod-form-rows .pb0{margin-bottom:0;}
        .manage-wrap .attr-input{width: 100px;height:25px}
    </style>
    <style>
        #container{
            /*            width:380px;
                        margin:20px auto;
                        padding:15px;
                        background-color:#eee;
                        border-radius: 15px;*/
        }
        /** 新增按钮 **/
        #addVar{
            margin:0 0 0 52px;
            padding:5px;
            display:inline-block;
            background-color:#3A9668;
            color:#f1f1f1;
            border:1px solid #005;
            border-radius: 4px;
        }
        /** 删除按钮 **/
        .removeVar{
            margin:auto;
            padding:5px;
            display:inline-block;
            background-color:#B02109;
            color:#f1f1f1;
            border:1px solid #005;
            border-radius: 4px;
        }

        #addVar:hover, .removeVar:hover{
            cursor: pointer;
        }

        .alignRight{
            text-align: right;
        }

        input, textarea{
            padding:5px;
            font-size: 16px;
        }
    </style>
    <script>
        //初始参数个数
        var type ="<?php echo $type;?>";

        var varCount = 1;
        if(type ==='edit'){
            var size ="<?php echo $attrCount;?>";
            varCount = size;
        }

        $(function () {
            //新增按钮点击
            $('#addVar').on('click', function(){
                if(varCount<20) {
                    varCount++;
                    $node = '<li class="row-item"><div><input type="text" value="属性名"  class="attr-input" name="key" id="key">：'
                        + '<input type="text" value="属性值"  class="attr-input" name="val" id="val">'
                        + '<span class="removeVar">删除</span></div></li>';
                    //新表单项添加到“新增”按钮前面
                    $(this).parent().before($node);
                }
            });

            //删除按钮点击
            $('form').on('click', '.removeVar', function(){
                $(this).parent().remove();
                varCount--;
            });
        });
    </script>
</head>
<body>
<div class="manage-wrapper">
    <div id="manage-wrap" class="manage-wrap">
        <form id="manage-form" action="">
            <ul class="mod-form-rows base-form cf" id="base-form">
                <li class="row-item">
                    <div class="label-wrap"><label for="BOMModel">型号</label></div>
                    <div class="ctn-wrap"><input type="text" value="" class="ui-input" name="BOMModel" id="BOMModel"></div>
                </li>
                <li class="row-item">
                    <div class="label-wrap"><label for="BOMName">名称</label></div>
                    <div class="ctn-wrap"><input type="text" value="" class="ui-input" name="BOMName" id="BOMName"></div>
                </li>
                <li class="row-item">
                    <div class="label-wrap"><label for="IsVirt">是否虚拟件:</label></div>
                    <div class="ctn-wrap">
                        <select name="IsKey" id="IsVirt" style="height: 30px">
                            <option value =0 selected="selected">否</option>
                            <option value =1>是</option>
                        </select></div>

                </li>
                <li class="row-item">
                    <div class="label-wrap"> <label  class="tit" for="filter-BOMCat_ID1">基础类别：</label></div>
                    <div class="ctn-wrap"><span class="ui-combo-wrap" id="BOMCat_ID1">
                    <input type="text" name="filter-user" id="filter-BOMCat_ID1" class="input-txt" autocomplete="off" />
                    <span class="trigger"></span>
                    </span></div>

                </li>
                <li class="row-item">
                    <div class="label-wrap"> <label  class="tit" for="filter-BOMCat_ID2">自定义类：</label></div>
                    <div class="ctn-wrap"><span class="ui-combo-wrap" id="BOMCat_ID2">
                    <input type="text" name="filter-user" id="filter-BOMCat_ID2" class="input-txt" autocomplete="off" />
                    <span class="trigger"></span>
                    </span></div>

                </li>
                <li class="row-item">
                    <div class="label-wrap"><label for="Desc">描述</label></div>
                    <div class="ctn-wrap"><input type="text" value="" class="ui-input" name="Desc" id="Desc"/>
                    </div>
                </li>
                <li class="row-item">
                    <div class="label-wrap"> <label  class="tit" for="filter-FK_UnitClass_ID">单位：</label></div>
                    <div class="ctn-wrap"><span class="ui-combo-wrap" id="FK_UnitClass_ID">
                    <input type="text" name="filter-user" id="filter-FK_UnitClass_ID" class="input-txt" autocomplete="off" />
                    <span class="trigger"></span>
                    </span></div>

                </li>
            </ul>
            <ul class="mod-form-rows base-form cf" id="base-form">
                <li class="row-item"><div class="ctn-wrap">属性：（请按 “属性名：属性值” 填写，最多20个）</div><div class="ctn-wrap"></div></li>
                <li class="row-item"><div class="ctn-wrap"></div><div class="ctn-wrap"></div></li>
                <?php if($type == 'add'){ ?>
                    <div id="container">
                        <form id="attr" action="" name="attr">
                            <li class="row-item">
                                <div><input type="text" value="属性名"  class="attr-input" name='key' id='key'>：
                                    <input type="text" value="属性值"  class="attr-input" name='val' id='val'>
                                    <span class="removeVar">删除</span></div>
                            </li>
                            <p><span id="addVar">新增一项</span></p>
                        </form>
                    </div>
                <?php }elseif($type == 'edit'){
                    if(count($attr) > 0){
                    foreach ($attr as $key => $item){ ?>
                        <li class="row-item">
                            <div><input type="text" value=<?php echo $item['attr'] ?>  class="attr-input" name='key' id='key'>：
                                <input type="text" value=<?php echo $item['val'] ?>   class="attr-input" name='val' id='val'>
                                <span class="removeVar">删除</span></div>
                        </li>
                    <?php } }?>
                    <p><span id="addVar">新增一项</span></p>
               <?php } ?>
            </ul>
        </form>
    </div>
</div>
<script src="<?=skin_url()?>/js/dist/goodsManage.js?339787"></script>

</body>
</html>