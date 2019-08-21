function setTabHeight() {
	var e = $(window).height(),
		t = $("#main-bd"),
		i = e - t.offset().top;
	t.height(i)
}
function initDate() {
	var e = new Date,
		t = e.getFullYear(),
		i = ("0" + (e.getMonth() + 1)).slice(-2),
		a = ("0" + e.getDate()).slice(-2);
	SYSTEM.beginDate = t + "-" + i + "-01";
	SYSTEM.endDate = t + "-" + i + "-" + a
}
function addUrlParam() {
	var e = "beginDate=" + SYSTEM.beginDate + "&endDate=" + SYSTEM.endDate;
	$("#nav").find("li.item-report .nav-item a").each(function() {
		var t = this.href;
		t += -1 === this.href.lastIndexOf("?") ? "?" : "&";
		this.href = "商品库存余额表" === $(this).html() ? t + "beginDate=" + SYSTEM.startDate + "&endDate=" + SYSTEM.endDate : t + e
	})
}
function BBSPop() {
	var e = $("#yswb-tab"),
		t = ['<ul id="yswbPop">', '<li class="yswbPop-title">请选择您要进入的论坛</li>', '<li><strong>产品服务论坛</strong><p>在线会计，在线进销存操作问题咨询</p><a href="http://kdweibo.com/youshang.com/042633" target="_blank">进入论坛>></a></li>', '<li><strong>会计交流论坛</strong><p>会计实操，会计学习，会计资讯</p><a href="http://kdweibo.com/youshang.com/026606" target="_blank">进入论坛>></a></li>', "</ul>"].join("");
	e.find("a").click(function(e) {
		e.preventDefault();
		var i = $.cookie("yswbPop_scm");
		i ? window.open(i) : $.dialog({
			id: "",
			title: "",
			lock: !0,
			padding: 0,
			content: t,
			max: !1,
			min: !1,
			init: function() {
				var e = $("#yswbPop"),
					t = this;
				e.find("a").each(function() {
					$(this).on("click", function() {
						$.cookie("yswbPop_scm", this.href, {
							expires: 7
						});
						t.close()
					})
				})
			}
		})
	})
}
function getStores() {
	SYSTEM.isAdmin || SYSTEM.rights.CLOUDSTORE_QUERY ? Public.ajaxGet("http://wd.youshang.com/bs/cloudStore.do?action=list", {}, function(e) {
		200 === e.status ? SYSTEM.storeInfo = e.data.items : 250 === e.status ? SYSTEM.storeInfo = [] : Public.tips({
			type: 1,
			content: e.msg
		})
	}) : SYSTEM.storeInfo = []
}
function getLogistics() {
	SYSTEM.isAdmin || SYSTEM.rights.EXPRESS_QUERY ? Public.ajaxGet("http://wd.youshang.com/bs/express.do?action=list", {}, function(e) {
		200 === e.status ? SYSTEM.logisticInfo = e.data.items : 250 === e.status ? SYSTEM.logisticInfo = [] : Public.tips({
			type: 1,
			content: e.msg
		})
	}) : SYSTEM.logisticInfo = []
}
function setCurrentNav(e) {
	if (e) {
		var t = e.match(/([a-zA-Z]+)[-]?/)[1];
		$("#nav > li").removeClass("current");
		$("#nav > li.item-" + t).addClass("current")
	}
}



