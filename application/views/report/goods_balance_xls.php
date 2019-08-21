<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<table width=100% class="caption">
  			<tr><td class='H'><h3>商品库存余额表</h3></td>
  			</tr>
  			<tr><td>日期：<?=$stt?> 至 <?=$ett?></td></tr>
  		</table>
  		<table class="list" border="1">
  			<thead>
  				<tr>
  				<td rowspan="2" width="250">商品名称</td>
  				<td rowspan="2" width="50">单位</td>	
  				<td colspan="2" align="center">所有</td> 
  				</tr>
  				<tr>
  				<td>数量</td>
  				<td>成本</td>
  				</tr>
  			</thead>
  			<tbody>
  			   <?php 
			   $qty = 0;
			   $sum = 0;
			   foreach($list as $arr=>$row) {
				   $qty += $row['qty'];
				   $sum += $row['puamount'];
			   ?>
			   
  				<!--<tr class="link" data-invNo="00019" data-locationNo="001" data-type="inv">-->
				<tr class="link" >
  					<!--<td><div style="width:60px;"><?=$row['number']?></div></td>-->
  					<td><div style="width:150px;"><?=$row['number']?> <?=$row['name']?>_<?=$row['spec']?></div></td>
  					<!--<td><div style="width:60px;"><?=$row['spec']?></div></td>-->
  					<td><div style="width:50px;"><?=$row['unitname']?></div></td>
  					<td class="R"><?=str_money($row['qty'])?></td>
  					<td class="R"><?=str_money($row['puamount'])?></td>
  				</tr>
  				<?php }?> 
  				<tr>
  					<td colspan="2" class="R B">合计</td>
  					<td class="R B"><?=str_money($qty)?></td>
  					<td class="R B"><?=str_money($sum)?></td>
  				</tr>
  			</tbody>
  		</table>