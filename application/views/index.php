<?php if(!defined('BASEPATH')) exit('No direct script access allowed');?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=1280, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<title>在线进销存</title>
<link href="<?=skin_url()?>/css/common.css?ver=20190505" rel="stylesheet" type="text/css">
<link href="<?=skin_url()?>/css/<?=skin()?>/ui.min.css?ver=20190505" rel="stylesheet">
<script src="<?=skin_url()?>/js/common/libs/jquery/jquery-1.10.2.min.js"></script>
<script src="<?=skin_url()?>/js/common/libs/json2.js"></script>
<script src="<?=skin_url()?>/js/common/common.js?ver=20190505"></script>
<script src="<?=skin_url()?>/js/common/grid.js?ver=20190505"></script>
<script src="<?=skin_url()?>/js/common/plugins.js?ver=20190505"></script>
<script src="<?=skin_url()?>/js/common/plugins/jquery.dialog.js?self=true&ver=20190505"></script>
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
var icon_url = "<?=skin_url()?>/css/base/dialog/icons/";                      //图片路径
var settings_customer_manage = "<?=site_url('settings/customer_manage')?>";   //新增修改客户 
var basedata_customer = "<?=site_url('basedata/customer')?>";                 //客户列表
var settings_vendor_manage = "<?=site_url('settings/vendor_manage')?>";       //新增供应商
var settings_vendor_batch = "<?=site_url('settings/vendor_batch')?>";             //批量选择供应商 
var basedata_vendor = "<?=site_url('basedata/vendor')?>";                     //供应商列表
var basedata_settlement = "<?=site_url('basedata/settlement')?>";             //结算方式列表
var settings_settlement_manage = "<?=site_url('settings/settlement_manage')?>";       //新增修改结算方式
var basedata_category = "<?=site_url('basedata/category')?>";                     //分类列表
var basedata_category_type= "<?=site_url('basedata/category_type')?>";            //分类分类
var settings_goods_manage = "<?=site_url('settings/goods_manage')?>";       //新增修改商品
var settings_goods_batch  = "<?=site_url('settings/goods_batch')?>";        //批量选择商品
var basedata_goods = "<?=site_url('basedata/goods')?>";                     //商品
//var basedata_unit  = "<?//=site_url('basedata/unit')?>//";                      //单位
var settings_unit_manage = "<?=site_url('settings/unit_manage')?>";       //单位增修改 
var settings_skins =  "<?=site_url('settings/skins  ')?>";  
</script>
<link href="<?=skin_url()?>/css/base.css" rel="stylesheet" type="text/css">
<link href="<?=skin_url()?>/css/<?=skin()?>/default.css?a" rel="stylesheet" type="text/css" id="defaultFile">
<script src="<?=skin_url()?>/js/common/tabs.js?ver=20190505"></script>

<script>
var CONFIG = {
	DEFAULT_PAGE: true,
	SERVICE_URL: '<?=base_url()?>'
};
//系统参数控制
var SYSTEM = {
	version: 1,
	skin: "<?=skin()?>",         //皮肤
	curDate: '<?=time()?>',    //系统当前日期
	DBID: '<?=$uid?>',         //账套ID
	serviceType: '12',         //账套类型，13：表示收费服务，12：表示免费服务
	realName: '<?=$name?>',    //真实姓名
	userName: '<?=$username?>',         //用户名
	companyName: '<?=COMPANYNAME?>',	//公司名称
	companyAddr: '',	//公司地址
	phone: '',	        //公司电话
	fax: '',	        //公司传真
	postcode: '',	    //公司邮编
	startDate: '2014-09-01', //启用日期
	currency: 'RMB',	     //本位币
	qtyPlaces: '2',	         //数量小数位
	pricePlaces: '2',	     //单价小数位
	amountPlaces: '2',       //金额小数位
	valMethods:	'movingAverage',	//存货计价方法
	invEntryCount: '300',           //试用版单据分录数
	rights: {},//权限列表
	billRequiredCheck: 0,  //是否启用单据审核功能  1：是、0：否
	requiredCheckStore: 1, //是否检查负库存  1：是、0：否
	hasOnlineStore: 0,	   //是否启用网店
	enableStorage: 0,	   //是否启用仓储
	genvChBill: 0,	       //生成凭证后是否允许修改单据
	requiredMoney: 0,      //是否启用资金功能  1：是、0：否
	taxRequiredCheck: 0,
	taxRequiredInput: 17,
	isAdmin:true,   //是否管理员
	siExpired:false,//是否过期
	siType:1,       //服务版本，1表示基础版，2表示标准版
	siVersion:1,    //1表示试用、2表示免费（百度版）、3表示收费，4表示体验版
	Mobile:"",      //当前用户手机号码
	isMobile:false, //是否验证手机
	isshortUser:false,  //是否联邦用户
	shortName:"",       //shortName
	main_url:"<?=site_url('home/main')?>",   //首页
	clear_url:"<?=site_url('home/clear')?>",   //清理系统缓存
	isOpen:false,        //是否弹出手机验证,
};
//区分服务支持
SYSTEM.servicePro = SYSTEM.siType === 2 ? 'forbscm3' : 'forscm3';
var cacheList = {};	//缓存列表查询
//全局基础数据
(function(){
	/*
	 * 判断IE6，提示使用高级版本
	 */
	if(Public.isIE6) {
		 var Oldbrowser = {
			 init: function(){
				 this.addDom();
			 },
			 addDom: function() {
			 	var html = $('<div id="browser">您使用的浏览器版本过低，影响网页性能，建议您换用<a href="http://www.google.cn/chrome/intl/zh-CN/landing_chrome.html" target="_blank">谷歌</a>、<a href="http://download.microsoft.com/download/4/C/A/4CA9248C-C09D-43D3-B627-76B0F6EBCD5E/IE9-Windows7-x86-chs.exe" target="_blank">IE9</a>、或<a href=http://firefox.com.cn/" target="_blank">火狐浏览器</a>，以便更好的使用！<a id="bClose" title="关闭">x</a></div>').insertBefore('#container').slideDown(500); 
			 	this._colse();
			 },
			 _colse: function() {
				  $('#bClose').click(function(){
						 $('#browser').remove();
				 });
			 }
		 };
		 Oldbrowser.init();
	};
	getGoods();
	//getStorage();
	getCustomer();
	getSupplier();
	getUnitcostHide();
})();

