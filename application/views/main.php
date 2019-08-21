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
var icon_url = "<?=skin_url()?>/css/base/dialog/icons/";                      //图片路径
var settings_customer_manage = "<?=site_url('settings/customer_manage')?>";   //新增修改客户 
var settings_vendor_manage = "<?=site_url('settings/vendor_manage')?>";       //新增供应商
var settings_vendor_batch = "<?=site_url('settings/vendor_batch')?>";         //批量选择供应商 
var basedata_settlement = "<?=site_url('basedata/settlement')?>";             //结算方式列表
var settings_settlement_manage = "<?=site_url('settings/settlement_manage')?>";       //新增修改结算方式
var basedata_category = "<?=site_url('basedata/category')?>";                     //分类列表
var basedata_category_type= "<?=site_url('basedata/category_type')?>";            //分类分类
var settings_goods_manage = "<?=site_url('settings/goods_manage')?>";       //新增修改商品
var settings_goods_batch  = "<?=site_url('settings/goods_batch')?>";        //批量选择商品
var basedata_goods = "<?=site_url('basedata/goods')?>";                     //商品
var basedata_unit  = "<?=site_url('basedata/unit')?>";                      //单位
var settings_unit_manage = "<?=site_url('settings/unit_manage')?>";       //单位增修改 
var basedata_contact  = "<?=site_url('basedata/contact')?>";              //客户、供应商列表
var settings_inventory =  "<?=site_url('settings/inventory')?>";          //单库存查询
</script>
<link href="<?=skin_url()?>/css/<?=skin()?>/index.css?ver=1" rel="stylesheet" type="text/css" id="indexFile">
<script src="<?=skin_url()?>/js/dist/template.js"></script>

</head>
<body>
<div id="hd" class="cf">
  <div class="fl welcome cf">
	  <strong><span id="greetings"></span>，<span id="username"></span></strong>
	  <!--<a href="javascrip:void(0);" id="manageAcct">账号管理</a>-->
	  <a tabTxt="权限管理" parentOpen="true" rel="pageTab" href="<?=site_url('home/editpwd')?>">账号管理</a>
	  <!--<a href="javascrip:void(0);" id="newGuide" title="新手入门">新手入门</a>-->
  </div>
  <div class="fr storages-search"><label for="">库存查询</label><span class="ui-search"><input type="text" id="goodsAuto" class="ui-input" /></span><span id="stockSearch"></span></div>
</div>
<script>
var greetings = "", cur_time = new Date().getHours();
if(cur_time >= 0 && cur_time <= 4 ) {
	greetings = "已经夜深了，请注意休息"
} else if (cur_time > 4 && cur_time <= 7 ) {
	greetings = "早上好";
} else if (cur_time > 7 && cur_time < 12 ) {
	greetings = "上午好";
} else if (cur_time >= 12 && cur_time <= 18 ) {
	greetings = "下午好";
} else {
	greetings = "晚上好";
};
$("#greetings").text(greetings);
$("#username").text(parent.SYSTEM.realName);
</script>
<div id="bd" class="index-body cf">
  <div class="col-main">
    <div class="main-wrap cf">
      <div class="m-top cf" id="profileDom">
      <!-- 
      	<div class="fr" id="interval">
          	<label class="radio"><input type="radio" name="interval" value="month" checked="checked">本月</label>
          	<label class="radio"><input type="radio" name="interval" value="year">本年</label>
      		<label class="radio"><input type="radio" name="interval" value="today">今日</label>
          	<label class="radio"><input type="radio" name="interval" value="yesterday">昨日</label>
        </div>
       -->
