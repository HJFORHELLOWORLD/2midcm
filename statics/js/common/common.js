var Public = Public || {};
var Business = Business || {};
Public.isIE6 = !window.XMLHttpRequest;	//ie6

$(function(){
	//菜单按钮
	$('.ui-btn-menu .menu-btn').on('mouseenter.menuEvent',function(e){
		if($(this).hasClass("ui-btn-dis")) {
			return false;
		}
		$(this).parent().addClass('ui-btn-menu-cur');
		$(this).blur();
		e.preventDefault();
	});

	$(document).on('click.menu',function(e){
		var target  = e.target || e.srcElement;
		$('.ui-btn-menu').each(function(){
			var menu = $(this);
			if($(target).closest(menu).length == 0 && $('.con',menu).is(':visible')){
				 menu.removeClass('ui-btn-menu-cur');
			}
		})
	});
});

//设置表格宽高
Public.setGrid = function(adjustH, adjustW){
	if(parent.SYSTEM.skin === 'green'){
		var adjustH = adjustH || 70;
	} else {
		var adjustH = adjustH || 65;
	};
	var adjustW = adjustW || 20;
	var gridW = $(window).width() - adjustW, gridH = $(window).height() - $(".grid-wrap").offset().top - adjustH;
	return {
		w : gridW,
		h : gridH
	}
};
//重设表格宽高
Public.resizeGrid = function(adjustH, adjustW){
	var grid = $("#grid");
	var gridWH = Public.setGrid(adjustH, adjustW);
	grid.jqGrid('setGridHeight', gridWH.h);
	grid.jqGrid('setGridWidth', gridWH.w);
};
//自定义报表宽高初始化以及自适应
Public.initCustomGrid = function(tableObj){
	//去除报表原始定义的宽度
	$(tableObj).css("width") && $(tableObj).attr("width","auto");
	//获取报表宽度当做最小宽度
	var _minWidth = $(tableObj).outerWidth();
	$(tableObj).css("min-width",_minWidth+"px");
	//获取当前window对象的宽度作为报表原始的宽度
	$(tableObj).width($(window).width() - 74);
	$(tableObj).closest('.mod-report').height($(window).height() - 66);
	//设置resize事件
	var _throttle = function(method,context){
		clearTimeout(method.tid);
		method.tid = setTimeout(function(){
			method.call(context);
		},100)
	};
	var _resize = function(){
		$(tableObj).width($(window).width() - 74);
		$(tableObj).closest('.mod-report').height($(window).height() - 66);
	};
	$(window).resize(function() {
		_throttle(_resize);
	});
}
/**
 * 节点赋100%高度
 *
 * @param {object} obj 赋高的对象
*/
Public.setAutoHeight = function(obj){
if(!obj || obj.length < 1){
	return ;
}

Public._setAutoHeight(obj);
$(window).bind('resize', function(){
	Public._setAutoHeight(obj);
});

}

Public._setAutoHeight = function(obj){
obj = $(obj);
//parent = parent || window;
var winH = $(window).height();
var h = winH - obj.offset().top - (obj.outerHeight() - obj.height());
obj.height(h);
}
//操作项格式化，适用于有“修改、删除”操作的表格
Public.operFmatter = function (val, opt, row) {
	var html_con = '<div class="operating" data-id="' + row.id + '"><span class="ui-icon ui-icon-pencil" title="修改"></span><span class="ui-icon ui-icon-trash" title="删除"></span></div>';
	return html_con;
};

Public.billsOper = function (val, opt, row) {
	var html_con = '<div class="operating" data-id="' + opt.rowId + '"><span class="ui-icon ui-icon-plus" title="新增行"></span><span class="ui-icon ui-icon-trash" title="删除行"></span></div>';
	return html_con;
};

Public.dateCheck = function(){
	$('.ui-datepicker-input').bind('focus', function(e){
		$(this).data('original', $(this).val());
	}).bind('blur', function(e){
		var reg = /((^((1[8-9]\d{2})|([2-9]\d{3}))(-)(10|12|0?[13578])(-)(3[01]|[12][0-9]|0?[1-9])$)|(^((1[8-9]\d{2})|([2-9]\d{3}))(-)(11|0?[469])(-)(30|[12][0-9]|0?[1-9])$)|(^((1[8-9]\d{2})|([2-9]\d{3}))(-)(0?2)(-)(2[0-8]|1[0-9]|0?[1-9])$)|(^([2468][048]00)(-)(0?2)(-)(29)$)|(^([3579][26]00)(-)(0?2)(-)(29)$)|(^([1][89][0][48])(-)(0?2)(-)(29)$)|(^([2-9][0-9][0][48])(-)(0?2)(-)(29)$)|(^([1][89][2468][048])(-)(0?2)(-)(29)$)|(^([2-9][0-9][2468][048])(-)(0?2)(-)(29)$)|(^([1][89][13579][26])(-)(0?2)(-)(29)$)|(^([2-9][0-9][13579][26])(-)(0?2)(-)(29)$))/;
		var _self = $(this);
		setTimeout(function(){
			if(!reg.test(_self.val())) {
				parent.Public.tips({type:1, content : '日期格式有误！如：2013-08-08。'});
				_self.val(_self.data('original'));
			};
		}, 10)

	});
}
//日期格式化
Date.prototype.format = function(format){ 
	var o = { 
		"M+" : this.getMonth()+1, //month 
		"d+" : this.getDate(), //day 
		"h+" : this.getHours(), //hour 
		"m+" : this.getMinutes(), //minute 
		"s+" : this.getSeconds(), //second 
		"q+" : Math.floor((this.getMonth()+3)/3), //quarter 
		"S" : this.getMilliseconds() //millisecond 
	} 

	if(/(y+)/.test(format)) { 
		format = format.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length)); 
	} 

	for(var k in o) { 
		if(new RegExp("("+ k +")").test(format)) { 
			format = format.replace(RegExp.$1, RegExp.$1.length==1 ? o[k] : ("00"+ o[k]).substr((""+ o[k]).length)); 
		} 
	} 
	return format; 
} 

//根据之前的编码生成下一个编码
Public.getSuggestNum = function(prevNum){
	if (prevNum == '' || !prevNum) {
		return '';
	}
	var reg = /^([a-zA-Z0-9\-_]*[a-zA-Z\-_]+)?(\d+)$/;
	var match = prevNum.match(reg);
	if (match) {
		var prefix = match[1] || '';
		var prevNum = match[2];
		var num = parseInt(prevNum, 10) + 1;
		var delta = prevNum.toString().length - num.toString().length;
		if (delta > 0) {
			for (var i = 0; i < delta; i++) {
				num = '0' + num;
			}
		}
		return prefix + num;
	} else {
		return '';
	}
};

Public.bindEnterSkip = function(obj, func){
	var args = arguments;
	$(obj).on('keydown', 'input:visible:not(:disabled)', function(e){
		if (e.keyCode == '13') {
			var inputs = $(obj).find('input:visible:not(:disabled)');
			var idx = inputs.index($(this));
			idx = idx + 1;
			if (idx >= inputs.length) {
				if (typeof func == 'function') {
					var _args = Array.prototype.slice.call(args, 2 );
					func.apply(null,_args);
				}
			} else {
				inputs.eq(idx).focus();
			}
		}
	});
};

