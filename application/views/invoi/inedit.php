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


var invoi_inlist = "<?=site_url('invoi/inlist')?>";
var invoi_outlist = "<?=site_url('invoi/outlist')?>";
var invoi_in    = "<?=site_url('invoi/in')?>";
var invoi_inedit    = "<?=site_url('invoi/inedit')?>";
var invoi_out   = "<?=site_url('invoi/out')?>";
var invoi_outedit  = "<?=site_url('invoi/outedit')?>";
var invoi_info  = "<?=site_url('invoi/info')?>";
var invoi_del   = "<?=site_url('invoi/del')?>";
var invoi_type  = "<?=site_url('invoi/type')?>";
var api_invoi_print = "<?=site_url('api/invoi_print')?>";
var plug_down = "<?=base_url()?>/install_lodop32.rar";
</script>
<script language="javascript" src="<?=skin_url()?>/js/common/plugins/print/LodopFuncs.js?2"></script>
<link href="<?=skin_url()?>/css/<?=skin()?>/bills.css" rel="stylesheet" type="text/css">
<style>
#bottomField{line-height:30px;}
#bottomField label{width: 75px;display: inline-block;}
.con-footer{padding:10px 0 0 0;}
</style>
</head>

<body>
<div class="wrapper">
  <div class="mod-toolbar-top mr0 cf dn" id="toolTop"></div>
  <div class="bills">
    <div class="con-header">
      <dl class="cf">
        <dd class="pct30">
          <label>供应商：</label>
          <span class="ui-combo-wrap" id="customer">
          <input type="text" name="" class="input-txt" autocomplete="off" value="" data-ref="date">
          <i class="ui-icon-ellipsis"></i></span></dd>
        <dd class="pct25 tc">
          <label>单据日期：</label>
          <input type="text" id="date" class="ui-input ui-datepicker-input" value="">
        </dd>
        <dd id="identifier" class="pct25 tc">
          <label>单据编号：</label>
          <span id="number"></span></dd>
        <dd class="fr pct20 tr">
          <label>业务类别：</label>
          <span class="ui-combo-wrap" id="transType">
          <input type="text" name="" class="input-txt" autocomplete="off" value="" data-ref="date">
          <i class="trigger"></i></span></dd>
      </dl>
    </div>
    <div class="grid-wrap">
      <table id="grid">
      </table>
      <div id="page"></div>
    </div>
    <div class="con-footer cf">
      <p>
        <label>备注:</label>
        <input type="text" id="note" class="ui-input" data-ref="discount">
      </p>
    </div>
    <div class="cf" id="bottomField">
    	<div class="fr" id="toolBottom"></div>
    	<div>
           <label>制单人:</label>
           <span id="userName"></span>
        </div>
    </div>
  </div>
  
  <div id="initCombo" class="dn">
    <input type="text" class="textbox goodsAuto" name="goods" autocomplete="off">
    <input type="text" class="textbox storageAuto" name="storage" autocomplete="off">
  </div>
  <div id="storageBox" class="shadow target_box dn">
  </div>
</div>
<script src="<?=skin_url()?>/js/dist/otherWarehouse.js?222"></script>
</body>
</html>




