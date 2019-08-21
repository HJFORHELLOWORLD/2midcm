function init() {
	//if (void 0 !== cRowId) Public.ajaxPost("../basedata/contact.do?type=10&action=query", {
	if (void 0 !== cRowId) Public.ajaxPost(basedata_contact_query+"?type=2", {	
		id: cRowId
	}, function(t) {
		if (200 == t.status) {
			rowData = t.data;
			initField();
			initEvent();
			initGrid(rowData.links)
		} else parent.$.dialog({
			title: "系统提示",
			content: "获取供应商数据失败，暂不能修改供应商，请稍候重试",
			icon: "alert.gif",
			max: !1,
			min: !1,
			cache: !1,
			lock: !0,
			ok: "确定",
			ok: function() {
				return !0
			},
			close: function() {
				api.close()
			}
		})
	});
	else {
		initField();
		initEvent();
		initGrid()
	}
}
function initPopBtns() {
	var t = "add" == oper ? ["保存", "关闭"] : ["确定", "取消"];
	api.button({
		id: "confirm",
		name: t[0],
		focus: !0,
		callback: function() {
			cancleGridEdit();
			$_form.trigger("validate");
			return !1
		}
	}, {
		id: "cancel",
		name: t[1]
	})
}
function initValidator() {
	$_form.validator({
		rules: {
			type: [/^[a-zA-Z0-9\-_]*$/, "编号只能由数字、字母、-或_等字符组成"],
			unique: function(t) {
				var e = $(t).val();
				return $.ajax({
					//url: "/basedata/contact.do?action=checkName",
					url: basedata_contact_checkname+"?type=2",
					type: "get",
					data: "name=" + e,
					dataType: "json",
					success: function(t) {
						if (-1 != t.status) return !0;
						parent.parent.Public.tips({
							type: 2,
							content: "存在相同的客户名称！"
						});
						return void 0
					}
				})
			},
			myRemote: function(t, e, i) {
				return i.old.value === t.value || $(t).data("tip") === !1 && t.value.length > 1 ? !0 : $.ajax({
					//url: "/basedata/contact.do?action=getNextNo&type=10",
					url: basedata_contact_getnextno+"?type=2",
					type: "post",
					data: "skey=" + t.value,
					dataType: "json",
					success: function(e) {
						if (e.data && e.data.number) {
							var i = t.value.length;
							t.value = e.data.number;
							var a = t.value.length;
							if (t.createTextRange) {
								var r = t.createTextRange();
								r.moveEnd("character", a);
								r.moveStart("character", i);
								r.select()
							} else {
								t.setSelectionRange(i, a);
								t.focus()
							}
							$(t).data("tip", !0)
						} else $(t).data("tip", !1)
					}
				})
			}
		},
		messages: {
			required: "请填写{0}"
		},
		fields: {
			number: {
				rule: "add" === oper ? "required; type; myRemote" : "required; type",
				timely: 3
			},
			name: "required;"
		},
		display: function(t) {
			return $(t).closest(".row-item").find("label").text()
		},
		valid: function() {
			var t = $.trim($("#name").val());
			//Public.ajaxPost("/basedata/contact.do?type=10&action=checkName", {
			Public.ajaxPost(basedata_contact_checkname+"?type=2", {	
				name: t,
				id: cRowId
			}, function(e) {
				-1 == e.status ? parent.$.dialog.confirm('供应商名称 "' + t + '" 已经存在！是否继续？', function() {
					postData()
				}, function() {}) : postData()
			})
		},
		ignore: ":hidden",
		theme: "yellow_bottom",
		timely: 1,
		stopOnError: !0
	})
}
function postData() {
	var t = "add" == oper ? "新增供应商" : "修改供应商",
		e = getData(),
		i = e.firstLink || {};
	delete e.firstLink;
	//Public.ajaxPost("../basedata/contact.do?type=10&action=" + ("add" == oper ? "add" : "update"), e, function(e) {
	Public.ajaxPost(vendor_save	+"?type=2&act=" + ("add" == oper ? "add" : "update"), e, function(e) {	
		if (200 == e.status) {
			parent.parent.Public.tips({
				content: t + "成功！"
			});
			if (callback && "function" == typeof callback) {
				var r = e.data.id;
				a = e;
				a.id = r;
				a.number = e.data.number;
				a.name = e.data.name;
				a.customerType = e.data.cCategoryName;
				a.contacter = i.linkName || "";
				a.mobile = i.linkMobile || "";
				a.telephone = i.linkPhone || "";
				a.linkIm = i.linkIm || "";
				callback(a, oper, window)
			}
		} else parent.parent.Public.tips({
			type: 1,
			content: t + "失败！" + a.msg
		})
	})
}
function getData() {
	var t = getEntriesData(),
		e = t.entriesData,
		i = {
			id: cRowId,
			number: $.trim($("#number").val()),
			name: $.trim($("#name").val()),
			cCategory: categoryCombo.getValue(),
			beginDate: $("#date").val(),
			amount: Public.currencyToNum($("#receiveFunds").val()),
			periodMoney: Public.currencyToNum($("#periodReceiveFunds").val()),
			linkMans: JSON.stringify(e),
			remark: $("#note").val() == $("#note")[0].defaultValue ? "" : $("#note").val(),
			taxRate: $("#taxRate").val()
		};
	i.firstLink = t.firstLink;
	return i
}
function getEntriesData() {
	for (var t = {}, e = [], i = $grid.jqGrid("getDataIDs"), a = !1, r = 0, n = i.length; n > r; r++) {
		var o, s = i[r],
			l = $grid.jqGrid("getRowData", s);
		if ("" == l.name) break;
		o = {
			linkName: l.name,
			linkMobile: l.mobile,
			linkPhone: l.phone,
			linkIm: l.im,
			linkFirst: "是" == l.first ? 1 : 0
		};
		o.id = "edit" == oper ? -1 != $.inArray(Number(s), linksIds) ? s : 0 : 0;
		if ("是" == l.first) {
			a = !0;
			t.firstLink = o
		}
		e.push(o)
	}
	if (!a && e.length > 0) {
		e[0].linkFirst = 1;
		t.firstLink = e[0]
	}
	t.entriesData = e;
	return t
}
function initField() {
	$("#note").placeholder();
	if ("edit" == oper) {
		$("#number").val(rowData.number);
		$("#name").val(rowData.name);
		$("#category").data("defItem", ["id", rowData.cCategory]);
		if (rowData.beginDate) {
			var t = new Date(rowData.beginDate),
				e = t.getFullYear(),
				i = 1 * t.getMonth() + 1,
				a = t.getDate();
			$("#date").val(e + "-" + i + "-" + a)
		}
		void 0 != rowData.amount && $("#receiveFunds").val(Public.numToCurrency(rowData.amount));
		void 0 != rowData.periodMoney && $("#periodReceiveFunds").val(Public.numToCurrency(rowData.periodMoney));
		rowData.remark && $("#note").val(rowData.remark);
		$("#taxRate").val(rowData.taxRate)
	} else {
		$("#taxRate").val(17);
		$("#date").val(parent.parent.SYSTEM.startDate)
	}
	if (!api.opener.parent.SYSTEM.isAdmin && !api.opener.parent.SYSTEM.rights.AMOUNT_INAMOUNT) {
		$("#receiveFunds").closest("li").hide();
		$("#periodReceiveFunds").closest("li").hide()
	}
}
function initEvent() {
	var t = "supplytype";
	categoryCombo = Business.categoryCombo($("#category"), {
		defaultSelected: $("#category").data("defItem") || void 0,
		editable: !0,
		trigger: !0,
		width: 210,
		ajaxOptions: {
			formatData: function(e) {
				categoryData = {};
				var i = Public.getDefaultPage();
				if (200 == e.status) {
					for (var a = 0; a < e.data.items.length; a++) {
						var r = e.data.items[a];
						categoryData[r.id] = r
					}
					i.SYSTEM.categoryInfo = i.SYSTEM.categoryInfo || {};
					i.SYSTEM.categoryInfo[t] = e.data.items;
					e.data.items.unshift({
						id: 0,
						name: "（空）"
					});
					return e.data.items
				}
				return []
			}
		}
	}, t);
	var e = $("#date");
	e.blur(function() {
		"" == e.val() && e.val(parent.parent.SYSTEM.startDate)
	});
	e.datepicker({
		onClose: function() {
			var t = /^\d{4}-((0?[1-9])|(1[0-2]))-\d{1,2}/;
			t.test(e.val()) || e.val("")
		}
	});
	$("#receiveFunds").keypress(Public.numerical).focus(function() {
		this.value = Public.currencyToNum(this.value);
		$(this).select()
	}).blur(function() {
		this.value = Public.numToCurrency(this.value)
	});
	$("#periodReceiveFunds").keypress(Public.numerical).focus(function() {
		this.value = Public.currencyToNum(this.value);
		$(this).select()
	}).blur(function() {
		this.value = Public.numToCurrency(this.value)
	});
	$("#taxRate").keypress(Public.numerical).focus(function() {
		this.value = Public.currencyToNum(this.value);
		$(this).select()
	}).blur(function() {
		this.value = this.value > 0 ? this.value : -1 * this.value;
		"" == this.value && (this.value = 0)
	});
	$(document).on("click.cancle", function(t) {
		var e = t.target || t.srcElement;
		!$(e).closest("#grid").length > 0 && cancleGridEdit()
	});
	bindEventForEnterKey();
	initValidator()
}
function addCategory() {
	Business.verifyRight("SUPPLYTYPE_ADD") && parent.$.dialog({
		title: "新增供应商类别",
		//content: "url:/settings/vendor-category-manage.jsp",
		content: "url:"+settings_vendor_cate_manage,
		data: {
			oper: "add",
			callback: function(t, e, i) {
				categoryCombo.loadData(basedata_category+"?typeNumber=supplytype", ["id", t.id]);
				i && i.api.close()
			}
		},
		width: 400,
		height: 100,
		max: !1,
		min: !1,
		cache: !1,
		lock: !1
	})
}
function bindEventForEnterKey() {
	Public.bindEnterSkip($("#base-form"), function() {
		$("#grid tr.jqgrow:eq(0) td:eq(0)").trigger("click")
	})
}
function initGrid(t) {
	t || (t = []);
	if (t.length < 3) for (var e = 3 - t.length, i = 0; e > i; i++) t.push({});
	t.push({});
	$grid.jqGrid({
		data: t,
		datatype: "local",
		width: 598,
		gridview: !0,
		onselectrow: !1,
		colNames: ["联系人", "手机", "座机", "QQ/MSN", "首要联系人"],
		colModel: [{
			name: "name",
			index: "name",
			width: 90,
			title: !1,
			editable: !0
		}, {
			name: "mobile",
			index: "mobile",
			width: 90,
			title: !1,
			editable: !0
		}, {
			name: "phone",
			index: "phone",
			width: 90,
			title: !1,
			editable: !0
		}, {
			name: "im",
			index: "im",
			width: 92,
			title: !1,
			editable: !0
		}, {
			name: "first",
			index: "first",
			width: 80,
			title: !1,
			formatter: isFirstFormate,
			editable: !0,
			edittype: "select",
			editoptions: {
				value: {
					1: "是",
					0: "否"
				}
			}
		}],
		cmTemplate: {
			sortable: !1
		},
		shrinkToFit: !0,
		forceFit: !0,
		cellEdit: !0,
		cellsubmit: "clientArray",
		localReader: {
			root: "items",
			records: "records",
			repeatitems: !0
		},
		loadComplete: function(t) {
			$grid.setGridHeight($grid.height() > 125 ? "125" : "auto");
			$grid.setGridWidth(598);
			if ("add" != oper) if (t && t.items) {
				linksIds = [];
				for (var e = t.items, i = 0; i < e.length; i++) e[i].id && linksIds.push(e[i].id)
			} else linksIds = []
		},
		afterSaveCell: function(t, e, i) {
			if ("first" == e) {
				i = "boolean" == typeof i ? i ? "1" : "0" : i;
				if ("1" === i) for (var a = $grid.jqGrid("getDataIDs"), r = 0; r < a.length; r++) {
					var n = a[r];
					n != t && $grid.jqGrid("setCell", n, "first", "0")
				}
			}
		}
	})
}
function isFirstFormate(t) {
	t = "boolean" == typeof t ? t ? "1" : "0" : t;
	return "1" === t ? "是" : "&#160;"
}
function cancleGridEdit() {
	if (null !== curRow && null !== curCol) {
		$grid.jqGrid("saveCell", curRow, curCol);
		curRow = null;
		curCol = null
	}
}
function resetForm(t) {
	var e = [{}, {}, {}, {}];
	$("#name").val("");
	$("#date").val("");
	$("#receiveFunds").val("");
	$("#note").val("");
	$("#taxRate").val("");
	$("#periodReceiveFunds").val("");
	$("#taxRate").val("17");
	$grid.jqGrid("clearGridData").jqGrid("setGridParam", {
		data: e
	}).trigger("reloadGrid");
	$("#number").val(Public.getSuggestNum(t.number)).focus().select()
}
var curRow, curCol, curArrears, api = frameElement.api,
	oper = api.data.oper,
	cRowId = api.data.rowId,
	rowData = {},
	linksIds = [],
	callback = api.data.callback,
	categoryCombo, categoryData = {},
	$grid = $("#grid"),
	$_form = $("#manage-form");
initPopBtns();
init();