/*获取URL参数值*/
Public.getRequest = Public.urlParam = function() {
   var param, url = location.search, theRequest = {};
   if (url.indexOf("?") != -1) {
      var str = url.substr(1);
      strs = str.split("&");
      for(var i = 0, len = strs.length; i < len; i ++) {
		 param = strs[i].split("=");
         theRequest[param[0]]=decodeURIComponent(param[1]);
      }
   }
   return theRequest;
};
/*
  通用post请求，返回json
  url:请求地址， params：传递的参数{...}， callback：请求成功回调
*/ 
Public.ajaxPost = function(url, params, callback, errCallback){    
	$.ajax({  
	   type: "POST",
	   url: url,
	   data: params, 
	   dataType: "json",  
	   success: function(data, status){  
		   callback(data);  
	   },  
	   error: function(err){  
			//parent.Public.tips({type: 1, content : '操作失败了哦，请检查您的网络链接！'});
			errCallback && errCallback(err);
	   }  
	});  
};  
Public.ajaxGet = function(url, params, callback, errCallback){    
	$.ajax({  
	   type: "GET",
	   url: url,
	   dataType: "json",  
	   data: params,    
	   success: function(data, status){  
		   callback(data);  
	   },   
	   error: function(err){  
			//parent.Public.tips({type: 1, content : '操作失败了哦，请检查您的网络链接！'});
			errCallback && errCallback(err);
	   }  
	});  
};
/*操作提示*/
Public.tips = function(options){ return new Public.Tips(options); }
Public.Tips = function(options){
	var defaults = {
		renderTo: 'body',
		type : 0,
		autoClose : true,
		removeOthers : true,
		time : undefined,
		top : 10,
		onClose : null,
		onShow : null
	}
	this.options = $.extend({},defaults,options);
	this._init();
	
	!Public.Tips._collection ?  Public.Tips._collection = [this] : Public.Tips._collection.push(this);
	
}

Public.Tips.removeAll = function(){
	try {
		for(var i=Public.Tips._collection.length-1; i>=0; i--){
			Public.Tips._collection[i].remove();
		}
	}catch(e){}
}

Public.Tips.prototype = {
	_init : function(){
		var self = this,opts = this.options,time;
		if(opts.removeOthers){
			Public.Tips.removeAll();
		}

		this._create();

		if(opts.autoClose){
			time = opts.time || opts.type == 1 ? 5000 : 3000;
			window.setTimeout(function(){
				self.remove();
			},time);
		}

	},
	
	_create : function(){
		var opts = this.options, self = this;
		if(opts.autoClose) {
			this.obj = $('<div class="ui-tips"><i></i></div>').append(opts.content);
		} else {
			this.obj = $('<div class="ui-tips"><i></i><span class="close"></span></div>').append(opts.content);
			this.closeBtn = this.obj.find('.close');
			this.closeBtn.bind('click',function(){
				self.remove();
			});
		};
		
		switch(opts.type){
			case 0 : 
				this.obj.addClass('ui-tips-success');
				break ;
			case 1 : 
				this.obj.addClass('ui-tips-error');
				break ;
			case 2 : 
				this.obj.addClass('ui-tips-warning');
				break ;
			default :
				this.obj.addClass('ui-tips-success');
				break ;
		}
		
		this.obj.appendTo('body').hide();
		this._setPos();
		if(opts.onShow){
				opts.onShow();
		}

	},

	_setPos : function(){
		var self = this, opts = this.options;
		if(opts.width){
			this.obj.css('width',opts.width);
		}
		var h =  this.obj.outerHeight(),winH = $(window).height(),scrollTop = $(window).scrollTop();
		//var top = parseInt(opts.top) ? (parseInt(opts.top) + scrollTop) : (winH > h ? scrollTop+(winH - h)/2 : scrollTop);
		var top = parseInt(opts.top) + scrollTop;
		this.obj.css({
			position : Public.isIE6 ? 'absolute' : 'fixed',
			left : '50%',
			top : top,
			zIndex : '9999',
			marginLeft : -self.obj.outerWidth()/2	
		});

		window.setTimeout(function(){
			self.obj.show().css({
				marginLeft : -self.obj.outerWidth()/2
			});
		},150);

		if(Public.isIE6){
			$(window).bind('resize scroll',function(){
				var top = $(window).scrollTop() + parseInt(opts.top);
				self.obj.css('top',top);
			})
		}
	},

	remove : function(){
		var opts = this.options;
		this.obj.fadeOut(200,function(){
			$(this).remove();
			if(opts.onClose){
				opts.onClose();
			}
		});
	}
};
//数值显示格式转化
Public.numToCurrency = function(val, dec) {
	val = parseFloat(val);	
	dec = dec || 2;	//小数位
	if(val === 0 || isNaN(val)){
		return '';
	}
	val = val.toFixed(dec).split('.');
	var reg = /(\d{1,3})(?=(\d{3})+(?:$|\D))/g;
	return val[0].replace(reg, "$1,") + '.' + val[1];
};
//数值显示
Public.currencyToNum = function(val){
	var val = String(val);
	if ($.trim(val) == '') {
		return 0;
	}
	val = val.replace(/,/g, '');
	val = parseFloat(val);
	return isNaN(val) ? 0 : val;
};
//只允许输入数字
Public.numerical = function(e){
	var allowed = '0123456789.-', allowedReg;
	allowed = allowed.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, '\\$&');
	allowedReg = new RegExp('[' + allowed + ']');
	var charCode = typeof e.charCode != 'undefined' ? e.charCode : e.keyCode; 
	var keyChar = String.fromCharCode(charCode);
	if(!e.ctrlKey && charCode != 0 && ! allowedReg.test(keyChar)){
		e.preventDefault();
	};
};

//限制只能输入允许的字符，不支持中文的控制
Public.limitInput = function(obj, allowedReg){
    var ctrlKey = null;
    obj.css('ime-mode', 'disabled').on('keydown',function(e){
        ctrlKey = e.ctrlKey;
    }).on('keypress',function(e){
        allowedReg = typeof allowedReg == 'string' ? new RegExp(allowedReg) : allowedReg;
        var charCode = typeof e.charCode != 'undefined' ? e.charCode : e.keyCode; 
        var keyChar = $.trim(String.fromCharCode(charCode));
        if(!ctrlKey && charCode != 0 && charCode != 13 && !allowedReg.test(keyChar)){
            e.preventDefault();
        } 
    });
};
//限制输入的字符长度
Public.limitLength = function(obj, count){
	obj.on('keyup',function(e){
        if(count < obj.val().length){
        	e.preventDefault();
        	obj.val(obj.val().substr(0,count));
        }
    });
};
/*批量绑定页签打开*/
Public.pageTab = function() {
	$(document).on('click', '[rel=pageTab]', function(e){
		e.preventDefault();
		var right = $(this).data('right');
		if (right && !Business.verifyRight(right)) {
			return false;
		};
		var tabid = $(this).attr('tabid'), url = $(this).attr('href'), showClose = $(this).attr('showClose'), text = $(this).attr('tabTxt') || $(this).text(),parentOpen = $(this).attr('parentOpen');
		if(parentOpen){
			parent.tab.addTabItem({tabid: tabid, text: text, url: url, showClose: showClose});
		} else {
			tab.addTabItem({tabid: tabid, text: text, url: url, showClose: showClose});
		}
	});
};

$.fn.artTab = function(options) {
  var defaults = {};
  var opts = $.extend({}, defaults, options);
  var callback = opts.callback || function () {};
  this.each(function(){
	  var $tab_a =$("dt>a",this);
	  var $this = $(this);
	  $tab_a.bind("click", function(){
		  var target = $(this);
		  target.siblings().removeClass("cur").end().addClass("cur");
		  var index = $tab_a.index(this);
		  var showContent = $("dd>div", $this).eq(index);
		  showContent.siblings().hide().end().show();
		  callback(target, showContent, opts);
	  });
	  if(opts.tab)
		  $tab_a.eq(opts.tab).trigger("click");
	  if(location.hash) {
		  var tabs = location.hash.substr(1);
		  $tab_a.eq(tabs).trigger("click");
	  }
  });	  
};

//文本列表滚动
Public.txtSlide = function(opt){
	var def = {
		notice: '#notices > ul',
		size: 1, //显示出来的条数
		pause_time: 3000, //每次滚动后停留的时间
		speed: 'fast', //滚动动画执行的时间
		stop: true //鼠标移到列表时停止动画
	};
	opt = opt || {};
	opt = $.extend({}, def, opt);

	var $list = $(opt.notice),
		$lis = $list.children(),
		height = $lis.eq(0).outerHeight() * opt.size,
		interval_id;
	if($lis.length <= opt.size) return;
	interval_id = setInterval(begin, opt.pause_time);

	opt.stop && $list.on({
		'mouseover': function(){
			clearInterval(interval_id);
			$list.stop(true,true);
		},
		'mouseleave': function(){
			interval_id = setInterval(begin, opt.pause_time);
		}
	});

	function begin(){
		$list.stop(true, true).animate({marginTop: -height}, opt.speed, function(){
			for(var i=0; i<opt.size; i++){
				$list.append($list.find('li:first'));
			}
			$list.css('margin-top', 0);
		});
	}
};

