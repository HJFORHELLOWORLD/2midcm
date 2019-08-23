function init() {
	//if (void 0 !== cRowId) Public.ajaxPost("../basedata/contact.do?action=query", {
	if (void 0 !== cRowId) Public.ajaxPost(basedata_contact_query, {
		id: cRowId
	}, function(e) {
		if (200 == e.status) {
			rowData = e.data;
			initField();
			initEvent();
			//initGrid(rowData.links)
		} else parent.$.dialog({
			title: "系统提示",
			content: "获取客户数据失败，暂不能修改客户，请稍候重试",
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
	var e = "add" == oper ? ["保存", "关闭"] : ["确定", "取消"];
	api.button({
		id: "confirm",
		name: e[0],
		focus: !0,
		callback: function() {
			cancleGridEdit();
			$_form.trigger("validate");
			return !1
		}
	}, {
		id: "cancel",
		name: e[1]
	})
}
function initValidator() {
    $_form.validator({
        rules: {
            type: [/^[a-zA-Z0-9\-_]*$/, "编号只能由数字、字母、-或_等字符组成"],
            unique: function(e) {
                var t = $(e).val();
                return $.ajax({
                    //url: "/basedata/contact.do?action=checkName",
                    url: basedata_contact_checkname+"?type=1",
                    type: "get",
                    data: "name=" + t,
                    dataType: "json",
                    success: function(e) {
                        if (-1 != e.status) return !0;
                        parent.parent.Public.tips({
                            type: 2,
                            content: "存在相同的客户名称！"
                        });
                        return void 0
                    }
                })
            },
            myRemote: function(e, t, i) {
                return i.old.value === e.value || $(e).data("tip") === !1 && e.value.length > 1 ? !0 : $.ajax({
                    //url: "/basedata/contact.do?action=getNextNo&type=-10",
                    url: basedata_contact_getnextno+"?type=1",
                    type: "post",
                    data: "skey=" + e.value,
                    dataType: "json",
                    success: function(t) {
                        if (t.data && t.data.number) {
                            var i = e.value.length;
                            e.value = t.data.number;
                            var a = e.value.length;
                            if (e.createTextRange) {
                                var r = e.createTextRange();
                                r.moveEnd("character", a);
                                r.moveStart("character", i);
                                r.select()
                            } else {
                                e.setSelectionRange(i, a);
                                e.focus()
                            }
                            $(e).data("tip", !0)
                        } else $(e).data("tip", !1)
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
        display: function(e) {
            return $(e).closest(".row-item").find("label").text()
        },
        valid: function() {
            var e = $.trim($("#name").val());
            //Public.ajaxPost("/basedata/contact.do?action=checkName", {
            Public.ajaxPost(basedata_contact_checkname+"?type=1", {
                name: e,
                id: cRowId
            }, function(t) {
                -1 == t.status ? parent.$.dialog.confirm('客户名称 "' + e + '" 已经存在！是否继续？', function() {
                    postCustomerData()
                }, function() {}) : postCustomerData()
            })
        },
        ignore: ":hidden",
        theme: "yellow_bottom",
        timely: 1,
        stopOnError: !0
    })
}
function postCustomerData() {
	var e = "add" == oper ? "新增客户" : "修改客户",
		t = getCustomerData(),
		i = t.firstLink || {};
	delete t.firstLink;

	//Public.ajaxPost("../basedata/contact.do?action=" + ("add" == oper ? "add" : "update"), t, function(t) {
	Public.ajaxPost(betweenUnit_modify+"?act=" + ("add" == oper ? "add" : "update"), t, function(t) {

		if (200 == t.status) {
			parent.parent.Public.tips({
				content: e + "成功！"
			});

			//var s = "";
//			xxx = t.data;
//			for (var property in xxx) {
//			    s = s + "\n "+property +": " + xxx[property] ;
//			}
//			alert(s);


/*			if (callback && "function" == typeof callback) {
				var r = t.data.id;
				a = t;
				a.id = r;
				a.number = t.data.number;
				a.name = t.data.name;
				a.customerType = t.data.BU_Cat;
				a.contacter = i.linkName || "";
				a.mobile = i.linkMobile || "";
				a.telephone = i.linkPhone || "";
				a.linkIm = i.linkIm || "";
				a.deliveryAddress = i.linkAddress || "";
				callback(a, oper, window)
			}*/
		} else parent.parent.Public.tips({
			type: 1,
			content: e + "失败！" + a.msg
		})
	})
}
function getCustomerData() {
	var e = getEntriesData(),
		t = e.entriesData,
		i = {
			pk_bu_id:rowData.id,
			name: $.trim($("#name").val()),
            BU_Cat:$.trim($("#BU_Cat").val()),
            Industry_ID:$.trim($("#Industry_ID").val()),
            Area_ID:$.trim($("#Area_ID").val()),
            Taxrate:$.trim($("#Taxrate").val()),
            phone:$.trim($("#phone").val()),
            remark:$.trim($("#remark").val())
           // Linkmans: JSON.stringify(t),
			//remark: $("#remark").val() == $("#remark")[0].defaultValue ? "" : $("#note").val()
		};


	i.firstLink = e.firstLink;
	return i
}
function getEntriesData() {
	for (var e = {}, t = [], i = $grid.jqGrid("getDataIDs"), a = !1, r = 0, n = i.length; n > r; r++) {
		var o, s = i[r],
			l = $grid.jqGrid("getRowData", s);
		if ("" == l.name) break;
		o = {
			linkName: l.name,
			linkMobile: l.mobile,
			linkPhone: l.phone,
			linkIm: l.weChat,
			linkAddress: l.address,
			linkFirst: "是" == l.first ? 1 : 0
		};
		o.id = "edit" == oper ? -1 != $.inArray(Number(s), linksIds) ? s : 0 : 0;
		if ("是" == l.first) {
			a = !0;
			e.firstLink = o
		}
		t.push(o)
	}
	if (!a && t.length > 0) {
		t[0].linkFirst = 1;
		e.firstLink = t[0]
	}
	e.entriesData = t;
	return e
}
function getTempData(e) {
	for (var t, i = $.extend({
		contacter: "",
		mobile: "",
		telephone: "",
		linkIm: ""
	}, e), a = i.links, r = 0; r < a.length; r++) if (a[r].first) {
		t = a[r];
		break
	}
	i.customerType = categoryData[i.cCategory] && categoryData[i.cCategory].name || "";
	i.firstLink = t;
	return i
}
function initField() {
	$("#remark").placeholder();
	if ("edit" == oper) {
		//$("#id").val(rowData.id);
		$("#name").val(rowData.name);
        $("#Industry_ID").val(rowData.Industry_ID);
        $("#Area_ID").val(rowData.Area_ID);
        $("#Taxrate").val(rowData.Taxrate);
        $("#BU_Cat").val(rowData.BU_Cat);
        $("#phone").val(rowData.links.phone);
        $("#remark").val(rowData.remark);

		$("#category").data("defItem", ["id", rowData.cCategory]);
		if (rowData.beginDate) {
			var e = new Date(rowData.beginDate),
				t = e.getFullYear(),
				i = 1 * e.getMonth() + 1,
				a = e.getDate();
			$("#date").val(t + "-" + i + "-" + a)
		}
		void 0 != rowData.amount && $("#receiveFunds").val(Public.numToCurrency(rowData.amount));
		void 0 != rowData.periodMoney && $("#periodReceiveFunds").val(Public.numToCurrency(rowData.periodMoney));
		rowData.remark && $("#note").val(rowData.remark)
	} else $("#date").val(parent.parent.SYSTEM.startDate);
	if (!api.opener.parent.SYSTEM.isAdmin && !api.opener.parent.SYSTEM.rights.AMOUNT_OUTAMOUNT) {
		$("#receiveFunds").closest("li").hide();
		$("#periodReceiveFunds").closest("li").hide()
	}
}
function initEvent() {
	var e = "customertype";
	categoryCombo = Business.categoryCombo($("#category"), {
		defaultSelected: $("#category").data("defItem") || void 0,
		editable: !0,
		trigger: !0,
		width: 210,
		ajaxOptions: {
			formatData: function(t) {
				categoryData = {};
				var i = Public.getDefaultPage();
				if (200 == t.status) {
					for (var a = 0; a < t.data.items.length; a++) {
						var r = t.data.items[a];
						categoryData[r.id] = r
					}
					i.SYSTEM.categoryInfo = i.SYSTEM.categoryInfo || {};
					i.SYSTEM.categoryInfo[e] = t.data.items;
					t.data.items.unshift({
						id: 0,
						name: "（空）"
					});
					return t.data.items
				}
				return []
			}
		}
	}, e);
	var t = $("#date");
	t.blur(function() {
		"" == t.val() && t.val(parent.parent.SYSTEM.startDate)
	});
	t.datepicker({
		onClose: function() {
			var e = /^\d{4}-((0?[1-9])|(1[0-2]))-\d{1,2}/;
			e.test(t.val()) || t.val("")
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
	$(document).on("click.cancle", function(e) {
		var t = e.target || e.srcElement;
		!$(t).closest("#grid").length > 0 && cancleGridEdit()
	});
	bindEventForEnterKey();
	initValidator()
}
function addCategory() {
	Business.verifyRight("BUTYPE_ADD") && parent.$.dialog({
		title: "新增客户类别",
		//content: "url:/settings/customer-category-manage.jsp",
		content: "url:"+settings_customer_cate_manage,
		data: {
			oper: "add",
			callback: function(e, t, i) {
				categoryCombo.loadData(basedata_category+"?typeNumber=customertype", ["id", e.id]);
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
function initGrid(e) {
	e || (e = []);
	if (e.length < 3) for (var t = 3 - e.length, i = 0; t > i; i++) e.push({});
	e.push({});
	$grid.jqGrid({
		data: e,
		datatype: "local",
		width: 598,
		gridview: !0,
		onselectrow: !1,
		colNames: ["联系人", "手机", "座机", "微信", "地址", "首要联系人"],
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
			name: "weChat",
			index: "weChat",
			width: 90,
			title: !1,
			editable: !0
		}, {
			name: "address",
			index: "address",
			width: 142,
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
		loadComplete: function(e) {
			$grid.setGridHeight($grid.height() > 124 ? "124" : "auto");
			$grid.setGridWidth(598);
			if ("add" != oper) if (e && e.items) {
				linksIds = [];
				for (var t = e.items, i = 0; i < t.length; i++) t[i].id && linksIds.push(t[i].id)
			} else linksIds = []
		},
		afterSaveCell: function(e, t, i) {
			if ("first" == t) {
				i = "boolean" == typeof i ? i ? "1" : "0" : i;
				if ("1" === i) for (var a = $grid.jqGrid("getDataIDs"), r = 0; r < a.length; r++) {
					var n = a[r];
					n != e && $grid.jqGrid("setCell", n, "first", "0")
				}
			}
		}
	})
}
function phoneCheck(e) {
	var t = /^(13|18|15|14)[\d]{9}$/;
	return t.test(e) ? [!0, ""] : [!1, "请填写正确的手机号码"]
}
function telephoneCheck(e) {
	var t = /^([\d]+-){1,2}[\d]+$/;
	return t.test(e) ? [!0, ""] : [!1, "请填写正确的座机号码，格式为0754-1234567或086-0754-1234567。"]
}
function isFirstFormate(e) {
	e = "boolean" == typeof e ? e ? "1" : "0" : e;
	return "1" === e ? "是" : "&#160;"
}
function cancleGridEdit() {
	if (null !== curRow && null !== curCol) {
		$grid.jqGrid("saveCell", curRow, curCol);
		curRow = null;
		curCol = null
	}
}
function resetForm(e) {
	var t = [{}, {}, {}, {}];
	$("#name").val("");
	$("#date").val("");
	$("#receiveFunds").val("");
	$("#note").val("");
	$("#periodReceiveFunds").val("");
	$grid.jqGrid("clearGridData").jqGrid("setGridParam", {
		data: t
	}).trigger("reloadGrid");
	$("#number").val(Public.getSuggestNum(e.number)).focus().select()
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