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

var invsa_lists = "<?=site_url('invsa/lists')?>";
var invsa_add   = "<?=site_url('invsa/add')?>";
var invsa_edit   = "<?=site_url('invsa/edit')?>";
var invsa_info   = "<?=site_url('invsa/info')?>";
var invsa_del   = "<?=site_url('invsa/del')?>";
var report_balance_detail = "<?=site_url('report/balance_detail')?>";
var report_balance_detail_xls = "<?=site_url('report/balance_detail_xls')?>";
</script>
<link rel="stylesheet" href="<?=skin_url()?>/css/report.css" />
<style>
.filter-list li.chk-list{height: 20px;}
.mod-report{position: relative;*zoom: 1;padding:66px 0 0 18px;}
.mod-report .search-wrap{position: fixed;left: 0;top: 0;width: 100%;_position:absolute;_left:expression(eval(document.documentElement.scrollLeft));_top:expression(eval(document.documentElement.scrollTop));background: #f5f5f5;}
.mod-report .search-wrap .s-inner{padding: 18px;}
.mod-report .search-wrap strong.tit{font-size:14px;line-height: 30px;}
.mod-report .search-wrap .txt{display: inline-block;*display: inline;*zoom: 1;font-size: 14px;line-height: 30px;}

.mod-report .grid-wrap:after{content: '.';display: block;clear: both;visibility: hidden;overflow: hidden;height: 0;}
.mod-report .grid-wrap{*zoom: 1;}
.mod-report .grid-wrap .grid{float: left;padding: 18px;border:1px solid #cfcfcf;background: #fff;box-shadow:0 1px 3px rgba(0,0,0,0.2);}
.mod-report .grid-wrap .H{font-size:24px;font-weight:bold;text-align: center;}
.mod-report .grid-wrap .R{text-align: right;}
.mod-report .grid-wrap .B{font-weight: bold;}
.mod-report .grid-wrap table{border-collapse:collapse;}
.mod-report .grid-wrap table.caption{margin-bottom: 5px;}
.mod-report .grid-wrap table.list{border:1px solid #666;}
.mod-report .grid-wrap table.list td{padding: 5px 5px;border:1px solid #666;}
.mod-report .grid-wrap table.list thead td{text-align: center;font-weight: bold;}
.link{ cursor:pointer; }
.tr-hover{ background:#f8ff94;}

#filter-menu .mod-choose-input{position: relative;*zoom: 1;}
#filter-menu .mod-choose-input .ui-input{padding-right: 25px;width:226px; font-family:"宋体";}
#filter-menu .ui-datepicker-input{width:105px; font-family:"宋体";}
.ui-icon-ellipsis{ right:3px; }

thead{word-break: keep-all;white-space:nowrap;}

@media print{
body{background: #fff;}
.mod-report{padding: 0;}
.mod-report .search-wrap{display: none;}
.mod-report .grid-wrap .grid{float: none;padding: 0;border:none;background: none;box-shadow:none;}
.mod-report .grid-wrap table.caption{margin-bottom: 0;}
.mod-report .grid-wrap table.list{width:100%;}
.mod-report .grid-wrap table.list td{padding: 1px;}
}
</style>
<script>
var defParams = {
	beginDate: '2014-09-01',
	endDate: '2014-09-19'
};
/*$(function(){
	$('.list').width($(window).width() - 74);
});
$(window).resize(function(){
	$('.list').width($(window).width() - 74);
});*/
</script>
</head>
<body>
<div class="mod-report">
  <div class="search-wrap" id="report-search">
    <div class="s-inner cf">
      <div class="fl"> <strong class="tit mrb fl">查询条件</strong>
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
              <li>
                <label class="tit">客户:</label>
                <span class="mod-choose-input" id="filter-customer"><input type="text" class="ui-input" id="customerAuto"/><span class="ui-icon-ellipsis"></span></span>
              </li>
            </ul>
            <div class="btns"> <a href="#" id="conditions-trigger" class="conditions-trigger" tabindex="-1">更多条件<b></b></a> <a class="ui-btn ui-btn-sp" id="filter-submit" href="#">确定</a> <a class="ui-btn" id="filter-reset" href="#" tabindex="-1">重置</a> </div>
          </div>
        </div>
        <a id="refresh" class="ui-btn ui-btn-refresh fl mrb"><b></b></a> <span class="txt fl" id="cur-search-tip"></span> </div>
      <div class="fr"><a href="#" class="ui-btn ui-btn-sp mrb fl" id="btn-print">打印</a><a href="#" class="ui-btn fl" id="btn-export">导出</a></div>
    </div>
  </div>
 
  <div class="grid-wrap">
  	<div class="grid">
  		<table width=100% class="caption">
  			<tr><td class='H'>应收账款明细表</td></tr>
  			<tr><td>日期：<?=$stt?>至<?=$ett?></td></tr>
  		</table>
  		<table width="1440px" class="list">
  			<thead>
  				<tr>
  				<td>客户</td>
  				<td>订单日期</td>
  				<td>订单编号</td>
  				<td>业务类型</td>
  				<td>应收款</td>
  				<td>已收款</td>
  				<td>收款余额</td>
  				</tr>
  			</thead>
  			<tbody>
  			    <?php 
				$amount1   = 0;
				$rpamount1 = 0;
				$arrears1  = 0;
				$amount2   = 0;
				$rpamount2 = 0;
				$arrears2  = 0;
				$amount3   = 0;
				$rpamount3 = 0;
				$arrears3  = 0;
				foreach($list1 as $arr1=>$row1){
					
					$arrears1  += $row1['amount']-$row1['periodmoney'];    //欠款
				?>
  				<tr class="link" data-id="0" data-type="">
  			       <td><?=$row1['number']?> <?=$row1['name']?></td>
  			       <td></td>
  			       <td>期初余额</td>
  			       <td></td>
  			       <td class="R"></td>
  			       <td class="R"></td>
  			       <td class="R"><?=str_money($row1['amount']-$row1['periodmoney'])?></td>
  				</tr>
  			    <?php foreach($list2 as $arr2=>$row2){?>
				<?php if($row1['id']==$row2['contactid']) {
						  $amount2   += $row2['amount'];
						  $rpamount2 += $row2['rpamount'];
						  $arrears2  += $row2['arrears'];
						  
				?>
  				<tr class="link" data-id="<?=$row2['id']?>" data-type="SALE">
  			       <td><?=$row2['contactname']?></td>
  			       <td><?=$row2['billdate']?></td>
  			       <td><?=$row2['billno']?></td>
  			       <td><?=$row2['type']==1 ? '普通销售' : '销售退回'?></td>
  			       <td class="R"><?=str_money($row2['amount'])?></td>
				   <td class="R"><?=str_money($row2['rpamount'])?></td>
				   <td class="R"><?=str_money($row2['arrears'])?></td>
  				</tr>
  			    <?php }}
				$amount3   += $amount2;
				$rpamount3 += $rpamount2;
				$arrears3  += $row1['amount']-$row1['periodmoney']+$arrears2;
				?>
  			  
  				<tr class="link" data-id="0" data-type="">
  			       <td></td>
  			       <td></td>
  			       <td>小计</td>
  			       <td></td>
  			       <td class="R"><?=str_money($amount2)?></td>
  			       <td class="R"><?=str_money($rpamount2)?></td>
  			       <td class="R"><?=str_money($row1['amount']-$row1['periodmoney']+$arrears2)?></td>
  				</tr>
  			    <?php 
					$amount2   = 0;
					$rpamount2 = 0;
					$arrears2  = 0;
				}
				?>
  				
  			 
  			    <tr>
  				<td colspan="4" class="R B">合计：</td>
  				<td class="R B"><?=str_money($amount3)?></td>
  				<td class="R B"><?=str_money($rpamount3)?></td>
  				<td class="R B"><?=str_money($arrears3)?></td>
  				</tr>
  		
  			</tbody>
  		</table>
  	</div>
  </div>

  
  
</div>
<script src="<?=skin_url()?>/js/dist/accountProceedsDetail.js?3"></script>
</body>
</html>