$.fn.enterKey = function() {
	this.each(function() {
		$(this).keydown(function(e) {
			if (e.which == 13) {
				var ref = $(this).data("ref");
				if (ref) {
					$('#' + ref).select().focus().click();
				}
				else {
					eval($(this).data("enterKeyHandler"));
				}
			}
		});
	});
};


//input占位符
$.fn.placeholder = function(){
	this.each(function() {
		$(this).focus(function(){
			if($.trim(this.value) == this.defaultValue){
				this.value = '';
			}
			$(this).removeClass('ui-input-ph');
		}).blur(function(){
			var val = $.trim(this.value);
			if(val == '' || val == this.defaultValue){
				$(this).addClass('ui-input-ph');
			}
			val == '' && $(this).val(this.defaultValue);
		});
	});
};

//单选框插件
$.fn.cssRadio = function(opts){
	var opts = $.extend({}, opts);
	var $_radio = $('label.radio', this), $_this = this;
	$_radio.each(function() {
		var self = $(this);
		if (self.find("input")[0].checked) {
			self.addClass("checked");
		};

	}).hover(function() {
		$(this).addClass("over");
	}, function() {
		$(this).removeClass("over");
	}).click(function(event) {
		$_radio.find("input").removeAttr("checked");
		$_radio.removeClass("checked");
		$(this).find("input").attr("checked", "checked");
		$(this).addClass("checked");
		opts.callback($(this));
	});
	return {
		getValue: function() {
			return $_radio.find("input[checked]").val();
		},
		setValue: function(index) {
			return $_radio.eq(index).click();
		}
	}
};
//复选框插件
$.fn.cssCheckbox = function() {
	var $_chk = $(".chk", this);
	$_chk.each(function() {
		if ($(this).find("input")[0].checked) {
			$(this).addClass("checked");
		};
		if ($(this).find("input")[0].disabled) {
			$(this).addClass("dis_check");
		};
	}).hover(function() {
		$(this).addClass("over")
	}, function() {
		$(this).removeClass("over")
	}).click(function(event) {
		if ($(this).find("input")[0].disabled) {
			return;
		};
		$(this).toggleClass("checked");
		$(this).find("input")[0].checked = !$(this).find("input")[0].checked;
		event.preventDefault();
	});
	
	return {
		chkAll:function(){
			$_chk.addClass("checked");
			$_chk.find("input").attr("checked","checked");
		},	
		chkNot:function(){
			$_chk.removeClass("checked");
			$_chk.find("input").removeAttr("checked");
		},
		chkVal:function(){
			var val = [];
			$_chk.find("input:checked").each(function() {
            	val.push($(this).val());
        	});
			return val;
		}
	}
};

Public.getDefaultPage = function(){
	var win = window.self;
	do{
		if (win.CONFIG) {
			return win;
		}
		win = win.parent;
	} while(true);
};

//权限验证
Business.verifyRight = function(right){
	var system = Public.getDefaultPage().SYSTEM;
	var isAdmin = system.isAdmin;
	var siExperied = system.siExpired;
	var rights = system.rights;
	if (isAdmin && !siExperied) {
		return true;
	};

	if(siExperied) {
		if(rights[right]) {
			return true;
		} else {
			var html = [
				'<div class="ui-dialog-tips">',
				'<p>谢谢您使用本产品，您的当前服务已经到期，到期3个月后数据将被自动清除，如需继续使用请购买/续费！</p>',
				'<p style="color:#AAA; font-size:12px;">(续费后请刷新页面或重新登录。)</p>',
				'</div>'
			].join('');
			$.dialog({
				width: 280,
				title: '系统提示',
				icon: 'alert.gif',
				fixed: true,
				lock: true,
				resize: false,
				ok: true,
				content: html
			});
			return false;
		}
	} else {
		if (rights[right]) {
			return true;
		} else {
			var html = [
				'<div class="ui-dialog-tips">',
				'<h4 class="tit">您没有该功能的使用权限哦！</h4>',
				'<p>请联系管理员为您授权！</p>',
				'</div>'
			].join('');
			$.dialog({
				width: 240,
				title: '系统提示',
				icon: 'alert.gif',
				fixed: true,
				lock: true,
				resize: false,
				ok: true,
				content: html
			});
			return false;
		}
	};
};

//获取文件
Business.getFile = function(url, args, isNewWinOpen){
	if (typeof url != 'string') {
		return ;
	}
	var url = url.indexOf('?') == -1 ? url += '?' : url;
	if(args.id) {
		url += '&id=' + args.id + '&random=' + new Date().getTime();
	} else {
		url += '&random=' + new Date().getTime();
	};
	
	var downloadForm = $('form#downloadForm');
	if (downloadForm.length == 0) {
		downloadForm = $('<form method="post" />').attr('id', 'downloadForm').hide().appendTo('body');
	} else {
		downloadForm.empty();
	}
	downloadForm.attr('action', url);
	for( k in args){
		$('<input type="hidden" />').attr({name: k, value: args[k]}).appendTo(downloadForm);
	}
	if (isNewWinOpen) {
		downloadForm.attr('target', '_blank');
	} else{
		var downloadIframe = $('iframe#downloadIframe');
		if (downloadIframe.length == 0) {
			downloadIframe = $('<iframe />').attr('id', 'downloadIframe').hide().appendTo('body');
		}
		downloadForm.attr('target', 'downloadIframe');
	}
	downloadForm.trigger('submit');
};

Business.customerCombo = function($_obj, opts){
	if ($_obj.length == 0) { return };
	var opts = $.extend(true, {
		data: function(){
			return parent.SYSTEM.customerInfo;
		},
		ajaxOptions: {
			formatData: function(data){
				parent.SYSTEM.customerInfo = data.data.rows;	//更新
				return data.data.rows;
			}	
		},
		width: 200,
		height: 300,
		formatText: function(row){
			return row.number + ' ' + row.name;
		},
		//formatResult: 'name',
		text: 'name',
		value: 'id',
		defaultSelected: 0,
		editable: true,
		extraListHtml: '<a href="javascript:void(0);" id="quickAddCustomer" class="quick-add-link"><i class="ui-icon-add"></i>新增客户</a>',
		maxListWidth: 500,
		cache: false,
		forceSelection: false,
		maxFilter: 10,
		trigger: false,	
		callback: {
			onChange: function(data){
				if(data) {
					$_obj.data('contactInfo', data);
				} else {
					$_obj.removeData('contactInfo');
				}
			}
		}
	}, opts);
	
	var customerCombo = $_obj.combo(opts).getCombo();	
	//新增客户
	$('#quickAddCustomer').on('click', function(e){
		e.preventDefault();
		if (!Business.verifyRight('BU_ADD')) {
			return ;
		};
		$.dialog({
			title : '新增客户',
			//content : 'url:/settings/customer-manage.jsp',
			content : 'url:'+settings_customer_manage,
			data: {oper: 'add', callback: function(data, oper, dialogWin){
				//parent.getCustomer();
				//_self.customerCombo.selectByValue(data.id, false);
				//customerCombo.loadData('/basedata/contact.do?action=list', ['id', data.id]);
				if(data && data.id) {
					$_obj.data('contactInfo', data);	//存储
					$_obj.find('input').val(data.number + ' ' + data.name);	//回填数据
					parent.SYSTEM.customerInfo.push(data);	//增加进缓存
					customerCombo.collapse();	//关闭下拉
				}
				dialogWin && dialogWin.api.close();
			}},
			width : 640,
			height : 456,
			max : false,
			min : false,
			cache : false,
			lock: true
		});
	});
	
	customerCombo.input.focus(function() {
		var $_this = $(this);
		setTimeout(function(){
			$_this.select();
		}, 15);
	});
	
	return customerCombo;
};