<!--		<table width="100%" border="0" cellspacing="0" cellpadding="20">
		  <tr>
		    <td><a class="ta t1" tabid="report-initialBalance" data-right="InvBalanceReport_QUERY" tabTxt="商品库存余额" parentOpen="true" rel="pageTab" href="<?/*=site_url('report/goods_balance')*/?>">商品库存余额</a></td>
		    <td><a class="ta t2" tabid="report-cashBankJournal" data-right="SettAcctReport_QUERY" tabTxt="现金银行报表" parentOpen="true" rel="pageTab" href="/report/bankBalance.do?action=detail">现金银行报表</a></td>
		    <td><a class="ta t3" tabid="report-contactDebt" data-right="ContactDebtReport_QUERY" tabTxt="往来单位欠款表" parentOpen="true" rel="pageTab" href="<?/*=site_url('report/arrears')*/?>">往来单位欠款表</a></td>
		    <td><a class="ta t4" tabid="report-salesSummary" data-right="SAREPORTINV_QUERY" tabTxt="销售汇总表（按商品）" parentOpen="true" rel="pageTab" href="<?/*=site_url('report/sales_summary')*/?>">销售汇总表</a></td>
		    <td><a class="ta t5" tabid="report-puSummary" data-right="PUREPORTINV_QUERY" tabTxt="采购汇总表（按商品）" parentOpen="true" rel="pageTab" href="<?/*=site_url('report/invpu_summary')*/?>">采购汇总表</a></td>
		  </tr>
		</table>-->
      </div>
      <ul class="quick-links">
        <li class="purchase-purchase">
        	<a tabid="purchase-purchase" data-right="PU_ADD" tabTxt="购货单" parentOpen="true" rel="pageTab" href="<?=site_url('invpu/add')?>"><span></span>购货</a>
        </li>
          <li class="sales-sales">
              <a tabid="sales-sales" data-right="SA_ADD" tabTxt="报价单" parentOpen="true" rel="pageTab" href="<?=site_url('sheet/add')?>"><span></span>报价</a>
          </li>
        <li class="sales-sales">
        	<a tabid="sales-sales" data-right="SA_ADD" tabTxt="销售单" parentOpen="true" rel="pageTab" href="<?=site_url('invsa/add')?>"><span></span>销货</a>
        </li>
<!--        <li class="storage-transfers">
        	<a tabid="storage-transfers" data-right="TF_ADD" tabTxt="调拨单" parentOpen="true" rel="pageTab11" href="#"><span></span>仓库调拨</a>
        </li>-->
        <li class="storage-inventory">
        	<a tabid="storage-inventory" data-right="PD_GENPD" tabTxt="盘点" parentOpen="true" rel="pageTab" href="<?=site_url('inventory')?>"><span></span>库存盘点</a>
        </li>
        <li class="storage-otherWarehouse">
        	<a tabid="storage-otherWarehouse" data-right="IO_ADD" tabTxt="其他入库" parentOpen="true" rel="pageTab" href="<?=site_url('invoi/in')?>"><span></span>其他入库</a>
        </li>
        <li class="storage-otherOutbound">
        	<a tabid="storage-otherOutbound" data-right="OO_ADD" tabTxt="其他出库" parentOpen="true" rel="pageTab" href="<?=site_url('invoi/out')?>"><span></span>其他出库</a>
        </li>
<!--        <li class="added-service">
        	<a tabid="setting-addedServiceList" tabTxt="增值服务" parentOpen="true" rel="pageTab111" href="#"><span></span>增值服务</a>
        </li>-->
<!--        <li class="feedback">
        	<a href="#" id="feedback111"><span></span>意见反馈</a>
        </li>-->
      </ul>
    </div>
  </div>
  <div class="col-extra">
    <div class="extra-wrap">
      <h2>快速查看</h2>
      <div class="list">
        <ul>
            <li><a tabid="sales-salesList" data-right="SA_QUERY" tabTxt="销售记录" parentOpen="true" rel="pageTab" href="<?=site_url('invsa')?>">销售记录</a></li>
            <li><a tabid="purchase-salesList" data-right="PU_QUERY" tabTxt="采购记录" parentOpen="true" rel="pageTab" href="<?=site_url('invpu')?>">采购记录</a></li>
        	<!--<li><a href="/scm/receipt.do?action=initReceiptList" rel="pageTab" tabid="money-receiptList" tabTxt="收款记录" data-right="RECEIPT_QUERY" parentOpen="true">收款记录</a></li>
            <li><a href="/scm/payment.do?action=initPayList" rel="pageTab" tabid="money-paymentList" tabTxt="付款记录" data-right="PAYMENT_QUERY" parentOpen="true">付款记录</a></li>-->
