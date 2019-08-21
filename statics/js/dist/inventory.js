var curRow, curCol, loading = null,
	import_dialog = null,
	queryConditions = {
		goods: "",
		showZero: 0
	},
	THISPAGE = {
		init: function(e) {
			this.initDom(e);
			this.addEvent();
			this.loaded = !1;
			this.loadGrid();
		},
		initDom: function() {
			//this.$_storage = $("#storage");
			this.$_category = $("#category");
			this.$_goods = $("#goods");
			this.$_note = $("#note");
			this.showZero = $("#showZero").cssCheckbox();
			this.categoryTree = Public.categoryTree(this.$_category, {
				rootTxt: "所有类别",
				width: 200
			})
		},
		loadGrid: function(e) {
			function t(e) {
				return parseFloat(e) < 0 ? '<span class="red">' + e + "</span>" : 0 === parseFloat(e) ? 0 : e || "&#160;"
			}
			function i(e) {
				return e.replace('<span class="red">', "").replace("</span>", "")
			}
			$("#grid").jqGrid("GridUnload");
			var a = $(window).height() - $(".grid-wrap").offset().top - 94;
			$("#grid").jqGrid({
				url:inventory_lists,
				datatype:"json",
				//data: e.rows,
				//datatype: "clientSide",
				autowidth: !0,
				height: a,
				rownumbers: !0,
				altRows: !0,
				gridview: !0,
				colModel: [
				{	name: "locationName",
					label: "仓库id",
					width: 100
				},{
					name: "invNumber",
					label: "物料编号",
					width: 200
                }, {
					name: "invCost",
					label: "单位成本",
					width: 100,
					align: "right"
				}, {
					name: "qty",
					label: "系统库存",
					width: 100,
					align: "right"
				}, {
					name: "checkInventory",
					label: "盘点库存",
					width: 100,
					title: !1,
					align: "right",
					editable: !0
				},{
                     name: "checker",
                     label: "盘点人",
                     width: 100,
                     title: !1,
                     align: "right",
                     editable: !0
                },{
					name: "change",
					label: "盘盈盘亏",
					width: 100,
					align: "right",
					formatter: t,
					unformat: i
				}],
				cmTemplate: {
					sortable: !1
				},
				page: 1,
				sortname: "number",
				sortorder: "desc",
				pager: "#page",
				rowNum: 2e3,
				rowList: [300, 500, 1e3],
				loadonce: !0,
				viewrecords: !0,
				shrinkToFit: !1,
				forceFit: !1,
				cellEdit: !0,
				triggerAdd: !1,
				cellsubmit: "clientArray",
				localReader: {
					root: "data.rows",
					records: "data.records",
					repeatitems: !1,
					id: "-1"
				},
				jsonReader: {
					root: "data.rows",
					records: "data.records",
					repeatitems: !1,
					id: "-1"
				},
				gridComplete: function() {
					$("tr#1").find("td:eq(11)").trigger("click")
				},
				afterSaveCell: function(e, t, i, a, r) {
					if ("checkInventory" == t) {
						var n = $("#grid").jqGrid("getCell", e, r - 1);
						if (!isNaN(parseFloat(n))) {
							$("#grid").jqGrid("setRowData", e, {
								change: parseFloat(i) - parseFloat(n)
							})
						}
					}
				},
				loadError: function() {}
			})
		},
		reloadData: function(e) {
			$("#grid").jqGrid("setGridParam", {
				//url: "/scm/invOi.do?action=queryToPD",
				url: inventory_lists,
				datatype: "json",
				postData: e,
				loadonce: !0
			}).trigger("reloadGrid")
		},
		_getEntriesData: function() {
			if (null !== curRow && null !== curCol) {
				$("#grid").jqGrid("saveCell", curRow, curCol);
				curRow = null;
				curCol = null
			}
			for (var e = [], t = $("#grid").jqGrid("getDataIDs"), i = 0, a = t.length; a > i; i++) {
				var r, n = t[i],
					o = $("#grid").jqGrid("getRowData", n);
				r = {
					locationName: o.locationName,
					invNumber: o.invNumber,
					invCost: o.invCost,
					qty: o.qty,
					checkInventory: o.checkInventory,
					checker:o.checker,
					change: o.change
				};
				e.push(r)
			}
			return e
		},
		addEvent: function() {
			var t = this;
			$("#search").click(function() {
				var  queryConditions = $("#goods").val() ;
					//{ locationId: t.storageCombo.getValue(),
					// categoryId: t.categoryTree.getValue(),
					// goods: t.$_goods.val(),
					// showZero: t.showZero.chkVal().join() ? 1 : 0 }

             queryConditions=JSON.stringify(queryConditions);
				console.log(queryConditions);
				//t.loaded ? t.reloadData(queryConditions) : Public.ajaxGet("/scm/invOi.do?action=queryToPD", queryConditions, function(e) {
				t.loaded ? t.reloadData(queryConditions) : Public.ajaxGet(inventory_lists, queryConditions, function(e) {																													  																										  
					if (200 === e.status) {
                        THISPAGE.reloadData(queryConditions)
/*						$(".grid-wrap").removeClass("no-query");
						t.loadGrid(e.data);
						t.loaded = !0;
						$("#handleDom").show();
						$(".mod-search .fr").show()*/
					} else parent.Public.tips({
						type: 1,
						content: msg
					})
				})
			});
			$("#save").click(function() {
				var e = t._getEntriesData();
				if (!(e.length > 0)) {
					parent.Public.tips({
						type: 2,
						content: "商品信息不能为空！"
					});
					$("#grid").jqGrid("editCell", 1, 2, !0);
					return !1
				}
				var i = {
					entries: e,
					description: $.trim(t.$_note.val())
				};
				//Public.ajaxPost("/scm/invOi.do?action=generatorPD", {
				Public.ajaxPost(inventory_generator, {
								
					postData: JSON.stringify(i)
				}, function(e) {
					if (200 === e.status) {
						parent.Public.tips({
							content: e.msg
						});
						$("#search").trigger("click");
                        $("#btn").val("开始盘点")
					} else parent.Public.tips({
						type: 1,
						content: e.msg
					})
				})
			});
			$("#export").click(function(e) {
				Business.verifyRight("PD_EXPORT") ? $(this).attr("href", inventory_export+"?locationId=" + queryConditions.locationId + "&categoryId=" + queryConditions.categoryId + "&goods=" + queryConditions.goods + "&showZero=" + queryConditions.showZero) : e.preventDefault()
			});
			$("#import").click(function() {
				if (Business.verifyRight("PD_IMPORT")) {
					var i, a = this;
					a.import_dialog = $.dialog({
						width: 520,
						height: 150,
						title: "批量导入",
						content: "url:/storage/import.jsp",
						data: {
							curID: t.curID,
							callback: function(e) {
								var t = "上传失败！";
								if (e && e.msg) {
									if ("success" === e.msg) {
										parent.Public.tips({
											content: e.data.msg,
											time: 5e3
										});
										$("#search").trigger("click");
										a.loading.close();
										a.import_dialog.close();
										return
									}
									t = e.msg
								}
								parent.Public.tips({
									type: 1,
									content: t
								});
								a.loading.close()
							}
						},
						lock: !0,
						ok: function() {
							i = this.content.$("#file-path");
							if ("" === i.val()) {
								parent.Public.tips({
									type: 2,
									content: "请先选择导入的文件！"
								});
								return !1
							}
							a.loading = $.dialog.tips("正在导入数据，请稍候...", 1e3, "loading.gif", !0);
							this.content.callback();
							return !1
						},
						cancel: !0
					})
				} else e.preventDefault()
			});
			$(document).bind("click.cancel", function(e) {
				if (!$(e.target).closest(".ui-jqgrid-btable").length > 0 && null !== curRow && null !== curCol) {
					$("#grid").jqGrid("saveCell", curRow, curCol);
					curRow = null;
					curCol = null
				}
			});
			$(window).resize(function() {
				Public.resizeGrid(94)
			})
		}
	};
THISPAGE.init();