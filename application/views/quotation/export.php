<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
        <?php foreach($list1 as $arr1=>$row1) {?>
		<table width="1500"  border="0">
		    <tr target="id" align="center">
				<td colspan="8" ><h3>销货单</h3></td>
		    </tr>
			<tr target="id">
				<td colspan="2">客户：<?=$row1['contactname']?></td>
			    <td colspan="2">订单日期：<?=$row1['billdate']?></td>
				<td colspan="2">订单编号：<?=$row1['billno']?></td>
				<td colspan="2" align="right">业务类型: <?=$row1['type']==1?'销货':'退货'?></td>
			</tr>
		</table>	
		
		<table width="1500"  border="1">
				<tr>
					<td width="30" align="center"><b>序号<b></td>
					<td width="150" ><b>商品<b></td>
					<td width="60" align="center"><b>单位<b></td>
					<td width="60" align="center"><b>数量<b></td>
					<td width="110" align="center"><b>销售单价<b></td>	
					<td width="110" align="center"><b>折扣率(%)<b></td>	
					<td width="110" align="center"><b>折扣额<b></td>	
					<td width="110" align="center"><b>销售金额<b></td>	
				</tr>
			  <?php 
			  $i = 1;
			  foreach($list2 as $arr2=>$row2) {
			      if ($row1['id']==$row2['invsaid']) {
			  ?>
				<tr>
				    <td align="center"><?=$i?></td>
					<td ><?=$row2['number']?> <?=$row2['goodsname']?>_<?=$row2['spec']?></td>
					<td ><?=$row2['unitname']?></td>
					<td ><?=abs($row2['qty'])?></td>
					<td ><?=$row2['price']?></td>
					<td ><?=$row2['discountrate']?></td>
					<td ><?=$row2['deduction']?></td>
					<td ><?=abs($row2['amount'])?></td>
				</tr>
				
				<?php $i++;}}?>
				<tr>
				    <td colspan="3" align="right" >合计：</td>
					<td ><?=$row1['totalqty']?></td>
					<td ></td>
					<td ></td>
					<td ></td>
					<td ><?=$row1['totalamount']?></td>
				</tr>
				<tr target="id">
				    <td colspan="8" >合计：</td>
				</tr>
		</table>	

		<table width="1500"  border="0">
				<tr target="id">
					<td colspan="2">折扣额：<?=$row1['disamount']?></td>
					<td colspan="2" >折扣金额：<?=$row1['amount']?></td>
					<td colspan="2" >本次<?=$row1['type']==1?'付款':'退款'?>：<?=$row1['rpamount']?></td>
					<td colspan="2" align="right" >本次欠款: <?=$row1['arrears']?></td>
				</tr>
				<tr target="id">
					<td colspan="8" > 备注：<?=$row1['description']?> </td>
				</tr>
				<tr target="id">
					<td>制单人：<?=$row1['username']?></td>
					<td></td>
					<td></td>
					<td>发货人签： </td>
					<td></td>
					<td>客户签字:  </td>
					<td></td>
				</tr>
		</table>	
 
<br><br><br>				 
				 
<?php }?>