<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
        <?php foreach($list1 as $arr1=>$row1) {?>
		<table width="1500"  border="0">
		    <tr target="id" align="center">
				<td colspan="6" ><h3>其他<?=$type?>单</h3></td>
		    </tr>
			<tr target="id">
<!--				<td colspan="2">供应商：<?/*=$row1['contactname']*/?></td>-->
			    <td colspan="2">单据日期：<?=$row1['billdate']?></td>
				<td colspan="2">单据编号：<?=$row1['billno']?></td>
<!--				<td colspan="2" align="right">业务类型: <?/*=$row1['type']==1?'购货':'退货'*/?></td>-->
			</tr>
		</table>	
		
		<table width="1500"  border="1">
				<tr>
					<td width="30" align="center"><b>序号<b></td>
					<td width="150" ><b>商品<b></td>
					<td width="60" align="center"><b>单位<b></td>
					<td width="60" align="center"><b>数量<b></td>
					<td width="110" align="center"><b><?=$type?>单价<b></td>
					<td width="110" align="center"><b><?=$type?>金额<b></td>
				</tr>
			  <?php 
			  $i = 1;
			  foreach($list2 as $arr2=>$row2) {
			      if ($row1['id']==$row2['invoiid']) {
			  ?>
				<tr>
				    <td align="center"><?=$i?></td>
					<td ><?=$row2['number']?> <?=$row2['goodsname']?>_<?=$row2['spec']?></td>
					<td ><?=$row2['unitname']?></td>
					<td ><?=str_money(abs($row2['qty']))?></td>
					<td ><?=str_money(abs($row2['price']))?></td>
					<td ><?=abs($row2['price']) * abs($row2['qty'])?></td>
				</tr>
				
				<?php $i++;}}?>
				<tr>
				    <td colspan="3" align="right" >合计：</td>
					<td ><?=str_money($row1['totalqty'])?></td>
					<td ></td>
					<td ><?=str_money(abs($row1['totalamount']))?></td>
				</tr>
<!--				<tr target="id">
				    <td colspan="8" >合计：</td>
				</tr>-->
		</table>	

		<table width="1500"  border="0">
<!--				<tr target="id">
					<td colspan="2">折扣额：<?/*=str_money($row1['disamount'])*/?></td>
					<td colspan="2" >折扣金额：<?/*=str_money($row1['amount'])*/?></td>
					<td colspan="2" >本次<?/*=$row1['type']==1?'付款':'退款'*/?>：<?/*=str_money($row1['rpamount'])*/?></td>
					<td colspan="2" align="right" >本次欠款: <?/*=str_money($row1['arrears'])*/?></td>
				</tr>-->
				<tr target="id">
					<td colspan="8" > 备注：<?=$row1['description']?> </td>
				</tr>
				<tr target="id">
					<td>制单人：<?=$row1['username']?></td>
					<td></td>
					<td></td>
<!--					<td>收货人签： </td>
					<td></td>
					<td>供应商签字:  </td>
					<td></td>-->
				</tr>
		</table>	
 
<br><br><br>				 
				 
<?php }?>