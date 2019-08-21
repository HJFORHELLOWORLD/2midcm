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
var basedata_goods = "<?=site_url('basedata/goods')?>";        
var basedata_unit = "<?=site_url('basedata/unit')?>";          
var settings_goods_manage = "<?=site_url('settings/goods_manage')?>";        
var settings_goods_batch  = "<?=site_url('settings/goods_batch')?>";
var settings_unit_manage = "<?=site_url('settings/unit_manage')?>";       //单位增修改 

var unit_del = "<?=site_url('unit/del')?>";
</script>
</head>
<body>
<div class="wrapper">
	<div class="mod-toolbar-top cf">
	    <div class="fl"><strong class="tit">计量单位</strong></div>
	    <div class="fr"><a href="#" class="ui-btn ui-btn-sp mrb" id="btn-add">新增</a><a class="ui-btn" id="btn-refresh">刷新</a></div>
	  </div>
    <div class="grid-wrap">
	    <table id="grid">
	    </table>
	    <div id="page"></div>
	  </div>
</div>
<script src="<?=skin_url()?>/js/dist/unitList.js?99"></script>
</body>
</html>