Business.supplierCombo = function($_obj, opts){
	if ($_obj.length == 0) { return };
	var opts = $.extend(true, {
		data: function(){
			return parent.SYSTEM.supplierInfo;;
		},
		ajaxOptions: {
			formatData: function(data){
				parent.SYSTEM.supplierInfo = data.data.rows;	//更新
				return data.data.rows;
			}	
		},			
		width: 200,
		height: 300,
		formatText: function(row){
			return row.number + ' ' + row.name;
		},
		//formatResult: 'name',
		text: 'name',
		value: 'id',
		defaultSelected: 0,
		editable: true,
		extraListHtml: '<a href="javascript:void(0);" id="quickAddVendor" class="quick-add-link"><i class="ui-icon-add"></i>新增供应商</a>',
		maxListWidth: 500,
		cache: false,
		forceSelection: false,
		maxFilter: 10,
		trigger: false,	
		callback: {
			onChange: function(data){
				if(data) {
					$_obj.data('contactInfo', data);
				} else {
					$_obj.removeData('contactInfo');
				}
			}
		}			
	}, opts);
	
	var supplierCombo = $_obj.combo(opts).getCombo();	
	//新增供应商
	$('#quickAddVendor').on('click', function(e){
		e.preventDefault();
		if (!Business.verifyRight('PUR_ADD')) {
			return ;
		};
		$.dialog({
			title : '新增供应商',
			//content : 'url:/settings/vendor-manage.jsp',
			content : 'url:'+settings_vendor_manage,
			data: {oper: 'add', callback: function(data, oper, dialogWin){
				//parent.getCustomer();
				//_self.customerCombo.selectByValue(data.id, false);
				supplierCombo.loadData(basedata_contact+'?type=2&action=list', ['id', data.id]);
				dialogWin && dialogWin.api.close();
			}},
			width : 640,
			height : 496,
			max : false,
			min : false,
			cache : false,
			lock: true
		});
	});
	
	supplierCombo.input.focus(function() {
		var $_this = $(this);
		setTimeout(function(){
			$_this.select();
		}, 15);
	});
	return supplierCombo;
};
//结算账户下拉框初始化
Business.settlementAccountCombo = function($_obj, opts){
	if ($_obj.length == 0) { return };
	var getInfo=(function(){
		//Public.ajaxGet('/basedata/settAcct.do?action=list', {}, function(data){
		Public.ajaxGet(basedata_settlement, {}, function(data){		
			if(data.status === 200) {
				parent.SYSTEM.settlementAccountInfo = data.data.items;
			} else if (data.status === 250){
				parent.SYSTEM.settlementAccountInfo = [];
			} else {
				Public.tips({type: 1, content : data.msg});
			}
		});
	})();
	var opts = $.extend(true, {
		data: function(){
			return parent.SYSTEM.settlementAccountInfo || [];
		},
		ajaxOptions: {
			formatData: function(data){
				parent.SYSTEM.settlementAccountInfo = data.data.items;	//更新
				return data.data.items;
			}	
		},
		width: 200,
		height: 300,
/*			formatText: function(row){
			return row.number + ' ' + row.name;
		},*/
		//formatResult: 'name',
		text: 'name',
		value: 'id',
		defaultSelected: -1,
		defaultFlag: false,
		cache: false,
		editable: true,
		callback: {
			onChange: function(data){
			}
		},
		extraListHtml: '<a href="javascript:void(0);" id="quickAddVendor" class="quick-add-link"><i class="ui-icon-add"></i>新增结算账户</a>'
	}, opts);
	
	var settlementAccountCombo = $_obj.combo(opts).getCombo();	
	//新增结算账户
	$('#quickAddVendor').on('click', function(e){
		e.preventDefault();
		if (!Business.verifyRight('SettAcct_ADD')) {
			return ;
		};
		$.dialog({
			title : '新增结算账户',
			//content : 'url:/settings/settlementAccount-manage.jsp', 
			content : 'url:'+settings_settlement_manage,
			data: {oper: 'add', callback: function(data, oper, dialogWin){
				parent.SYSTEM.settlementAccountInfo.push(data);
				settlementAccountCombo.loadData('/basedata/settAcct.do?action=query', ['id', data.id]);
				dialogWin && dialogWin.api.close();
			}},
			width : 640,
			height : 205,
			max : false,
			min : false,
			cache : false,
			lock: true
		});
	});
	return settlementAccountCombo;
};

Business.goodsCombo = function($_obj, opts){
	if ($_obj.length == 0) { return };
	var opts = $.extend(true, {
		data: function(){
			if(parent.SYSTEM.goodsInfo) {
				return parent.SYSTEM.goodsInfo;
			} else {
				//return '/basedata/inventory.do?action=list';
				return basedata_category;
			}
		},
		ajaxOptions: {
			formatData: function(data){
				parent.SYSTEM.goodsInfo = data.data.rows;	//更新
				return data.data.rows;
			}	
		},
		formatText: function(data){
			if(data.spec === '') {
				return data.number + ' ' + data.name;
			} else {
				return data.number + ' ' + data.name + '_' + data.spec;
			}
		},
		value: 'id',
		defaultSelected: -1,
		editable: true,
		extraListHtml: '<a href="javascript:void(0);" id="quickAddGoods" class="quick-add-link"><i class="ui-icon-add"></i>新增商品</a>',
		maxListWidth: 500,
		cache: false,
		forceSelection: true,
		trigger: false,
		listHeight: 182,
		listWrapCls: 'ui-droplist-wrap',
		callback: {
			onChange: function(data){
				var _self = this;
				_self.addQuery = true;
				var parentTr = this.input.parents('tr');
				if(data) {
					parentTr.data('goodsInfo', data);
					!parentTr.data('storageInfo') && parentTr.data('storageInfo', { id: data.locationId, name: data.localtionName});
				}else{
					var oldData1 = parentTr.data('goodsInfo');
					var oldData2 = parentTr.data('storageInfo');
					parentTr.data('oldGoodsInfo',oldData1);
					parentTr.data('storageInfo',oldData2);
					parentTr.data('goodsInfo', null);
					parentTr.data('storageInfo',null);
				}
			},
			incrementalSearch: function(pos, callback){
				var _self = this;
				var query = $_obj.val()
				//Public.ajaxGet('/basedata/inventory.do?action=list', { rows: 20, skey: query }, function(data){
				Public.ajaxGet(basedata_goods, { rows: 20, skey: query }, function(data){																						
					if(data.status === 200 || data.status === 250) {
						//SYSTEM.goodsInfo.push(data.data.rows);				
						_self.rawData = _self.addData = data.data.rows;	
						if(data.data.rows.length < _self.opts.maxFilter) {
							_self.addQuery = false;
						} else {
							_self.addQuery = true;
						};
						callback.call(_self);
						var addId = [];
						$.each(data.data.rows, function(i, n){
							addId.push(n.id);	
						});
						$.each(pos, function(i, n){
							if($.inArray(n.value, addId) !== -1) {
								if(i === 0) {
									parent.SYSTEM.goodsInfo.splice(i, 1);
								} else {
									parent.SYSTEM.goodsInfo.splice(i - 1, 1);
								};
							};	
						});
						$.merge(parent.SYSTEM.goodsInfo, data.data.rows);
						var goodsInfo = parent.SYSTEM.goodsInfo;
						if(goodsInfo.length > 100) {
							goodsInfo.splice(0, goodsInfo.length - 100);
						}
					};
				});
			},
			onListClick: function(){

			}
		},
		forceSelection : false,
		queryDelay: 0,
		inputCls: 'edit_subject',
		wrapCls: 'edit_subject_wrap',
		focusCls: '',
		disabledCls: '',
		activeCls: ''
	}, opts);
	
	var goodsCombo = $_obj.combo(opts).getCombo();
	
	//新增商品
	$('#quickAddGoods').on('click', function(e){
		e.preventDefault();
		if (!Business.verifyRight('INVENTORY_ADD')) {
			return ;
		};
		$.dialog({
			title : '新增商品',
			//content : 'url:/settings/goods-manage.jsp',
			content : 'url:'+settings_goods_manage,
			data: {oper: 'add', callback: function(data, oper, dialogWin){
				var goodID = data.id;
				//_self.goodsCombo.getAllRawData().push(data);
				parent.SYSTEM.goodsInfo.push(data);
				dialogWin && dialogWin.api.close();
				//var allRawData = _self.goodsCombo.getAllRawData();
				goodsCombo.loadData(parent.SYSTEM.goodsInfo, '-1', false);
				setTimeout( function() {
					 //$("#grid").jqGrid("editCell", editRow, 2, true)
					 goodsCombo.selectByValue(goodID, true);
					 $_obj.focus();
				}, 10);
				
			}},
			width : 640,
			height : 530,
			max : false,
			min : false,
			cache : false,
			lock: true
		});
	});
	return goodsCombo;
};
Business.categoryCombo = function($_obj, opts, type){
	if ($_obj.length == 0) { return };
	var typeNumber = type||'';
	if(typeof opts != 'object'){
		typeNumber = opts;
		opts = {};
	}
	if(!typeNumber) { return };
	var defaultPage = Public.getDefaultPage();
	var opts = $.extend(true, {
		data: function(){
			if(defaultPage.SYSTEM.categoryInfo && defaultPage.SYSTEM.categoryInfo[typeNumber]) {
				return defaultPage.SYSTEM.categoryInfo[typeNumber];
			} else {
				return basedata_category+'?action=list&isDelete=2&typeNumber='+typeNumber;
			}
		},
		ajaxOptions: {
			formatData: function(data){
				defaultPage.SYSTEM.categoryInfo = defaultPage.SYSTEM.categoryInfo ||{};
				defaultPage.SYSTEM.categoryInfo[typeNumber] = data.data.items;	//更新
				return data.data.items;
			}	
		},
		text: 'name',
		value: 'id',
		defaultSelected: -1,
		editable: true,
		extraListHtml: '<a href="javascript:void(0);" id="quickAddCategory" class="quick-add-link"><i class="ui-icon-add"></i>新增类别</a>',
		maxListWidth: 500,
		cache: false,
		forceSelection: true,
		maxFilter: 10,
		trigger: false,
		callback: {
			onChange: function(data){
				var parentTr = this.input.parents('tr');
				if(data) {
					parentTr.data('categoryInfo', data);
				}
			},
			onListClick: function(){

			}
		},
		queryDelay: 0
	}, opts);
	
	var categoryCombo = $_obj.combo(opts).getCombo();
	var rights = {
			'customertype' : 'BUTYPE_ADD',// '客户',
			'supplytype' : 'SUPPLYTYPE_ADD',// '供应商',
			'trade' : 'TRADETYPE_ADD'// '商品'
		};
	//新增分类
	$('#quickAddCategory').on('click', function(e){
		e.preventDefault();
		if (rights[typeNumber] && !Business.verifyRight(rights[typeNumber])) {
			return ;
		};
		var callback=function(data,dialogWin){
			categoryCombo.loadData(function(){return defaultPage.SYSTEM.categoryInfo[typeNumber]}, '-1', false);
			dialogWin.close();
			setTimeout( function() {
				categoryCombo.selectByValue(data.id, true);
				$_obj.focus();
			}, 10);
		};
		Public.categoryPop(typeNumber,window.parent,callback);
	});
	return categoryCombo;
};
Business.forSearch = function(id, text){
	if(id) {
		$.dialog({
			width: 470,
			height: 410,
			title: '商品库存查询',
			content: 'url:'+settings_inventory,
			data: { id: id, text: text},
			cancel: true,
			//lock: true,
			cancelVal: '关闭'
			
		});
		//goodsCombo.removeSelected(false);
	} else {
		parent.Public.tips({type: 2, content : '请先选择一个商品！'});
	};
};

