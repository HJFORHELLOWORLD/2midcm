<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
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
var settings_parameter =  "<?=site_url('settings/parameter')?>";  
</script>


<style>
#para-wrapper{font-size:14px; }
#para-wrapper .para-item{margin-bottom:30px;}
#para-wrapper .para-item h3{font-size:14px;font-weight:bold;margin-bottom:10px;}

.mod-form-rows .label-wrap { width:128px; }
.para-item .ui-input{width:220px;font-size:14px;}

.subject-para .ui-input{width:40px;}

.code-length .ui-spinbox-wrap{margin-right:0;}

.books-para input{margin-top:-3px;}

#currency{width: 68px;}
.ui-droplist-wrap .list-item {font-size:14px;}
</style>
</head>
<body>
<div class="wrapper">
  <div id="para-wrapper">
    <div class="para-item">
      <h3>基础参数</h3>
      <ul class="mod-form-rows" id="establish-form">
        <li class="row-item">
          <div class="label-wrap">
            <label for="companyName">公司名称：</label>
          </div>
          <div class="ctn-wrap">
            <input type="text" name="companyname" value="<?=COMPANYNAME?>" class="ui-input" id="companyName" />
          </div>
        </li>
        <li class="row-item">
          <div class="label-wrap">
            <label for="companyAddress">公司地址：</label>
          </div>
          <div class="ctn-wrap">
            <input type="text" name="companyaddress" class="ui-input" value="<?=COMPANYADDRESS?>" id="companyAddress" />
          </div>
        </li>
        <li class="row-item">
          <div class="label-wrap">
            <label for="companyTel">公司电话：</label>
          </div>
          <div class="ctn-wrap">
            <input type="text" name="companytel" class="ui-input" value="<?=COMPANYTEL?>" id="companyTel" />
          </div>
        </li>
        <li class="row-item">
          <div class="label-wrap">
            <label for="companyFax">公司传真：</label>
          </div>
          <div class="ctn-wrap">
            <input type="text" name="companyfax" class="ui-input" value="<?=COMPANYFAX?>" id="companyFax" />
          </div>
        </li>
        <li class="row-item">
          <div class="label-wrap">
            <label for="postcode">公司邮编：</label>
          </div>
          <div class="ctn-wrap">
            <input type="text" name="postcode" class="ui-input" value="<?=POSTCODE?>" id="postcode" />
          </div>
        </li>
        
        
      </ul>
    </div>
    
    <div class="btn-wrap"> <a id="save" class="ui-btn ui-btn-sp">保存</a> </div>
  </div>
</div>
<script src="<?=skin_url()?>/js/dist/parameter.js?1"></script>
</body>
</html>