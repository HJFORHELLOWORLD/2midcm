var queryConditions = {
	matchCon: "",
	locationId: -1,
	transTypeId: -1
},
	hiddenAmount = !1,
	SYSTEM = system = parent.SYSTEM,
	THISPAGE = {
		init: function() {
			SYSTEM.isAdmin !== !1 || SYSTEM.rights.AMOUNT_COSTAMOUNT || (hiddenAmount = !0);
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
			this.$_endDate.datepicker()
		},
		loadGrid: function() {
			function e(e, t, i) {
				var a = '<div class="operating" data-id="' + i.id + '"><span class="ui-icon ui-icon-pencil" title="修改"></span><span class="ui-icon ui-icon-trash" title="删除"></span></div>';
				return a
			}
			var t = Public.setGrid();
			queryConditions.beginDate = this.$_beginDate.val();
			queryConditions.endDate = this.$_endDate.val();
			$("#grid").jqGrid({
				//url: "/scm/invOi.do?action=listOut&type=out",
				url: invoi_outlist,
				postData: queryConditions,
				datatype: "json",
				autowidth: !0,
				height: t.h,
				altRows: !0,
				gridview: !0,
				multiselect: !0,
				multiboxonly: !0,
				colModel: [{
					name: "operating",
					label: "操作",
					width: 60,
					fixed: !0,
					formatter: e,
					align: "center"
				}, {
					name: "billDate",
					label: "单据日期",
					width: 100,
					align: "center"
				}, {
					name: "billNo",
					label: "单据编号",
					width: 150,
					align: "center"
				}, {
					name: "transTypeName",
					label: "业务类别",
					width: 150
				}, {
					name: "amount",
					label: "金额",
					hidden: hiddenAmount,
					width: 100,
					align: "right",
					formatter: "currency"
				}, {
					name: "contactName",
					label: "客户",
					width: 200
				}, {
					name: "userName",
					label: "制单人",
					index: "userName",
					width: 80,
					fixed: !0,
					align: "center",
					title: !1
				}, {
					name: "description",
					label: "备注",
					width: 200,
					classes: "ui-ellipsis"
				}],
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
					repeatitems: !1,
					total: "data.total",
					id: "id"
				},
				loadError: function() {},
				ondblClickRow: function(e) {
					$("#" + e).find(".ui-icon-pencil").trigger("click")
				}
			})
		},
		reloadData: function(e) {
			$("#grid").jqGrid("setGridParam", {
				//url: "/scm/invOi.do?action=listOut&type=out",
				url: invoi_outlist+"?type=2",
				datatype: "json",
				postData: e
			}).trigger("reloadGrid")
		},
		addEvent: function() {
			var e = this;
			$(".grid-wrap").on("click", ".ui-icon-pencil", function(e) {
				e.preventDefault();
				var t = $(this).parent().data("id");
				parent.tab.addTabItem({
					tabid: "storage-otherOutbound",
					text: "其他出库",
					url: invoi_outedit+"?id=" + t
					//url: "/storage/other-outbound.jsp?id=" + t
				});
				$("#grid").jqGrid("getDataIDs");
				parent.salesListIds = $("#grid").jqGrid("getDataIDs")
			});
			$(".grid-wrap").on("click", ".ui-icon-trash", function(e) {
				e.preventDefault();
				if (Business.verifyRight("OO_DELETE")) {
					var t = $(this).parent().data("id");
					$.dialog.confirm("您确定要删除该出库记录吗？", function() {
						//Public.ajaxGet("/scm/invOi.do?action=deleteOut", {
						Public.ajaxGet(invoi_del, {			   
							id: t
						}, function(e) {
							if (200 === e.status) {
								$("#grid").jqGrid("delRowData", t);
								parent.Public.tips({
									content: "删除成功！"
								})
							} else parent.Public.tips({
								type: 1,
								content: e.msg
							})
						})
					})
				}
			});
			$("#search").click(function() {
				queryConditions.matchCon = "请输入单据号或客户名或备注" === e.$_matchCon.val() ? "" : e.$_matchCon.val();
				queryConditions.beginDate = e.$_beginDate.val();
				queryConditions.endDate = e.$_endDate.val();
				queryConditions.locationId = -1;
				queryConditions.transTypeId = -1;
				THISPAGE.reloadData(queryConditions)
			});
			$("#moreCon").click(function() {
				queryConditions.matchCon = "请输入单据号或客户名或备注" === e.$_matchCon.val() ? "" : e.$_matchCon.val();
				queryConditions.beginDate = e.$_beginDate.val();
				queryConditions.endDate = e.$_endDate.val();
				$.dialog({
					id: "moreCon",
					width: 480,
					height: 330,
					min: !1,
					max: !1,
					title: "高级搜索",
					button: [{
						name: "确定",
						focus: !0,
						callback: function() {
							queryConditions = this.content.handle();
							THISPAGE.reloadData(queryConditions);
							"" !== queryConditions.matchCon && e.$_matchCon.val(queryConditions.matchCon);
							e.$_beginDate.val(queryConditions.beginDate);
							e.$_endDate.val(queryConditions.endDate)
						}
					}, {
						name: "取消"
					}],
					resize: !1,
					content: "url:/storage/other-search.jsp?type=other&diff=outbound",
					data: queryConditions
				})
			});
			$("#add").click(function(e) {
				e.preventDefault();
				Business.verifyRight("OO_ADD") && parent.tab.addTabItem({
					tabid: "storage-otherOutbound",
					text: "其他出库",
					url: invoi_out
					//url: "/scm/invOi.do?action=initOi&type=out"
				})
			});
			$(window).resize(function() {
				Public.resizeGrid()
			})
		}
	};
THISPAGE.init();