Business.forInvsaSearch = function(id, text){
    if(id) {
        $.dialog({
            width: 600,
            height: 410,
            title: '销售单据信息',
            content: 'url:'+settings_invsa_info+"?id="+id,
            data: { id: id, text: text},
            cancel: true,
            //lock: true,
            cancelVal: '关闭'

        });
        //goodsCombo.removeSelected(false);
    } else {
        parent.Public.tips({type: 2, content : '请先选择一个商品！'});
    };
};

Business.forPurchasePlanSearch = function(id, text){
    if(id) {
        $.dialog({
            width: 600,
            height: 410,
            title: id+' 采购计划信息',
            content: 'url:'+settings_purchasePlan_info+"?id="+id,
            data: { id: id, text: text},
            cancel: true,
            //lock: true,
            cancelVal: '关闭'

        });
        //goodsCombo.removeSelected(false);
    } else {
        parent.Public.tips({type: 2, content : '请先选择一个商品！'});
    };
};

Business.forPurchaseReviewSearch = function(id, text){
    if(id) {
        $.dialog({
            width: 700,
            height: 410,
            title: text+' 采购信息',
            content: 'url:'+settings_invpu_info,
            data: { id: id, text: text},
            cancel: true,
            //lock: true,
            cancelVal: '关闭',

        });
        //goodsCombo.removeSelected(false);
    } else {
        parent.Public.tips({type: 2, content : '请先选择一个商品！'});
    };
};


Business.storageCombo = function($_obj, opts){
	if ($_obj.length == 0) { return };
	var opts = $.extend(true, {
			//data: parent.SYSTEM.storageInfo/*'/basedata/invlocation.do?action=list&isEnable=1'*/,
			data: function(){
				return (parent.SYSTEM || opts.userData.system).storageInfo;
			},
/*			ajaxOptions: {
				formatData: function(data){
					return data.data.items;
				}	
			},*/
			text: 'name',
			value: 'id',
			defaultSelected: 0,
			cache: false,
			editable: false,
			trigger: false,
			defaultFlag: false,
			callback: {
				onChange: function(data){
					var parentTr = this.input.parents('tr');
					//var storageInfo = parentTr.data('storageInfo');
					//console.log(parentTr.data('storageInfo'))
/*					if(!storageInfo) {
						storageInfo = {};
					};*/
					if(data) {
						parentTr.data('storageInfo', {id: data.id, name: data.name});
						//storageInfo.id = data.id;
						//storageInfo.name = data.name;
					}
				}
			}
		}, opts);
	
	var storageCombo = $_obj.combo(opts).getCombo();
	return storageCombo;
};

Business.accountCombo = function($_obj, opts){
	if ($_obj.length == 0) { return };
	var opts = $.extend(true, {
		data: function(){
			if(SYSTEM.accountInfo) {
				return SYSTEM.accountInfo;
			} else {
				return '/basedata/settAcct.do?action=list';
			}
		},
		ajaxOptions: {
			formatData: function(data){
				SYSTEM.accountInfo = data.data.items;	//更新
				return data.data.items;
			}	
		},
		formatText: function(data){
			return data.number + ' ' + data.name;
		},
		value: 'id',
		defaultSelected: 0,
		defaultFlag: false,
		cache: false,
		editable: true
	}, opts);	
	var accountCombo = $_obj.combo(opts).getCombo();
	return accountCombo;
};

Business.paymentCombo = function($_obj, opts){
	if ($_obj.length == 0) { return };
	var opts = $.extend(true, {
		data: function(){
			if(SYSTEM.paymentInfo) {
				return SYSTEM.paymentInfo;
			} else {
				return '/basedata/assist.do?action=list&typeNumber=PayMethod&isDelete=2';
			}
		},
		ajaxOptions: {
			formatData: function(data){
				SYSTEM.paymentInfo = data.data.items;	//更新缓存
				return data.data.items;
			}	
		},
		emptyOptions: true,
		text: 'name',
		value: 'id',
		defaultSelected: 0,
		cache: false,
		editable: false,
		trigger: false,
		defaultFlag: false
		
	}, opts);
	var paymentCombo = $_obj.combo(opts).getCombo();	
	return paymentCombo;
};
/*
 * 网店下拉框
 */