//缓存商品信息
function getGoods() {
	if(SYSTEM.isAdmin || SYSTEM.rights.INVENTORY_QUERY) {
		Public.ajaxGet('<?=site_url('basedata/goods')?>', {}, function(data){
			if(data.status === 200) {
				SYSTEM.goodsInfo = data.data.rows;
			} else if (data.status === 250){
				SYSTEM.goodsInfo = [];
			} else {
				Public.tips({type: 1, content : data.msg});
			}
		});
	} else {
		SYSTEM.goodsInfo = [];
	}
};

function getUnitcostHide() {
    Public.ajaxGet('<?=site_url('sheet/unitcostHide')?>', {}, function (data) {
        if (data.status === 200) {
            SYSTEM.unitcostHide = data.data;
        }
    });
};

/*缓存仓库信息*/
// function getStorage() {
// 	if(SYSTEM.isAdmin || SYSTEM.rights.INVLOCTION_QUERY) {
// 		Public.ajaxGet('http://images.xxxxxx.com/basedata/invlocation.do?action=list&isDelete=2', {}, function(data){
// 			if(data.status === 200) {
// 				SYSTEM.allStorageInfo = data.data.items;
// 				SYSTEM.storageInfo = [];
// 				$.each(SYSTEM.allStorageInfo, function(i, n){
// 					if(n['delete'] === false){
// 						SYSTEM.storageInfo.push(n);
// 					};
// 				});
// 			} else if (data.status === 250){
// 				SYSTEM.allStorageInfo = [];
// 				SYSTEM.storageInfo = [];
// 			}  else {
// 				Public.tips({type: 1, content : data.msg});
// 			}
// 		});
// 	} else {
// 		SYSTEM.allStorageInfo = [];
// 		SYSTEM.storageInfo = [];
// 	}
// };

//缓存客户信息
function getCustomer() {
	if(SYSTEM.isAdmin || SYSTEM.rights.BU_QUERY) {
		Public.ajaxGet('<?=site_url('basedata/contact')?>?type=1', {}, function(data){
			if(data.status === 200) {
				SYSTEM.customerInfo = data.data.items;
			} else if (data.status === 250){
				SYSTEM.customerInfo = [];
			} else {
				Public.tips({type: 1, content : data.msg});
			}
		});
	} else {
		SYSTEM.customerInfo = [];
	}
};

//缓存供应商信息
function getSupplier() {
	if(SYSTEM.isAdmin || SYSTEM.rights.PUR_QUERY) {
		Public.ajaxGet('<?=site_url('basedata/contact')?>?type=2', {}, function(data){
			if(data.status === 200) {
				SYSTEM.supplierInfo = data.data.items;
			} else if (data.status === 250){
				SYSTEM.supplierInfo = [];
			} else {
				Public.tips({type: 1, content : data.msg});
			}
		});
	} else {
		SYSTEM.supplierInfo = [];
	}
};

