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
					name: "number",
					label: "编号",
					width: 100,
					title: !1
				}, {
					name: "name",
					label: "名称",
					width: 170,
					classes: "ui-ellipsis"
				}, {
					name: "customerType",
					label: "类别",
					width: 106,
					title: !1
				}, {
					name: "amount",
					label: "期初应付款",
					width: 90,
					title: !1,
					align: "right"
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
				queryConditions.skey = "请输入供应商编号或名称或联系人" === t.$_matchCon.val() ? "" : t.$_matchCon.val();
				THISPAGE.reloadData(queryConditions)
			});
			$("#refresh").click(function() {
				THISPAGE.reloadData(queryConditions)
			})
		}
	};
THISPAGE.init();