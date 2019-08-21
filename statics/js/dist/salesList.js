var queryConditions = {
	matchCon: ""
},
	SYSTEM = system = parent.SYSTEM,
	hiddenAmount = !1,
	billRequiredCheck = system.billRequiredCheck,
	THISPAGE = {
		init: function() {
			SYSTEM.isAdmin !== !1 || SYSTEM.rights.AMOUNT_OUTAMOUNT || (hiddenAmount = !0);
			this.initDom();
			this.loadGrid();
			this.addEvent()
		},
		initDom: function() {
			this.$_matchCon = $("#matchCon");
			this.$_beginDate = $("#beginDate").val(system.beginDate);
			this.$_endDate = $("#endDate").val(system.endDate);
			this.$_matchCon.placeholder();
			this.$_beginDate.datepicker();
			this.$_endDate.datepicker();
            this.$_review = $("#review");
		},
		loadGrid: function() {
			function t(t, e, i) {
                if(4 == i.review || 7 == i.review) {
                    var a = '<div class="operating" data-id="' + i.PK_BOM_Sale_ID + '"><a class="ui-icon ui-icon-pencil" title="修改"></a><a class="ui-icon ui-icon-trash" title="删除"></a></div>';
                }
                if (6 == i.review){
                    var a = '<div class="operating" data-id="' + i.PK_BOM_Sale_ID + '"><a class="ui-icon ui-icon-search" title="查看"></a><a class="ui-icon ui-icon-copy"  title="出库"></a><a class="ui-icon ui-icon-trash" title="删除"></a></div>';
                }
                if (9 == i.review){
                    var a = '<div class="operating" data-id="' + i.PK_BOM_Sale_ID + '"><a class="ui-icon ui-icon-search" title="查看"></a><span style="color: red">已出库</div>';
                }return a
			}
			function e(t, e, i) {
				if (1 === t) return "销货";
				a.markRow.push(i.id);
				return "退货"
			}
			var i = Public.setGrid(),
				a = this;
			queryConditions.beginDate = this.$_beginDate.val();
			queryConditions.endDate = this.$_endDate.val();
			a.markRow = [];
			var r = [{
				name: "operating",
				label: "操作",
				width: 70,
				fixed: !0,
				formatter: t,
				align: "center"
			}, {
				name: "Create_Date",
				label: "订单日期",
				index: "Create_Date",
				width: 120,
				align: "center"
			}, {
				name: "PK_BOM_Sale_ID",
				label: "订单编号",
				index: "PK_BOM_Sale_ID",
				width: 150,
				align: "center"
			},/* {
				name: "transType",
				label: "业务类别",
				index: "transType",
				width: 100,
				formatter: e,
				align: "center"
			},*/
                {
                    name: "orderName",
                    label: "订单名称",
                    index: "orderName",
                    width: 200,
                    align:"center"
                },{
				name: "Customer_Name",
				label: "客户",
				index: "Customer_Name",
				width: 200,
				align:"center"
			}, {
				name: "SaleOrder_Total",
				label: "订单总金额",
				hidden: hiddenAmount,
				index: "SaleOrder_Total",
				width: 100,
				align: "right",
				formatter: "currency"
			},/* {
				name: "amount",
				label: "折后金额",
				hidden: hiddenAmount,
				index: "amount",
				width: 100,
				align: "right",
				formatter: "currency"
			}, {
				name: "rpAmount",
				label: "已收款金额",
				hidden: hiddenAmount,
				index: "rpAmount",
				width: 100,
				align: "right",
				formatter: "currency"
			},*/{
                    name: "SaleOrder_Payment",
                    label: "付款条件",
                    index: "SaleOrder_Payment",
                    width: 200,
                    title: !0,
                    classes: "ui-ellipsis"
                },  {
				name: "Username",
				label: "制单人",
				index: "Username",
				width: 80,
				fixed: !0,
				align: "center",
				title: !0,
				classes: "ui-ellipsis"
			},/* {
				name: "checkName",
				label: "审核人",
				index: "checkName",
				width: 80,
				hidden: billRequiredCheck ? !1 : !0,
				fixed: !0,
				align: "center",
				title: !0,
				classes: "ui-ellipsis"
			},*/ {
				name: "disEditable",
				label: "不可编辑",
				index: "disEditable",
				hidden: !0
			},{
                name: "reviewDes",
				label:"审核状态",
                index: "reviewDes",
                width: 100,
                align: "center"
            }];
			$("#grid").jqGrid({
				//url: "/scm/invSa.do?action=list",
				url: invsa_lists,
				postData: queryConditions,
				datatype: "json",
				autowidth: !0,
				height: i.h,
				altRows: !0,
				gridview: !0,
				multiselect: !0,
				colModel: r,
				cmTemplate: {
					sortable: !1,
					title: !1
				},
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
				loadComplete: function() {
					var t = a.markRow.length;
					if (t > 0) for (var e = 0; t > e; e++) $("#" + a.markRow[e]).addClass("red")
				},
				loadError: function() {},
				ondblClickRow: function(t) {
					$("#" + t).find(".ui-icon-pencil").trigger("click")
				}
			})
		},
		reloadData: function(t) {
			this.markRow = [];
			$("#grid").jqGrid("setGridParam", {
				//url: "/scm/invSa.do?action=list",
				url: invsa_lists,
				datatype: "json",
				postData: t
			}).trigger("reloadGrid")
		},
		addEvent: function() {
			var t = this;
			$(".grid-wrap").on("click", ".ui-icon-pencil", function(t) {
				t.preventDefault();
				var e = $(this).parent().data("id"),
					i = $("#grid").jqGrid("getRowData", e),
					a = 1 == i.disEditable ? "&disEditable=true" : "";
				parent.tab.addTabItem({
					tabid: "sales-sales",
					text: "销售单",
					//url: "/sales/sales.jsp?id=" + e + "&flag=list" + a
					url: invsa_edit+"?id=" + e + "&flag=list"
				});
				$("#grid").jqGrid("getDataIDs");
				parent.cacheList.salesId = $("#grid").jqGrid("getDataIDs")
			});
			$(".grid-wrap").on("click", ".ui-icon-trash", function(t) {
				t.preventDefault();
				if (Business.verifyRight("SA_DELETE")) {
					var e = $(this).parent().data("id");
					$.dialog.confirm("您确定要删除该销售记录吗？", function() {
						//Public.ajaxGet("/scm/invSa.do?action=delete", {
						Public.ajaxGet(invsa_del, { 			   
							id: e
						}, function(t) {
							if (200 === t.status) {
								$("#grid").jqGrid("delRowData", e);
								parent.Public.tips({
									content: "删除成功！"
								});
                                THISPAGE.reloadData(queryConditions);
							} else parent.Public.tips({
								type: 1,
								content: t.msg
							})
						})
					})
				}
			});
			$(".wrapper").on("click", "#print", function(t) {
				t.preventDefault();
				Business.verifyRight("SA_PRINT") && Public.print({
					title: "购货单列表",
					$grid: $("#grid"),
					pdf: "/scm/invSa.do?action=toPdf",
					billType: 10201,
					filterConditions: queryConditions
				})
			});
			
			$(".wrapper").on("click", "#export", function(t) {
				if (Business.verifyRight("SA_EXPORT")) {
					var e = $("#grid").jqGrid("getGridParam", "selarrrow"),
						i = e.join();
					//if (i) $(this).attr("href", "/scm/invSa.do?action=exportInvSa&id=" + i);
					if (i) $(this).attr("href", invsa_export+"?id=" + i);
					else {
						parent.Public.tips({
							type: 2,
							content: "请先选择需要导出的项！"
						});
						t.preventDefault()
					}
				} else t.preventDefault()
			});
			
			 
			if (billRequiredCheck) {
				{
					$("#audit").css("display", "inline-block"), $("#reAudit").css("display", "inline-block")
				}
				$(".wrapper").on("click", "#audit", function(t) {
					t.preventDefault();
					var e = $("#grid").jqGrid("getGridParam", "selarrrow"),
						i = e.join();
					i ? Public.ajaxPost("/scm/invSa.do?action=batchCheckInvSa", {
						id: i
					}, function(t) {
						if (200 === t.status) {
							for (var i = 0, a = e.length; a > i; i++) $("#grid").setCell(e[i], "checkName", system.realName);
							parent.Public.tips({
								content: "审核成功！"
							})
						} else parent.Public.tips({
							type: 1,
							content: t.msg
						})
					}) : parent.Public.tips({
						type: 2,
						content: "请先选择需要审核的项！"
					})
				});
				$(".wrapper").on("click", "#reAudit", function(t) {
					t.preventDefault();
					var e = $("#grid").jqGrid("getGridParam", "selarrrow"),
						i = e.join();
					i ? Public.ajaxPost("/scm/invSa.do?action=rsBatchCheckInvSa", {
						id: i
					}, function(t) {
						if (200 === t.status) {
							for (var i = 0, a = e.length; a > i; i++) $("#grid").setCell(e[i], "checkName", "&#160;");
							parent.Public.tips({
								content: "反审核成功！"
							})
						} else parent.Public.tips({
							type: 1,
							content: t.msg
						})
					}) : parent.Public.tips({
						type: 2,
						content: "请先选择需要反审核的项！"
					})
				})
			}
			$("#search").click(function() {
				queryConditions.matchCon = "请输入订单编号或订单名称或付款条件" === t.$_matchCon.val() ? "" : $.trim(t.$_matchCon.val());
				queryConditions.beginDate = t.$_beginDate.val();
				queryConditions.endDate = t.$_endDate.val();
                queryConditions.review = t.$_review.val();
				THISPAGE.reloadData(queryConditions)
			});
            $(".grid-wrap").on("click", ".ui-icon-pencil,.ui-icon-search", function(t) {
                t.preventDefault();
                var e = $(this).parent().data("id"),
                    i = $("#grid").jqGrid("getRowData", e),
                    a = 1 == i.disEditable ? "&disEditable=true" : "";
                parent.tab.addTabItem({
                    tabid: "sales-sales",
                    text: "销售单",
                    url: invsa_edit+"?id=" + e + "&flag=list"
                    //url: "/purchase/purchase.jsp?id=" + e + "&flag=list" + a
                });
                $("#grid").jqGrid("getDataIDs");
                parent.cacheList.purchaseId = $("#grid").jqGrid("getDataIDs")
            });
            $(".grid-wrap").on("click", ".ui-icon-copy", function(t) {
                t.preventDefault();
                var e = $(this).parent().data("id");
                $.dialog.confirm("您确定要出库该销售单吗？", function() {
                    //Public.ajaxGet("/scm/invPu.do?action=delete", {
                    Public.ajaxPost(invoi_orderOut, {
                        id: e
                    }, function(t) {
                        if (200 === t.status) {
                            parent.Public.tips({
                                content: t.msg
                            });
                            THISPAGE.reloadData(queryConditions);
                        } else parent.Public.tips({
                            type: 1,
                            content: t.msg
                        });
                    })
                })
            });
			$("#refresh").click(function() {
				THISPAGE.reloadData(queryConditions)
			});
			$("#add").click(function(t) {
				t.preventDefault();
				Business.verifyRight("SA_ADD") && parent.tab.addTabItem({
					tabid: "sales-sales",
					text: "销货单",
					//url: "/scm/invSa.do?action=initSale"
					url: invsa_add
				})
			});
			$(window).resize(function() {
				Public.resizeGrid()
			})
		}
	};
THISPAGE.init();