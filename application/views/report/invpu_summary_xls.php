<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
  		<table width=100% class="caption">
  			<tr><td class='H'><h3>采购汇总表（按商品）</h3></td></tr>
  			<tr><td>日期：<?=$stt?>至<?=$ett?></td></tr>
  		</table>
  		<table class="list" border="1">
  			<thead>
  				<tr>
	  				<td width="200px">商品名称</td>
	  				<td width="50px">单位</td>
	  				<td width="80px">数量</td>
	  				<td width="80px">单价</td>
	  				<td width="100px">采购金额</td>
	  				
  				</tr>
  			</thead>
  			<tbody>
  			    <?php 
				 $qty    = 0;
				 $price  = 0;
				 $amount = 0;
				 foreach($list as $arr=>$row){?>
  				<tr class="link" data-invNo="<?=$row['goodsno']?>" data-locationNo="<?=$row['goodsno']?>" data-type="inv">
  			       <td><?=$row['goodsno']?> <?=$row['goodsname']?>_<?=$row['spec']?></td>
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
  				<td colspan="2" class="R B">合计：</td>
  				<td class="R B"><?=$qty?></td>
  				<td class="R B"><?=$qty>0?str_money($amount/$qty):0?></td>
  				<td class="R B"><?=$amount?></td>
  				</tr>
  			</tbody>
  		</table>
