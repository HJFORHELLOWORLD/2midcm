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
var basedata_contact_query = "<?=site_url('basedata/contact_query')?>";
var basedata_contact_getnextno = "<?=site_url('basedata/contact_getnextno')?>";
var basedata_contact_checkname = "<?=site_url('basedata/contact_checkname')?>";
var betweenUnit_modify  = "<?=site_url('betweenUnit/modify')?>";
var settings_customer_cate_manage = "<?=site_url('settings/customer_cate_manage')?>";
var basedata_area = "<?=site_url('basedata/area')?>";
var category_lists = "<?=site_url('category/lists')?>";

</script>

<style>
body{background: #fff;}
/*.mod-form-rows .label-wrap{font-size:12px;}
.mod-form-rows .row-item {padding-bottom: 15px;margin-bottom: 0;}!*兼容IE7 ，重写common的演示*!
.manage-wrap .ui-input{width: 198px;}
.base-form li{float: left;width: 290px;}
.base-form li.odd{padding-right:20px;}
.manage-wrap textarea.ui-input{width: 588px;height: 60px;overflow:hidden;}
.contacters h3{margin-bottom: 10px;font-weight: normal;}
.mod-form-rows .pb0{padding-bottom:0;}
.mod-form-rows .ctn-wrap{overflow: visible;;}*/
.manage-wrap{margin: 20px auto 10px;width: 300px;}
.manage-wrap .ui-input{width: 200px;font-size:14px;}
</style>
</head>
<body>
    <div id="manage-wrap" class="manage-wrap">
    	<form id="manage-form" action="">
    		<ul class="mod-form-rows">
<!--    			<li class="row-item odd">
    				<div class="label-wrap"><label for="number">客户编号</label></div>
    				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="number" id="number"></div>
    			</li>-->
                <li class="row-item">
                    <div class="label-wrap"><label for="name">单位名称</label></div>
                    <div class="ctn-wrap"><input type="text" value="" class="ui-input" name="name" id="name"></div>
                </li>
                <li class="row-item">
                    <div class="label-wrap"><label for="remark">描述</label></div>
                    <div class="ctn-wrap"><input type="text" value="" class="ui-input" name="remark" id="remark"></div>
                </li>
                <li class="row-item">
<!--                    <div class="label-wrap"><label for="Area_ID">地区</label></div>
                    <div class="ctn-wrap"><input type="text" value="" class="ui-input" name="Area_ID" id="Area_ID"></div>-->
                    <div class="label-wrap"> <label  class="tit" for="filter-area">地区：</label></div>
                    <div class="ctn-wrap"><span class="ui-combo-wrap" id="Area_ID">
                    <input type="text" name="filter-area" id="filter-area" class="input-txt" autocomplete="off" />
                    <span class="trigger"></span>
                    </span></div>
                </li>
                <li class="row-item">
<!--                    <div class="label-wrap"><label for="BU_Cat">类别</label></div>
                    <div class="ctn-wrap"><input type="text" value="" class="ui-input" name="BU_Cat" id="BU_Cat"></div>-->
                    <div class="label-wrap"><label for="BU_Cat">类别:</label></div>
                    <div class="ctn-wrap">
                        <select name="BU_Cat" id="BU_Cat" style="height: 30px">
                            <option value =1>客户</option>
                            <option value =2>厂家</option>
                            <option value =4>第三方</option>
                        </select></div>
                </li>
                <li class="row-item">
<!--                    <div class="label-wrap"><label for="Industry_ID">所属行业</label></div>
                    <div class="ctn-wrap"><input type="text" value="" class="ui-input" name="Industry_ID" id="Industry_ID"></div>-->
                    <div class="label-wrap"> <label  class="tit" for="filter-industry">所属行业:</label></div>
                    <div class="ctn-wrap"><span class="ui-combo-wrap" id="Industry_ID">
                    <input type="text" name="filter-industry" id="filter-industry" class="input-txt" autocomplete="off" />
                    <span class="trigger"></span>
                    </span></div>
                </li>
    			<li class="row-item">
    				<div class="label-wrap"><label for="Taxrate">税率</label></div>
    				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="Taxrate" id="Taxrate"></div>
    			</li>
    			<li class="row-item">
    				<div class="label-wrap"><label for="phone">联系方式</label></div>
    				<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="phone" id="phone"></div>
    			</li>
                <li class="row-item">
                    <div class="label-wrap"><label for="Status">状态:</label></div>
                    <div class="ctn-wrap">
                        <select name="Status" id="Status" style="height: 30px">
                            <option value =0>不正常</option>
                            <option value =1>正常</option>
                        </select></div>
                </li>
    		</ul>
<!--    		<div class="contacters">
    			<h3 class="dn">联系方式</h3>
    			<div class="grid-wrap">
				  <table id="grid">
				  </table>
				  <div id="page"></div>
				</div>
    		</div>-->
<!--    		<ul class="mod-form-rows">
    			<li class="row-item pb0">
    				<div class="label-wrap"><label for="note">备注</label></div>
    				<div class="ctn-wrap"><textarea name="" id="note" class="ui-input ui-input-ph">添加备注信息</textarea></div>
    			</li>
    		</ul>-->
    	</form>

    </div>

<script src="<?=skin_url()?>/js/dist/customerManage.js?a"></script>
</body>
</html>