function callbackSp() {
	var e = frameElement.api,
		t = parent.THISPAGE || e.data.page,
		i = t.curID,
		a = (t.newId, e.data.callback, $("#grid").jqGrid("getGridParam", "selarrrow")),
		r = a.length,
		n = oldRow = parent.curRow,
		o = parent.curCol;
	if (r > 0) {
		parent.$("#fixedGrid").jqGrid("restoreCell", n, o);
		var s = $("#grid").jqGrid("getRowData", a[0]);
		if ("" === s.BOMModel) var l = s.BOMName + " ";
		else var l = s.BOMName + "（" + s.BOMModel + "）";
		var d = {
			id: i,
			goods: s.BOMName,
			bomModel:s.BOMModel,
			mainUnit: s.unitName,
			qty: 1,
			outLocationName: s.localtionName
		};
		if (i) var c = parent.$("#fixedGrid").jqGrid("setRowData", i, d);
		c && parent.$("#" + i).data("goodsInfo", {
			id: s.PK_BOM_ID,
			number: s.number,
			name: s.bomName,
			spec: s.spec,
			unitId: s.unitId,
			unitName: s.unitName,
			unitCost:s.unitCost,
			salePrice:s.salePrice
		}).data("storageInfo", {
			id: s.locationId,
			name: s.localtionName
		})
	}
}
function callback(e) {
	var t = frameElement.api,
		i = parent.THISPAGE || t.data.page,
		a = i.curID,
		r = i.newId,
		n = t.data.callback,
		o = $("#grid").jqGrid("getGridParam", "selarrrow"),
		s = o.length,
		l = oldRow = parent.curRow,
		d = parent.curCol,
		colName = t.data.name;
	if (!urlParam.byOnlineStore) {
		if (s > 0) {
			parent.$("#grid").jqGrid("restoreCell", l, d);
			for (rowid in addList) {
				var c = addList[rowid];
				if ("" === c.BOMModel) var u = c.BOMName;
				else var u = c.BOMName + " " + c.BOMModel;
				if (a) var p = a;
				else var p = r;
				switch (e) {
				case "purchase":
					var h = {
						id: p,
						goods: c.BOMName,
						mainUnit: c.unitName,
						bomName:c.BOMName,
						bomModel : c.BOMModel,
						qty: 1,
						price: c.purPrice,
						discountRate: 0,
						deduction: 0,
						amount: c.purPrice,
						unitCost:c.unitCost,
						salePrice:c.salePrice,
						locationName: c.localtionName
					};
					if (taxRequiredCheck) {
						var f = Number(c.purPrice),
							g = parent.taxRequiredInput,
							m = f * g / 100,
							v = f + Number(m);
						h.taxRate = g;
						h.tax = m;
						h.taxAmount = v
					}
					break;
				case "sales":
					var h = {
						id: p,
						goods: u,
						mainUnit: c.BOMName,
                        bomModel : c.BOMModel,
                        bomName:c.BOMName,
						qty: 1,
						price: c.salePrice,
						discountRate: 0,
						deduction: 0,
						amount: c.salePrice,
                        unitCost:c.unitCost,
                        salePrice:c.salePrice,
						locationName: c.localtionName
					};
					if (taxRequiredCheck) {
						var f = Number(c.salePrice),
							g = taxRequiredInput,
							m = f * g / 100,
							v = f + Number(m);
						h.taxRate = g;
						h.tax = m;
						h.taxAmount = v
					}
					break;
				case "otherWarehouse":
					var h = {
						id: p,
						goods: u,
						mainUnit: c.unitName,
						qty: 1,
						price: c.purPrice,
						amount: c.purPrice,
						locationName: c.localtionName
					};
					break;
				case "transfers":
					var h = {
						id: p,
						goods: u,
						mainUnit: c.unitName,
						qty: 1,
						price: c.salePrice,
						amount: c.salePrice,
						outLocationName: c.localtionName
					};
					break;
				case "assemble":
				case "otherOutbound":
					var h = {
						id: p,
						goods: u,
						mainUnit: c.unitName,
						qty: 1,
						outLocationName: c.localtionName
					};
					break;
					case "bomDesign":
						if(colName === 'up_bom_name'){
                            var h={
                                id:p,
                                up_bom_id : c.PK_BOM_ID,
                                up_bom_name:c.BOMName
                            };
						}

                        if(colName === 'down_bom_name'){
                            var h={
                                id:p,
                                down_bom_id : c.PK_BOM_ID,
                                down_bom_name:c.BOMName
                            };
                        }
						break;
				default:
					var h = {
						id: p,
						goods: u,
						mainUnit: c.unitName,
						qty: 1,
						price: c.salePrice,
						amount: c.salePrice,
						locationName: c.localtionName
					}
				}
				if (a) var b = parent.$("#grid").jqGrid("setRowData", Number(a), h);
				else {
					var b = parent.$("#grid").jqGrid("addRowData", Number(r), h, "last");
					r++
				}
				b && parent.$("#" + p).data("goodsInfo", {
					id: c.PK_BOM_ID,
					number: c.number,
					name: c.BOMName,
					spec: c.spec,
					unitId: c.unitId,
					unitName: c.unitName
				}).data("storageInfo", {
					id: c.locationId,
					name: c.localtionName
				});
				l++;
				var y = parent.$("#" + a).next();

				a = y.length > 0 ? parent.$("#" + a).next().attr("id") : ""
			}
			"bomDesign" !== e && n(r, a, l);//bom设计的时候，不自动选中下一行
			$("#grid").jqGrid("resetSelection");
			addList = {}
		}
		return !1
	}
	parent.$("#grid").jqGrid("restoreCell", l, d);
	var c = $("#grid").jqGrid("getRowData", $("#grid").jqGrid("getGridParam", "selrow")),
		u = c.name;
	if (l > 8 && l > oldRow) var p = l;
	else var p = a;
	var h = parent.$("#grid").jqGrid("getRowData", Number(a));
	h = $.extend({}, h, {
		goods: c.bomName,
		invNumber: c.number,
		bomName: c.bomName,
		unitName: c.unitName,
		qty: 1,
		price: c.salePrice,
		spec: c.spec
	});
	parent.$("#grid").jqGrid("setRowData", Number(a), h);
	parent.$("#" + a).data("goodsInfo", {
		id: c.PK_BOM_ID,
		invNumber: c.number,
		name: c.bomName,
		unitName: c.unitName,
		qty: c.quantity,
		spec: c.spec
	});
	n(a)
}
var queryConditions = {
	skey: (frameElement.api.data ? frameElement.api.data.skey : "") || ""
},
	$grid = $("#grid"),
	addList = {},
	urlParam = Public.urlParam(),
	zTree, SYSTEM = parent.parent.SYSTEM,
	taxRequiredCheck = SYSTEM.taxRequiredCheck;
