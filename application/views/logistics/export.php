<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
    <table width="1500"  border="1">
        <tr>
            <td width="60" align="center"><b>序号<b></td>
            <td width="200" align="center"><b>销售单号<b></td>
            <td width="200" align="center"><b>物流单号<b></td>
            <td width="250" align="center"><b>客户名<b></td>
            <td width="100" align="center"><b>货物件数<b></td>
            <td width="100" align="center"><b>操作人<b></td>
            <td width="400" align="center"><b>备注<b></td>
            <td width="150" align="center"><b>添加日期<b></td>
        </tr>
        <?php
        $i = 1;
        foreach($data as $arr=>$row) {?>
            <tr>
                <td align="center"><?=$i?></td>
                <td align="center"><?=$row['billno']?></td>
                <td align="center"><?=$row['logistics_no']?></td>
                <td align="center"><?=$row['contactname']?></td>
                <td align="center"><?=$row['num']?></td>
                <td align="center"><?=$row['operatorName']?></td>
                <td align="center"><?=$row['remark']?></td>
                <td align="center"><?=$row['create_time']?></td>
                </tr>
            <?php $i++;}?>
    </table>

