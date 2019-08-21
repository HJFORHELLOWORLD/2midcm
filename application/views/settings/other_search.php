<?php if(!defined('BASEPATH')) exit('No direct script access allowed');?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=1280, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<title>在线进销存</title>
<link href="<?=skin_url()?>/saas/scm/app2/css/common.css?ver=20140815" rel="stylesheet" type="text/css">
<link href="<?=skin_url()?>/saas/scm/app2/css/<?=skin()?>/ui.min.css?ver=20140815" rel="stylesheet">
<script src="<?=skin_url()?>/saas/scm/app2/js/common/libs/jquery/jquery-1.10.2.min.js"></script>
<script src="<?=skin_url()?>/saas/scm/app2/js/common/libs/json2.js"></script>
<script src="<?=skin_url()?>/saas/scm/app2/js/common/common.js?ver=20140815"></script>
<script src="<?=skin_url()?>/saas/scm/app2/js/common/grid.js?ver=20140815"></script>
<script src="<?=skin_url()?>/saas/scm/app2/js/common/plugins.js?ver=20140815"></script>
<script src="<?=skin_url()?>/saas/scm/app2/js/common/plugins/jquery.dialog.js?self=true&ver=20140815"></script>
<script type="text/javascript">
try{
	document.domain = '<?=base_url()?>';
}catch(e){
	//console.log(e);
}
</script>

<script type="text/javascript">
var WDURL = "";
var SCHEME= "blue";
var icon_url = "<?=skin_url()?>/css/base/dialog/icons/";                       
var settings_customer_manage = "<?=site_url('settings/customer_manage')?>";    
var basedata_customer = "<?=site_url('basedata/customer')?>";                  
var settings_vendor_manage = "<?=site_url('settings/vendor_manage')?>";      
var settings_vendor_batch = "<?=site_url('settings/vendor_batch')?>";             
var basedata_vendor = "<?=site_url('basedata/vendor')?>";                      
var basedata_settlement = "<?=site_url('basedata/settlement')?>";              
var settings_settlement_manage = "<?=site_url('settings/settlement_manage')?>";       
var basedata_category = "<?=site_url('basedata/category')?>";                    
var settings_goods_manage = "<?=site_url('settings/goods_manage')?>";        
var settings_goods_batch  = "<?=site_url('settings/goods_batch')?>";
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
<body style="background:#FFF; ">
<div class="wrapper">
  <div class="mod-search-adv">
    <ul>
      <li>
        <label>搜索条件:</label>
        <input type="text" id="matchCon" class="ui-input con" value="请输入单据号或客户名或备注">
      </li>
      <li>
        <label>日期:</label>
        <input type="text" id="beginDate" class="ui-input ui-datepicker-input">
        <i>至</i>
        <input type="text" id="endDate" class="ui-input ui-datepicker-input">
      </li>
      <li>
        <label>仓库:</label>
        <span id="storageA"></span>
      </li>
      <li>
        <label>业务类型:</label>
        <span id="transType"></span>
      </li>
    </ul>
  </div>
</div>
<script src="<?=skin_url()?>/js/dist/advSearch.js?23"></script>
</body>
</html>