taxRequiredInput = SYSTEM.taxRequiredInput;
var THISPAGE = {
	init: function() {
		this.initDom();
		this.loadGrid();
		this.initZtree();
		this.addEvent()
	},
	initDom: function() {
		this.$_matchCon = $("#matchCon").val(queryConditions.skey || "请输入商品编号或名称或型号");
		this.$_matchCon.placeholder()
	},
	initZtree: function() {
		zTree = Public.zTree.init($(".grid-wrap"), {
			defaultClass: "ztreeDefault",
			showRoot: !0
		}, {
			callback: {
				beforeClick: function(e, t) {
					queryConditions.assistId = t.id;
					$("#search").trigger("click")
				}
			}
		})
	},
	loadGrid: function() {
		function e(e, t, i) {
			//var a = '<div class="operating" data-id="' + i.id + '"><a class="ui-icon ui-icon-search" title="查询"></a><span class="ui-icon ui-icon-copy" title="商品图片"></span></div>';
			//var a = '<div class="operating" data-id="' + i.id + '"><a class="ui-icon ui-icon-search" title="查询"></a></div>';
			a = '';
			return a
		}
		$(window).height() - $(".grid-wrap").offset().top - 84;
		$("#grid").jqGrid({
			url: basedata_goods,			  
			//url: "/basedata/inventory.do?action=list",
			postData: queryConditions,
			datatype: "json",
			width: 578,
			height: 354,
			altRows: !0,
			gridview: !0,
			colModel: [{
				name: "id",
				label: "ID",
				width: 0,
				hidden: !0
			},/* {
				name: "operating",
				label: "操作",
				width: 60,
				fixed: !0,
				formatter: e,
				align: "center",
				hidden:1
			},*/ {
				name: "PK_BOM_ID",
				label: "物品编号",
				width: 100,
				title: !1
			}, {
				name: "BOMName",
				label: "物品名称",
				width: 200,
				classes: "ui-ellipsis"
			}, {
				name: "BOMModel",
				label: "型号",
				width: 106,
				title: !1
			}, {
				name: "unitName",
				label: "单位",
				width: 60,
				title: !1
			}, {
				name: "unitId",
				label: "单位ID",
				width: 0,
				hidden: !0
			},/* {
				name: "salePrice",
				label: "销售单价",
				width: 0,
				hidden: !0
			},
				{
				name: "unitCost",
                label: "成本价",
                width: 0,
                hidden: SYSTEM.unitcostHide
             },*/
				{
				name: "purPrice",
				label: "购货单价",
				width: 0,
				hidden: !0
			}, {
				name: "locationId",
				label: "仓库ID",
				width: 0,
				hidden: !0
			}, {
				name: "localtionName",
				label: "仓库名称",
				width: 0,
				hidden: !0
			}],
			cmTemplate: {
				sortable: !1
			},
			multiselect: urlParam.byOnlineStore ? !1 : !0,
			page: 1,
			sortname: "number",
			sortorder: "desc",
			pager: "#page",
			page: 1,
			rowNum: 100,
			rowList: [100, 200, 500],
			viewrecords: !0,
			shrinkToFit: !0,
			forceFit: !1,
			jsonReader: {
				root: "data.rows",
				records: "data.records",
				total: "data.total",
				repeatitems: !1,
				id: "id"
			},
			loadError: function() {},
			ondblClickRow: function() {
				if (urlParam.byOnlineStore) {
					callback();
					frameElement.api.close()
				}
			},
			onSelectRow: function(e, t) {
				if (t) {
					var i = $grid.jqGrid("getRowData", e);
					addList[e] = i
				} else addList[e] && delete addList[e]
			},
			onSelectAll: function(e, t) {
				for (var i = 0, a = e.length; a > i; i++) {
					var r = e[i];
					if (t) {
						var n = $grid.jqGrid("getRowData", r);
						addList[r] = n
					} else addList[r] && delete addList[r]
				}
			},
			gridComplete: function() {
				for (item in addList) $grid.jqGrid("setSelection", item, !1)
			}
		})
	},
	reloadData: function(e) {
		addList = {};
		$("#grid").jqGrid("setGridParam", {
			url: basedata_goods,			   
			//url: "/basedata/inventory.do?action=list",
			datatype: "json",
			postData: e
		}).trigger("reloadGrid")
	},
	addEvent: function() {
		var e = this;
		$(".grid-wrap").on("click", ".ui-icon-search", function(e) {
			e.preventDefault();
			var t = $(this).parent().data("id");
			Business.forSearch(t, "")
		});
		$(".grid-wrap").on("click", ".ui-icon-copy", function(e) {
			e.preventDefault();
			var t = $(this).parent().data("id"),
				i = "商品图片";
			parent.$.dialog({
				content: "url:../settings/fileUpload.jsp",
				data: {
					title: i,
					id: t,
					callback: function() {}
				},
				title: i,
				width: 775,
				height: 470,
				max: !1,
				min: !1,
				cache: !1,
				lock: !0
			})
		});
		$("#search").click(function() {
			queryConditions.catId = e.catId;
			queryConditions.skey = "请输入商品编号或名称或型号" === e.$_matchCon.val() ? "" : e.$_matchCon.val();
			e.reloadData(queryConditions)
		});
		$("#refresh").click(function() {
			e.reloadData(queryConditions)
		})
	}
};
THISPAGE.init();