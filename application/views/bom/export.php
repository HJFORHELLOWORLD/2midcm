<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
		<table class="table" width="1500"  border="1">
			<thead>
			    <tr>
				    <th colspan="12" align="center"><h3>商品明细表</h3></th>
				</tr>
				
				<tr>
				    <th width="60" align="center">序号</th>
					<th width="180" >商品编号</th>
					<th width="180" align="center">商品名称</th>
					<th width="150" align="center">规格型号</th>
					<th width="100" align="center">商品类别</th>	
					<th width="100" align="center">计量单位</th>	
					<th width="120" align="center">预计采购价</th>
					<th width="120" align="center">预计销售价</th>	
					<th width="120" align="center">备注</th>	
					<th width="100" align="center">库存数量</th>
					<th width="100" align="center">单位成本</th>	
					<th width="100" align="center">初期总价</th>	
				</tr>
			</thead>
			<tbody>
			  <?php 
			  $i = 1;
			  foreach($list as $arr=>$row) {
			  ?>
				<tr target="id">
				    <td ><?=$i?></td>
					<td ><?=$row['number']?></td>
					<td ><?=$row['name']?></td>
					<td ><?=$row['spec']?></td>
					<td ><?=$row['categoryname']?></td>
					<td ><?=$row['unitname']?></td>
					<td ><?=$row['purprice']?></td>
					<td ><?=$row['saleprice']?></td>
					<td ><?=$row['remark']?></td>
					<td ><?=$row['quantity']?></td>
					<td ><?=$row['unitcost']?></td>
					<td ><?=$row['amount']?></td>
				</tr>
				<?php $i++;}?>
 
 </tbody>
</table>	
