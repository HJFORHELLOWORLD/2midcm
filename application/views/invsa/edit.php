<?php if(!defined('BASEPATH')) exit('No direct script access allowed');?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=1280, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<title>在线进销存</title>
<link href="<?=skin_url()?>/css/common.css?ver=20140815" rel="stylesheet" type="text/css">
<link href="<?=skin_url()?>/css/<?=skin()?>/ui.min.css?ver=20140815" rel="stylesheet">
<script src="<?=skin_url()?>/js/common/libs/jquery/jquery-1.10.2.min.js"></script>
<script src="<?=skin_url()?>/js/common/libs/json2.js"></script>
<script src="<?=skin_url()?>/js/common/common.js?ver=20140815"></script>
<script src="<?=skin_url()?>/js/common/grid.js?ver=20140815"></script>
<script src="<?=skin_url()?>/js/common/plugins.js?ver=20140815"></script>
<script src="<?=skin_url()?>/js/common/plugins/jquery.dialog.js?self=true&ver=20140815"></script>
<script type="text/javascript">
try{
	document.domain = '<?=base_url()?>';
}catch(e){
	//console.log(e);
}
</script>

<script type="text/javascript">
var WDURL = "";
var SCHEME= "<?=skin()?>";
var icon_url = "<?=skin_url()?>/css/base/dialog/icons/";
var settings_customer_manage = "<?=site_url('settings/customer_manage')?>";
var settings_vendor_manage = "<?=site_url('settings/vendor_manage')?>";
var settings_vendor_batch = "<?=site_url('settings/vendor_batch')?>";
var settings_customer_batch = "<?=site_url('settings/customer_batch')?>";
var basedata_settlement = "<?=site_url('basedata/settlement')?>";
var settings_settlement_manage = "<?=site_url('settings/settlement_manage')?>";
var basedata_category = "<?=site_url('basedata/category')?>";
var basedata_category_type= "<?=site_url('basedata/category_type')?>";
var settings_goods_manage = "<?=site_url('settings/goods_manage')?>";
var settings_goods_batch  = "<?=site_url('settings/goods_batch')?>";
var basedata_goods = "<?=site_url('basedata/goods')?>";
var basedata_unit  = "<?=site_url('basedata/unit')?>";
var settings_unit_manage = "<?=site_url('settings/unit_manage')?>";
var basedata_contact  = "<?=site_url('basedata/contact')?>";
var settings_inventory =  "<?=site_url('settings/inventory')?>";
var settings_skins =  "<?=site_url('settings/skins')?>";
var settings_select_customer =  "<?=site_url('settings/select_customer')?>";


var invsa_lists = "<?=site_url('invsa/lists')?>";
var invsa_add   = "<?=site_url('invsa/add')?>";
var invsa_edit   = "<?=site_url('invsa/edit')?>";
var invsa_info   = "<?=site_url('invsa/info')?>";
var invsa_del   = "<?=site_url('invsa/del')?>";
var invsa_export   = "<?=site_url('invsa/export')?>";
var api_invsa_print   = "<?=site_url('api/invsa_print')?>";
var plug_down = "<?=base_url()?>/install_lodop32.rar";
</script>
<script language="javascript" src="<?=skin_url()?>/js/common/plugins/print/LodopFuncs.js?2"></script>
<link href="<?=skin_url()?>/css/<?=skin()?>/bills.css" rel="stylesheet" type="text/css">
<style>
#bottomField{line-height:30px;}
#bottomField label{width: 75px;display: inline-block;}
.con-footer{padding:10px 0 0 0;}
</style>
</head>

<body>
<div class="wrapper">
    <div class="mod-toolbar-top mr0 cf dn" id="toolTop"></div>
    <div class="bills" style="width:1192px;">
        <div class="con-header">
            <dl class="cf">
                <dd class="pct30">
                    <label>客户:</label>
                    <span class="ui-combo-wrap" id="customer">
          <input type="text" name="" class="input-txt" autocomplete="off" value="" data-ref="date">
          <i class="ui-icon-ellipsis"></i></span></dd>
                <dd class="pct25 tc">
                    <label>订单日期:</label>
                    <input type="text" id="date" class="ui-input ui-datepicker-input" value="">
                </dd>
                <dd id="identifier" class="pct25 tc">
                    <label>订单编号:</label>
                    <span id="number"></span></dd>
                <!--        <dd id="classes" class="pct20 tr">
                          <label class="radio">
                            <input type="radio" name="classes" value="1">
                            报价</label>
                        </dd>-->
            </dl>
        </div>
        <div class="grid-wrap">
            <table id="grid">
            </table>
            <div id="page"></div>
        </div>
        <div class="con-footer cf">
            <div class="mb10">

                <div class="label-wrap">
                    <label>订单名称:</label>
                </div>
                <div class="ctn-wrap">
                    <input type="text" id="orderName" class="ui-input"  style="width:100%; margin:0 -6px; ">
                </div>
            </div>

            <div class="mb10">
                <div class="label-wrap">
                    <label>付款条件:</label>
                </div>
                <div class="ctn-wrap">
                    <input type="text" id="paymentType" class="ui-input"  style="width:100%; margin:0 -6px; ">
                </div>
            </div>
            <!--      <div id="amountArea">
                      <p>
                        <label>折扣率:</label>
                        <input type="text" id="discountRate" class="ui-input" data-ref="deduction">%
                        <span>
                        <label>折扣额:</label>
                        <input type="text" id="deduction" class="ui-input" data-ref="payment">
                        </span>
                        <span>
                        <label>折后金额:</label>
                        <input type="text" id="discount" class="ui-input ui-input-dis" data-ref="discountRate" disabled>
                        </span></p>
                      <p>
                        <label id="paymentTxt">本次付款:</label>
                        <input type="text" id="payment" class="ui-input">&emsp;
                        <span id="accountWrap" class="dn">
                        <label>结算账户:</label>
                          <span class="ui-combo-wrap" id="account" style="padding:0;">
                          <input type="text" class="input-txt" autocomplete="off">
                          <i class="trigger"></i></span><a id="accountInfo" class="ui-icon ui-icon-folder-open" style="display:none;"></a>
                        </span>
                        <span>
                        <label>本次欠款:</label>
                        <input type="text" id="arrears" class="ui-input ui-input-dis" disabled>
                        </span><span class="dn">
                        <label>累计欠款:</label>
                        <input type="text" id="totalArrears" class="ui-input ui-input-dis" disabled>
                        </span></p>
                    </div>-->

        </div>
        <div class="cf" id="bottomField">
            <div class="fr" id="toolBottom"></div>
            <div>
                <label>制单人:</label>
                <span id="userName"></span>
            </div>
        </div>
        <div id="mark"></div>
    </div>

    <div id="initCombo" class="dn">
        <input type="text" class="textbox goodsAuto" name="goods" autocomplete="off">
        <input type="text" class="textbox storageAuto" name="storage" autocomplete="off">
    </div>
    <div id="storageBox" class="shadow target_box dn">
    </div>
</div>
<script src="<?=skin_url()?>/js/dist/sales.js?cc23"></script>
</body>
</html>