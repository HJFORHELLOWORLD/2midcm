function initGrid() {
	var t = Public.setGrid(),
		e = ["操作", "供应商类别", "供应商编号", "供应商名称", "首要联系人", "手机", "座机", "QQ/MSN", "期初往来余额"],
		i = parent.SYSTEM.rights,
		a = !(parent.SYSTEM.isAdmin || i.AMOUNT_INAMOUNT),
		r = [{
			name: "operate",
			width: 60,
			fixed: !0,
			formatter: Public.operFmatter,
			title: !1
		}, {
			name: "customerType",
			index: "customerType",
			width: 100,
			title: !1
		}, {
			name: "number",
			index: "number",
			width: 100,
			title: !1
		}, {
			name: "name",
			index: "name",
			width: 220,
			classes: "ui-ellipsis"
		}, {
			name: "contacter",
			index: "contacter",
			width: 100,
			align: "center"
		}, {
			name: "mobile",
			index: "mobile",
			width: 100,
			align: "center",
			title: !1
		}, {
			name: "telephone",
			index: "telephone",
			width: 100,
			title: !1
		}, {
			name: "linkIm",
			index: "linkIm",
			width: 100,
			title: !1
		}, {
			name: "difMoney",
			index: "difMoney",
			width: 100,
			align: "right",
			title: !1,
			formatter: "currency",
			hidden: a
		}];
	$("#grid").jqGrid({
		//url: "/basedata/contact.do?type=10&action=list&isDelete=2",
		url: basedata_contact+"?type=2",
		datatype: "json",
		autowidth: !0,
		height: t.h,
		altRows: !0,
		gridview: !0,
		onselectrow: !1,
		multiselect: !0,
		colNames: e,
		colModel: r,
		pager: "#page",
		viewrecords: !0,
		cmTemplate: {
			sortable: !1
		},
		rowNum: 100,
		rowList: [100, 200, 500],
		shrinkToFit: !0,
		jsonReader: {
			root: "data.rows",
			records: "data.records",
			total: "data.total",
			repeatitems: !1,
			id: "id"
		},
		loadComplete: function(t) {
			if (t && 200 == t.status) {
				var e = {};
				t = t.data;
				for (var i = 0; i < t.rows.length; i++) {
					var a = t.rows[i];
					e[a.id] = a
				}
				$("#grid").data("gridData", e)
			} else {
				var r = 250 === t.status ? searchFlag ? "没有满足条件的结果哦！" : "没有客户数据哦！" : t.msg;
				parent.Public.tips({
					type: 2,
					content: r
				})
			}
		},
		loadError: function() {
			parent.Public.tips({
				type: 1,
				content: "操作失败了哦，请检查您的网络链接！"
			})
		}
	})
}
function initEvent() {
	$_matchCon = $("#matchCon");
	$_matchCon.placeholder();
	$("#search").on("click", function(t) {
		t.preventDefault();
		var e = $_matchCon.val() === $_matchCon[0].defaultValue ? "" : $.trim($_matchCon.val());
		$("#grid").jqGrid("setGridParam", {
			postData: {
				skey: e
			}
		}).trigger("reloadGrid")
	});
	$("#btn-add").on("click", function(t) {
		t.preventDefault();
		Business.verifyRight("PUR_ADD") && handle.operate("add")
	});
	$("#btn-print").on("click", function(t) {
		t.preventDefault()
	});
	$("#btn-import").on("click", function(t) {
		t.preventDefault();
		Business.verifyRight("BaseData_IMPORT") && parent.$.dialog({
			width: 560,
			height: 300,
			title: "批量导入",
			content: "url:/import.jsp",
			lock: !0
		})
	});
	$("#btn-export").on("click", function() {
		if (Business.verifyRight("PUR_EXPORT")) {
			var t = $_matchCon.val() === $_matchCon[0].defaultValue ? "" : $.trim($_matchCon.val());
			//$(this).attr("href", "/basedata/supplier.do?action=exporter&isDelete=2&skey=" + t)
			$(this).attr("href", vendor_export+"?type=2&skey=" + t)
		}
	});
	$("#grid").on("click", ".operating .ui-icon-pencil", function(t) {
		t.preventDefault();
		if (Business.verifyRight("PUR_UPDATE")) {
			var e = $(this).parent().data("id");
			handle.operate("edit", e)
		}
	});
	$("#grid").on("click", ".operating .ui-icon-trash", function(t) {
		t.preventDefault();
		if (Business.verifyRight("PUR_DELETE")) {
			var e = $(this).parent().data("id");
			handle.del(e + "")
		}
	});
	$("#btn-batchDel").click(function(t) {
		t.preventDefault();
		if (Business.verifyRight("PUR_DELETE")) {
			var e = $("#grid").jqGrid("getGridParam", "selarrrow");
			e.length ? handle.del(e.join()) : parent.Public.tips({
				type: 2,
				content: "请选择需要删除的项"
			})
		}
	});
	$(window).resize(function() {
		Public.resizeGrid()
	})
}
var searchFlag = !1,
	filterClassCombo, handle = {
		operate: function(t, e) {
			if ("add" == t) var i = "新增供应商",
				a = {
					oper: t,
					callback: this.callback
				};
			else var i = "修改供应商",
				a = {
					oper: t,
					rowId: e,
					callback: this.callback
				};
			$.dialog({
				title: i,
				//content: "url:vendor-manage.jsp",
				content: "url:"+settings_vendor_manage,
				data: a,
				width: 640,
				height: 442,
				max: !1,
				min: !1,
				cache: !1,
				lock: !0
			})
		},
		del: function(t) {
			$.dialog.confirm("删除的供应商将不能恢复，请确认是否删除？", function() {
				//Public.ajaxPost("../basedata/contact.do?type=10&action=delete", {											  
				Public.ajaxPost(vendor_del+"?type=10", {
					id: t
				}, function(e) {
					if (e && 200 == e.status) {
						var i = e.data.id || [];
						parent.Public.tips(t.split(",").length === i.length ? {
							content: "成功删除" + i.length + "个供应商！"
						} : {
							type: 2,
							content: e.data.msg,
							autoClose: !1
						});
						for (var a = 0, r = i.length; r > a; a++) {
							$("#grid").jqGrid("setSelection", i[a]);
							$("#grid").jqGrid("delRowData", i[a])
						}
					} else parent.Public.tips({
						type: 1,
						content: "删除供应商失败！" + e.msg
					})
				})
			})
		},
		callback: function(t, e, i) {
			var a = $("#grid").data("gridData");
			if (!a) {
				a = {};
				$("#grid").data("gridData", a)
			}
			t.difMoney = t.amount - t.periodMoney;
			a[t.id] = t;
			if ("edit" == e) {
				$("#grid").jqGrid("setRowData", t.id, t);
				i && i.api.close()
			} else {
				$("#grid").jqGrid("addRowData", t.id, t, "first");
				i && i.resetForm(t)
			}
		}
	};
initGrid();
initEvent();