Business.storeCombo = function($_obj, opts){
	if ($_obj.length == 0) { return };
	var SYSTEM = SYSTEM || parent.SYSTEM || opts.system;
	var opts = $.extend(true, {
		data: function(){
			if(SYSTEM.storeInfo) {
				return SYSTEM.storeInfo;
			} else {
				return '/bs/cloudStore.do?action=list';
			}
		},
		ajaxOptions: {
			formatData: function(data){
				SYSTEM.storeInfo = data.data.items;	//更新
				return data.data.items;
			}	
		},
		formatText: function(data){
			return data.number + ' ' + data.name;
		},
		value: 'id',
		defaultSelected: 0,
		addOptions : {text : '(所有)',value : -1	},
		defaultFlag: false,
		cache: false,
		editable: true
	}, opts);	
	var storeCombo = $_obj.combo(opts).getCombo();
	return storeCombo;
};
/*
 * 物流公司下拉框
 */
Business.logisticCombo = function($_obj, opts){
	if ($_obj.length == 0) { return };
	var SYSTEM = SYSTEM || parent.SYSTEM || opts.system;
	var opts = $.extend(true, {
		data: function(){
			if(SYSTEM.logisticInfo) {
				return SYSTEM.logisticInfo;
			} else {
				return '/bs/express.do?action=list';
			}
		},
		ajaxOptions: {
			formatData: function(data){
				SYSTEM.logisticInfo = data.data.items;	//更新
				return data.data.items;
			}	
		},
		formatText: function(data){
			return data.number + ' ' + data.name;
		},
		value: 'id',
		defaultSelected: 0,
		addOptions : {text : '(空)',value : 0	},
		defaultFlag: false,
		cache: false,
		editable: true
	}, opts);	
	var logisticCombo = $_obj.combo(opts).getCombo();
	return logisticCombo;
};
Business.billsEvent = function(obj, type, flag){
	var _self = obj;
	//新增分录
	$('.grid-wrap').on('click', '.ui-icon-plus', function(e){
		var rowId = $(this).parent().data('id');
		var newId = $('#grid tbody tr').length;
		var datarow = { id: _self.newId };
		var su = $("#grid").jqGrid('addRowData', _self.newId, datarow, 'before', rowId);
		if(su) {
			$(this).parents('td').removeAttr('class');
			$(this).parents('tr').removeClass('selected-row ui-state-hover');
			$("#grid").jqGrid('resetSelection');
			_self.newId++;
		}
	});
	//删除分录
	$('.grid-wrap').on('click', '.ui-icon-trash', function(e){
		if($('#grid tbody tr').length === 2) {
			parent.Public.tips({type: 2, content: '至少保留一条分录！'});
			return false;
		}
		var rowId = $(this).parent().data('id');
		var su = $("#grid").jqGrid('delRowData', rowId);
		if(su) {
			_self.calTotal();
		};
	});



	//区分组装拆卸单
	if(type !== 'assemble') {
		$('#customer').on('click', '.ui-icon-ellipsis', function(e){			
			if($(this).data('hasInstance')) {
				_self.customerDialog.show().zindex();
			} else {
				var lable = $('#customer').prev().text().slice(0, -1);
				var title = '选择' + lable;
				//if(lable === '供应商') {
//					var content = 'url:/settings/select-customer.jsp?type=10';
//				} else {
//					var content = 'url:/settings/select-customer.jsp';
//				}
				if(lable === '供应商') {
					var content = 'url:'+settings_select_vendor+'?type=2';
				} else {
					var content = 'url:'+settings_select_customer+'?type=1';
				}
				_self.customerDialog = $.dialog({
					width: 775,
					height: 510,
					title: title,
					content: content,
					data: {
						
					},
					lock: true,
					ok: function(){
						this.content.callback();
						this.hide();
				        return false;
					},
					cancel: function(){
						this.hide();
				        return false;
					}
				});
				$(this).data('hasInstance', true);
			};
		});
		
		//批量添加
		$('.grid-wrap').on('click', '.ui-icon-ellipsis', function(e){
			var a = $("#grid").jqGrid('getGridParam','colModel');
			var name = a[$(this).closest('td').index()]['name'];
			$.dialog({
					width: 775,
					height: 510,
					title: '选择商品',
					//content: 'url:/settings/goods-batch.jsp',
					content: 'url:'+settings_goods_batch,
					data: {
						skey:_self.skey,
						name:name,
						callback: function(newId, curID, curRow){
							if(curID === '') {
								$("#grid").jqGrid('addRowData', newId, {}, 'last');
								_self.newId = newId + 1;
							};
							setTimeout( function() { $("#grid").jqGrid("editCell", curRow, 2, true) }, 10);
							_self.calTotal();
						}
					},
					lock: true,
					ok: function(){
						this.content.callback(type);
						
				        return false;
					},
					okVal:'选中',
					cancel: function() {
						
				        return true;
					},
					cancelVal:'关闭'
				});
				$(this).data('hasInstance', true);
		}),

		//取消分录编辑状态
		$(document).bind('click.cancel', function(e){
			if(!$(e.target).closest(".ui-jqgrid-bdiv").length > 0 && curRow !== null && curCol !== null){
			   $("#grid").jqGrid("saveCell", curRow, curCol);
			   curRow = null;
			   curCol = null;
			};
		});
	};	
	//initStorage();
	
	function initStorage() {
		var data = parent.SYSTEM.storageInfo;
		var list = '<ul>';
		for(var i = 0, len = data.length; i < data.length; i++) {
			list += '<li data-id="' + data[i].id + '" data-name="' + data[i].name + '" >' + data[i].locationNo + ' ' +data[i].name + '</li>';
		};
		list += '</ul>';
		$("#storageBox").html(list);
	};

	if(type === 'transfers') {
		return;
	};
	
	$("#batchStorage").powerFloat({
		eventType: "click",
		hoverHold: false,
		reverseSharp: true,
		target: function(){
			if(curRow !== null && curCol !== null){
			   $("#grid").jqGrid("saveCell", curRow, curCol);
			   curRow = null;
			   curCol = null;
			};
			return $("#storageBox");
		}
	});

	$('.wrapper').on('click', '#storageBox li', function(e){
		var stoId = $(this).data('id');
		var stoName = $(this).data('name');
		var ids = $("#grid").jqGrid('getDataIDs');
		var batName = 'locationName';
		var batInfo = 'storageInfo';
		for(var i = 0, len = ids.length; i < len; i++){
			var id = ids[i], itemData;
			var row = $("#grid").jqGrid('getRowData',id);
			var $_id = $('#' + id);
			if(row.goods === '' || $_id.data('goodsInfo') === undefined) {
				continue;	//跳过无效分录
			};
			var setData = {};
			setData[batName] = stoName;
			$("#grid").jqGrid('setRowData', id, setData);
			$('#' + id).data(batInfo, { id: stoId, name: stoName });
		};
		$.powerFloat.hide();
	});

};

Business.filterCustomer = function(){
	Business.customerCombo($('#customerAuto'), {
		width: '',
		formatText: function(data){
			return data.number + ' ' + data.name;
		},
		trigger: false,
		forceSelection: false,
		noDataText: '',
		extraListHtmlCls: '',
		extraListHtml: '', 
		callback: {
			onChange: function(data){
				if(data) {
					//this.input.data('ids', data.id);
					this.input.val(data.number);
				}
			}
		}
	});
	
	//客户
	$('#filter-customer .ui-icon-ellipsis').on('click', function(){
		var $input = $(this).prev('input');
		$.dialog({
			width: 570,
			height: 500,
			title: '选择客户',
			//content: 'url:/settings/customer-batch.jsp',
			content: 'url:'+settings_customer_batch,
			lock: true,
			ok: function(){
				Business.setFilterData(this.content, $input);
			},
			cancel: function(){
				return true;
			}
		});
	});
};

