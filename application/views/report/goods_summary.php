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

var inventory_lists= "<?=site_url('inventory/lists')?>";
var inventory_query= "<?=site_url('inventory/query')?>";
var inventory_export= "<?=site_url('inventory/export')?>";
var inventory_generator = "<?=site_url('inventory/generator')?>";
var report_goods_summary = "<?=site_url('report/goods_summary')?>";
var report_goods_summary_xls = "<?=site_url('report/goods_summary_xls')?>";
</script>
<link rel="stylesheet" href="<?=skin_url()?>/css/report.css" />
<script>
var defParams = {
	beginDate: '2014-09-01',
	endDate: '2014-09-19'
};
</script>
<style type="text/css">
html {height:auto; overflow:auto; }
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
  
  
  <div class="grid-wrap">
  	<div class="grid">
  		<table width=100% class="caption">
  			<tr><td class='H'>商品收发汇总表</td></tr>
  			<tr><td>日期：<?=$stt?> 至 <?=$ett?></td></tr>
  		</table>
  		<table class="list">
  			<thead>
  				<tr>
  				<!--<td rowspan="2" width="50">商品编号</td>-->
  				<td rowspan="2" width="150">商品名称</td>
  				<!--<td rowspan="2" width="50">规格型号</td>-->
  				<td rowspan="2">单位</td>
  						<td colspan="2">期初</td>
  							<td colspan="2">普通采购</td> 
  							<td colspan="2">盘盈</td> 
  							<td colspan="2">其他入库</td>
  							<td colspan="2">入库合计</td> 
  							<td colspan="2">采购退回</td> 
  							<td colspan="2">普通销售</td> 
  							<td colspan="2">盘亏</td> 
  							<td colspan="2">其他出库</td>
  							<td colspan="2">出库合计</td> 
  							<td colspan="2">结存</td> 
  				</tr>
  				<tr>
  				<td>数量</td>
  				<td>成本</td>
  				<td>数量</td>
  				<td>成本</td>
  				<td>数量</td>
  				<td>成本</td>
  				<td>数量</td>
  				<td>成本</td>
  				<td>数量</td>
  				<td>成本</td>
  				<td>数量</td>
  				<td>成本</td>
  				<td>数量</td>
  				<td>成本</td>
  				<td>数量</td>
  				<td>成本</td>
  				<td>数量</td>
  				<td>成本</td>
  				<td>数量</td>
  				<td>成本</td>
  				<td>数量</td>
  				<td>成本</td>
  				</tr>
  			</thead>
  			<tbody>
  			   <?php 
			   $quantity = 0;
			   $quantitytotal = 0;
			   $puqty1 = 0;
			   $puamount1 = 0;
			   $oiqty2 = 0;
			   $oiamount2 = 0;
			   $oiqty1 = 0;
			   $oiamount1 = 0;
			   $puqty = 0;
			   $puqtytotal = 0;
			   $puqty2 = 0;
			   $puamount2 = 0;
			   $saqty1 = 0;
			   $saamount1 = 0;
			   $oiqty4 = 0;
			   $oiamount3 = 0;
			   $oiqty3 = 0;
			   $oiamount4 = 0;
			   $saqty = 0;
			   $saqtytotal = 0;
			   $qty = 0;
			   $qtytotal = 0;
			   foreach($list as $arr=>$row) {
				   $quantity += $row['quantity'];
				   $quantitytotal += $row['quantity']*$row['unitcost'];
				   $puqty1 += $row['puqty1'];
				   $puamount1 += $row['puamount1'];
				   $oiqty2 += $row['oiqty2'];
				   $oiamount2 += $row['oiqty2']*$row['price'];
				   $oiqty1 += $row['oiqty1'];
				   $oiamount1 += $row['oiqty1']*$row['price'];
				   $puqty += $row['puqty'];
				   $puqtytotal += $row['puqty']*$row['price'];
				   $puqty2 += $row['puqty2'];
				   $puamount2 += $row['puqty2']*$row['price'];
				   $saqty1 += $row['saqty1'];
				   $saamount1 += $row['saqty1']*$row['price'];
				   $oiqty4 = +$row['oiqty4'];
				   $oiamount4 += $row['oiqty4']*$row['price'];
				   $oiqty3 = $row['oiqty3'];
				   $oiamount3 += $row['oiqty3']*$row['price']; 
				   $saqty += $row['saqty'];
				   $saqtytotal += $row['saqty']*$row['price'];
				   $qty += $row['qty'];
				   $qtytotal += ($row['qty']-$row['quantity'])*$row['price']+$row['quantity']*$row['unitcost'];
			   ?>
  				<!--<tr class="link" data-invNo="00019" data-locationNo="001" data-type="inv">-->
				<tr class="link" >
  					<!--<td><div style="width:60px;"><?=$row['number']?></div></td>-->
  					<td><div style="width:150px;"><?=$row['number']?> <?=$row['name']?>_<?=$row['spec']?></div></td>
  					<!--<td><div style="width:60px;"><?=$row['spec']?></div></td>-->
  					<td><div style="width:50px;"><?=$row['unitname']?></div></td>
  					<td class="R"><?=str_money($row['quantity'])?></td>
  				    	<td class="R"><?=str_money($row['quantity']*$row['unitcost'])?></td>
	  							<td class="R"><?=str_money($row['puqty1'])?></td>
	  							<td class="R"><?=str_money($row['puamount1'])?></td>
	  							<td class="R"><?=str_money($row['oiqty2'])?></td>
	  							<td class="R"><?=str_money($row['oiqty2']*$row['price'])?></td>
	  							<td class="R"><?=str_money($row['oiqty1'])?></td>
	  							<td class="R"><?=str_money($row['oiqty1']*$row['price'])?></td>
	  					<td class="R"><?=str_money($row['puqty'])?></td>
	  						<td class="R"><?=str_money($row['puqty']*$row['price'])?></td>
	  							<td class="R"><?=str_money(abs($row['puqty2']))?></td>
	  							<td class="R"><?=str_money(abs($row['puqty2'])*$row['price'])?></td>
	  							<td class="R"><?=str_money(abs($row['saqty1']))?></td>
	  							<td class="R"><?=str_money(abs($row['saqty1'])*$row['price'])?></td>
	  							<td class="R"><?=str_money(abs($row['oiqty4']))?></td>
	  							<td class="R"><?=str_money(abs($row['oiqty4']*$row['price']))?></td>
	  							<td class="R"><?=str_money(abs($row['oiqty3']))?></td>
	  							<td class="R"><?=str_money(abs($row['oiqty3']*$row['price']))?></td>
	  					<td class="R"><?=str_money(abs($row['saqty']))?></td>
	  						<td class="R"><?=str_money(abs($row['saqty']*$row['price']))?></td>
  					<td class="R"><?=str_money($row['qty'])?></td>
  						<td class="R"><?=str_money(($row['qty']-$row['quantity'])*$row['price']+$row['unitcost']*$row['quantity'])?></td>
  				</tr>
  				<?php }?> 
  				<tr>
  					<td colspan="2" class="R B">合计</td>
  					<td class="R B"><?=str_money($quantity)?></td>
  					<td class="R B"><?=str_money($quantitytotal)?></td>
	  							<td class="R B"><?=str_money($puqty1)?></td>
	  							<td class="R B"><?=str_money($puamount1)?></td>
	  							<td class="R B"><?=str_money($oiqty2)?></td>
	  							<td class="R B"><?=str_money($oiamount2)?></td>
	  							<td class="R B"><?=str_money($oiqty1)?></td>
	  							<td class="R B"><?=str_money($oiamount1)?></td>
	  					<td class="R B"><?=str_money($puqty)?></td>
	  					<td class="R B"><?=str_money($puqtytotal)?></td>
	  							<td class="R B"><?=str_money(abs($puqty2))?></td>
	  							<td class="R B"><?=str_money(abs($puamount2))?></td>
	  							<td class="R B"><?=str_money(abs($saqty1))?></td>
	  							<td class="R B"><?=str_money(abs($saamount1))?></td>
	  							<td class="R B"><?=str_money(abs($oiqty4))?></td>
	  							<td class="R B"><?=str_money(abs($oiamount4))?></td>
	  							<td class="R B"><?=str_money(abs($oiqty3))?></td>
	  							<td class="R B"><?=str_money(abs($oiamount3))?></td>
	  					<td class="R B"><?=str_money(abs($saqty))?></td>
	  					<td class="R B"><?=str_money(abs($saqtytotal))?></td>
  					<td class="R B"><?=str_money(abs($qty))?></td>
  					<td class="R B"><?=str_money($qtytotal)?></td>
  				</tr>
  			</tbody>
  		</table>
  	</div>
  </div>
  
  
  
</div>
<script src="<?=skin_url()?>/js/dist/goods-flow-summary.js?3"></script>
</body>
</html>