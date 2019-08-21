<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<table width=100% class="caption">
  			<tr><td class='H'><h3>商品采购明细表</h3></td></tr>
  			<tr><td>日期：<?=$stt?>至<?=$ett?></td></tr>
  		</table>
  		<table class="list" border="1">
  			<thead>
  				<tr>
  				<td width="60">采购日期</td>
  				<td width="90">采购订单编号</td>
  				<td width="60">业务类别</td>
  				<td width="150">供应商</td>
  				<td width="150">商品名称</td>
  				<td width="50">单位</td>
  				<td width="50">数量</td>
  				<td width="50">单价</td>
  				<td width="80">采购金额</td>
  				</tr>
  			</thead>
  			<tbody>
  			     <?php 
				 $qty    = 0;
				 $price  = 0;
				 $amount = 0;
				 foreach($list as $arr=>$row){?>
  			       <tr class="link" data-id="<?=$row['invpuid']?>" data-type="PUR">
  			       <td><?=$row['billdate']?></td>
  			       <td><?=$row['billno']?></td>
  			       <td><?=$row['type']==1?'购货':'退货'?></td>
  			       <td><?=$row['contactname']?></td>
  			       <td><?=$row['number']?> <?=$row['goodsname']?>_<?=$row['spec']?></td>
  			       <td><?=$row['unitname']?></td>
  			       <td class="R"><?=str_money($row['qty'])?></td>
  			       <td class="R"><?=str_money($row['price'])?></td>
  			       <td class="R"><?=str_money($row['amount'])?></td>
  			       </tr>
  			     <?php 
				 $qty    += $row['qty'];
				 $price  += $row['price'];
				 $amount += $row['amount'];
				 }?>
  				<tr>
  				<td colspan="6" class="R B">合计：</td>
  				<td class="R B"><?=str_money($qty)?></td>
  				<td class="R B"><?=$qty>0?str_money($amount/$qty):0?></td>
  				<td class="R B"><?=str_money($amount)?></td>
  				</tr>
  			</tbody>
  		</table>