//缓存账户信息
function getAccounts() {
	if(true) {
		Public.ajaxGet('<?=site_url('basedata/vendor')?>?action=list', {}, function(data){
			if(data.status === 200) {
				SYSTEM.accountInfo = data.data.items;
			} else if (data.status === 250){
				SYSTEM.accountInfo = [];
			} else {
				Public.tips({type: 1, content : data.msg});
			}
		});
	} else {
		SYSTEM.accountInfo = [];
	}
};


//缓存结算方式
function getPayments() {
	if(true) {
		Public.ajaxGet('<?=site_url('basedata/settlement')?>', {}, function(data){
			if(data.status === 200) {
				SYSTEM.paymentInfo = data.data.items;
			} else if (data.status === 250){
				SYSTEM.paymentInfo = [];
			} else {
				Public.tips({type: 1, content : data.msg});
			}
		});
	} else {
		SYSTEM.paymentInfo = [];
	}
};

//左上侧版本标识控制
function markupVension(){
	var imgSrcList = {
				base:'<?=skin_url()?>/css/blue/img/icon_v_b.png',	//基础版正式版
				baseExp:'<?=skin_url()?>/css/blue/img/icon_v_b_e.png',	//基础版体验版
				baseTrial:'<?=skin_url()?>/css/blue/img/icon_v_b_t.png',	//基础版试用版
				standard:'<?=skin_url()?>/css/blue/img/icon_v_s.png', //标准版正式版
				standardExp:'<?=skin_url()?>/css/blue/img/icon_v_s_e.png', //标准版体验版
				standardTrial :'<?=skin_url()?>/css/blue/img/icon_v_s_t.png' //标准版试用版
			};
	var imgModel = $("<img id='icon-vension' src='' alt=''/>");
	if(SYSTEM.siType === 1){
		switch(SYSTEM.siVersion){
			case 1:	imgModel.attr('src',imgSrcList.baseTrial).attr('alt','基础版试用版');
				break;
			case 2:	imgModel.attr('src',imgSrcList.baseExp).attr('alt','免费版（百度版）');
				break;
			case 3: imgModel.attr('src',imgSrcList.base).attr('alt','基础版');//标准版
				break;
			case 4: imgModel.attr('src',imgSrcList.baseExp).attr('alt','基础版体验版');//标准版
				break;
		};
	} else {
		switch(SYSTEM.siVersion){
			case 1:	imgModel.attr('src',imgSrcList.standardTrial).attr('alt','标准版试用版');
				break;
			case 3: imgModel.attr('src',imgSrcList.standard).attr('alt','标准版');//标准版
				break;
			case 4: imgModel.attr('src',imgSrcList.standardExp).attr('alt','标准版体验版');//标准版
				break;
		};
	};
	
	//$('#col-side').prepend(imgModel);
};

</script>
<!--<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "//hm.baidu.com/hm.js?0613c265aa34b0ca0511eba4b45d2f5e";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>-->
</head>
<body>
<div id="container" class="cf">
  <div id="col-side">
<!--    <img id="icon-vension" alt="正式版" src="<?/*=skin_url()*/?>/css/<?/*=skin()*/?>/img/icon_v_b_t.png">-->
    <ul id="nav" class="cf">
    <li class="item item-vip"> <a href="javascript:void(0);" class="vip main-nav">高级<span class="arrow">&gt;</span></a>
        <div class="sub-nav-wrap group-nav group-nav-t0 vip-nav cf">
          <div class="nav-item nav-onlineStore">
            <h3>网店</h3>
            <ul class="sub-nav" id="vip-onlineStore">
          	</ul>
          </div>
          <div class="nav-item nav-JDstore last">
            <h3>京东仓储</h3>
            <ul class="sub-nav" id="vip-JDStorage">
          	</ul>
          </div>
          </div>
      </li>
      <li class="item item-purchase"> <a href="javascript:void(0);" class="purchase main-nav">购货<span class="arrow">&gt;</span></a>
        <div class="sub-nav-wrap single-nav">
          <ul class="sub-nav" id="purchase">
          </ul>
        </div>
      </li>
      <li class="item item-sales"> <a href="javascript:void(0);" class="sales main-nav">销货<span class="arrow">&gt;</span></a>
        <div class="sub-nav-wrap single-nav">
          <ul class="sub-nav" id="sales">
          </ul>
        </div>
      </li>
      <li class="item item-storage"> <a href="javascript:void(0);" class="storage main-nav">仓库<span class="arrow">&gt;</span></a>
        <div class="sub-nav-wrap single-nav">
          <ul class="sub-nav" id="storage">
          </ul>
        </div>
      </li>           
      <!--<li class="item item-money"> <a href="javascript:void(0);" class="money main-nav">资金<span class="arrow">&gt;</span></a>
        <div class="sub-nav-wrap single-nav">
          <ul class="sub-nav" id="money"> 
          </ul>
        </div>
      </li>-->
