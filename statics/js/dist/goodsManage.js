function init() {
	//if (void 0 !== cRowId) Public.ajaxPost("/basedata/inventory.do?action=query", {
	if (void 0 !== cRowId) Public.ajaxPost(basedata_goods_query, {
		id: cRowId
	}, function(e) {
		if (200 === e.status) {
			rowData = e.data;
			initField();
			initEvent();
			initGrid(rowData.propertys)
		} else parent.parent.Public.tips({
			type: 1,
			content: e.msg
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
			$form.trigger("validate");
			return !1
		}
	}, {
		id: "cancel",
		name: e[1]
	})
}
function postCustomerData() {
	if ("add" == oper) {
		cancleGridEdit();
		var e = $("#name1").val();
		//Public.ajaxPost("../basedata/inventory.do?action=checkName", {
		Public.ajaxPost( basedata_goods_checkname, {			
			name: e
		}, function(t) {
			-1 == t.status ? $.dialog.confirm('物料名称 "' + e + '" 已经存在！是否继续？', function() {
				postData()
			}) : postData()
		})
	} else postData()
}


//调试  打印对象
function dump_obj(myObject) { 
  var s = ""; 
  for (var property in myObject) { 
   s = s + "\n "+property +": " + myObject[property] ; 
  } 
  alert(s); 
} 

function postData() {
	var e = "add" == oper ? "新增物料" : "修改物料",
		t = getCustomerData();

	    console.log(typeof(t));
	    // console.log(t);
	Public.ajaxPost(goods_save+"?act=" + ("add" == oper ? "add" : "update"), t, function(t) {
		if (200 == t.status) {
			parent.parent.Public.tips({
				content: e + "成功！"
			});
			if (callback && "function" == typeof callback) {
				//var i = getTempData(t.data);
				//dump_obj(t.data);
				//callback(i, oper, window)
				callback(t.data, oper, window)
			}
		} else parent.parent.Public.tips({
			type: 1,
			content: e + "失败！" + t.msg
		})
	})
}


function getCustomerData() {
	var e = getEntriesData(),
		t = {
			id: rowData.id,
			pk_bom_id: $.trim($("#number").val()),
			bomName: $.trim($("#name1").val()),
			bomModel: $.trim($("#model").val()),
			isVirt: $.trim($("#name2").val()),
			// categoryId: categoryTree.getValue(),
            bomAttr1:$.trim($("#spec1").val()),
            bomAttr2:$.trim($("#spec2").val()),
            bomAttr3:$.trim($("#spec3").val()),
            bomAttr4:$.trim($("#spec4").val()),
            bomAttr5:$.trim($("#spec5").val()),
            bomAttr6:$.trim($("#spec6").val()),
            bomAttr7:$.trim($("#spec7").val()),
            //
			/*spec: $.trim($("#specs").val()),*/
			//locationId: storageCombo.getValue(),
			//localtionName: 0 === storageCombo.getValue() ? "" : storageCombo.getText(),
			// baseUnitId: unitCombo.getValue(),
			// purPrice: Public.currencyToNum($("#purchasePrice").val()),
			// salePrice: Public.currencyToNum($("#salePrice").val()),
			bomCat_id1: $.trim($("#purchasePrice").val()),
			bomCat_id2: $.trim($("#salePrice").val()),
			desc: $.trim($("#desc").val()),
			fk_unitClass_id: $.trim($("#unitCost").val()),
			bomAttr: $.trim($("#attr").val()),

			propertys: JSON.stringify(e),
			remark: $("#note").val() == $("#note")[0].defaultValue ? "" : $("#note").val()
		};
	if (SYSTEM.enableStorage) {
		t.barCode = $("#barCode").val();
		t.jianxing = jianxingCombo.getValue();
		t.length = $("#length").val();
		t.width = $("#width").val();
		t.height = $("#height").val();
		t.weight = $("#weight").val()
	}
	"edit" == oper && (t.deleteRow = JSON.stringify(deleteRow));
	return t
}


function getEntriesData() {
	var e = [],
		t = $grid.jqGrid("getDataIDs");
	cancleGridEdit();
	for (var i = 0, a = t.length; a > i; i++) {
		var r, n = t[i],
			o = $grid.jqGrid("getRowData", n),
			s = $("#" + n).data("storageInfo");
		if (!(s && "" != o.quantity && "" != o.unitCost && "" != o.amount || s && "" != o.quantity)) break;
		r = {
			//locationId: s.id,
			quantity: o.quantity,
			unitCost: Public.currencyToNum(o.unitCost),
			amount: Public.currencyToNum(o.amount)
		};
		r.id = "edit" == oper ? -1 != $.inArray(Number(n), propertysIds) ? n : 0 : 0;
		e.push(r)
	}
	return e
}
function getTempData(e) {
	var t, i, a, r, n = 0,
		o = 0,
		s = e.propertys;
	i = categoryTree.getText() || "";
	unitData[e.baseUnitId] && (a = unitData[e.baseUnitId].name || "");
	for (var l = 0; l < s.length; l++) {
		s[l].quantity && (n += s[l].quantity);
		s[l].amount && (o += s[l].amount)
	}
	n && o && (r = o / n);
	t = $.extend({}, e, {
		categoryName: i,
		unitName: a,
		quantity: n,
		unitCost: r,
		amount: o
	});
	return t
}
function initField() {
	$("#note").placeholder();
	if ("edit" == oper) {
		$("#number").val(rowData.number);
		$("#name").val(rowData.name);
		$("#quantity").val(rowData.quantity);
		$category.data("defItem", rowData.categoryId);
		/*$("#specs").val(rowData.spec);*/
        $("#spec1").val(rowData.spec1);
        $("#spec2").val(rowData.spec2);
        $("#spec3").val(rowData.spec3);
        $("#spec4").val(rowData.spec4);
        $("#spec5").val(rowData.spec5);
        $("#spec6").val(rowData.spec6);
        $("#spec7").val(rowData.spec7);
        $("#spec8").val(rowData.spec8);
        $("#spec9").val(rowData.spec9);
        $("#spec10").val(rowData.spec10);
		$("#storage").data("defItem", rowData.locationId);
		$("#unit").data("defItem", ["id", rowData.baseUnitId]);
		void 0 != rowData.purPrice && $("#purchasePrice").val(Public.numToCurrency(rowData.purPrice));
		void 0 != rowData.salePrice && $("#salePrice").val(Public.numToCurrency(rowData.salePrice));
		void 0 != rowData.unitCost && $("#unitCost").val(Public.numToCurrency(rowData.unitCost));
		void 0 != rowData.amount && $("#amount").val(Public.numToCurrency(rowData.amount));
		rowData.remark && $("#note").val(rowData.remark);
		$("#barCode").val(rowData.barCode);
		$("#length").val(rowData.length);
		$("#width").val(rowData.width);
		$("#height").val(rowData.height);
		$("#weight").val(rowData.weight)
	} else $("#storage").data("defItem", 0);
	//if (!api.opener.parent.SYSTEM.isAdmin) {
//		rights.AMOUNT_INAMOUNT || $("#purchasePrice").closest("li").hide();
//		rights.AMOUNT_OUTAMOUNT || $("#salePrice").closest("li").hide();
//		
//	}
	if (SYSTEM.enableStorage) {
		comboWidth = 147;
		gridWidth = 718;
		$(".manage-wrapper").parent().addClass("hasJDStorage");
		$("#barCode").closest("li").show()
	}
}


function initEvent() {
	Public.limitInput($("#number"), /^[a-zA-Z0-9\-_]*$/);
	$("#name").blur(function() {});
	var e = {
		width: 200,
		inputWidth: SYSTEM.enableStorage ? 145 : 208,
		defaultSelectValue: rowData.categoryId || "",
		showRoot: !1
	};
	categoryTree = Public.categoryTree($category, e);
	//$("#specs").blur(function() {
//		var e = $.trim(this.value);
//		"" == e || "edit" == oper && e == rowData.spec || Public.ajaxPost("../basedata/inventory.do?action=checkSpec", {
//			spec: e
//		}, function(t) {
//			-1 == t.status && parent.parent.Public.tips({
//				type: 2,
//				content: '规格型号 "' + e + '" 已经存在！'
//			})
//		})
//	});
	storageCombo = $("#storage").combo({
		data: function() {
			return SYSTEM.storageInfo
		},
		value: "id",
		text: "name",
		width: comboWidth,
		defaultSelected: 0,
		cache: !1,
		editable: !1,
		emptyOptions: !0,
		extraListHtml: '<a href="#" class="quick-add-link" onclick="addStorage();return false;"><i class="ui-icon-add"></i>新增</a>'
	}).getCombo();
	// storageCombo.selectByValue($("#storage").data("defItem"));
	unitCombo = $("#unit").combo({
		//data: "../basedata/unit.do?action=list",
		data: basedata_unit+"?action=list",
		value: "id",
		text: "name",
		width: comboWidth,
		defaultSelected: $("#unit").data("defItem") || void 0,
		editable: !0,
		ajaxOptions: {
			formatData: function(e) {
				unitData = {};
				if (200 == e.status) {
					for (var t, i = e.data.items, a = 0; a < i.length; a++) {
						t = i[a];
						unitData[t.id] = t
					}
					i.unshift({
						id: 0,
						name: "(空)"
					});
					return i
				}
				return []
			}
		},
		extraListHtml: '<a href="#" class="quick-add-link" onclick="addUnit();return false;"><i class="ui-icon-add"></i>新增</a>'
	}).getCombo();
	$("#purchasePrice, #salePrice").keypress(Public.numerical).focus(function() {
		this.value = Public.currencyToNum(this.value);
		$(this).select()
	}).blur(function() {
		this.value = Public.numToCurrency(this.value, pricePlaces).replace("-", "")
	});
	gridStoCombo = Business.storageCombo($(".storageAuto"), {
		data: function() {
			return SYSTEM.storageInfo
		}
	});
	$(".grid-wrap").on("click", ".ui-icon-triangle-1-s", function() {
		setTimeout(function() {
			$(".storageAuto").trigger("click")
		}, 10)
	});
	$(document).bind("click.cancel", function(e) {
		if (!$(e.target).closest(".ui-jqgrid-bdiv").length > 0 && null !== curRow && null !== curCol) {
			$("#grid").jqGrid("saveCell", curRow, curCol);
			curRow = null;
			curCol = null
		}
	});
	initValidator();
	bindEventForEnterKey();
	$(".grid-wrap").on("click", ".ui-icon-plus", function() {
		var e = $(this).parent().data("id"),
			t = ($("#grid tbody tr").length, {
				id: "num_" + THISPAGE.newId
			}),
			i = $("#grid").jqGrid("addRowData", "num_" + THISPAGE.newId, t, "before", e);
		if (i) {
			$(this).parents("td").removeAttr("class");
			$(this).parents("tr").removeClass("selected-row ui-state-hover");
			$("#grid").jqGrid("resetSelection");
			THISPAGE.newId++
		}
	});
	$(".grid-wrap").on("click", ".ui-icon-trash", function() {
		if (2 === $("#grid tbody tr").length) {
			parent.parent.Public.tips({
				type: 2,
				content: "至少保留一条分录！"
			});
			return !1
		}
		var e = $(this).parent().data("id"),
			t = $("#grid").jqGrid("delRowData", e);
		if (t) {
			deleteRow.push(e);
			setGridFooter()
		}
	});
	SYSTEM.enableStorage && (jianxingCombo = $("#jianxing").combo({
		data: [{
			id: "0",
			name: "免费"
		}, {
			id: "1",
			name: "超大件"
		}, {
			id: "2",
			name: "超大件半件"
		}, {
			id: "3",
			name: "大件"
		}, {
			id: "4",
			name: "大件半件"
		}, {
			id: "5",
			name: "中件"
		}, {
			id: "6",
			name: "中件半件"
		}, {
			id: "7",
			name: "小件"
		}, {
			id: "8",
			name: "超小件"
		}],
		value: "id",
		text: "name",
		width: comboWidth,
		defaultSelected: rowData.jianxing || void 0,
		editable: !1
	}).getCombo())
}
function addStorage() {
	parent.$.dialog({
		title: "新增仓库",
		content: "url:/settings/storage-manage.jsp",
		data: {
			oper: "add",
			callback: function(e, t, i) {
				Public.ajaxPost("../basedata/invlocation.do?action=list", {}, function(t) {
					if (t && 200 == t.status) {
						var i = t.data.items;
						parent.parent.SYSTEM.storageInfo = t.data.items
					} else {
						var i = [];
						parent.parent.Public.tips({
							type: 1,
							content: "获取仓库信息失败！" + t.msg
						})
					}
					storageCombo.loadData(i, "-1", !1);
					storageCombo.selectByValue(e.id)
				});
				i && i.api.close()
			}
		},
		width: 400,
		height: 160,
		max: !1,
		min: !1,
		cache: !1
	})
}
function addUnit() {
	parent.$.dialog({
		title: "新增计量单位",
		//content: "url:/settings/unit-manage.jsp",
		content: "url:"+settings_unit_manage,
		data: {
			oper: "add",
			callback: function(e, t, i) {
				//unitCombo.loadData("../basedata/unit.do?action=list", ["id", e.id]);
				unitCombo.loadData(basedata_unit+"?action=list", ["id", e.id]);
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
	function t() {
		var e = $(".storageAuto")[0];
		return e
	}
	function i(e, t, i) {
		if ("get" === t) {
			if ("" !== $(".storageAuto").getCombo().getValue()) return $(e).val();
			var a = $(e).parents("tr");
			a.removeData("storageInfo");
			return ""
		}
		"set" === t && $("input", e).val(i)
	}
	function a() {
		$("#initCombo").append($(".storageAuto").val(""))
	}
	e || (e = []);
	if (e.length < 4) for (var r = 4 - e.length, n = 0; r > n; n++) e.push({
		id: "num_" + (4 - n)
	});
	else THISPAGE.newId = e.length + 1;
	var o = api.opener.parent.SYSTEM.rights,
		s = !(api.opener.parent.SYSTEM.isAdmin || o.AMOUNT_COSTAMOUNT);
	$grid.jqGrid({
		data: e,
		datatype: "clientSide",
		width: gridWidth,
		height: 124,
		rownumbers: !0,
		gridview: !0,
		onselectrow: !1,
		colNames: ["", "仓库", "期初数量", "单位成本", "期初总价"],
		colModel: [{
			name: "operating",
			label: "操作",
			width: 40,
			fixed: !0,
			formatter: Public.billsOper,
			align: "center"
		}, {
			name: "locationName",
			index: "locationName",
			width: 120,
			title: !1,
			editable: !0,
			edittype: "custom",
			edittype: "custom",
			editoptions: {
				custom_element: t,
				custom_value: i,
				handle: a,
				trigger: "ui-icon-triangle-1-s"
			}
		}, {
			name: "quantity",
			index: "quantity",
			width: 90,
			title: !1,
			formatter: "number",
			formatoptions: {
				decimalPlaces: qtyPlaces
			},
			editable: !0,
			align: "right"
		}, {
			name: "unitCost",
			index: "unitCost",
			width: 90,
			title: !1,
			formatter: "currency",
			formatoptions: {
				showZero: !0,
				decimalPlaces: pricePlaces
			},
			editable: !0,
			align: "right",
			hidden: s
		}, {
			name: "amount",
			index: "amount",
			width: 90,
			title: !1,
			formatter: "currency",
			formatoptions: {
				showZero: !0,
				decimalPlaces: amountPlaces
			},
			align: "right",
			hidden: s
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
			repeatitems: !0,
			id: "id"
		},
		footerrow: !0,
		loadComplete: function() {
			$grid.setGridHeight($grid.height() > 125 ? "125" : "auto");
			$grid.setGridWidth(gridWidth)
		},
		gridComplete: function() {
			if ("add" != oper) {
				$grid.footerData("set", {
					locationName: "合计:",
					quantity: rowData.quantity,
					unitCost: rowData.unitCost,
					amount: rowData.amount
				});
				propertysIds = [];
				for (var t, i = 0; i < e.length; i++) {
					t = e[i];
					if ($.isNumeric(t.id)) {
						propertysIds.push(t.id);
						$("#" + t.id).data("storageInfo", {
							id: t.locationId,
							name: t.locationName
						})
					}
				}
			}
		},
		afterEditCell: function(e, t, i, a) {
			"locationName" === t && $("#" + a + "_locationName", "#grid").val(i)
		},
		afterSaveCell: function(e, t, i, a, r) {
			if ("quantity" == t || "unitCost" == t) {
				var n = floatCheck(i, t);
				if (n[0]) {
					var o, s = $grid.jqGrid("getRowData", e),
						l = parseFloat(s.quantity),
						d = parseFloat(Public.currencyToNum(s.unitCost));
					if (!isNaN(l) && !isNaN(d)) {
						o = l * d;
						$grid.jqGrid("setCell", e, "amount", o)
					}
				} else {
					parent.parent.Public.tips({
						type: 1,
						content: n[1]
					});
					$grid.jqGrid("restoreCell", a, r)
				}
				setGridFooter()
			}
		}
	})
}
function floatCheck(e, t) {
	var i = /^[0-9\.]+$/,
		e = $.trim(e);
	"quantity" == t ? t = "期初数量" : "unitCost" == t && (t = "单位成本");
	return i.test(e) ? [!0, ""] : "" == e ? [!1, t + "不能为空"] : [!1, "请填写正确的" + t]
}
function setGridFooter() {
	for (var e, t, i = $grid.jqGrid("getRowData"), a = 0, r = 0, n = 0; n < i.length; n++) {
		e = i[n];
		e.quantity && (a += parseFloat(e.quantity));
		e.amount && (r += parseFloat(e.amount))
	}
	a && r && (t = r / a);
	$grid.footerData("set", {
		locationName: "合计",
		quantity: a || "&#160",
		unitCost: t || "&#160",
		amount: r || "&#160"
	})
}
function initValidator() {
	$form.validator({
		rules: {
			code: [/^[a-zA-Z0-9\-_]*$/, "物料编号只能由数字、字母、-或_等字符组成"],
			number: function(e) {
				var t = $(e).val();
				try {
					t = Number(t);
					if (t) {
						$(e).val(t);
						return !0
					}
					return "字段不合法！请输入数值"
				} catch (i) {
					return "字段不合法！请输入数值"
				}
			},
			checkCode: function(e) {
				var t = $(e).val();
				$.ajax({
					type: "POST",
					url: "../basedata/inventory.do?action=checkBarCode",
					
					data: {
						barCode: t
					},
					dataType: "json",
					async: !1,
					success: function(e) {
						if (!e) return !1;
						t = -1 == e.status ? rowData && rowData.barCode === t ? !0 : "商品条码已经存在！" : !0;
						return void 0
					},
					error: function() {
						t = "远程数据校验失败！"
					}
				});
				return t
			},
			myRemote: function(e, t, i) {
				return i.old.value === e.value || $(e).data("tip") === !1 && e.value.length > 1 ? !0 : $.ajax({
					//url: "/basedata/inventory.do?action=getNextNo",
					url: basedata_goods_getnextno,
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
			required: "请填写{0}",
			checkCode: "{0}",
			name: "{0}"
		},
		fields: {
			number: {
				rule: "add" === oper ? "required; code; myRemote" : "required; code",
				timely: 3
			},
			name: "required",
			barCode: "code;checkCode;",
			length: "number;",
			width: "number;",
			height: "number;",
			weight: "number;"
		},
		display: function(e) {
			return $(e).closest(".row-item").find("label").text()
		},
		valid: function() {
			postCustomerData()
		},
		ignore: ":hidden",
		theme: "yellow_bottom",
		timely: 1,
		stopOnError: !0
	})
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
/*	$("#specs").val("");*/
    $("#spec1").val("");
    $("#spec2").val("");
    $("#spec3").val("");
    $("#spec4").val("");
    $("#spec5").val("");
    $("#spec6").val("");
    $("#spec7").val("");
    $("#spec8").val("");
    $("#spec9").val("");
    $("#spec10").val("");
	$("#purchasePrice").val("");
	$("#salePrice").val("");
	$("#note").val("");
	$grid.jqGrid("clearGridData", !0).jqGrid("setGridParam", {
		data: t
	}).trigger("reloadGrid");
	gridStoCombo.collapse();
	$("#number").val(Public.getSuggestNum(e.number)).focus().select();
	$("#barCode").val("");
	jianxingCombo && jianxingCombo.selectByIndex(0);
	$("#length").val("");
	$("#width").val("");
	$("#height").val("");
	$("#weight").val("")
}
var curRow, curCol, curArrears, api = frameElement.api,
	oper = api.data.oper,
	cRowId = api.data.rowId,
	rowData = {},
	propertysIds = [],
	deleteRow = [],
	callback = api.data.callback,
	categoryTree, storageCombo, unitCombo, gridStoCombo, jianxingCombo, comboWidth = 210,
	gridWidth = 598,
	$grid = $("#grid"),
	$form = $("#manage-form"),
	$category = $("#category"),
	categoryData = {},
	unitData = {},
	SYSTEM = parent.parent.SYSTEM,
	qtyPlaces = Number(SYSTEM.qtyPlaces) || 4,
	pricePlaces = Number(SYSTEM.pricePlaces) || 4,
	amountPlaces = Number(SYSTEM.amountPlaces) || 2,
	format = {
		quantity: function(e) {
			var t = parseFloat(e);
			return isNaN(t) ? "&#160;" : e
		},
		money: function(e) {
			var e = Public.numToCurrency(e);
			return e || "&#160;"
		}
	},
	THISPAGE = {
		newId: 5
	},
	rights = api.opener.parent.SYSTEM.rights;
initPopBtns();
init();