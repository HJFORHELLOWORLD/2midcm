<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<table width=100% class="caption">
  			<tr><td class='H'><h3>往来单位欠款表</h3></td></tr>
  			<tr><td>日期：<?=$stt?> 至  <?=$ett?></td></tr>
  		</table>
  		<table width="700px" class="list" border="1">
  			<thead>
  				<tr>
	  				<td width="30">行号</td>
	  				<!--<td>往来单位编号</td>-->
	  				<td width="150">往来单位名称</td>
	  				<td width="80">往来单位性质</td>
	  				<td width="70">应收款余额</td>
	  				<td width="70">应付款余额</td>
  				</tr>
  			</thead>
  			<tbody>
  			    <?php 
				 $i = 1;
				 $arrears1  = 0;
				 $arrears2  = 0;
				 foreach($list1 as $arr=>$row){?>
  				<tr>
  			       <td><?=$i?></td>
  			       <!--<td>0001</td>-->
  			       <td><?=$row['number']?> <?=$row['name']?></td>
  			       <td>客户</td>
  			       <td class="R"><?=str_money($row['arrears']);?></td>
  			       <td class="R">0</td>
  				</tr>
  			    <?php 
				 $arrears1   += $row['arrears'];
				 $i++;
				 }?>

  			    <?php foreach($list2 as $arr=>$row){?>
  				<tr>
  			       <td><?=$i?></td>
  			       <!--<td>0003</td>-->
  			       <td><?=$row['number']?> <?=$row['name']?></td>
  			       <td>供应商</td>
  			       <td class="R">0</td>
  			       <td class="R"><?=str_money($row['arrears']);?></td>
  				</tr>
  			    <?php 
				 $arrears2   += $row['arrears'];
				 $i++;}?>

  			 
  				<tr>
  				<td colspan="3" class="R B" >合计：</td>
  				<td class="R B"><?=str_money($arrears1)?></td>
  				<td class="R B"><?=str_money($arrears2)?></td>
  				</tr>
  			</tbody>
  		</table>	