<!--      <li class="item item-report"> <a href="javascript:void(0);" class="report main-nav">报表<span class="arrow">&gt;</span></a>
        <div class="sub-nav-wrap group-nav group-nav-b0 report-nav cf">
          <div class="nav-item nav-pur">
            <h3>采购报表</h3>
            <ul class="sub-nav" id="report-purchase">
            </ul>
          </div>
          <div class="nav-item nav-sales">
            <h3>销售报表</h3>
            <ul class="sub-nav" id="report-sales">
            </ul>
          </div>
          <div class="nav-item nav-fund">
            <h3>仓存报表</h3>
            <ul class="sub-nav" id="report-storage">
            </ul>
          </div>
          
          <div class="nav-item nav-fund last">
            <h3>资金报表</h3>
            <ul class="sub-nav" id="report-money">
            </ul>
          </div>
          
       </div>
      </li>-->
      <li class="item item-setting"> <a href="javascript:void(0);" class="setting main-nav">设置<span class="arrow">&gt;</span></a>
        <div class="sub-nav-wrap cf group-nav group-nav-b0 setting-nav">
          <div class="nav-item">
            <h3>基础资料</h3>
            <ul class="sub-nav" id="setting-base">
            </ul>
          </div>
          <div class="nav-item">
            <h3>辅助资料</h3>
            <ul class="sub-nav" id="setting-auxiliary">
            </ul>
          </div>
          <div class="nav-item cf last">
            <h3>高级设置</h3>
            <ul class="sub-nav" id="setting-advancedSetting">
            </ul>
            <ul class="sub-nav" id="setting-advancedSetting-right">
            </ul>
          </div>
        </div>
      </li>
    </ul>
    <!--<div id="navScroll" class="cf"><span id="scollUp"><i>dd</i></span><span id="scollDown"><i>aa</i></span></div>-->
    <!--<a href="#" class="side_fold">收起</a>--> 
  </div>
  <div id="col-main">
    <div id="main-hd" class="cf">
      <div class="tit"> <span class="company" id="companyName"></span> <span class="period" id="period"></span> </div>
      <ul class="user-menu">
      	<li class="qq"><a href="http://wpa.qq.com/msgrd?v=3&uin=1585335003&site=qq&menu=yes" target="_blank">QQ咨询：1585335003</a></li>
      	<li class="space">|</li>
        
      	<li class="telphone">电话：13533757141</li>
      
        <li class="space">|</li>
      	<li id="sysSkin">换肤</li>
        <li class="space">|</li>
        
      	<li><a href="javascript:void(0);" id="clear">清空缓存</a></li>
		<li class="space">|</li>
        <li><a href="<?=site_url('login/out')?>">退出</a></li>
      </ul>  
    </div>
    <div id="main-bd">
      <div class="page-tab" id="page-tab"> 
        <!--<ul class="tab_hd">
					<li><a href="#">首页</a></li>
					<li><a href="#">会计科目</a></li>
				</ul>
				<div class="tab_bd">
					内容
				</div>--> 
      </div>
    </div>
  </div>
</div>
<div id="selectSkin" class="shadow dn">
	<ul class="cf">
    	<li><a id="skin-default"><span></span><small>经典</small></a></li>
        <li><a id="skin-blue"><span></span><small>丰收</small></a></li>
        <li><a id="skin-green"><span></span><small>小清新</small></a></li>
    </ul>
</div>
<script>