$(function() {
	$("#companyName").text(SYSTEM.companyName).prop("title", SYSTEM.companyName)
});
if (SYSTEM.siExpired) {
	var button = [{
		name: "立即续费",
		focus: !0,
		callback: function() {
			window.open("http://service.youshang.com/fee/renew.do?langOption=zh-CHS&accIds=" + SYSTEM.DBID)
		}
	}, {
		name: "下次再说"
	}],
		tipsContent = ['<div class="ui-dialog-tips">', "<p>谢谢您使用本产品，您的当前服务已经到期，到期3个月后数据将被自动清除，如需继续使用请购买/续费！</p>", '<p style="color:#AAA; font-size:12px;">(续费后请刷新页面或重新登录。)</p>', "</div>"].join("");
	$.dialog({
		width: 400,
		min: !1,
		max: !1,
		title: "系统提示",
		fixed: !0,
		lock: !0,
		button: button,
		resize: !1,
		content: tipsContent
	})
}
setTabHeight();
$(window).bind("resize", function() {
	setTabHeight()
});
!
function(e) {
	menu.init(e("#nav"));
	initDate();
	addUrlParam();
	BBSPop();
	var t = e("#nav"),
		i = e("#nav > li");
	e.each(i, function() {
		var i = e(this).find(".sub-nav-wrap");
		e(this).on("mouseenter", function() {
			t.removeClass("static");
			e(this).addClass("on");
			i.find("i:eq(0)").closest("li").addClass("on");
			i.stop(!0, !0).fadeIn(250)
		}).on("mouseleave", function() {
			t.addClass("static");
			e(this).removeClass("on");
			i.stop(!0, !0).hide()
		});
		if (0 != i.length && "auto" == i.css("top") && "auto" == i.css("bottom")) {
			var a = (e(this).outerHeight() - i.outerHeight()) / 2;
			i.css({
				top: a
			})
		}
	});
	e(".sub-nav-wrap a").bind("click", function() {
		e(this).parents(".sub-nav-wrap").hide()
	});
	e(".sub-nav").each(function() {
		e(this).on("mouseover", "li", function() {
			var t = e(this);
			t.siblings().removeClass("on");
			t.addClass("on")
		}).on("mouseleave", "li", function() {
			var t = e(this);
			t.removeClass("on")
		})
	})
}(jQuery);
$("#page-tab").ligerTab({
	height: "100%",
	changeHeightOnResize: !0,
	onBeforeAddTabItem: function(e) {
		setCurrentNav(e)
	},
	onAfterAddTabItem: function() {},
	onAfterSelectTabItem: function(e) {
		setCurrentNav(e)
	},
	onBeforeRemoveTabItem: function() {},
	onAfterLeaveTabItem: function(e) {
		switch (e) {
		case "setting-vendorList":
			getSupplier();
			break;
		case "setting-customerList":
			getCustomer();
			break;
		case "setting-storageList":
			getStorage();
			break;
		case "setting-goodsList":
			getGoods();
			break;
		case "setting-settlementaccount":
			getAccounts();
			break;
		case "setting-settlementCL":
			getPayments();
			break;
		case "onlineStore-onlineStoreList":
			break;
		case "onlineStore-logisticsList":
		}
	}
});
var tab = $("#page-tab").ligerGetTabManager();
$("#nav").on("click", "[rel=pageTab]", function(e) {
	e.preventDefault();
	var t = $(this).data("right");
	if (t && !Business.verifyRight(t)) return !1;
	var i = $(this).attr("tabid"),
		a = $(this).attr("href"),
		r = $(this).attr("showClose"),
		n = $(this).attr("tabTxt") || $(this).text().replace(">", ""),
		o = $(this).attr("parentOpen");
	o ? parent.tab.addTabItem({
		tabid: i,
		text: n,
		url: a,
		showClose: r
	}) : tab.addTabItem({
		tabid: i,
		text: n,
		url: a,
		showClose: r
	});
	return !1
});
tab.addTabItem({
	tabid: "index",
	text: "首页",
	url: "index.jsp",
	showClose: !1
});
!
function(e) {
	if (2 === SYSTEM.siVersion && SYSTEM.isOpen) {
		var t, i = location.protocol + "//" + location.host + "/update_info.jsp",
			a = '您的订单分录已经录入达到300条，继续使用选择<a href="http://www.youshang.com/buy/invoicing/" target="_blank">购买产品</a>或者完善个人信息赠送1000条免费容量。';
		if (SYSTEM.isshortUser) {
			if (SYSTEM.isshortUser) {
				t = "http://service.youshang.com/user/set_password.jsp?updateUrl=" + encodeURIComponent(i) + "&warning=" + encodeURIComponent(a) + "&loginPage=http://www.youshang.com/buy/invoicing/";
				e.dialog({
					min: !1,
					max: !1,
					cancle: !1,
					lock: !0,
					width: 450,
					height: 490,
					title: "完善个人信息",
					content: "url:" + t
				})
			}
		} else {
			t = "http://service.youshang.com/user/phone_validate.jsp?updateUrl=" + encodeURIComponent(i) + "&warning=" + encodeURIComponent(a);
			e.dialog({
				min: !1,
				max: !1,
				cancle: !1,
				lock: !0,
				width: 400,
				height: 280,
				title: "完善个人信息",
				content: "url:" + t
			})
		}
	}
}(jQuery);
$(window).load(function() {
	function e() {
		var e;
		switch (SYSTEM.siVersion) {
		case 3:
			e = "1";
			break;
		case 4:
			e = "3";
			break;
		default:
			e = "2"
		}
		$.getJSON(CONFIG.SERVICE_URL + "asy/Services.ashx?callback=?", {
			coid: SYSTEM.DBID,
			loginuserno: SYSTEM.UserName,
			version: e,
			type: "getallunreadcount" + SYSTEM.servicePro
		}, function(e) {
			if (0 != e.count) {
				{
					var t = $("#SysNews a");
					t.attr("href")
				}
				t.append("<span>" + e.count + "</span>");
				0 == e.syscount && t.data("tab", 2)
			}
		})
	}
	markupVension();
	e();
	$("#skin-" + SYSTEM.skin).addClass("select").append("<i></i>");
	$("#sysSkin").powerFloat({
		eventType: "click",
		reverseSharp: !0,
		target: function() {
			return $("#selectSkin")
		},
		position: "5-7"
	});
	$("#selectSkin li a").click(function() {
		var e = this.id.split("-")[1];
		Public.ajaxPost("/basedata/systemProfile.do?action=changeSysSkin", {
			skin: e
		}, function(e) {
			200 === e.status && window.location.reload()
		})
	});
	var t = $("#nav .item");
	$("#scollUp").click(function() {
		var e = t.filter(":visible");
		if (e.first().prev().length > 0) {
			e.first().prev().show(500);
			e.last().hide()
		}
	});
	$("#scollDown").click(function() {
		var e = t.filter(":visible");
		if (e.last().next().length > 0) {
			e.first().hide();
			e.last().next().show(500)
		}
	});
	$(".service-tab").click(function() {
		var e = $(this).data("tab");
		tab.addTabItem({
			tabid: "myService",
			text: "服务支持",
			url: "/service/service.jsp",
			callback: function() {
				document.getElementById("myService").contentWindow.openTab(e)
			}
		})
	});
	if ($.cookie("ReloadTips")) {
		Public.tips({
			content: $.cookie("ReloadTips")
		});
		$.cookie("ReloadTips", null)
	}
	$("#nav").on("click", "#reInitial", function(e) {
		e.preventDefault();
		$.dialog({
			lock: !0,
			width: 430,
			height: 180,
			title: "系统提示",
			content: '<div class="re-initialize"><h3>重新初始化系统将会清空你录入的所有数据，请慎重！</h3><ul><li>系统将删除您新增的所有商品、供应商、客户</li><li>系统将删除您录入的所有订单</li><li>系统将删除您录入的所有初始化数据</li></ul><p><input type="checkbox" id="understand" /><label for="understand">我已清楚了解将产生的后果</label></p><p class="check-confirm">（请先确认并勾选“我已清楚了解将产生的后果”）</p></div>',
			icon: "alert.gif",
			okVal: "重新初始化",
			ok: function() {
				if ($("#understand").is(":checked")) {
					this.close();
					var e = $.dialog.tips("正在重新初始化，请稍候...", 1e3, "loading.gif", !0).show();
					$.ajax({
						type: "GET",
						url: "/user/recover?siId=" + SYSTEM.DBID + "&userName=" + SYSTEM.userName,
						cache: !1,
						async: !0,
						dataType: "json",
						success: function(t) {
							if (200 === t.status) {
								$("#container").html("");
								e.close();
								window.location.href = "start.jsp?re-initial=true&serviceType=" + SYSTEM.serviceType
							}
						},
						error: function(e) {
							Public.tips({
								type: 1,
								content: "操作失败了哦！" + e
							})
						}
					})
				} else $(".check-confirm").css("visibility", "visible");
				return !1
			},
			cancelVal: "放弃",
			cancel: !0
		})
	})
});