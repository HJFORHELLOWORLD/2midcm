<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
		<table class="table" width="1500"  border="1">
			<thead>
			    <tr>
				    <th colspan="7" align="center"><h3>盘点表</h3></th>
				</tr>
				
				<tr>
					<th width="100" >仓库编号</th>
					<th width="100" align="center">物料编号</th>
					<th width="70" align="center">单位成本</th>
					<th width="80" align="center">系统库存</th>	
					<th width="80" align="center">盘点库存</th>
				</tr>
			</thead>
			<tbody>
			  <?php foreach($list as $arr=>$row) {?>
				<tr target="id">
				    <td ><?=$row['stock_id']?></td>
					<td >No.<?=$row['bom_id']?></td>
					<td ><?=$row['cost']?></td>
					<td ><?=$row['account']?></td>
					<td ><?=$row['qty']?></td>
					<td ></td>
				</tr>
				<?php }?>
 
 </tbody>
</table>	
