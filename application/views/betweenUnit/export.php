<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
		<table class="table" width="1600"  border="1">
			<thead>
				<tr>
				    <th colspan="12" align="center"><h3>往来单位列表</h3></th>
				</tr>
				<tr>
				    <th width="100" align="center">往来单位编号</th>
					<th width="180" >单位名称</th>
					<th width="180" align="center">描述</th>
					<th width="150" align="center">地区编号</th>
					<th width="100" align="center">类别</th>
					<th width="100" align="center">所属行业</th>
					<th width="120" align="center">税率</th>
					<th width="120" align="center">客户联系方式</th>
					<th width="100" align="center">状态</th>
					<th width="100" align="center">创建人</th>
					<th width="100" align="center">创建时间</th>
					<th width="100" align="center">变更人</th>
					<th width="150" align="center">变更时间</th>
				</tr>
			</thead>
			<tbody>
			  <?php 
			  $i = 1;
			  foreach($list as $arr=>$row) {
//				  if (strlen($row['linkmans'])>0) {                               //获取首个联系人
//					  $array = (array)json_decode($row['linkmans']);
//					  foreach ($array as $arr1=>$row1) {
//						  if ($row1->linkFirst==1) {
//							  $name        = $row1->linkName;
//							  $mobile      = $row1->linkMobile;
//							  $phone       = $row1->linkPhone;
//							  $im          = $row1->linkIm;
//							  $first       = $row1->linkFirst==1 ? true : false;
//							  $address     = $row1->linkAddress;
//						  }
//					  }
//				  }
			  ?>
				<tr target="id">
					<td >No.<?=$row['pk_bu_id']?></td>
					<td ><?=$row['name']?></td>
					<td ><?=$row['desc']?></td>
					<td ><?=$row['area_id']?></td>
					<td ><?=$row['bu_cat']?></td>
					<td ><?=$row['industry_id']?></td>
					<td ><?=$row['taxRate']?></td>
					<td ><?=isset($linkMans)?$linkMans:''?></td>
					<td ><?=isset($status)?$status:'1'?></td>
					<td ><?=isset($creator_id)?$creator_id:''?></td>
					<td ><?=isset($create_date)?$create_date:''?></td>
					<td ><?=isset($modify_id)?$modify_id:''?></td>
					<td ><?=isset($modify_date)?$modify_date:''?></td>
				</tr>
				<?php $i++;}?>
 
 </tbody>
</table>	
