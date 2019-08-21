function initFilter() {
	Business.filterCustomer(); 
	//Business.filterSupplier();
	Business.filterGoods();
	Business.filterStorage();
	$("#filter-fromDate, #filter-toDate").datepicker();
	var e = Public.urlParam();
	filterConditions = {
		beginDate: e.beginDate || defParams.beginDate,
		endDate: e.endDate || defParams.endDate,
		customerNo: e.customerNo || "",
		goodsNo: e.goodsNo || "",
		storageNo: e.storageNo || ""
	};
	$("#filter-fromDate").val(filterConditions.beginDate || "");
	$("#filter-toDate").val(filterConditions.endDate || "");
	$("#filter-customer input").val(filterConditions.customerNo || "");
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
				customerNo: $("#filter-customer input").val() || "",
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
		$("#filter-customer input").val("").removeData("ids");
		$("#filter-goods input").val("").removeData("ids");
		$("#filter-storage input").val("").removeData("ids")
	})
}
function initField() {
	var e = filterConditions.customer ? filterConditions.customer.split(",") : "",
		t = filterConditions.goods ? filterConditions.goods.split(",") : "",
		i = "";
	e && t ? i = "「您已选择了<b>" + e.length + "</b>个客户，<b>" + t.length + "</b>个商品进行查询」" : e ? i = "「您已选择了<b>" + e.length + "</b>个客户进行查询」" : t && (i = "「您已选择了<b>" + t.length + "</b>个商品进行查询」");
	$("#cur-search-tip").html(i)
}
function initEvent() {
	$("#refresh").on("click", function(e) {
		e.preventDefault();
		reloadReport()
	});
	$("#btn-print").click(function(e) {
		e.preventDefault();
		Business.verifyRight("PUREPORTINV_PRINT") && window.print()
	});
	$("#btn-export").click(function(e) {
		e.preventDefault();
		if (Business.verifyRight("PUREPORTINV_EXPORT")) {
			var t = {};
			for (var i in filterConditions) filterConditions[i] && (t[i] = filterConditions[i]);
			//Business.getFile("/report/puDetail.do?action=invExporter", t)
			Business.getFile(report_sales_summary_xls, t)
			
		}
	});
	$(".grid-wrap").on("click", ".link", function(e) {
		e.preventDefault();
		if (Business.verifyRight("PUREOORTDETAIL_QUERY")) {
			var t = $(this).data("invno"),
				i = $(this).data("locationno");
			parent.tab.addTabItem({
				tabid: "report-puDetail",
				text: "销售明细表",
				url: report_sales_detail+"?action=detail&beginDate=" + filterConditions.beginDate + "&endDate=" + filterConditions.endDate + "&customerNo=" + filterConditions.customerNo + "&goodsNo=" + t + "&storageNo=" + i
				
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
	//window.location = "/report/puDetail.do?action=inv" + e
	window.location = report_sales_summary+"?action=inv" + e
	
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