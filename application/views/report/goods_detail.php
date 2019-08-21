<?php if(!defined('BASEPATH')) exit('No direct script access allowed');?>
<!doctype html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="renderer" content="webkit">
<title>在线进销存</title>
<script>var skin_url  = "<?=skin_url()?>/js/";</script>
<link href="<?=skin_url()?>/css/common.css?ver=20140303" rel="stylesheet">
<link href="<?=skin_url()?>/css/<?=skin()?>/ui.min.css?ver=20140303" rel="stylesheet">
<script src="<?=skin_url()?>/js/common/libs/jquery/jquery-1.10.2.min.js"></script>
<script src="<?=skin_url()?>/js/common/libs/json2.js"></script>
<script src="<?=skin_url()?>/js/common/common.js?ver=20140815"></script>
<script src="<?=skin_url()?>/js/common/grid.js?ver=20140815"></script>
<script src="<?=skin_url()?>/js/common/plugins.js?ver=20140815"></script>
<script src="<?=skin_url()?>/js/common/plugins/jquery.dialog.js?self=true&ver=20140815"></script>
<script src="<?=skin_url()?>/js/common/seajs/2.1.1/sea.js?ver=20140303" id="seajsnode"></script>
<script src="<?=skin_url()?>/js/common/libs/jquery/jquery-1.10.2.min.js"></script>
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


var invpu_lists = "<?=site_url('invpu/lists')?>";
var invpu_add   = "<?=site_url('invpu/add')?>";
var invpu_edit   = "<?=site_url('invpu/edit')?>";
var invpu_info   = "<?=site_url('invpu/info')?>";
var invpu_del   = "<?=site_url('invpu/del')?>";
var report_invpu_detail_xls  = "<?=site_url('report/invpu_detail_xls')?>";
var report_invpu_detail  = "<?=site_url('report/invpu_detail')?>";


try{
	document.domain = '<?=base_url()?>';
}catch(e){
	//console.log(e);
}
</script>
<link rel="stylesheet" href="<?=skin_url()?>/css/report.css" />
</head>
<body>
<div class="mod-report">

	<!-- seach begin -->
	<div class="search-wrap" id="report-search">
	  <div class="s-inner cf">
	    <div class="fl"> <strong class="tit mrb fl">选择查询条件：</strong>
	      <div class="ui-btn-menu fl" id="filter-menu"> <span class="ui-btn menu-btn"> <strong id="selected-period">请选择查询条件</strong><b></b> </span>
	        <div class="con">
	          <ul class="filter-list">
	            <li>
	              <label class="tit">日期:</label>
	              <input type="text" value="" class="ui-input ui-datepicker-input" name="filter-fromDate" id="filter-fromDate" />
	              <span>至</span>
	              <input type="text" value="" class="ui-input ui-datepicker-input" name="filter-toDate" id="filter-toDate" />
	            </li>
	          </ul>
	          <ul class="filter-list" id="more-conditions">
	            <li style="height:60px; ">
	              <label class="tit">商品:</label>
	              <span class="mod-choose-input" id="filter-goods"><input type="text" class="ui-input" id="goodsAuto"/><span class="ui-icon-ellipsis"></span></span>
	              <p style="color:#999; padding:3px 0 0 0; ">（可用,分割多个编码如1001,1008,2001，或直接输入编码段如1001--1009查询）</p>
	            </li>
	            <!--<li>
	              <label class="tit">仓库:</label>
	              <span class="mod-choose-input" id="filter-storage"><input type="text" class="ui-input" id="storageAuto"/><span class="ui-icon-ellipsis"></span></span>
	            </li>-->
	          </ul>
	          <div class="btns"> <a href="#" id="conditions-trigger" class="conditions-trigger" tabindex="-1">更多条件<b></b></a> <a class="ui-btn ui-btn-sp" id="filter-submit" href="#">确定</a> <a class="ui-btn" id="filter-reset" href="#" tabindex="-1">重置</a> </div>
	        </div>
	      </div>
	      <a id="refresh" class="ui-btn ui-btn-refresh fl mrb"><b></b></a> <span class="txt fl" id="cur-search-tip"></span> </div>
	    <div class="fr"><a href="#" class="ui-btn ui-btn-sp mrb fl" id="btn-print">打印</a><a href="#" class="ui-btn fl" id="btn-export">导出</a></div>
	  </div>
	</div>
	<!-- seach end -->
	
	<!-- grid begin -->
	<div class="ui-print">
		<div class="grid-wrap" id="grid-wrap">
			<div class="grid-title">商品收发明细表</div>
			<div class="grid-subtitle"></div>
	    	<table id="grid"></table>
	   	</div>
	</div>
	<!-- grid end -->

</div>

<script>
	seajs.use("<?=skin_url()?>/js/dist/goodsFlowDetail");
</script>
</body>
</html>