Business.filterSupplier = function(){
	Business.supplierCombo($('#supplierAuto'), {
		width: '',
		formatText: function(data){
			return data.number + ' ' + data.name;
		},
		trigger: false,
		forceSelection: false,
		noDataText: '',
		extraListHtmlCls: '',
		extraListHtml: '', 
		callback: {
			onChange: function(data){
				if(data) {
					//this.input.data('ids', data.id);
					this.input.val(data.number);
				}
			}
		}
	});
	
	//客户
	$('#filter-customer .ui-icon-ellipsis').on('click', function(){
		var $input = $(this).prev('input');
		$.dialog({
			width: 570,
			height: 500,
			title: '选择供应商',
			//content: 'url:/settings/supplier-batch.jsp',
			content: 'url:'+settings_vendor_batch,
			lock: true,
			ok: function(){
				Business.setFilterData(this.content, $input);
			},
			cancel: function(){
				return true;
			}
		});
	});

};
//结算账户查询区域下拉框初始化
Business.filterSettlementAccount = function(){
	Business.settlementAccountCombo($('#settlementAccountAuto'), {
		width: '',
		formatText: function(data){
			return data.number + ' ' + data.name;
		},
		trigger: false,
		forceSelection: false,
		noDataText: '',
		extraListHtmlCls: '',
		extraListHtml: '', 
		callback: {
			onChange: function(data){
				if(data) {
					//this.input.data('ids', data.id);
					this.input.val(data.number);
				}
			}
		}
	});
	
	//结算账户
	$('#filter-settlementAccount .ui-icon-ellipsis').on('click', function(){
		var $input = $(this).prev('input');
		$.dialog({
			width: 470,
			height: 500,
			title: '选择结算账户',
			content: 'url:/settings/settlementAccount-batch.jsp',
			lock: true,
			ok: function(){
				Business.setFilterData(this.content, $input);
			},
			cancel: function(){
				return true;
			}
		});
	});
};

Business.filterGoods = function(){
	Business.goodsCombo($('#goodsAuto'), { 
		forceSelection: false,
		noDataText: '',
		extraListHtmlCls: '',
		extraListHtml: '', 
		forceSelection: false,
		callback: {
			onChange: function(data){
				if(data) {
					this.input.data('ids', data.number);
					this.input.val(data.number);
				}
			}
		}
	});
	//商品
    $('#filter-goods .ui-icon-ellipsis').on('click', function(){
        var $input = $(this).prev('input');
        $.dialog({
            width: 775,
            height: 500,
            title: '选择商品',
            //content: 'url:/settings/goods-batch.jsp',
            content: 'url:'+settings_goods_batch,
            lock: true,
            ok: function(){
                Business.setFilterGoods(this.content, $input);
            },
            cancel: function(){
                return true;
            }
        });
    });
};
Business.filterStorage = function(){
	Business.storageCombo($('#storageAuto'), {
		data: function(){
			return parent.SYSTEM.allStorageInfo;
		},
		formatText: function(data){
			return data.locationNo + ' ' + data.name;
		},
		editable: true,
		defaultSelected: -1,
		forceSelection: false,
		callback: {
			onChange: function(data){
				if(data) {
					//this.input.data('ids', data.id);
					this.input.val(data.locationNo);
				}
			}
		}
	});
	//仓库
	$('#filter-storage .ui-icon-ellipsis').on('click', function(){
		var $input = $(this).prev('input');
		$.dialog({
			width: 510,
			height: 500,
			title: '选择仓库',
			content: 'url:/settings/storage-batch.jsp',
			lock: true,
			ok: function(){
				Business.setFilterData(this.content, $input);
			},
			cancel: function(){
				return true;
			}
		});
	});
};

//将弹窗中返回的数据记录到相应的input中
Business.setFilterData = function(dialogCtn, $input){
	var numbers = [];
	var ids = [];
	for(rowid in dialogCtn.addList){
		var row = dialogCtn.addList[rowid];
		ids.push(rowid);
		numbers.push(row.number || row.locationNo);
	}
	$input.data('ids', ids.join(',')).val(numbers.join(','));
};

Business.setFilterGoods = function(dialogCtn, $input){
	var numbers = [];
	var ids = [];
	for(rowid in dialogCtn.addList){
		var row = dialogCtn.addList[rowid];
		ids.push(rowid);
		numbers.push(row.number || row.locationNo);
	}
	$input.data('ids', ids.join(',')).val(numbers.join(','));
};

Business.moreFilterEvent = function(){
	$('#conditions-trigger').on('click', function(e){
		e.preventDefault();
	  if (!$(this).hasClass('conditions-expand')) {
		  $('#more-conditions').stop().slideDown(200, function(){
			   $('#conditions-trigger').addClass('conditions-expand').html('收起更多<b></b>');
			   $('#filter-reset').css('display', 'inline');
		  });
	  } else {
		  $('#more-conditions').stop().slideUp(200, function(){
			  $('#conditions-trigger').removeClass('conditions-expand').html('更多条件<b></b>');
			  $('#filter-reset').css('display', 'none');
		  });
	  };
	});
};

Business.gridEvent = function(){
	$('.grid-wrap').on('mouseenter', '.list tbody tr', function(e){
		$(this).addClass('tr-hover');
		if($_curTr) {
			$_curTr.removeClass('tr-hover');
			$_curTr = null;
		}
	}).on('mouseleave', '.list tbody tr', function(e){
		$(this).removeClass('tr-hover');
	});
};

//判断:当前元素是否是被筛选元素的子元素
$.fn.isChildOf = function(b){
    return (this.parents(b).length > 0);
};

//判断:当前元素是否是被筛选元素的子元素或者本身
$.fn.isChildAndSelfOf = function(b){
    return (this.closest(b).length > 0);
};

//数字输入框
$.fn.digital = function() {
	this.each(function(){
		$(this).keyup(function() {
			this.value = this.value.replace(/\D/g,'');
		})
	});
};

