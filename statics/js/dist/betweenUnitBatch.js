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
            goods: l,
            mainUnit: s.unitName,
            qty: 1,
            outLocationName: s.localtionName
        };
        if (i) var c = parent.$("#fixedGrid").jqGrid("setRowData", i, d);
        c && parent.$("#" + i).data("goodsInfo", {
            id: s.PK_BU_ID,
            name: s.Name,
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
                            goods: u,
                            mainUnit: c.unitName,
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
                            mainUnit: c.unitName,
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
                    case "logistics":
                        var h = {
                            name:Name

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
                                up_bom_id : c.id,
                                up_bom_name:c.name
                            };
                        }

                        if(colName === 'down_bom_name'){
                            var h={
                                id:p,
                                down_bom_id : c.id,
                                down_bom_name:c.name
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
                    name: c.name,
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
            n(r, a, l);
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
        goods: u,
        invNumber: c.number,
        invName: c.name,
        unitName: c.unitName,
        qty: 1,
        price: c.salePrice,
        spec: c.spec
    });
    parent.$("#grid").jqGrid("setRowData", Number(a), h);
    parent.$("#" + a).data("goodsInfo", {
        id: c.PK_BOM_ID,
        invNumber: c.number,
        invName: c.name,
        unitName: c.unitName,
        qty: c.quantity,
        spec: c.spec
    });
    n(a)
}
var queryConditions = {
	skey: ""
},
	$grid = $("#grid"),
	addList = {},
	THISPAGE = {
		init: function() {
			this.initDom();
			this.loadGrid();
			this.addEvent()
		},
		initDom: function() {
			this.$_matchCon = $("#matchCon");
			this.$_matchCon.placeholder()
		},
		loadGrid: function() {
			$(window).height() - $(".grid-wrap").offset().top - 84;
			$grid.jqGrid({
				//url: "/basedata/contact.do?type=10&action=list",
				url: basedata_contact+"?type=2",
				postData: queryConditions,
				datatype: "json",
				width: 528,
				height: 354,
				altRows: !0,
				gridview: !0,
				colModel: [{
					name: "PK_BU_ID",
					label: "编号",
					width: 100,
					title: !1
				}, {
					name: "Name",
					label: "名称",
					width: 170,
					classes: "ui-ellipsis"
				}, {
					name: "BU_Cat",
					label: "类别",
					width: 106,
					title: !1
				}],
				cmTemplate: {
					sortable: !1
				},
				multiselect: !0,
				page: 1,
				sortname: "number",
				sortorder: "desc",
				pager: "#page",
				rowNum: 100,
				rowList: [100, 200, 500],
				viewrecords: !0,
				shrinkToFit: !1,
				forceFit: !1,
				jsonReader: {
					root: "data.rows",
					records: "data.records",
					total: "data.total",
					repeatitems: !1,
					id: "id"
				},
				loadError: function() {},
				onSelectRow: function(t, e) {
					if (e) {
						var i = $grid.jqGrid("getRowData", t);
						addList[t] = i
					} else addList[t] && delete addList[t]
				},
				onSelectAll: function(t, e) {
					for (var i = 0, a = t.length; a > i; i++) {
						var r = t[i];
						if (e) {
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
		reloadData: function(t) {
			addList = {};
			$grid.jqGrid("setGridParam", {
				//url: "/basedata/contact.do?type=10&action=list",
				url: basedata_contact+"?type=2",
				datatype: "json",
				postData: t
			}).trigger("reloadGrid")
		},
		addEvent: function() {
			var t = this;
			$("#search").click(function() {
				queryConditions.skey = "请输入往来单位编号或名称或联系人" === t.$_matchCon.val() ? "" : t.$_matchCon.val();
				THISPAGE.reloadData(queryConditions)
			});
			$("#refresh").click(function() {
				THISPAGE.reloadData(queryConditions)
			})
		}
	};
THISPAGE.init();