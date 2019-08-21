<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
  		<table width=100% class="caption">
  			<tr><td class='H'><h3>销售汇总表（按客户）</h3></td></tr>
  			<tr><td>日期：<?=$stt?>至<?=$ett?></td></tr>
  		</table>
  		<table width="1200px" class="list" border="1">
  			<thead>
  				<tr>
  					<td width="150">客户</td>
	  				<td width="200">商品名称</td>
	  				<td width="50">单位</td>
	  				<td width="80">数量</td>
	  				<td width="80">单价</td>
	  				<td width="100">销售收入</td>
	  				
  				</tr>
  			</thead>
  			<tbody>
  			     <?php 
				 $qty    = 0;
				 $price  = 0;
				 $amount = 0;
				 foreach($list as $arr=>$row){?>
  			       <tr class="link" data-buNo="" data-locationNo="CK001" data-invNo="<?=$row['goodsno']?>" data-type="supply">
  			       <td><?=$row['contactname']?></td>
  			       <td><?=$row['goodsno']?> <?=$row['goodsname']?>_<?=$row['spec']?></td>
				   <td><?=$row['unitname']?></td>
				   <td class="R"><?=$row['qty']?></td>
  			       <td class="R"><?=$row['price']?></td>
  			       <td class="R"><?=$row['amount']?></td>
  			       </tr>
				   <?php 
				 $qty    += $row['qty'];
				 $price  += $row['price'];
				 $amount += $row['amount'];
				 }?>
  			     
  			       
  				<tr>
  				<td colspan="3" class="R B">合计：</td>
  				<td class="R B"><?=$qty?></td>
  				<td class="R B"></td>
  				<td class="R B"><?=$amount?></td>
  				
  				</tr>
  			</tbody>
  		</table>