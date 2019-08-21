<?php if(!defined('BASEPATH')) exit('No direct script access allowed');?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=1280, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<title>在线进销存</title>
<!--<link href="<?=skin_url()?>/css/common.css?ver=20140815" rel="stylesheet" type="text/css">-->
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

var invpu_lists = "<?=site_url('invpu/lists')?>";
var invpu_add   = "<?=site_url('invpu/add')?>";
var invpu_edit   = "<?=site_url('invpu/edit')?>";
var invpu_info   = "<?=site_url('invpu/info')?>";
var invpu_del   = "<?=site_url('invpu/del')?>";
var report_invpu_detail = "<?=site_url('report/invpu_detail')?>";
var report_invpu_detail_xls = "<?=site_url('report/invpu_detail_xls')?>";
</script>
 
<style>
@charset "utf-8";
/* CSS Document */
article, aside, details, figcaption, figure, footer, header, hgroup, menu, nav, section { display: block; }
body, h1, h2, h3, h4, h5, h6, hr, p, blockquote, dl, dt, dd, ul, ol, li, pre, form, fieldset, legend, button, input, textarea, th, td { margin: 0; padding: 0; }
body, button, input, select, textarea { font: 12px/1.5 arial, \5b8b\4f53; color:#555; }
h1, h2, h3, h4, h5, h6 { font-size: 100%; }
address, cite, dfn, em, var { font-style: normal; }
code, kbd, pre, samp { font-family: courier new, courier, monospace; }
small { font-size: 12px; }
ul, ol { list-style: none; }
a { text-decoration: none; color:#555; cursor:pointer; }
a:hover { text-decoration: none; color:#3B87B1; }
sup { vertical-align: text-top; }
sub { vertical-align: text-bottom; }
legend { color: #000; }
fieldset, img { border: 0; }
button, input, select, textarea { font-size: 100%; }


html{_text-overflow:ellipsis; overflow:hidden;}
html,body {height:100%;}
thead{word-break: keep-all;white-space:nowrap;}
/* 表格 */
.ui-print {display:inline-block; width:100%;}
/*.ui-print .grid-wrap {  padding: 8px; margin-right:18px;  box-shadow: 0 1px 3px rgba(0,0,0,0.2); border: 1px solid #cfcfcf; clear: both;}*/

.ui-print .ui-widget-content {border:none;}
.ui-print .ui-jqgrid .ui-jqgrid-btable {*margin-top: -1px;}
.ui-print .ui-jqgrid {border:1px solid #999;}
.ui-print .ui-jqgrid .ui-jqgrid-sdiv {border-top: 1px solid #666;}
.ui-print .ui-jqgrid .ui-jqgrid-sdiv td {border-top:none;}
.ui-print .ui-jqgrid .frozen-div {background:#fff !important;}
.ui-print .ui-jqgrid .frozen-div .ui-jqgrid-resize-ltr {margin: 0 -2px 0 0px;}
.filter-list li.chk-list{height: 10px;}
/*.mod-report{position: relative;*zoom: 1;padding:66px 0 0 18px;}*/
/*.mod-report .search-wrap{position: fixed;left: 0;top: 0;width: 100%;_position:absolute;_left:expression(eval(document.documentElement.scrollLeft));_top:expression(eval(document.documentElement.scrollTop));background: #f5f5f5;}
.mod-report .search-wrap .s-inner{padding: 18px;}
.mod-report .search-wrap strong.tit{font-size:14px;line-height: 30px;}
.mod-report .search-wrap .txt{display: inline-block;*display: inline;*zoom: 1;font-size: 14px;line-height: 30px;}*/

/*.mod-report .grid-wrap:after{content: '.';display: block;clear: both;visibility: hidden;overflow: hidden;height: 0;}
.mod-report .grid-wrap{*zoom: 1;}*/
/*.mod-report .grid-wrap .grid{float: left;padding: 18px;border:1px solid #cfcfcf;background: #fff;box-shadow:0 1px 3px rgba(0,0,0,0.2);}*/
.mod-report .grid-wrap .H{font-size:20px;font-weight:bold;text-align: center;}
.mod-report .grid-wrap .R{text-align: right;}
.mod-report .grid-wrap .B{font-weight: bold;}
.mod-report .grid-wrap table{border-collapse:collapse;}
.mod-report .grid-wrap table.caption{margin-bottom: 5px;}
.mod-report .grid-wrap table.list{border:1px solid #666;}
/*.mod-report .grid-wrap table.list td{padding: 5px 5px;border:1px solid #666;} //调整表格大小*/
.mod-report .grid-wrap table.list td{border:1px solid #666;}
.mod-report .grid-wrap table.list thead td{text-align: center;font-weight: bold;}
.link{ cursor:pointer; }
.tr-hover{ background:#f8ff94;}
.mod-report {
    overflow: auto;
    padding: 10px 0 0 18px;             /* 顶部间隔 */
    position: relative;
}

thead{word-break: keep-all;white-space:nowrap;}


</style>
</head>
<body>
<div class="mod-report">
 
 <div class="grid-wrap">
  	<div class="grid">
	<?php for($n=1;$n<ceil(count($list)/13);$n++){?>
		<table width="690"  border="0">
		    <tr align="center">
				<td colspan="8" height="15"><h2 style="font-size:14px"><?=COMPANYNAME?></h1></td>
		    </tr>
		    <tr target="id" align="center">
				<td colspan="8" height="15"><h2 style="font-size:14px">销货(出库)单</h2></td>
		    </tr>
			<tr>
				<td colspan="2" height="15">客户：<?=$contactname?></td>
			    <td colspan="2">订单日期：<?=$billdate?></td>
				<td colspan="2">订单编号：<?=$billno?></td>
				<td width="133" colspan="2" align="right">业务类型: <?=$type==1?'销货':'退货'?></td>
			</tr>
			<tr>
				<td colspan="2" height="15">联系人：<?=$links['name']?></td>
			    <td colspan="2">电话：<?=$links['phone']?></td>
				<td colspan="2">地址：<?=$links['address']?></td>
				<td width="133" colspan="2" align="right"></td>
			</tr>
		</table>	
		
		<table class="list" width="690">
				<tr height="15">
					<td width="100" align="center"><b>商品编号</b></td>
					<td width="180" align="center"><b>商品名称</b></td>
					<td width="50" align="center"><b>单位</b></td>
					<td width="50" align="center"><b>数量</b></td>
					<td width="50" align="center"><b>单价</b></td>	
					<td width="50" align="center"><b>金额</b></td>	
					<td width="120" align="center"><b>备注</b></td>	
				</tr>
			  <?php 
			  foreach($list as $arr=>$row) {
			  ?>
				<tr height="15">
				    <td width="100" align="center"><?=$row['number']?></td>
					<td width="180" align="center"><?=$row['goodsname']?></td>
					<td width="50" align="center"><?=$row['unitname']?></td>
					<td width="50" align="center"><?=str_money(abs($row['qty']))?></td>
					<td width="50" align="center"><?=str_money($row['price'])?></td>
					<td width="50" align="center"><?=str_money(abs($row['amount']))?></td>
					<td width="120"><?=$row['description']?></td>
				</tr>
				<?php }?>
				<?php for($i=count($list);$i<13;$i++) {?>
				<tr>
				    <td width="100" align="center">&nbsp;</td>
					<td width="180" align="center">&nbsp;  </td>
					<td width="50" align="center">&nbsp;</td>
					<td width="50" align="center">&nbsp;</td>
					<td width="50" align="center">&nbsp;</td>
					<td width="50" align="center">&nbsp;</td>
					<td width="120" align="center">&nbsp;</td>
				</tr>
				<?php }?>
				 
				<tr target="id">
				    <td colspan="7" >备注：<?=$description?></td>
				</tr>
				 
				<tr target="id">
				    <td colspan="3" >金额大写：<?=str_num2rmb($totalamount)?></td>
					<td colspan="2">合计数量：<?=str_money($totalqty)?> </td>
					<td colspan="2">合计金额：<?=str_money($totalamount)?> </td>
				</tr>
			
				<tr target="id">
				    <td colspan="4" height="10">发出部门：上海总仓 &nbsp;&nbsp;&nbsp;&nbsp; 业务：李明芳 &nbsp;&nbsp;&nbsp;&nbsp; 制单：<?=$username?></td>
					<td colspan="3" rowspan="2"  >     <span style="font-size:18px">客户签收：</span> </td>
				</tr>
				
				<tr target="id">
				    <td colspan="4" height="10">主管审批：   &nbsp;   &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; 核对：  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;     &nbsp;&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;配送人员： </td>
				</tr>
		</table>	
		
		
		<table width="690"  border="0">
				<tr target="id">
					<td height="15" >公司地址：<?=COMPANYADDRESS?></td>
					<td > </td>
					<td > </td>
					<td > </td>
				</tr>
				
				<tr target="id">
					<td height="15" >欢迎监督我的工作，投诉电话：18118198922 </td>
					<td ></td>
					<td > </td>
					<td > </td>
				</tr>
				
				<tr target="id">
					<td height="15" >1白联：存根 2红联：客户 </td>
					<td ></td>
					<td >  </td>
					<td > </td>
				</tr>
				 
		</table>	
        <?php }?>
 		 
  	</div>
  </div>
</div>
</body>
</html>