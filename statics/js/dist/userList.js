function initEvent() {
	$("#btn-add").click(function(t) {
		t.preventDefault();
		Business.verifyRight("USER_ADD") && handle.operate("add")
	});
	$("#grid").on("click", ".operating .ui-icon-pencil", function(t) {
		t.preventDefault();
		if (Business.verifyRight("USER_UPDATE")) {
			var e = $(this).parent().data("id");
			handle.operate("edit", e)
		}
	});
	$("#grid").on("click", ".operating .ui-icon-trash", function(t) {
		t.preventDefault();
		if (Business.verifyRight("USER_DELETE")) {
			var e = $(this).parent().data("id");
			handle.del(e)
		}
	});
	$("#btn-refresh").click(function(t) {
		t.preventDefault();
		$("#grid").trigger("reloadGrid")
	});
	$(window).resize(function() {
		Public.resizeGrid()
	})
}
function initGrid() {
	var t = ["操作","编号", "姓名","部门","状态"],
		e = [{
			name: "operate",
			width: 60,
			fixed: !0,
			align: "center",
			formatter: Public.operFmatter
		},{
            name: "PK_User_ID",
            index: "PK_User_ID",
            width: 200
        },{
			name: "Username",
			index: "Username",
			width: 200
		},{
		  name:"deptName",
		  index:"deptName",
		  width: 200
		},{
            name: "Status",
            index: "Status",
            width: 200
        }];
	$("#grid").jqGrid({
		//url: "../basedata/unit.do?action=list&isDelete=2",
		url: basedata_user,
		datatype: "json",
		height: Public.setGrid().h,
		altRows: !0,
		gridview: !0,
		colNames: t,
		colModel: e,
		autowidth: !0,
		viewrecords: !0,
		cmTemplate: {
			sortable: !1,
			title: !1
		},
		page: 1,
		pager: "#page",
		rowNum: 2e3,
		shrinkToFit: !1,
		scroll: 1,
		jsonReader: {
			root: "data.items",
			records: "data.totalsize",
			repeatitems: !1,
			id: "id"
		},
		loadComplete: function(t) {
			if (t && 200 == t.status) {
				var e = {};
				t = t.data;
				for (var i = 0; i < t.items.length; i++) {
					var a = t.items[i];
					e[a.id] = a
				}
				$("#grid").data("gridData", e)
			} else {
				var r = 250 == t.status ? "没有人员数据！" : "获取人员数据失败！" + t.msg;
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
var handle = {
	operate: function(t, e) {
		if ("add" == t) var i = "新增人员",
			a = {
				oper: t,
				callback: this.callback
			};
		else var i = "修改",
			a = {
				oper: t,
				rowData: $("#grid").data("gridData")[e],
				callback: this.callback
			};
		$.dialog({
			title: i,
			content: "url:"+settings_user_manage,
			data: a,
			width: 400,
			height: 100,
			max: !1,
			min: !1,
			cache: !1,
			lock: !0
		})
	},
	del: function(t) {
		$.dialog.confirm("删除的人员将不能恢复，请确认是否删除？", function() {
			Public.ajaxPost(user_del, {
				id: t
			}, function(e) {
				if (e && 200 == e.status) {
					parent.Public.tips({
						content: "删除人员成功！"
					});
					$("#grid").jqGrid("delRowData", t)
				} else parent.Public.tips({
					type: 1,
					content: "删除人员失败！" + e.msg
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
		a[t.id] = t;
		if ("edit" == e) {
			$("#grid").jqGrid("setRowData", t.id, t);
			i && i.api.close()
		} else {
			$("#grid").jqGrid("addRowData", t.id, t, "last");
			i && i.resetForm(t)
		}
	}
};
initEvent();
initGrid();