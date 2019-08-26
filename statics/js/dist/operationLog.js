var queryConditions = {
	fromDate: "",
	toDate: "",
	type: "",
	user: ""
},
	SYSTEM = parent.SYSTEM,
	THISPAGE = {
		init: function() {
			this.initDom();
			this.loadGrid();
			this.addEvent()
		},
		initDom: function() {
			this.$_beginDate = $("#beginDate").val(SYSTEM.beginDate);
			this.$_endDate = $("#endDate").val(SYSTEM.endDate);
			this.$_beginDate.datepicker();
			this.$_endDate.datepicker();
			queryConditions.fromDate = this.$_beginDate.val();
			queryConditions.toDate = this.$_endDate.val();
			this.initFilter(queryConditions)
		},
		initFilter: function(e) {
			function t(e, t) {
				var i = "<strong>" + e + "</strong>";
				e != t && (i += " 至 <strong>" + t + "</strong>");
				$("#selected-date").html(i)
			}
			var i = this;
			i.userCombo = $("#user").combo({
				text: "name",
				value: "userid",
				width: 240,
				//data: "/basedata/log.do?action=queryAllUser",
				data: basedata_admin,
				
				ajaxOptions: {
					formatData: function(e) {
						e.data.items.unshift({
							userid: "",
							name: "所有用户"
						});
						return e.data.items
					}
				}
			}).getCombo();
			
			//i.typeCombo = $("#type").combo({
//				text: "operateTypeName",
//				value: "indexid",
//				width: 240,
//				data: "/basedata/log.do?action=queryAllOperateType",
//				ajaxOptions: {
//					formatData: function(e) {
//						e.data.items.unshift({
//							operateTypeName: "所有操作",
//							indexid: ""
//						});
//						return e.data.items
//					}
//				}
//			}).getCombo();
			
			t(e.fromDate, e.toDate);
			Business.moreFilterEvent();
			$("#conditions-trigger").trigger("click");
			$("#filter-submit").on("click", function(e) {
				e.preventDefault();
				var a = i.$_beginDate.val(),
					r = i.$_endDate.val();
				if (new Date(a).getTime() > new Date(r).getTime()) parent.Public.tips({
					type: 2,
					content: "开始日期不能大于结束日期！"
				});
				else {
					var n = i.userCombo.getText();
					queryConditions = {
						fromDate: a,
						toDate: r,
						user: "所有用户" === n ? "" : n
						//type: i.typeCombo.getValue()
					};
					$("#grid").jqGrid("setGridParam", {
						//url: "/basedata/log.do?action=list",
						url: basedata_logs,
						postData: queryConditions,
						datatype: "json"
					}).trigger("reloadGrid");
					$("#filter-menu").removeClass("ui-btn-menu-cur");
					t(a, r)
				}
			});
			$("#filter-reset").on("click", function(e) {
				e.preventDefault();
				i.$_beginDate.val("");
				i.$_endDate.val("");
				i.userCombo.selectByIndex(0);
				i.typeCombo.selectByIndex(0)
			})
		},
		loadGrid: function() {
			var e = Public.setGrid();
			$("#grid").jqGrid({
				//url: "/basedata/log.do?action=list",
				url: basedata_logs,
				postData: queryConditions,
				datatype: "json",
				autowidth: !0,
				height: e.h,
				altRows: !0,
				gridview: !0,
				colModel: [{
					name: "Log_Date",
					label: "日期",
					width: 150,
					align: "center"
				}, {
					name: "name",
					label: "操作人",
					width: 150
				},
				//{
//					name: "operateTypeName",
//					label: "操作类型",
//					width: 200
//				},
				{
					name: "Action",
					label: "日志",
					width: 900
				}],
				cmTemplate: {
					sortable: !1
				},
				page: 1,
				sortname: "number",
				sortorder: "desc",
				pager: "#page",
				rowNum: 100,
				rowList: [100, 200, 500],
				loadonce: !0,
				viewrecords: !0,
				shrinkToFit: !1,
				forceFit: !1,
				jsonReader: {
					root: "data.rows",
					records: "data.records",
					repeatitems: !1,
					id: "id"
				},
				loadError: function() {}
			})
		},
		reloadData: function(e) {
			$("#grid").jqGrid("setGridParam", {
				//url: "/basedata/log.do?action=list",
				url: basedata_logs,
				datatype: "json",
				postData: e
			}).trigger("reloadGrid")
		},
		addEvent: function() {
			$("#refresh").click(function() {
				THISPAGE.reloadData(queryConditions)
			});
			$(window).resize(function() {
				Public.resizeGrid()
			})
		}
	};
THISPAGE.init();