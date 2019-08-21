function initFilter() {
	Business.filterGoods();
	Business.filterStorage();
	$("#filter-fromDate, #filter-toDate").datepicker();
	var e = Public.urlParam();
	filterConditions = {
		beginDate: e.beginDate || defParams.beginDate,
		endDate: e.endDate || defParams.endDate,
		goodsNo: e.goodsNo || "",
		storageNo: e.storageNo || ""
	};
	$("#filter-fromDate").val(filterConditions.beginDate || "");
	$("#filter-toDate").val(filterConditions.endDate || "");
	$("#filter-goods input").val(filterConditions.goodsNo || "");
	$("#filter-storage input").val(filterConditions.storageNo || "");
	filterConditions.beginDate && filterConditions.endDate && $("#selected-period").text(filterConditions.beginDate + "至" + filterConditions.endDate);
	Public.dateCheck();
	$(document).on("click", "#ui-datepicker-div,.ui-datepicker-header", function(e) {
		e.stopPropagation()
	});
	Business.moreFilterEvent();
	$("#conditions-trigger").trigger("click");
	$("#filter-submit").on("click", function(e) {
		e.preventDefault();
		var t = $("#filter-fromDate").val(),
			i = $("#filter-toDate").val();
		if (t && i && new Date(t).getTime() > new Date(i).getTime()) parent.Public.tips({
			type: 1,
			content: "开始日期不能大于结束日期"
		});
		else {
			filterConditions = {
				beginDate: t,
				endDate: i,
				goodsNo: $("#filter-goods input").val() || "",
				storageNo: $("#filter-storage input").val() || ""
			};
			reloadReport()
		}
	});
	$("#filter-reset").on("click", function(e) {
		e.preventDefault();
		$("#filter-fromDate").val("");
		$("#filter-toDate").val("");
		$("#filter-goods input").val("");
		$("#filter-storage input").val("")
	})
}
function initField() {
	var e = filterConditions.storage ? filterConditions.storage.split(",") : "",
		t = filterConditions.goods ? filterConditions.goods.split(",") : "",
		i = "";
	e && t ? i = "「您已选择了<b>" + e.length + "</b>个仓库，<b>" + t.length + "</b>个商品进行查询」" : e ? i = "「您已选择了<b>" + customer.length + "</b>个仓库进行查询」" : t && (i = "「您已选择了<b>" + t.length + "</b>个商品进行查询」");
	$("#cur-search-tip").html(i)
}
function initEvent() {
	$("#refresh").on("click", function(e) {
		e.preventDefault();
		reloadReport()
	});
	$("#btn-print").click(function(e) {
		e.preventDefault();
		Business.verifyRight("DeliverSummaryReport_PRINT") && window.print()
	});
	$("#btn-export").click(function(e) {
		e.preventDefault();
		if (Business.verifyRight("DeliverSummaryReport_EXPORT")) {
			var t = {};
			for (var i in filterConditions) filterConditions[i] && (t[i] = filterConditions[i]);
			//Business.getFile("/report/deliverSummary.do?action=exporter", t)
			Business.getFile(report_goods_summary_xls+"?action=exporter", t)
		}
	});
	$(".grid-wrap").on("click", ".link", function(e) {
		e.preventDefault();
		return false
		if (Business.verifyRight("DeliverDetailReport_QUERY")) {
			var t = $(this).data("invno"),
				i = $(this).data("locationno");
			parent.tab.addTabItem({
				tabid: "report-goodsFlowDetail",
				text: "商品收发明细表",
				url: "/report/goods-flow-detail.jsp?beginDate=" + filterConditions.beginDate + "&endDate=" + filterConditions.endDate + "&goodsNo=" + t + "&storageNo=" + i
			});
			$(this).addClass("tr-hover");
			$_curTr = $(this)
		}
	});
	Business.gridEvent()
}
function reloadReport() {
	var e = "";
	for (key in filterConditions) filterConditions[key] && (e += "&" + key + "=" + encodeURIComponent(filterConditions[key]));
	window.location = report_goods_summary+"?action=detail" + e
}
var filterConditions = {},
	profitChk, $_curTr;
initFilter();
initEvent();
!
function() {
	if (Public.isIE6) {
		var e = $("#report-search"),
			t = $(window);
		e.width(t.width());
		t.resize(function() {
			e.width(t.width())
		})
	}
}();
$(function() {
	Public.initCustomGrid($("table.list"))
});