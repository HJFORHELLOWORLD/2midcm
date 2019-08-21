<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
    <table width="1500"  border="1">
        <tr>
            <td width="60" align="center"><b>编号<b></td>
            <td width="200" align="center"><b>bom设计<b></td>
            <td width="200" align="center"><b>描述<b></td>
            <td width="250" align="center"><b>工作中心编号<b></td>
            <td width="100" align="center"><b>上位编号<b></td>
            <td width="100" align="center"><b>下位编号<b></td>
            <td width="400" align="center"><b>下位数量<b></td>
            <td width="150" align="center"><b>计算方法<b></td>
            <td width="150" align="center"><b>计算公式<b></td>
            <td width="150" align="center"><b>管理系数<b></td>
            <td width="150" align="center"><b>创建人<b></td>
            <td width="150" align="center"><b>创建时间<b></td>
            <td width="150" align="center"><b>变更人<b></td>
            <td width="150" align="center"><b>变更时间<b></td>

        </tr>
        <?php
        $i = 1;
        foreach($data as $arr=>$row) {?>
            <tr>
                <td align="center"><?=$i?></td>
                <td align="center"><?=$row['pk_bom_desi_id']?></td>
                <td align="center"><?=$row['name']?></td>
                <td align="center"><?=$row['desc']?></td>
                <td align="center"><?=$row['wc_id']?></td>
                <td align="center"><?=$row['upBom_id']?></td>
                <td align="center"><?=$row['downBom_id']?></td>
                <td align="center"><?=$row['downBom_amount']?></td>
                <td align="center"><?=$row['method']?></td>
                <td align="center"><?=$row['formula']?></td>
                <td align="center"><?=$row['des_coef']?></td>
                <td align="center"><?=$row['creator_id']?></td>
                <td align="center"><?=$row['create_date']?></td>
                <td align="center"><?=$row['modify_id']?></td>
                <td align="center"><?=$row['modify_date']?></td>
                </tr>
            <?php $i++;}?>
    </table>

