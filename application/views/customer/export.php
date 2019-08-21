<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
		<table class="table" width="1500"  border="1">
			<thead>
				<tr>
				    <th colspan="12" align="center"><h3>客户列表</h3></th>
				</tr>
				<tr>
				    <th width="150" align="center">客户编号</th>
					<th width="180" >客户名称</th>
					<th width="180" align="center">客户类别</th>
					<th width="150" align="center">余额日期</th>
					<th width="100" align="center">期初应收款</th>	
					<th width="100" align="center">期初预收款</th>	
					<th width="120" align="center">备注</th>
					<th width="120" align="center">联系人</th>	
					<th width="100" align="center">手机</th>	
					<th width="100" align="center">座机</th>	
					<th width="100" align="center">QQ/MSN</th>	
					<th width="100" align="center">送货地址</th>
				</tr>
			</thead>
			<tbody>
			  <?php 
			  $i = 1;
			  foreach($list as $arr=>$row) {
				  if (strlen($row['linkmans'])>0) {                               //获取首个联系人
					  $array = (array)json_decode($row['linkmans']);
					  foreach ($array as $arr1=>$row1) {
						  if ($row1->linkFirst==1) {
							  $name        = $row1->linkName;
							  $mobile      = $row1->linkMobile; 
							  $phone       = $row1->linkPhone; 
							  $im          = $row1->linkIm; 
							  $first       = $row1->linkFirst==1 ? true : false; 
							  $address     = $row1->linkAddress; 
						  }
					  } 
				  }
			  ?>
				<tr target="id">
					<td >No.<?=$row['number']?></td>
					<td ><?=$row['name']?></td>
					<td ><?=$row['categoryname']?></td>
					<td ><?=$row['begindate']?></td>
					<td ><?=$row['amount']?></td>
					<td ><?=$row['periodmoney']?></td>
					<td ><?=$row['remark']?></td>
					<td ><?=isset($name)?$name:''?></td>
					<td ><?=isset($mobile)?$mobile:''?></td>
					<td ><?=isset($phone)?$phone:''?></td>
					<td ><?=isset($im)?$im:''?></td>
					<td ><?=isset($address)?$address:''?></td>
				</tr>
				<?php $i++;}?>
 
 </tbody>
</table>	