var list = {
	onlineStoreMap: {
		name: "新手导航",
		href: WDURL + "/online-store/map.jsp?language=zh-CHS&site=SITE_MAIN&siId=" + SYSTEM.DBID + "&scheme=" + SCHEME + "&logonName=" + SYSTEM.userName,
		dataRight: "",
		target: "vip-onlineStore"
	},
	onlineStoreList: {
		name: "网店记录",
		href: WDURL + "/online-store/onlineStoreList.jsp?language=zh-CHS&site=SITE_MAIN&siId=" + SYSTEM.DBID + "&scheme=" + SCHEME + "&logonName=" + SYSTEM.userName,
		dataRight: "CLOUDSTORE_QUERY",
		target: "vip-onlineStore"
	},
	onlineStoreRelation: {
		name: "商品对应关系",
		href: WDURL + "/online-store/onlineStoreRelation.jsp?language=zh-CHS&site=SITE_MAIN&siId=" + SYSTEM.DBID + "&scheme=" + SCHEME + "&logonName=" + SYSTEM.userName,
		dataRight: "INVENTORYCLOUD_QUERY",
		target: "vip-onlineStore"
	},
	logisticsList: {
		name: "物流公司记录",
		href: WDURL + "/online-store/logisticsList.jsp?language=zh-CHS&site=SITE_MAIN&siId=" + SYSTEM.DBID + "&scheme=" + SCHEME + "&logonName=" + SYSTEM.userName,
		dataRight: "EXPRESS_QUERY",
		target: "vip-onlineStore"
	},
	onlineOrderList: {
		name: "网店订单记录",
		href: WDURL + "/online-store/onlineOrderList.jsp?language=zh-CHS&site=SITE_MAIN&siId=" + SYSTEM.DBID + "&scheme=" + SCHEME + "&logonName=" + SYSTEM.userName,
		dataRight: "ORDERCLOUD_QUERY",
		target: "vip-onlineStore"
	},
	onlineSalesList: {
		name: "销货记录",
		href: "/scm/invSa.do?action=initSaleList",
		dataRight: "SA_QUERY",
		target: "vip-onlineStore"
	},
	JDStorageList: {
		name: "授权管理",
		href: "/JDStorage/JDStorageList.jsp",
		dataRight: "",
		target: "vip-JDStorage"
	},
	JDStorageGoodsList: {
		name: "商品上传管理",
		href: "/JDStorage/JDStorageGoodsList.jsp",
		dataRight: "",
		target: "vip-JDStorage"
	},
	JDStoragePurchaseOrderList: {
		name: "购货订单上传",
		href: "/JDStorage/JDStoragePurchaseOrderList.jsp",
		dataRight: "",
		target: "vip-JDStorage"
	},
	JDStorageSaleOrderList: {
		name: "销货订单上传",
		href: "/JDStorage/JDStorageSaleOrderList.jsp",
		dataRight: "",
		target: "vip-JDStorage"
	},
	JDStorageReturnsManage: {
		name: "退货管理",
		href: "/JDStorage/JDStorageReturnsManage.jsp",
		dataRight: "",
		target: "vip-JDStorage"
	},
	JDStorageInvManage: {
		name: "库存管理",
		href: "/JDStorage/JDStorageInvManage.jsp",
		dataRight: "",
		target: "vip-JDStorage"
	},
    purchasePlan: {
         name: "采购计划",
         href: "<?=site_url('invpu/purchaseIndex')?>",
         dataRight: "PU_QUERY",
         target: "purchase"
        },
	purchase: {
		name: "采购单",
		href: "<?=site_url('invpu/add')?>",
		dataRight: "PU_ADD",
		target: "purchase"
	},<?php
        if ($this->purview_model->checkpurview(96,true) == true){?>
        purchaseReview: {
            name: "<span style='color: red'>采购单审核</span>",
            href: "<?=site_url('invpu/reviewIndex')?>",
            dataRight: "PO_ADD",
            target: "purchase"
        },
        <?php }?>
	purchaseList: {
		name: "采购记录",
		href: "<?=site_url('invpu')?>",
		dataRight: "PU_QUERY",
		target: "purchase"
	},
     purchaseOrder: {
         name: "报价单",
         href: "<?=site_url('sheet/add')?>",
         dataRight: "PO_ADD",
         target: "sales"
      },<?php
        if ($this->purview_model->checkpurview(99,true) == true){?>
        sheetReview: {
            name: "<span style='color: red'>报价单审核</span>",
            href: "<?=site_url('sheet/reviewIndex')?>",
            dataRight: "PO_ADD",
            target: "sales"
        },
        <?php }?>
     purchaseOrderList: {
         name: "报价单记录",
         href: "<?=site_url('sheet')?>",
         dataRight: "PO_QUERY",
         target: "sales"
     },
	salesOrder: {
		name: "销售订单",
		href: "/scm/invSo.do?action=initSo",
		dataRight: "SO_ADD",
		target: "sales"
	},
	salesOrderList: {
		name: "销货订单记录",
		href: "/scm/invSo.do?action=initSoList",
		dataRight: "SO_QUERY",
		target: "sales"
	},
//	sales: {
//		name: "销货单",
//		href: "<?//=site_url('invsa/add')?>//",
//		dataRight: "SA_ADD",
//		target: "sales"
//	},
        <?php
        if ($this->purview_model->checkpurview(97,true) == true){?>
        salesReview: {
            name: "<span style='color: red'>销售单审核</span>",
            href: "<?=site_url('invsa/reviewIndex')?>",
            dataRight: "PO_ADD",
            target: "sales"
        },
        <?php }?>
	salesList: {
		name: "销售记录",
		href: "<?=site_url('invsa')?>",
		dataRight: "SA_QUERY",
		target: "sales"
	},
//	transfers: {
//		name: "调拨单",
//		href: "/scm/invTf.do?action=initTf",
//		dataRight: "TF_ADD",
//		target: "storage"
//	},
//	transfersList: {
//		name: "调拨记录",
//		href: "/scm/invTf.do?action=initTfList",
//		dataRight: "TF_QUERY",
//		target: "storage"
//	},
	inventory: {
		name: "盘点",
		href: "<?=site_url('inventory')?>",
		dataRight: "PD_GENPD",
		target: "storage"
	},
	otherWarehouse: {
		name: "入库",
		href: "<?=site_url('invoi/in')?>",
		dataRight: "IO_ADD",
		target: "storage"
	},
	otherWarehouseList: {
		name: "入库记录",
		href: "<?=site_url('invoi')?>",
		dataRight: "IO_QUERY",
		target: "storage"
	},
	otherOutbound: {
		name: "出库",
		href: "<?=site_url('invoi/out')?>",
		dataRight: "OO_ADD",
		target: "storage"
	},
	otherOutboundList: {
		name: "出库记录",
		href: "<?=site_url('invoi/outindex')?>",
		dataRight: "OO_QUERY",
		target: "storage"
	},
        logisticsManage: {
            name: "物流信息管理",
            href: "<?=site_url('logistics/index')?>",
            dataRight: "OO_QUERY",
            target: "storage"
        },
	//adjustment: {
//		name: "成本调整单",
//		href: "/scm/invOi.do?action=initOi&type=cbtz",
//		dataRight: "CADJ_ADD",
//		target: "storage"
//	},
//	adjustmentList: {
//		name: "成本调整记录",
//		href: "/scm/invOi.do?action=initOiList&type=cbtz",
//		dataRight: "CADJ_QUERY",
//		target: "storage"
//	},
	receipt: {
		name: "收款单",
		href: "/scm/receipt.do?action=initReceipt",
		dataRight: "RECEIPT_ADD",
		target: "money"
	},
	receiptList: {
		name: "收款记录",
		href: "/scm/receipt.do?action=initReceiptList",
		dataRight: "RECEIPT_QUERY",
		target: "money"
	},
	payment: {
		name: "付款单",
		href: "/scm/payment.do?action=initPay",
		dataRight: "PAYMENT_ADD",
		target: "money"
	},
	paymentList: {
		name: "付款记录",
		href: "/scm/payment.do?action=initPayList",
		dataRight: "PAYMENT_QUERY",
		target: "money"
	},
	verification: {
		name: "核销单",
		href: "/scm/verifica.do?action=initVerifica",
		dataRight: "VERIFICA_ADD",
		target: "money"
	},
	verificationList: {
		name: "核销记录",
		href: "/money/verification-list.jsp",
		dataRight: "VERIFICA_QUERY",
		target: "money"
	},
	puOrderTracking: {
		name: "采购订单跟踪表",
		href: "/report/pu-order-tracking.jsp",
		dataRight: "PURCHASEORDER_QUERY",
		target: "report-purchase"
	},
	puDetail: {
		name: "采购明细表",
		href: "<?=site_url('report/invpu_detail')?>",
		dataRight: "PUREOORTDETAIL_QUERY",
		target: "report-purchase"
	},
	puSummary: {
		name: "采购汇总表（按商品）",
		href: "<?=site_url('report/invpu_summary')?>",
		dataRight: "PUREPORTINV_QUERY",
		target: "report-purchase"
	},
	puSummarySupply: {
		name: "采购汇总表（按供应商）",
		href: "<?=site_url('report/invpu_supply')?>",
		dataRight: "PUREPORTPUR_QUERY",
		target: "report-purchase"
	},
	salesOrderTracking: {
		name: "销售订单跟踪表",
		href: "/report/sales-order-tracking.jsp",
		dataRight: "SALESORDER_QUERY",
		target: "report-sales"
	},
	salesDetail: {
		name: "销售明细表",
		href: "<?=site_url('report/sales_detail')?>",
		dataRight: "SAREPORTDETAIL_QUERY",
		target: "report-sales"
	},
	salesSummary: {
		name: "销售汇总表（按商品）",
		href: "<?=site_url('report/sales_summary')?>",
		dataRight: "SAREPORTINV_QUERY",
		target: "report-sales"
	},
	salesSummaryCustomer: {
		name: "销售汇总表（按客户）",
		href: "<?=site_url('report/sales_customer')?>",
		dataRight: "SAREPORTBU_QUERY",
		target: "report-sales"
	},
//	contactDebt: {
//		name: "往来单位欠款表",
//		href: "/report/contactDebt.do?action=detail",
//		dataRight: "ContactDebtReport_QUERY",
//		target: "report-sales"
//	},
	initialBalance: {
		name: "商品库存余额表",
		href: "<?=site_url('report/goods_balance')?>",
		dataRight: "InvBalanceReport_QUERY",
		target: "report-storage"
	},
	//goodsFlowDetail: {
//		name: "商品收发明细表",
//		href: "<?=site_url('report/goods_detail')?>",
//		dataRight: "DeliverDetailReport_QUERY",
//		target: "report-storage"
//	},
	goodsFlowSummary: {
		name: "商品收发汇总表",
		href: "<?=site_url('report/goods_summary')?>?action=detail",
		dataRight: "DeliverSummaryReport_QUERY",
		target: "report-storage"
	},
	contactDebt: {
		name: "往来单位欠款表",
		href: "<?=site_url('report/arrears')?>?action=detail",
		dataRight: "ContactDebtReport_QUERY",
		target: "report-money"
	},
	//cashBankJournal: {
//		name: "现金银行报表",
//		href: "/report/bankBalance.do?action=detail",
//		dataRight: "SettAcctReport_QUERY",
//		target: "report-money"
//	},
	accountPayDetail: {
		name: "应付账款明细表",
		href: "<?=site_url('report/balance_supply')?>",
		dataRight: "PAYMENTDETAIL_QUERY",
		target: "report-money"
	},
	accountProceedsDetail: {
		name: "应收账款明细表",
		href: "<?=site_url('report/balance_detail')?>",
		dataRight: "RECEIPTDETAIL_QUERY",
		target: "report-money"
	},
	//customersReconciliation: {
//		name: "客户对账单",
//		href: "/report/customerBalance.do?action=detail",
//		dataRight: "CUSTOMERBALANCE_QUERY",
//		target: "report-money"
//	},
//	suppliersReconciliation: {
//		name: "供应商对账单",
//		href: "/report/supplierBalance.do?action=detail",
//		dataRight: "SUPPLIERBALANCE_QUERY",
//		target: "report-money"
//	},
	customerList: {
		name: "往来单位管理",
		href: "<?=site_url('betweenUnit')?>",
		dataRight: "BU_QUERY",
		target: "setting-base"
	},

//	vendorList: {
//		name: "供应商管理",
//		href: "<?//=site_url('vendor')?>//",
//		dataRight: "PUR_QUERY",
//		target: "setting-base"
//	},
	goodsList: {
            name: "仓库管理",
            href: "<?=site_url('stock')?>",
            dataRight: "INVENTORY_QUERY",
            target: "setting-base"
        },

     designList: {
     name: "BOM设计管理",
     href: "<?=site_url('design')?>",
     dataRight: "INVENTORY_QUERY",
     target: "setting-base"
        },
	//settlementCL: {
//		name: "结算方式",
//		href: "<?=site_url('settlement')?>",
//		dataRight: "Assist_QUERY",
//		target: "setting-base"
//	},
	storageList: {
		name: "BOM管理",
		href: "<?=site_url('bom')?>",
		dataRight: "INVLOCTION_QUERY",
		target: "setting-base"
	},
	//settlementaccount: {
//		name: "账户管理",
//		href: "<?=site_url('admin')?>",
//		dataRight: "SettAcct_QUERY",
//		target: "setting-base"
//	},
	shippingAddress: {
		name: "发货地址管理",
		href: "/settings/shippingAddress.jsp",
		dataRight: "DELIVERYADDR_QUERY",
		target: "setting-base"
	},
	customerCategoryList: {
		name: "往来单位类别",
		href: "<?=site_url('category')?>",
		dataRight: "BUTYPE_QUERY",
		target: "setting-auxiliary"
	},

        vendorCategoryList: {
            name: "供应商类别",
            href: "<?=site_url('category')?>?typeNumber=supplytype",
            dataRight: "SUPPLYTYPE_QUERY",
            target: "setting-auxiliary"
        },
        vendorCategoryList: {
            name: "工作中心",
            href: "<?=site_url('workcenter')?>",
            dataRight: "SUPPLYTYPE_QUERY",
            target: "setting-auxiliary"
        },

//	goodsCategoryList: {
//		name: "商品类别",
//		href: "<?//=site_url('category')?>//?typeNumber=trade",
//		dataRight: "TRADETYPE_QUERY",
//		target: "setting-auxiliary"
//	},
	areaList: {
            name: "地区分类",
            href: "<?=site_url('area')?>",
            dataRight: "AREA_QUERY",
            target: "setting-auxiliary"
        },
   departmentList: {
            name: "部门",
            href: "<?=site_url('department')?>",
            dataRight: "DEPARTMENT_QUERY",
            target: "setting-auxiliary"
        },
   unitList: {
       name: "计量单位",
       href: "<?=site_url('unit')?>",
       dataRight: "UNIT_QUERY",
       target: "setting-auxiliary"
   },


        parameter: {
		name: "系统参数",
		href: "<?=site_url('settings/parameter')?>",
		dataRight: "",
		target: "setting-advancedSetting"
	},
	authority: {
		name: "员工权限",
		href: "<?=site_url('admin')?>",
		dataRight: "",
		target: "setting-advancedSetting"
	},
	operationLog: {
		name: "操作日志",
		href: "<?=site_url('logs')?>",
		dataRight: "OPERATE_QUERY",
		target: "setting-advancedSetting"
	},
	
	backup: {
		name: "备份与恢复",
		href: "<?=site_url('backup')?>",
		dataRight: "",
		target: "setting-advancedSetting"
	},
	reInitial: {
		name: "重新初始化",
		href: "",
		dataRight: "",
		id: "reInitial",
		target: "setting-advancedSetting-right"
	}
	
},
	menu = {
		init: function(e, t) {
			var i = {
				callback: {}
			};
			this.obj = e;
			this.opts = $.extend(!0, {}, i, t);//合并
			this.sublist = this.opts.sublist;
			this.sublist || this._getMenuData();
			this._menuControl();
			this._initDom();
			$(".vip").length || $(".main-nav").css("margin", "5px 0")
		},
		_display: function(e, t) {
			for (var i = e.length - 1; i >= 0; i--) this.sublist[e[i]].disable = !t;
			return this
		},
		_show: function(e) {
			return this._display(e, !0)
		},
		_hide: function(e) {
			return this._display(e, !1)
		},
		_getMenuData: function() {
			this.sublist = list
		},
		_menuControl: function() {
			var e = SYSTEM.siType,
				t = SYSTEM.isAdmin,
				i = SYSTEM.siVersion;
			this._hide(["authority", "reInitial", "onlineStoreMap", "onlineStoreList", "onlineStoreRelation", "logisticsList", "onlineOrderList", "onlineSalesList", "JDStorageList", "JDStorageGoodsList", "JDStoragePurchaseOrderList", "JDStorageSaleOrderList", "JDStorageReturnsManage", "JDStorageInvManage"]);
			switch (e) {
			case 1:
				this._hide([  "salesOrder", "salesOrderList", "verification", "verificationList", "shippingAddress", "puOrderTracking", "salesOrderTracking"]);
				break;
			case 2:
			}
			switch (i) {
			case 1:
				break;
			case 2:
				break;
			case 3:
				break;
			case 4:
				this._hide(["backup"])
			}
			if (t) {
				3 == i && this._show(["reInitial"]);
				this._show(["authority"])
			}
			if (2 == e) {
				1 == SYSTEM.hasOnlineStore ? this._show(["onlineStoreMap", "onlineStoreList", "onlineStoreRelation", "logisticsList", "onlineOrderList", "onlineSalesList"]) : 1 == SYSTEM.enableStorage && $(".vip-nav").width(125);
				1 == SYSTEM.enableStorage ? this._show(["JDStorageList", "JDStorageGoodsList", "JDStoragePurchaseOrderList", "JDStorageSaleOrderList", "JDStorageReturnsManage", "JDStorageInvManage"]) : 1 == SYSTEM.hasOnlineStore && $(".vip-nav").width(120)
			}
		},
		_getDom: function() {
			this.objCopy = this.obj.clone(!0);
			this.container = this.obj.closest("div")
		},
		_setDom: function() {
			this.obj.remove();
			this.container.append(this.objCopy)
		},
		_initDom: function() {
			if (this.sublist && this.obj) {
				this.obj.find("li:not(.item)").remove();
				this._getDom();
				var e = this.sublist,
					t = {};
				t.target = {};
				for (var i in e) if (!e[i].disable) {
					var a = e[i],
						r = t.target[a.target],
						n = a.id ? "id=" + a.id : "",
						o = a.id ? "" : "rel=pageTab",
						s = "<li><a " + n + ' tabid="' + a.target.split("-")[0] + "-" + i + '" ' + o + ' href="' + a.href + '" data-right="' + a.dataRight + '">' + a.name + "</a></li>";
					if (r) r.append(s);
					else {
						t.target[a.target] = this.objCopy.find("#" + a.target);
						t.target[a.target] && t.target[a.target].append(s)
					}
				}
				this.objCopy.find("li.item").each(function() {
					var e = $(this);
					e.find("li").length || e.remove();
					e.find(".nav-item").each(function() {
						var e = $(this);
						if (!e.find("li").length) {
							e.hasClass("last") && e.prev().addClass("last");
							e.remove()
						}
					})
				});
				this._setDom()
			}
		}
	};
</script>
<script src="<?=skin_url()?>/js/dist/default.js?a2"></script>
<!--<script type="text/javascript" src="http://wpa.b.qq.com/cgi/wpa.php"></script>-->

</body>
</html>