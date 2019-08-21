<?php if(!defined('BASEPATH')) exit('No direct script access allowed');?>
  		<table width=100% class="caption">
  			<tr><td class='H'><h3>商品收发汇总表</h3></td></tr>
  			<tr><td>日期：<?=$stt?> 至 <?=$ett?></td></tr>
  		</table>
  		 
  		<table class="list" border="1">
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
  		