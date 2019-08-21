//define(skin_url+"/js/dist/goodsFlowDetail", ["jquery", "print"], function(e) {
define(skin_url+"/js/dist/goodsFlowDetail", '', function(e) {																 
	function t() {
		Business.filterGoods();
		Business.filterStorage();
		Business.moreFilterEvent();
		d("#conditions-trigger").trigger("click");
		if (u.beginDate && u.endDate) {
			d("#selected-period").text(u.beginDate + "至" + u.endDate);
			d("div.grid-subtitle").text("日期: " + u.beginDate + "至" + u.endDate)
		}
		d("#filter-fromDate").val(u.beginDate);
		d("#filter-toDate").val(u.endDate);
		d("#filter-goods input").val(u.goodsNo);
		d("#filter-storage input").val(u.storageNo);
		Public.dateCheck();
		d("#filter-fromDate, #filter-toDate").datepicker();
		d("#filter-submit").on("click", function(e) {
			e.preventDefault();
			var t = d("#filter-fromDate").val(),
				i = d("#filter-toDate").val();
			if (t && i && new Date(t).getTime() > new Date(i).getTime()) parent.Public.tips({
				type: 1,
				content: "开始日期不能大于结束日期"
			});
			else {
				u = {
					beginDate: t,
					endDate: i,
					goodsNo: d("#filter-goods input").val() || "",
					storageNo: d("#filter-storage input").val() || ""
				};
				d("#selected-period").text(t + "至" + i);
				d("div.grid-subtitle").text("日期: " + t + " 至 " + i);
				l()
			}
		});
		d("#filter-reset").on("click", function(e) {
			e.preventDefault();
			d("#filter-fromDate").val("");
			d("#filter-toDate").val("");
			d("#filter-goods input").val("");
			d("#filter-storage input").val("")
		})
	}
	function i() {
		var e = u.storage ? u.storage.split(",") : "",
			t = u.goods ? u.goods.split(",") : "",
			i = "";
		e && t ? i = "「您已选择了<b>" + e.length + "</b>个仓库，<b>" + t.length + "</b>个商品进行查询」" : e ? i = "「您已选择了<b>" + customer.length + "</b>个仓库进行查询」" : t && (i = "「您已选择了<b>" + t.length + "</b>个商品进行查询」");
		d("#cur-search-tip").html(i)
	}
	function a() {
		d("#refresh").on("click", function(e) {
			e.preventDefault();
			l()
		});
		d("#btn-print").click(function(e) {
			e.preventDefault();
			Business.verifyRight("DeliverDetailReport_PRINT") && d("div.ui-print").printTable()
		});
		d("#btn-export").click(function(e) {
			e.preventDefault();
			if (Business.verifyRight("DeliverDetailReport_EXPORT")) {
				var t = {};
				for (var i in u) u[i] && (t[i] = u[i]);
				Business.getFile("/report/deliverDetail.do?action=exporter", t)
			}
		})
	}
	function r() {
		var e = !1;
		c.isAdmin !== !1 || c.rights.AMOUNT_COSTAMOUNT || (e = !0);
		var t = [{
			name: "invNo",
			label: "商品编号",
			frozen: !0,
			width: 80
		}, {
			name: "invName",
			label: "商品名称",
			frozen: !0,
			width: 200,
			classes: "ui-ellipsis",
			title: !0
		}, {
			name: "spec",
			label: "规格型号",
			width: 60,
			align: "center"
		}, {
			name: "unit",
			label: "单位",
			width: 50,
			fixed: !0,
			align: "center"
		}, {
			name: "date",
			label: "日期",
			width: 80,
			fixed: !0,
			align: "center"
		}, {
			name: "billNo",
			label: "订单编号",
			width: 120,
			fixed: !0,
			align: "center"
		}, {
			name: "billId",
			label: "销售ID",
			width: 0,
			hidden: !0
		}, {
			name: "billType",
			label: "销售类型",
			width: 0,
			hidden: !0
		}, {
			name: "transType",
			label: "业务类别",
			width: 60,
			fixed: !0,
			align: "center"
		}, {
			name: "buName",
			label: "往来单位",
			width: 100,
			classes: "ui-ellipsis",
			title: !0
		}, {
			name: "location",
			label: "仓库",
			width: 60,
			classes: "ui-ellipsis",
			title: !0
		}, {
			name: "inqty",
			label: "数量",
			width: 80,
			fixed: !0,
			align: "right"
		}, {
			name: "inunitCost",
			label: "单位成本",
			width: 80,
			fixed: !0,
			hidden: e,
			align: "right"
		}, {
			name: "incost",
			label: "成本",
			width: 80,
			fixed: !0,
			hidden: e,
			align: "right"
		}, {
			name: "outqty",
			label: "数量",
			width: 80,
			fixed: !0,
			align: "right"
		}, {
			name: "outunitCost",
			label: "单位成本",
			width: 80,
			fixed: !0,
			hidden: e,
			align: "right"
		}, {
			name: "outcost",
			label: "成本",
			width: 80,
			fixed: !0,
			hidden: e,
			align: "right"
		}, {
			name: "totalqty",
			label: "数量",
			width: 80,
			fixed: !0,
			align: "right"
		}, {
			name: "totalunitCost",
			label: "单位成本",
			width: 80,
			fixed: !0,
			hidden: e,
			align: "right"
		}, {
			name: "totalcost",
			label: "成本",
			width: 80,
			fixed: !0,
			hidden: e,
			align: "right"
		}];
		d("#grid").jqGrid({
			url: "/report/deliverDetail.do?action=detail",
			postData: u,
			datatype: "json",
			autowidth: !0,
			gridview: !0,
			colModel: t,
			cmTemplate: {
				sortable: !1,
				title: !1
			},
			page: 1,
			sortname: "date",
			sortorder: "desc",
			rowNum: 3e3,
			loadonce: !0,
			viewrecords: !0,
			shrinkToFit: !1,
			footerrow: !0,
			userDataOnFooter: !0,
			cellLayout: 0,
			jsonReader: {
				root: "data.rows",
				records: "data.records",
				total: "data.total",
				userdata: "data.userdata",
				repeatitems: !1,
				id: "0"
			},
			ondblClickRow: function(e) {
				var t = d("#grid").getRowData(e),
					i = t.billId,
					a = t.billType;
				switch (a) {
				case "PUR":
					if (!Business.verifyRight("PU_QUERY")) return;
					parent.tab.addTabItem({
						tabid: "purchase-purchase",
						text: "购货单",
						url: "/purchase/purchase.jsp?id=" + i
					});
					break;
				case "SALE":
					if (!Business.verifyRight("SA_QUERY")) return;
					parent.tab.addTabItem({
						tabid: "sales-sales",
						text: "销售单",
						url: "/sales/sales.jsp?id=" + i
					});
					break;
				case "TRANSFER":
					if (!Business.verifyRight("TF_QUERY")) return;
					parent.tab.addTabItem({
						tabid: "storage-transfers",
						text: "调拨单",
						url: "/storage/transfers.jsp?id=" + i
					});
					break;
				case "OI":
					if (!Business.verifyRight("IO_QUERY")) return;
					parent.tab.addTabItem({
						tabid: "storage-otherWarehouse",
						text: "其他入库",
						url: "/storage/other-warehouse.jsp?id=" + i
					});
					break;
				case "OO":
					if (!Business.verifyRight("OO_QUERY")) return;
					parent.tab.addTabItem({
						tabid: "storage-otherOutbound",
						text: "其他出库",
						url: "/storage/other-outbound.jsp?id=" + i
					});
					break;
				case "CADJ":
					if (!Business.verifyRight("CADJ_QUERY")) return;
					parent.tab.addTabItem({
						tabid: "storage-adjustment",
						text: "成本调整单",
						url: "/storage/adjustment.jsp?id=" + i
					})
				}
			},
			loadComplete: function(e) {
				var t;
				if (e && e.data) {
					var i = e.data.rows.length;
					t = i ? 31 * i : "auto"
				}
				n(t)
			},
			gridComplete: function() {
				d("#grid").footerData("set", {
					location: "合计:"
				});
				d("table.ui-jqgrid-ftable").find('td[aria-describedby="grid_location"]').prevUntil().css("border-right-color", "#fff")
			}
		}).jqGrid("setGroupHeaders", {
			useColSpanStyle: !0,
			groupHeaders: [{
				startColumnName: "inqty",
				numberOfColumns: 3,
				titleText: "入库"
			}, {
				startColumnName: "outqty",
				numberOfColumns: 3,
				titleText: "出库"
			}, {
				startColumnName: "totalqty",
				numberOfColumns: 3,
				titleText: "结存"
			}]
		}).jqGrid("setFrozenColumns")
	}
	function n(e) {
		e && (n.h = e);
		var t = o(),
			i = n.h,
			a = s(),
			r = d("#grid");
		i > a && (i = a);
		t < r.width() && (i += 17);
		r.jqGrid("setGridWidth", t, !1);
		r.jqGrid("setGridHeight", i);
		d("#grid-wrap").height(function() {
			return document.body.clientHeight - this.offsetTop - 36 - 5
		})
	}
	function o() {
		return d(window).width() - (o.offsetLeft || (o.offsetLeft = d("#grid-wrap").offset().left)) - 36 - 20
	}
	function s() {
		return d(window).height() - (s.offsetTop || (s.offsetTop = d("#grid").offset().top)) - 36 - 16
	}
	function l() {
		d("#grid").jqGrid("setGridParam", {
			datatype: "json",
			postData: u
		}).trigger("reloadGrid")
	}
	var d = e("jquery"),
		c = parent.SYSTEM,
		u = d.extend({
			beginDate: "",
			endDate: "",
			goodsNo: "",
			storageNo: ""
		}, Public.urlParam());
	e("print");
	
	t();
	i();
	a();
	r();
	var p;
	d(window).on("resize", function() {
		p || (p = setTimeout(function() {
			n();
			p = null
		}, 50))
	})
});