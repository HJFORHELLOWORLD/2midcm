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
var category_save = "<?=site_url('category/save')?>";
var basedata_category = "<?=site_url('basedata/category')?>";  //分类接口
var basedata_goods_query = "<?=site_url('basedata/goods_query')?>";
var basedata_goods_checkname = "<?=site_url('basedata/goods_checkname')?>";
var basedata_goods_getnextno = "<?=site_url('basedata/goods_getnextno')?>";
var goods_save = "<?=site_url('goods/save')?>";

</script>
<link rel="stylesheet" href="<?=skin_url()?>/js/common/plugins/validator/jquery.validator.css">
<script type="text/javascript" src="<?=skin_url()?>/js/common/plugins/validator/jquery.validator.js"></script>
<script type="text/javascript" src="<?=skin_url()?>/js/common/plugins/validator/local/zh_CN.js"></script>
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
#purchasePrice,#salePrice{text-align: right;}

.contacters{margin-bottom: 10px;}
.contacters h3{margin-bottom: 10px;font-weight: normal;}
.ui-jqgrid-bdiv .ui-state-highlight { background: none; }
.operating .ui-icon{ margin:0; }
.ui-icon-plus { background-position:-80px 0; }
.ui-icon-trash { background-position:-64px 0; }
.mod-form-rows .ctn-wrap{overflow: visible;;}
.mod-form-rows .pb0{margin-bottom:0;}
.jdInfo{display:none;margin-top: 5px;}
.jdInfo h3{position: absolute;left: 50%;margin-left: -62px;top: -11px;background-color: #fff;padding: 0 10px;color: #ccc;}
.jdInfo a{cursor: help;border-bottom:dotted #555 1px;}
.hasJDStorage .manage-wrapper{margin:20px auto 0;width:723px;}
.hasJDStorage .jdInfo{display:block;position:relative;z-index:1;padding-top: 15px;margin: 10px 0;border-top: solid 1px #ccc;}
.hasJDStorage .manage-wrap textarea.ui-input{height: 30px;width:708px;}
.hasJDStorage .base-form .row-item{width: 227px;padding-bottom:9px;}
.hasJDStorage .manage-wrap .ui-input{width: 135px;}
</style>
</head>
<body>
<div class="manage-wrapper">
    <div id="manage-wrap" class="manage-wrap">
        <form id="manage-form" action="">
            <ul class="mod-form-rows base-form cf" id="base-form">
                <li class="row-item">
                    <div class="label-wrap"><label for="number">BOM编号</label></div>
                    <div class="ctn-wrap"><input type="text" value="" class="ui-input" name="number" id="number"></div>
                </li>
                <li class="row-item">
                    <div class="label-wrap"><label for="model">型号</label></div>
                    <div class="ctn-wrap"><input type="text" value="" class="ui-input" name="model" id="model"></div>
                </li>
                <li class="row-item">
                    <div class="label-wrap"><label for="name1">名称</label></div>
                    <div class="ctn-wrap"><input type="text" value="" class="ui-input" name="name" id="name1"></div>
                </li>
                <li class="row-item">
                    <div class="label-wrap"><label for="name2">是否虚拟件</label></div>
                    <div class="ctn-wrap"><input type="text" value="" class="ui-input" name="name" id="name2"></div>
                </li>


<!--                <li class="row-item">
                    <div class="label-wrap"><label for="spec">规格</label></div>
                    <div class="ctn-wrap"><input type="text" value="" class="ui-input" name="spec"
                                                 id="spec"/></div>
                </li>-->
                <li class="row-item">
                    <div class="label-wrap"><label for="purchasePrice">大类</label></div>
                    <div class="ctn-wrap"><input type="text" value="" class="ui-input" name="purchasePrice"
                                                 id="purchasePrice"/></div>
                </li>
                <li class="row-item">
                    <div class="label-wrap"><label for="salePrice">小类</label></div>
                    <div class="ctn-wrap"><input type="text" value="" class="ui-input" name="salePrice" id="salePrice">
                    </div>
                </li>
                <li class="row-item">
                    <div class="label-wrap"><label for="desc">描述</label></div>
                    <div class="ctn-wrap"><input type="text" value="" class="ui-input" name="desc" id="desc"/>
                    </div>
                </li>
                <li class="row-item">
                    <div class="label-wrap"><label for="unitCost">单位类别</label></div>
                    <div class="ctn-wrap"><input type="text" value="" class="ui-input" name="unitCost" id="unitCost">
                    </div>
                </li>
                <li class="row-item">
                    <div class="label-wrap"><label for="attr">属性描述</label></div>
                    <div class="ctn-wrap"><input type="text" value="" class="ui-input" name="attr" id="attr"></div>
                </li>
            </ul>
            <ul class="mod-form-rows base-form cf" id="base-form">
<!--                <div class="label-wrap"><label for="specs">规格型号：</label></div>-->
                <?php foreach ($specArr as $key => $item): ?>

                    <li class="row-item">
                        <div class="label-wrap"><label for="specs"><?php echo $item ?>：</label></div>
                        <div class="ctn-wrap"><input type="text" value="" class="ui-input" name=<?php echo $key ?> id=<?php echo $key ?>></div>
                    </li>

                    <!--                    <li class="row-item"><?php /*echo $item */ ?>：<input type="text" value="" class="ui-input" name=<?php /*echo $key */ ?> id=<?php /*echo $key */ ?> /></li>-->
                <?php endforeach ?>
                <!--                <div class="ctn-wrap"><input type="text" value="" class="ui-input" name="specs" id="specs" /></div></ul>-->
                <div id="jdInfo" class="jdInfo cf dn">
                    <h3>维护京东仓储信息</h3>
                    <ul class="mod-form-rows base-form cf">
                        <li class="row-item">
                            <div class="label-wrap"><label for="jianxing">商品件型</label></div>
                            <div class="ctn-wrap"><span id="jianxing"></span></div>
                        </li>
                        <li class="row-item">
                            <div class="label-wrap"><label for="length">长(mm)</label></div>
                            <div class="ctn-wrap"><input type="text" value="" class="ui-input" name="length"
                                                         id="length"></div>
                        </li>
                        <li class="row-item">
                            <div class="label-wrap"><label for="width">宽(mm)</label></div>
                            <div class="ctn-wrap"><input type="text" value="" class="ui-input" name="width" id="width">
                            </div>
                        </li>
                        <li class="row-item">
                            <div class="label-wrap"><label for="height">高(mm)</label></div>
                            <div class="ctn-wrap"><input type="text" value="" class="ui-input" name="height"
                                                         id="height"></div>
                        </li>
                        <li class="row-item">
                            <div class="label-wrap"><label for="weight">重量(kg)</label></div>
                            <div class="ctn-wrap"><input type="text" value="" class="ui-input" name="weight"
                                                         id="weight"></div>
                        </li>
                    </ul>
                </div>
                <div class="contacters">

                    <!--<div class="grid-wrap">
                      <table id="grid">
                      </table>
                      <div id="page"></div>
                    </div>-->
                </div>

                <ul class="mod-form-rows">
                    <li class="row-item pb0">
                        <div class="ctn-wrap" style="line-height: normal;"><textarea name="" id="note"
                                                                                     class="ui-input ui-input-ph">添加备注信息</textarea>
                        </div>
                    </li>
                </ul>

            </ul>
        </form>
    </div>
    
    <div id="initCombo" class="dn">
      <input type="text" class="textbox storageAuto" name="storage" autocomplete="off">
    </div>
</div>
<script src="<?=skin_url()?>/js/dist/goodsManage.js?339787"></script>

</body>
</html>