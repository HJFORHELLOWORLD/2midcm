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
</script>

<script>
function validMaxForShare(){
    window.location.href='<?=site_url('admin/add')?>';
    //$.ajax({
//      url: '<?=site_url('admin/add')?>',
//      dataType: 'json',
//      type: 'POST',
//      success: function(data){
//        if(data.status === 200){
//        	var json = data.data;
//        	if(json.shareTotal >= json.totalUserNum)
//        	{
//        		parent.Public.tips({type:2, content : '共享用户已经达到上限值：'+json.totalUserNum});
//        		return false;
//        	}else
//        	{
//        		window.location.href='<?=site_url('admin/add')?>';
//        	}	
//        }
//      }
//  });
}
</script>
</head>
<body>
<div class="wrapper">
    <div class="mod-toolbar-top">
       <a href="javascript:validMaxForShare();" class="ui-btn ui-btn-sp mrb">新增同事</a>
       <span class="tit" id="shareInfo" style="display:none;">该账套用户共<strong id="usedTotal"></strong>人。</span>
    </div>    
    <div class="grid-wrap">
      <table id="grid">
      </table>
      <div id="page"></div>
    </div>
</div>
<script>
  (function($){
    var totalUser, usedTotal, leftTotal;
    initGrid();

    $('.grid-wrap').on('click', '.delete', function(e){
      var id = $(this).parents('tr').attr('id');
      var rowData = $('#grid').getRowData(id);
      var userName = rowData.userName;
      e.preventDefault();
      $.ajax({
        url: '<?=site_url('admin/doset')?>?act=nostatus&username=' + userName,
        type: 'POST',
        dataType: 'json',
        success: function(data){
          if (data.status == 200) {
            parent.Public.tips({content: '取消用户授权成功！'});
            usedTotal--;
            leftTotal++;
            showShareCount();
            if (rowData.isCom) {
                rowData.share = false;
                $("#grid").jqGrid('setRowData', id, rowData);
            } else {
                $("#grid").jqGrid('delRowData',id);
            }
           
          } else {
            parent.Public.tips({type: 1, content: '取消用户授权失败！' + data.msg});
          }
        },
        error: function(){
           parent.Public.tips({content:'取消用户授权失败！请重试。', type: 1});
        }
      });
    });

    $('.grid-wrap').on('click', '.authorize', function(e){
      var id = $(this).parents('tr').attr('id');
      var rowData = $('#grid').getRowData(id);
      var userName = rowData.userName;
      e.preventDefault();
       $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '<?=site_url('admin/doset')?>?act=isstatus&username=' + userName,
        success: function(data){
          if (data.status == 200) {
            parent.Public.tips({content : '授权成功！'});
            rowData.share = true;
            $("#grid").jqGrid('setRowData', id, rowData);
            usedTotal++;
            leftTotal--;
            showShareCount();
            //window.location.href = 'authority-setting.jsp?userName=' + userName + '&right=0';
          } else {
            parent.Public.tips({type:1, content : data.msg});
          }
        },
        error: function(){
          parent.Public.tips({type:1, content : '用户授权失败！请重试。'});
        }
      });
    });

   
    function initGrid(){
      $('#grid').jqGrid({
        url: '<?=site_url('admin/lists')?>',
        datatype: 'json',
        height: Public.setGrid().h,
        colNames:['用户', '姓名', '部门','功能授权','数据授权','启用授权'],
        colModel:[
          {name:'userName',index:'userName', width:200},
          {name:'realName', index:'realName', width:200},
          {name:'deptName', index:'deptName', width:200},
          {name:'setting', index:'setting', width:100, align:"center", title:false, formatter: settingFormatter},
		  {name:'setting_data', index:'setting_data', width:100, align:"center", title:false, formatter: settingDataFormatter, hidden:(parent.SYSTEM.siType == 1)},
		  {name:'share', index:'share', width:100, align:"center", title:false, formatter: shareFormatter}
        ],
        altRows:true,
        gridview: true,
        page: 1,
        scroll: 1,
        autowidth: true,
        cmTemplate: {sortable:false}, 
        rowNum:150,
        shrinkToFit:false,
        forceFit:false,
        pager: '#page',
        viewrecords: true,
        jsonReader: {
          root: 'data.items', 
          records: 'data.totalsize',  
          repeatitems : false,
          id: 'userId'
        },
        loadComplete: function(data){
          if (data.status == 200) {
            data = data.data;
            totalUser = data.totalUserNum;
            usedTotal = data.shareTotal;
            leftTotal = totalUser - usedTotal;
            showShareCount();
            $('#shareInfo').show();
          } else {
        	  parent.Public.tips({type: 1, content: data.msg});
          }
          
        },
        loadonce: true
      });
    }


    function showShareCount(){
        $('#totalUser').text(totalUser);
        $('#usedTotal').text(usedTotal);
        $('#leftTotal').text(leftTotal);
    }

    function shareFormatter(val, opt, row) {
        if (val || row.admin) {
          if (row.admin) {
              return '管理员';
          } else {
		       if (row.isCom) {
                   return '<div class="operating" data-id="' + row.userId + '"><span class="delete ui-label ui-label-success">已启用</span></div>';
			   } else {
			       return '<p class="operate-wrap"><span class="authorize ui-label ui-label-default">已停用</span></p>';
			   }
          }
        } else {
          return '<p class="operate-wrap"><span class="authorize ui-label ui-label-default">已停用</span></p>';
        } 
    };
    function settingFormatter(val, opt, row) {
		if (row.admin || row.share === false) {
			return '&nbsp;';
		} else {
		    if (row.isCom) {
				return '<div class="operating" data-id="' + row.userId + '"><a class="ui-icon ui-icon-pencil" title="详细设置授权信息" href="<?=site_url('admin/authority')?>?username=' + row.userName + '"></a></div>';
			} else {	
			    return '&nbsp;';
			}
		}
    };
    function settingDataFormatter(val, opt, row) {
		if (row.admin || row.share === false) {
			return '&nbsp;';
		} else {
		    if (row.isCom) {
				return '<div class="operating" data-id="' + row.userId + '"><a class="ui-icon ui-icon-pencil" title="详细设置授权信息" href="/settings/authority-setting-data.jsp?userName=' + row.userName + '"></a></div>';
			}
		}
    };
  })(jQuery)
  
  $(window).resize(function(){
	  Public.resizeGrid();
  });
</script>
</body>
</html>