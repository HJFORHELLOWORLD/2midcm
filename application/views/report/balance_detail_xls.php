<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<table width=100% class="caption">
  			<tr><td class='H'><h3>应收账款明细表</h3></td></tr>
  			<tr><td>日期：<?=$stt?>至<?=$ett?></td></tr>
  		</table>
  		<table width="1440px" class="list" border="1">
  			<thead>
  				<tr>
  				<td>客户</td>
  				<td>单据日期</td>
  				<td>单据编号</td>
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