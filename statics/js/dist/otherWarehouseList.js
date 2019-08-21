var queryConditions = {
	matchCon: "",
	locationId: -1,
	transTypeId: -1
},
	hiddenAmount = !1,
	SYSTEM = system = parent.SYSTEM,
	THISPAGE = {
		init: function() {
			SYSTEM.isAdmin !== !1 || SYSTEM.rights.AMOUNT_INAMOUNT || (hiddenAmount = !0);
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
				var a = '<div class="operating" data-id="' + i.id + '"><span class="ui-icon ui-icon-search" title="查看"></span><span class="ui-icon ui-icon-trash" title="删除"></span></div>';
				return a
			}
			function t(e) {
				var t;
				switch (e) {
				case 2:
					t = "盘盈";
					break;
				case 1:
					t = "其他入库";
					break;
				}
				return t
			}
			var i = Public.setGrid();
			queryConditions.beginDate = this.$_beginDate.val();
			queryConditions.endDate = this.$_endDate.val();
			$("#grid").jqGrid({
				//url: "/scm/invOi.do?action=listIn&type=in",
				url: invoi_inlist,
				postData: queryConditions,
				datatype: "json",
				autowidth: !0,
				height: i.h,
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
					label: "入库日期",
					width: 150,
					align: "center"
				}, {
					name: "billNo",
					label: "订单编号",
					width: 150,
					align: "center"
				}, {
					name: "transType",
					label: "业务类别",
					width: 100,
					formatter: t,
                    align: "center"
				}, {
					name: "amount",
					label: "金额",
					hidden: hiddenAmount,
					width: 100,
					align: "right",
					formatter: "currency",
                    align: "center"
				}, {
					name: "contactName",
					label: "供应商",
					width: 200,
                    align: "center"
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
					$("#" + e).find(".ui-icon-search").trigger("click")
				}
			})
		},
		reloadData: function(e) {
			$("#grid").jqGrid("setGridParam", {
				//url: "/scm/invOi.do?action=listIn&type=in",
				url: invoi_inlist+"?type=1",
				datatype: "json",
				postData: e
			}).trigger("reloadGrid")
		},
		addEvent: function() {
			var e = this;
			$(".grid-wrap").on("click", ".ui-icon-search", function(e) {
				e.preventDefault();
				var t = $(this).parent().data("id");
				parent.tab.addTabItem({
					tabid: "storage-otherWarehouse",
					text: "其他入库",
					//url: "/storage/other-warehouse.jsp?id=" + t
					url: invoi_inedit+"?id=" + t
					
				});
				$("#grid").jqGrid("getDataIDs");
				parent.salesListIds = $("#grid").jqGrid("getDataIDs")
			});
			$(".grid-wrap").on("click", ".ui-icon-trash", function(e) {
				e.preventDefault();
				if (Business.verifyRight("IO_DELETE")) {
					var t = $(this).parent().data("id");
					$.dialog.confirm("您确定要删除该入库记录吗？", function() {
						//Public.ajaxGet("/scm/invOi.do?action=deleteIn", {
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
            $(".wrapper").on("click", "#export", function(t) {
                if (Business.verifyRight("PU_EXPORT")) {
                    var e = $("#grid").jqGrid("getGridParam", "selarrrow"),
                        i = e.join();
                    //if (i) $(this).attr("href", "/scm/invSa.do?action=exportInvSa&id=" + i);
                    if (i) $(this).attr("href", invoi_export+"?type=in&id=" + i);
                    else {
                        parent.Public.tips({
                            type: 2,
                            content: "请先选择需要导出的项！"
                        });
                        t.preventDefault()
                    }
                } else t.preventDefault()
            });
			$("#search").click(function() {
				queryConditions.matchCon = "请输入订单编号或供应商或备注" === e.$_matchCon.val() ? "" : e.$_matchCon.val();
				queryConditions.beginDate = e.$_beginDate.val();
				queryConditions.endDate = e.$_endDate.val();
				queryConditions.locationId = -1;
				queryConditions.transTypeId = -1;
				THISPAGE.reloadData(queryConditions)
			});
			$("#moreCon").click(function() {
				queryConditions.matchCon = "请输入订单编号或供应商或备注" === e.$_matchCon.val() ? "" : e.$_matchCon.val();
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
					//content: "url:/storage/other-search.jsp?type=other",
					content: "url:"+settings_other_search,
					data: queryConditions
				})
			});
			$("#add").click(function(e) {
				e.preventDefault();
				Business.verifyRight("IO_ADD") && parent.tab.addTabItem({
					tabid: "storage-otherWarehouse",
					text: "其他入库",
					//url: "/scm/invOi.do?action=initOi&type=in"
					url: invoi_in
				})
			});
			$(window).resize(function() {
				Public.resizeGrid()
			})
		}
	};
THISPAGE.init();