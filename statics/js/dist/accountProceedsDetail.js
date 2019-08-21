var $_curTr;
$(function() {
	var e = function(e) {
			var t = Public.urlParam(),
				i = report_balance_detail+"?action=detail",
				r = report_balance_detail_xls+"?action=exporter";
			$_fromDate = $("#filter-fromDate"), $_toDate = $("#filter-toDate"), $_accountNoInput = $("#customerAuto");
			var a = {
				SALE: {
					tabid: "sales-sales",
					text: "销货单",
					right: "SA_QUERY",
					//url: "/sales/sales.jsp?id="
					url: invsa_edit+"?id="
				},
				PUR: {
					tabid: "purchase-purchase",
					text: "购货单",
					right: "PU_QUERY",
					url: "/purchase/purchase.jsp?id="
				},
				TRANSFER: {
					tabid: "storage-transfers",
					text: "调拨单",
					right: "TF_QUERY",
					url: "/storage/transfers.jsp?id="
				},
				OO: {
					tabid: "storage-otherOutbound",
					text: "其它出库 ",
					right: "OO_QUERY",
					url: "/storage/other-outbound.jsp?id="
				},
				OI: {
					tabid: "storage-otherWarehouse",
					text: "其它入库 ",
					right: "IO_QUERY",
					url: "/storage/other-warehouse.jsp?id="
				},
				CADJ: {
					tabid: "storage-adjustment",
					text: "成本调整",
					right: "CADJ_QUERY",
					url: "/storage/adjustment.jsp?id="
				},
				PAYMENT: {
					tabid: "money-payment",
					text: "付款单",
					right: "PAYMENT_QUERY",
					url: "/money/payment.jsp?id="
				},
				RECEIPT: {
					tabid: "money-receipt",
					text: "收款单",
					right: "RECEIPT_QUERY",
					url: "/money/receipt.jsp?id="
				},
				VERIFICA: {
					tabid: "money-verifica",
					text: "核销单 ",
					right: "VERIFICA_QUERY",
					url: "/money/verification.jsp?id="
				}
			},
				n = {
					beginDate: t.beginDate || defParams.beginDate,
					endDate: t.endDate || defParams.endDate,
					accountNo: t.accountNo || ""
				},
				s = function() {
					$_fromDate.datepicker();
					$_toDate.datepicker()
				},
				o = function() {
					Business.moreFilterEvent();
					$("#conditions-trigger").trigger("click")
				},
				l = function() {
					var e = "";
					for (key in n) n[key] && (e += "&" + key + "=" + encodeURIComponent(n[key]));
					window.location = i + e
				},
				d = function() {
					$("#filter-submit").on("click", function(e) {
						e.preventDefault();
						var t = $_fromDate.val(),
							i = $_toDate.val();
						if (t && i && new Date(t).getTime() > new Date(i).getTime()) parent.Public.tips({
							type: 1,
							content: "开始日期不能大于结束日期"
						});
						else {
							n = {
								beginDate: t,
								endDate: i,
								accountNo: $_accountNoInput.val() || ""
							};
							l()
						}
					});
					$(document).on("click", "#ui-datepicker-div,.ui-datepicker-header", function(e) {
						e.stopPropagation()
					});
					$("#filter-reset").on("click", function(e) {
						e.preventDefault();
						$_fromDate.val("");
						$_toDate.val("");
						$_accountNoInput.val("")
					});
					$("#refresh").on("click", function(e) {
						e.preventDefault();
						l()
					});
					$("#btn-print").click(function(e) {
						e.preventDefault();
						Business.verifyRight("RECEIPTDETAIL_PRINT") && window.print()
					});
					$("#btn-export").click(function(e) {
						e.preventDefault();
						if (Business.verifyRight("RECEIPTDETAIL_EXPORT")) {
							var t = {};
							for (var i in n) n[i] && (t[i] = n[i]);
							Business.getFile(r, t)
						}
					});
					$(".grid-wrap").on("click", ".link", function() {
						var e = $(this).data("id"),
							t = $(this).data("type").toLocaleUpperCase(),
							i = a[t];
						if (i && Business.verifyRight(i.right)) {
							parent.tab.addTabItem({
								tabid: i.tabid,
								text: i.text,
								url: i.url + e
							});
							$(this).addClass("tr-hover");
							$_curTr = $(this)
						}
					});
					Business.gridEvent()
				};
			e.init = function() {
				$_fromDate.val(n.beginDate || "");
				$_toDate.val(n.endDate || "");
				$_accountNoInput.val(n.accountNo || "");
				n.beginDate && n.endDate && $("#selected-period").text(n.beginDate + "至" + n.endDate);
				Business.filterCustomer();
				$("#customerAuto").val("");
				s();
				o();
				d()
			};
			return e
		}(e || {});
	e.init();
	Public.initCustomGrid($("table.list"))
});