/** 
 1. 设置cookie的值，把name变量的值设为value   
example $.cookie(’name’, ‘value’);
 2.新建一个cookie 包括有效期 路径 域名等
example $.cookie(’name’, ‘value’, {expires: 7, path: ‘/’, domain: ‘jquery.com’, secure: true});
3.新建cookie
example $.cookie(’name’, ‘value’);
4.删除一个cookie
example $.cookie(’name’, null);
5.取一个cookie(name)值给myvar
var account= $.cookie('name');
**/
$.cookie = function(name, value, options) {
    if (typeof value != 'undefined') { // name and value given, set cookie
        options = options || {};
        if (value === null) {
            value = '';
            options.expires = -1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
            var date;
            if (typeof options.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
        }
        var path = options.path ? '; path=' + options.path : '';
        var domain = options.domain ? '; domain=' + options.domain : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
    } else { // only name given, get cookie
        var cookieValue = null;
        if (document.cookie && document.cookie != '') {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = jQuery.trim(cookies[i]);
                // Does this cookie string begin with the name we want?
                if (cookie.substring(0, name.length + 1) == (name + '=')) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }
        return cookieValue;
    }
};
Public.print = function (opt){
	var voucherIds = opt.$grid.jqGrid('getGridParam','selarrrow').join();
	var pdfUrl = opt.pdf;
	var sidx = opt.$grid.jqGrid('getGridParam','sortname');
	var sord = opt.$grid.jqGrid('getGridParam','sortorder');
	var billType = opt.billType;
	var data = {
		sidx: sidx,
		sord: sord,
		op: 2
	};
	if(opt.filterConditions){
		data = $.extend(true, data, opt.filterConditions);
	};
	if(voucherIds) {
		data.id = voucherIds;
	};
	$.dialog({
		title: opt.title,
		content : 'url:../print/print-settings-voucher.jsp',
		data: {taodaData: data, pdfData: data, pdfUrl: pdfUrl, billType:billType ,opt:opt},
		width: 520,
		height: 400,
		min: false,
		max: false,
		lock: true,
		ok: function(){
			this.content.doPrint();
			return false;
		},
		okVal: '打印',
		cancel: true
	});
};
//生成树
Public.zTree = {
    zTree: {},
    opts:{
    	showRoot:true,
    	defaultClass:'',
    	disExpandAll:false,//showRoot为true时无效
    	callback:'',
    	rootTxt:'全部'
    },
    setting: {
        view: {
            dblClickExpand: false,
            showLine: true,
            selectedMulti: false
        },
        data: {
            simpleData: {
                enable: true,
                idKey: "id",
                pIdKey: "parentId",
                rootPId: ""
            }
        },
        callback: {
            //beforeClick: function(treeId, treeNode) {}
        }
    },
    _getTemplate: function(opts) {
    	this.id = 'tree'+parseInt(Math.random()*10000);
        var _defaultClass = "ztree";
        if (opts) {
            if(opts.defaultClass){
                _defaultClass += ' ' + opts.defaultClass;
            }
        }
        return '<ul id="'+this.id+'" class="' + _defaultClass + '"></ul>';
    },
    init: function($target, opts, setting ,callback) {
        if ($target.length === 0) {
            return;
        }
        var self = this;
        self.opts = $.extend(true, self.opts, opts);
        self.container = $($target);
        self.obj = $(self._getTemplate(opts)); 
        self.container.append(self.obj);
        setting = $.extend(true, self.setting, setting);
        //Public.ajaxPost('../basedata/assist.do?action=list&typeNumber=trade&isDelete=2', {}, function(data) {
		Public.ajaxPost(basedata_category+'?action=list&typeNumber=trade&isDelete=2', {}, function(data) {
            if (data.status === 200 && data.data) {
            	self._callback(data.data.items);
            } else {
            	Public.tips({
                    type: 2,
                    content: "加载分类信息失败！"
                });
            }
        });
        return self;
    },
    _callback: function(data){
    	var self = this;
    	var callback = self.opts.callback;
    	if(self.opts.showRoot){
    		data.unshift({name:self.opts.rootTxt,id:0});
        	self.obj.addClass('showRoot');
    	}
    	if(!data.length) return;
    	self.zTree = $.fn.zTree.init(self.obj, self.setting, data);
    	//self.zTree.selectNode(self.zTree.getNodeByParam("id", 101));
    	self.zTree.expandAll(!self.opts.disExpandAll);
    	if(callback && typeof callback === 'function'){
    		callback(self, data);
    	}
    }
};
//分类下拉框
Public.categoryTree = function($obj, opts) {
	if ($obj.length === 0) {
        return;
    }
	opts = opts ? opts : opts = {};
	var opts = $.extend(true, {
		inputWidth:'145',
		width:'',//'auto' or int
		height:'240',//'auto' or int
		trigger:true,
		defaultClass:'ztreeCombo',
		disExpandAll:false,//展开全部
		defaultSelectValue:0,
		showRoot:true,//显示root选择
		rootTxt:'全部',
		treeSettings : {
			callback:{
				beforeClick: function(treeId, treeNode) {
					if(_Combo.obj){
						_Combo.obj.val(treeNode.name);
						_Combo.obj.data('id', treeNode.id);
						_Combo.hideTree();
					}
				}
			}
		}
	}, opts);
	var _Combo = {
		container:$('<span class="ui-tree-wrap" style="width:'+opts.inputWidth+'px"></span>'),
		obj : $('<input type="text" class="input-txt" style="width:'+(opts.inputWidth-26)+'px" id="'+$obj.attr('id')+'" autocomplete="off" readonly value="'+($obj.val()||$obj.text())+'">'),
		trigger : $('<span class="trigger"></span>'),
		data:{},
		init : function(){
			var _parent = $obj.parent();
			var _this = this;
			$obj.remove();
			this.obj.appendTo(this.container);
			if(opts.trigger){
				this.container.append(this.trigger);
			}
			this.container.appendTo(_parent);
			opts.callback = function(publicTree ,data){
				_this.zTree = publicTree;
				//_this.data = data;
				if(publicTree){
					publicTree.obj.css({
						'max-height' : opts.height
					});
					for ( var i = 0, len = data.length; i < len; i++){
						_this.data[data[i].id] = data[i];
					};
					if(opts.defaultSelectValue !== ''){
						_this.selectByValue(opts.defaultSelectValue);
					};
					_this._eventInit();
				}
			};
			this.zTree = Public.zTree.init($('body'), opts , opts.treeSettings);
			return this;
		},
		showTree:function(){
			if(!this.zTree)return;
			if(this.zTree){
				var offset = this.obj.offset();
				var topDiff = this.obj.outerHeight();
				var w = opts.width? opts.width : this.obj.width();
				var _o = this.zTree.obj.hide();
				_o.css({width:w, top:offset.top + topDiff,left:offset.left-1});
			}
			var _o = this.zTree.obj.show();
			this.isShow = true;
			this.container.addClass('ui-tree-active');
		},
		hideTree:function(){
			if(!this.zTree)return;
			var _o = this.zTree.obj.hide();
			this.isShow = false;
			this.container.removeClass('ui-tree-active');
		},
		_eventInit: function(){
			var _this = this;
			if(opts.trigger){
				_this.trigger.on('click',function(e){
					e.stopPropagation();
					if(_this.zTree && !_this.isShow){
						_this.showTree();
					}else{
						_this.hideTree();
					}
				});
			};
			_this.obj.on('click',function(e){
				e.stopPropagation();
				if(_this.zTree && !_this.isShow){
					_this.showTree();
				}else{
					_this.hideTree();
				}
			});
			if(_this.zTree){
				_this.zTree.obj.on('click',function(e){
					e.stopPropagation();
				});
			}
			$(document).click(function(){
				_this.hideTree();
			});
		},
		getValue:function(){
			var id = this.obj.data('id') || '';
			if(!id){
				var text = this.obj.val();
				if(this.data){
					for(var item in this.data){
						if(this.data[item].name === text){
							id = this.data[item].id;
						}
					}
				}
			}
			return id;
		},
		getText:function(){
			if(this.obj.data('id'))
				return this.obj.val();
			return '';
		},
		selectByValue:function(value){
			if(value !== ''){
				if(this.data){
					this.obj.data('id', value);
					this.obj.val(this.data[value].name);
				}
			}
			return this;
		}
	};
	return _Combo.init();
};
/*
 * 分类新增弹窗
 * 不支持多级结构（树）
 * type string 分类类型
 * parentWin object 父窗口对象,决定弹窗的覆盖范围，默认当前窗口的parent
 */
Public.categoryPop = function(type,targetWin,callback){ 
	var defaultPage = Public.getDefaultPage();
	var self = {
			init:function(){
				var myParent = targetWin || parent;
				var showParentCategory = false;
				var content = $(['<form id="manage-form" action="" style="width: 282px;">',
				               '<ul class="mod-form-rows manage-wrap" id="manager">',
						           '<li class="row-item">',
						               '<div class="label-wrap"><label for="category">类别:</label></div>',
						               '<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="category" id="category" style="width:190px;"></div>',
						           '</li>',
						       '</ul>',
					       '</form>'].join(''));
				var height = 90;
				var dialog = myParent.$.dialog({
					title : '新增类别',
					content : content,
					//data: data,
					width : 400,
					height : height,
					max : false,
					min : false,
					cache : false,
					lock: true,
					okVal:'确定',
					ok:function(){
						var	category = $.trim(content.find('#category').val());
						if(!category){
							defaultPage.Public.tips({content : '请输入类别名称！'});
							category.focus();
							return false;
						}
						var oper = 'add'; 
						var params = { name: category ,typeNumber: type};
						var msg = '新增类别';
						Public.ajaxPost(category_save+'?act=' + oper, params, function(data){
							if (data.status == 200) {
								defaultPage.Public.tips({content : msg + '成功！'});
								defaultPage.SYSTEM.categoryInfo[type].push(data.data);
								if(typeof callback ==='function'){
									callback(data.data,dialog);
								}
							} else {
								defaultPage.Public.tips({type:1, content : msg + '失败！' + data.msg});
							}
						});
						return false;
					},
					cancelVal:'取消',
					cancel:function(){
						return true;
					}
				});
			}
	};
	self.init();
};
/*
 * 兼容IE8 数组对象不支持indexOf()
 * create by guoliang_zou ,20140812
 */
if (!Array.prototype.indexOf)
{
  Array.prototype.indexOf = function(elt /*, from*/)
  {
    var len = this.length >>> 0;
    var from = Number(arguments[1]) || 0;
    from = (from < 0)
         ? Math.ceil(from)
         : Math.floor(from);
    if (from < 0)
      from += len;
    for (; from < len; from++)
    {
      if (from in this &&
          this[from] === elt)
        return from;
    }
    return -1;
  };
}












