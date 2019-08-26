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

</script>
</head>

<body>
<div class="wrapper">
  <div class="mod-search cf">
    <div class="fl">
      <ul class="ul-inline">
        <li>
          <input type="text" id="matchCon" class="ui-input ui-input-ph con" value="请输入单据号或客户名或备注">
        </li>
        <li>
          <label>日期:</label>
          <input type="text" id="beginDate" value="2014-08-30" class="ui-input ui-datepicker-input">
          <i>-</i>
          <input type="text" id="endDate" value="2014-09-05" class="ui-input ui-datepicker-input">
        </li>
        <li><!--<a class="mrb more" id="moreCon">(高级搜索)</a>--><a class="ui-btn mrb" id="search">查询</a></li>
      </ul>
    </div>
    <div class="fr"><a class="ui-btn ui-btn-sp" id="add">新增</a><!--<a class="ui-btn" id="export">导出</a>--></div>
  </div>
  <div class="grid-wrap">
    <table id="grid">
    </table>
    <div id="page"></div>
  </div>
</div>
<script src="<?=skin_url()?>/js/dist/otherOutboundList.js?s22"></script>
</body>
</html>