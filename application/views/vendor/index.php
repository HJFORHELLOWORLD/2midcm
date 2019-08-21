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
var basedata_customer = "<?=site_url('basedata/customer')?>";                  
var settings_vendor_manage = "<?=site_url('settings/vendor_manage')?>";      
var settings_vendor_batch = "<?=site_url('settings/vendor_batch')?>";             
var basedata_vendor = "<?=site_url('basedata/vendor')?>";                      
var basedata_settlement = "<?=site_url('basedata/settlement')?>";              
var settings_settlement_manage = "<?=site_url('settings/settlement_manage')?>";       
var basedata_category = "<?=site_url('basedata/category')?>"; 
var basedata_category_type= "<?=site_url('basedata/category_type')?>";                   
var settings_goods_manage = "<?=site_url('settings/goods_manage')?>";        
var settings_goods_batch  = "<?=site_url('settings/goods_batch')?>";

var basedata_contact    = "<?=site_url('basedata/contact')?>";
var vendor_del    = "<?=site_url('vendor/del')?>";
var vendor_export = "<?=site_url('vendor/export')?>";
var vendor_save   = "<?=site_url('vendor/save')?>";
</script>
<style>
html{ overflow:hidden; }
.matchCon{width:280px;}
</style>
</head>
<body>
<div class="wrapper">
	<div class="mod-search cf">
	    <div class="fl">
	      <ul class="ul-inline">
	        <li>
	          <input type="text" id="matchCon" class="ui-input ui-input-ph matchCon" value="按供应商编号，供应商名称，联系人，电话等查询">
	        </li>
	        <li><a class="ui-btn mrb" id="search">查询</a></li>
	      </ul>
	    </div>
	    <div class="fr"><a href="#" class="ui-btn ui-btn-sp mrb" id="btn-add">新增</a>
		<!--<a href="#" class="ui-btn mrb" id="btn-print">打印</a>-->
		<!--<a href="#" class="ui-btn mrb" id="btn-import">导入</a>-->
		<a href="#" class="ui-btn mrb" id="btn-export">导出</a>
		<a href="#" class="ui-btn" id="btn-batchDel">删除</a></div>
	  </div>
    <div class="grid-wrap">
	    <table id="grid">
	    </table>
	    <div id="page"></div>
	  </div>
</div>
<script src="<?=skin_url()?>/js/dist/vendorList.js?2"></script>
</body>
</html>