<!--        	<li><a href="<?/*=site_url('report/sales_detail')*/?>" rel="pageTab" tabid="report-salesDetail" tabTxt="销售明细表" data-right="SAREPORTDETAIL_QUERY" parentOpen="true">销售明细表</a></li>
            <li><a href="<?/*=site_url('report/invpu_detail')*/?>" rel="pageTab" tabid="report-puDetail" tabTxt="采购明细表" data-right="PUREOORTDETAIL_QUERY" parentOpen="true">采购明细表</a></li>-->
<!--            <li><a href="--><?//=site_url('report/goods_balance')?><!--" rel="pageTab" tabid="report-initialBalance" data-right="InvBalanceReport_QUERY" tabTxt="采购明细表"  parentOpen="true">商品库存余额</a></li>-->
        	<!--<li style="border-bottom:none; line-height: 42px; "><a tabid="storage-transfersList" data-right="TF_QUERY" tabTxt="调拨记录" parentOpen="true" rel="pageTab" href="/scm/invTf.do?action=initTfList">调拨记录</a></li>-->
        	<!--
        	<li><a tabid="storage-otherWarehouseList" data-right="IO_QUERY" tabTxt="其他入库记录" parentOpen="true" rel="pageTab" href="/scm/invOi.do?action=initOiList&type=in">其他入库记录</a></li>
        	<li><a tabid="storage-otherOutboundList" data-right="OO_QUERY" tabTxt="其他出库记录" parentOpen="true" rel="pageTab" href="/scm/invOi.do?action=initOiList&type=out">其他出库记录</a></li>
        	<li><a tabid="report-initialBalance" data-right="InvBalanceReport_QUERY" tabTxt="商品库存余额" parentOpen="true" rel="pageTab" href="/report/invBalance.do?action=detail">商品库存余额</a></li>
        	<li><a tabid="report-contactDebt" data-right="ContactDebtReport_QUERY" tabTxt="往来单位欠款表" parentOpen="true" rel="pageTab" href="/report/contactDebt.do?action=detail">往来单位欠款</a></li>
        	-->
        </ul>
      </div>
    </div>
  </div>
</div>
<script id="profile" type="text/html">
		<table width="100%" border="0" cellspacing="0" cellpadding="20">
		  <tr>
		    <td><a class="tad t1" tabid="report-initialBalance" data-right="InvBalanceReport_QUERY" tabTxt="商品库存余额" parentOpen="true" rel="pageTab" href="<?=site_url('report/goods_balance')?>"><span>库存总量:<b><#= items[0].total1 #></b></span><span>库存成本:<b><#= items[0].total2 #></b></span></a></td>
		   
		    <td><a class="tad t3" tabid="report-contactDebt" data-right="ContactDebtReport_QUERY" tabTxt="往来单位欠款表" parentOpen="true" rel="pageTab" href="<?=site_url('report/arrears')?>"><span>客户欠款:<b><#= items[2].total1 #></b></span><span>供应商欠款:<b><#= items[2].total2 #></b></span></a></td>
		    <td><a class="tad t4" tabid="report-salesSummary" data-right="SAREPORTINV_QUERY" tabTxt="销售汇总表（按商品）" parentOpen="true" rel="pageTab" href="<?=site_url('report/sales_summary')?>"><span>销售收入(本月):<b><#= items[3].total1 #></b></span><span>销售毛利(本月):<b><#= items[3].total2 #></b></span></a></td>
		    <td><a class="tad t5" tabid="report-puSummary" data-right="PUREPORTINV_QUERY" tabTxt="采购汇总表（按商品）" parentOpen="true" rel="pageTab" href="<?=site_url('report/invpu_summary')?>"><span>采购金额(本月):<b><#= items[4].total1 #></b></span><span>商品种类(本月):<b><#= items[4].total2 #></b></span></a></td>  
		  </tr>
		</table>
</script>

<script>
if(parent.SYSTEM.isAdmin || parent.SYSTEM.rights.INDEXREPORT_QUERY) {
	template.openTag = '<#';
	template.closeTag = '#>';
	Public.ajaxGet('<?=site_url('basedata/main_data')?>', {finishDate: parent.SYSTEM.endDate, beginDate: parent.SYSTEM.beginDate, endDate: parent.SYSTEM.endDate }, function(data){
		if(data.status === 200) {
			var html = template.render('profile', data.data);
			document.getElementById('profileDom').innerHTML = html;
			reportParam();
		} else {
			parent.Public.tips({type: 1, content : data.msg});
		}
	});
};
</script>

<script>
Public.pageTab();
reportParam();
function reportParam(){
	$("[tabid^='report']").each(function(){
		var dateParams = "beginDate="+parent.SYSTEM.beginDate+"&endDate="+parent.SYSTEM.endDate;
		var href = this.href;
		href += (this.href.lastIndexOf("?")===-1) ? "?" : "&";
		if($(this).html() === '商品库存余额表'){
			this.href = href + "beginDate="+parent.SYSTEM.startDate+"&endDate="+parent.SYSTEM.endDate;
		}
		else{
			this.href = href + dateParams;
		}
	});
}

var goodsCombo = Business.goodsCombo($('#goodsAuto'), {
	extraListHtml: ''
});

$('#goodsAuto').click(function(){
	var _self = this;
	setTimeout(function(){
		_self.select();
	}, 50);
});

$('#stockSearch').click(function(e){
	e.preventDefault();
	//$('#result').html('').addClass('loading');
	var id = goodsCombo.getValue();
	var text = $('#goodsAuto').val();
	Business.forSearch(id, text);
});

//$("#feedback").click(function(e){
//	e.preventDefault();
//	parent.tab.addTabItem({tabid: 'myService', text: '服务支持', url: '/service/service.jsp', callback: function(){
//		parent.document.getElementById('myService').contentWindow.openTab(3);
//	}});
//});

$('.bulk-import').click(function(e){
  e.preventDefault();
  if (!Business.verifyRight('BaseData_IMPORT')) {
	  return ;
  };
  parent.$.dialog({
	  width: 560,
	  height: 300,
	  title: '批量导入',
	  content: 'url:/import.jsp',
	  data: {
		  callback: function(row){

		  }
	  },
	  lock: true
  });
});

//$('#manageAcct').click(function(){
//    var updateUrl = location.protocol + '//' + location.host + '/update_info.jsp',
//    url = 'http://service.xxxx.com/user/set_password.jsp?updateUrl=' + encodeURIComponent(updateUrl) + '&userName' + parent.SYSTEM.userName;
//    $.dialog({
//        min: false,
//        max: false,
//        cancle: false,
//        lock: true,
//        width: 450,
//        height: 470,
//        title: '账号管理',
//        content: 'url:' + url
//    });
//});

//公告
//(function (){
//	var URL = parent.CONFIG.SERVICE_URL, SYSTEM = parent.SYSTEM;
//	var version;
//	switch (SYSTEM.siVersion) {
//		case 3:
//		  version = '1';
//		  break;
//		case 4:
//		  version = '3';
//		  break;
//		default:
//		  version = '2';
//	};
//	var param = '?eventType=2&serviceId=' + SYSTEM.DBID;	//自带参数
//	$.getJSON( URL+ "asy/Services.ashx?callback=?", {coid : SYSTEM.DBID, loginuserno: SYSTEM.UserName, version: version, type: 'getsystemmsg' + SYSTEM.servicePro}, function(data){ 
//		if(data.msg == 'success'){
//			if(data.data.length == 0){
//				return;
//			}
//			var $notices = $('<span class="notices" id="notices"></span>'), 
//				html = [], 
//				notice,
//				li = '';
//			data = data.data;
//			for(var i=0; i<data.length; i++){
//				notice = data[i];
//				if(notice.msglink){
//					li = '<li><a target="_blank" href="' + notice.msglink + param + '" title="' + notice.msgtitle + '" data-id="' + notice.msgid + '"><i></i>' + notice.msgtitle + '</a></li>'
//				}else{
//					li = '<li><a href="/service/service.jsp?newsId=' + notice.msgid + '" rel="pageTab" tabId="myService" tabTxt="服务支持" parentOpen="true" title="' + notice.msgtitle + '" data-id="' + notice.msgid + '"><i></i>' + notice.msgtitle + '</a></li>'
//				}
//				html.push(li);
//			}
//			$notices.append('<ul>' + html.join('') + '</ul>').appendTo('.welcome');
//			Public.txtSlide();
//		}
//	});
//})();
</script>
</body>
</html>