var curRow, curCol, loading, urlParam = Public.urlParam(),
	SYSTEM = parent.SYSTEM,
	hiddenAmount = !1,
	qtyPlaces = Number(parent.SYSTEM.qtyPlaces),
	pricePlaces = Number(parent.SYSTEM.pricePlaces),
	amountPlaces = Number(parent.SYSTEM.amountPlaces),
	THISPAGE = {
		init: function(e) {
			SYSTEM.isAdmin !== !1 || SYSTEM.rights.AMOUNT_INAMOUNT || (hiddenAmount = !0);
			this.loadGrid(e);
			this.initDom(e);
			this.initCombo();
			this.addEvent()
		},
		initDom: function(e) {
			this.$_customer = $("#customer");
			this.$_date = $("#date").val(SYSTEM.endDate);
			this.$_number = $("#number");
			this.$_transType = $("#transType");
			this.$_note = $("#note");
			this.$_toolTop = $("#toolTop");
			this.$_toolBottom = $("#toolBottom");
			this.$_userName = $("#userName");
			this.customerArrears = 0;
			if ("add" === e.status) var t = ["id", 1];
			else var t = ["id", e.transType];
/*			this.customerCombo = Business.supplierCombo($("#customer"), {
				defaultSelected: 0,
				emptyOptions: !0
			});*/
			this.transTypeCombo = this.$_transType.combo({
				//data: "/scm/invOi.do?action=queryTransType&type=in",
				data: invoi_type+"?type=in",
				ajaxOptions: {
					formatData: function(e) {
						return e.data.items
					}
				},
				width: 80,
				height: 300,
				text: "name",
				value: "id",
				defaultSelected: t,
				cache: !1,
				defaultFlag: !1
			}).getCombo();
			this.$_date.datepicker({
				onSelect: function(e) {
					var t = e.format("yyyy-MM-dd");
					//THISPAGE.$_number.text("");
					//Public.ajaxPost("/basedata/systemProfile.do?action=generateDocNo", {
//						billType: "OI",
//						billDate: t
//					}, function(e) {
//						200 === e.status ? THISPAGE.$_number.text(e.data.billNo) : parent.Public.tips({
//							type: 1,
//							content: e.msg
//						})
//					})
				}
			});
			if (e.id > 0) {
				this.$_customer.data("contactInfo", {
					id: e.buId,
					name: e.contactName
				});
				this.customerCombo.input.val(e.contactName);
				this.$_number.text(e.billNo);
				this.$_date.val(e.date);
				this.$_note.val(e.description);
				$("#grid").jqGrid("footerData", "set", {
					qty: e.totalQty,
					amount: e.totalAmount
				});
				/*<a id="add" class="ui-btn ui-btn-sp mrb">新增</a><a id="edit" class="ui-btn mrb">保存</a><a href="#"  id="print"  rel="'+ e.id +'" class="ui-btn">打印</a>*/
				this.$_toolBottom.html("edit" === e.status ? '' : '<a id="add" class="ui-btn ui-btn-sp mrb">新增</a><a href="#"  id="print"  rel="'+ e.id +'" class="ui-btn">打印</a><a class="ui-btn-prev mrb" id="prev" title="上一张"><b></b></a><a class="ui-btn-next" id="next" title="下一张"><b></b></a>');
				this.salesListIds = parent.salesListIds || [];
				this.idPostion = $.inArray(String(e.id), this.salesListIds);
				this.idLength = this.salesListIds.length;
				0 === this.idPostion && $("#prev").addClass("ui-btn-prev-dis");
				this.idPostion === this.idLength - 1 && $("#next").addClass("ui-btn-next-dis");
				this.$_userName.html(e.userName)
			} else {
				this.$_toolBottom.html('<a id="savaAndAdd" class="ui-btn ui-btn-sp mrb">保存并新增</a><!--<a id="save" class="ui-btn">保存</a>-->');
				this.$_userName.html(SYSTEM.realName || "")
			}
		},
		loadGrid: function(e) {
			function t(e, t, i) {
				return e ? e : i.invNumber ? i.invSpec ? i.invNumber + " " + i.invName + "_" + i.invSpec : i.invNumber + " " + i.invName : "&#160;"
			}
			function i() {
				var e = $(".goodsAuto")[0];
				return e
			}
			function a(e, t, i) {
				if ("get" === t) {
					if ("" !== $(".goodsAuto").getCombo().getValue()) return $(e).val();
					var a = $(e).parents("tr");
					a.removeData("goodsInfo");
					return ""
				}
				"set" === t && $("input", e).val(i)
			}
			function r() {
				$("#initCombo").append($(".goodsAuto").val("").unbind("focus.once"))
			}
			function n() {
				var e = $(".storageAuto")[0];
				return e
			}
			function o(e, t, i) {
				if ("get" === t) {
					if ("" !== $(".storageAuto").getCombo().getValue()) return $(e).val();
					var a = $(e).parents("tr");
					a.removeData("storageInfo");
					return ""
				}
				"set" === t && $("input", e).val(i)
			}
			function s() {
				$("#initCombo").append($(".storageAuto").val(""))
			}
			var l = this;
			if (e.id) {
				var d = 8 - e.entries.length;
				if (d > 0) for (var c = 0; d > c; c++) e.entries.push({})
			}
			l.newId = 9;
			$("#grid").jqGrid({
				data: e.entries,
				datatype: "clientSide",
				width: 1e3,
				height: "100%",
				rownumbers: !0,
				gridview: !0,
				onselectrow: !1,
				colModel: [{
					name: "operating",
					label: " ",
					width: 40,
					fixed: !0,
					formatter: Public.billsOper,
					align: "center"
				}, {
					name: "goods",
					label: "物料",
					width: 320,
					title: !0,
					classes: "ui-ellipsis",
					formatter: t,
					editable: !0,
					edittype: "custom",
					editoptions: {
						custom_element: i,
						custom_value: a,
						handle: r,
						trigger: "ui-icon-ellipsis"
					}
				}, {
					name: "mainUnit",
					label: "单位",
					width: 60
				}, {
					name: "qty",
					label: "数量",
					width: 80,
					align: "right",
					formatter: "number",
					formatoptions: {
						decimalPlaces: qtyPlaces
					},
					editable: !0
				}, {
					name: "price",
					label: "入库单价",
					hidden: hiddenAmount,
					width: 100,
					fixed: !0,
					align: "right",
					formatter: "currency",
					formatoptions: {
						showZero: !0,
						decimalPlaces: pricePlaces
					},
					editable: !0
				}, {
					name: "amount",
					label: "入库金额",
					hidden: hiddenAmount,
					width: 100,
					fixed: !0,
					align: "right",
					formatter: "currency",
					formatoptions: {
						showZero: !0,
						decimalPlaces: amountPlaces
					},
					editable: !0
				},
				//{
//					name: "locationName",
//					label: '仓库<small id="batchStorage">(批量)</small>',
//					width: 100,
//					title: !0,
//					editable: !0,
//					edittype: "custom",
//					editoptions: {
//						custom_element: n,
//						custom_value: o,
//						handle: s,
//						trigger: "ui-icon-triangle-1-s"
//					}
//				}, 
				{
					name: "description",
					label: "备注",
					width: 150,
					title: !0,
					editable: !0
				}],
				cmTemplate: {
					sortable: !1,
					title: !1
				},
				shrinkToFit: !0,
				forceFit: !0,
				rowNum: 1e3,
				cellEdit: !1,
				cellsubmit: "clientArray",
				localReader: {
					root: "rows",
					records: "records",
					repeatitems: !1,
					id: "id"
				},
				jsonReader: {
					root: "data.entries",
					records: "records",
					repeatitems: !1,
					id: "id"
				},
				loadComplete: function(e) {
					if (urlParam.id > 0) {
						var t = e.rows,
							i = t.length;
						l.newId = i + 1;
						for (var a = 0; i > a; a++) {
							var r = a + 1,
								n = t[a];
							if ($.isEmptyObject(t[a])) break;
							$("#" + r).data("goodsInfo", {
								id: n.invId,
								number: n.invNumber,
								name: n.invName,
								spec: n.invSpec,
								unitId: n.unitId,
								unitName: n.mainUnit
							}).data("storageInfo", {
								id: n.locationId,
								name: n.locationName
							})
						}
					}
				},
				//loadComplete: function(e) {
//					if (urlParam.id > 0) {
//						var t = e.rows,
//							i = t.length;
//						l.newId = i + 1;
//						for (var a = 0; i > a; a++) {
//							var r = t[a];
//							if ($.isEmptyObject(t[a])) break;
//							$("#" + t[a].id).data("goodsInfo", {
//								id: r.invId,
//								number: r.invNumber,
//								name: r.invName,
//								spec: r.invSpec,
//								unitId: r.unitId,
//								unitName: r.mainUnit
//							}).data("storageInfo", {
//								id: r.locationId,
//								name: r.locationName
//							})
//						}
//					}
//				},
				gridComplete: function() {},
				afterEditCell: function(e, t, i, a) {
					if ("goods" === t) {
						$("#" + a + "_goods", "#grid").val(i);
						THISPAGE.goodsCombo.selectByText(i);
						THISPAGE.curID = e
					}
					"locationName" === t && $("#" + a + "_locationName", "#grid").val(i)
				},
				formatCell: function() {},
				beforeSubmitCell: function() {},
				afterSaveCell: function(e, t, i, a, r) {
					if ("goods" == t) {
						var n = $("#" + e).data("goodsInfo");
						if (n) {
							var o = $("#grid").jqGrid("setRowData", e, {
								mainUnit: n.unitName,
								qty: 1,
								price: n.purPrice,
								amount: n.purPrice,
								locationName: n.localtionName
							});
							o && THISPAGE.calTotal()
						}
					}
					if ("qty" == t) {
						var s = $("#grid").jqGrid("getCell", e, r + 1);
						if (!isNaN(parseFloat(s))) {
							var o = $("#grid").jqGrid("setRowData", e, {
								amount: parseFloat(i) * parseFloat(s)
							});
							o && THISPAGE.calTotal()
						}
					}
					if ("price" == t) {
						var l = $("#grid").jqGrid("getCell", e, r - 1);
						if (!isNaN(parseFloat(l))) {
							var o = $("#grid").jqGrid("setRowData", e, {
								amount: parseFloat(i) * parseFloat(l)
							});
							o && THISPAGE.calTotal()
						}
					}
					if ("amount" == t) {
						var l = $("#grid").jqGrid("getCell", e, r - 2);
						if (!isNaN(parseFloat(l))) {
							var s = parseFloat(i) / parseFloat(l);
							$("#grid").jqGrid("setRowData", e, {
								price: s
							})
						}
						THISPAGE.calTotal()
					}
				},
				loadonce: !0,
				footerrow: !0,
				userData: {
					goods: "合计：",
					qty: e.totalQty,
					amount: e.totalAmount
				},
				userDataOnFooter: !0,
				loadError: function(e, t) {
					Public.tips({
						type: 1,
						content: "Type: " + t + "; Response: " + e.status + " " + e.statusText
					})
				}
			});
			$("#grid").jqGrid("setGridParam", {
				cellEdit: !0
			})
		},
		reloadData: function(e) {
			function t() {
				i.$_customer.data("contactInfo", {
					id: e.buId,
					name: e.contactName
				});
				i.customerCombo.input.val(e.contactName);
				i.$_date.val(e.date);
				i.$_number.text(e.billNo);
				i.$_note.val(e.note);
				i.$_userName.html(e.userName)
			}
			$("#grid").clearGridData();
			var i = this,
				a = 8 - e.entries.length;
			if (a > 0) for (var r = 0; a > r; r++) e.entries.push({});
			if ("edit" === e.status) {
				$("#grid").jqGrid("setGridParam", {
					data: e.entries,
					userData: {
						qty: e.totalQty,
						amount: e.totalAmount
					},
					cellEdit: !0,
					datatype: "clientSide"
				}).trigger("reloadGrid");
				t();
				if (!this.editable) {
					this.customerCombo.enable();
					this.$_date.removeAttr("disabled");
					this.editable = !0
				}
			} else {
				$("#grid").jqGrid("setGridParam", {
					url: "",
					datatype: "json",
					cellEdit: !1
				}).trigger("reloadGrid");
				t();
				if (this.editable) {
					this.customerCombo.disable();
					this.$_data.attr(disabled, "disabled");
					this.editable = !1
				}
			}
		},
		initCombo: function() {
			this.goodsCombo = Business.goodsCombo($(".goodsAuto"));
			Business.storageCombo($(".storageAuto"))
		},
		addEvent: function() {
			var e = this;
/*			this.customerCombo.input.enterKey();*/
			this.$_date.bind("keydown", function(e) {
				13 === e.which && $("#grid").jqGrid("editCell", 1, 2, !0)
			}).bind("focus", function() {
				e.dateValue = $(this).val()
			}).bind("blur", function() {
				var t = /((^((1[8-9]\d{2})|([2-9]\d{3}))(-)(10|12|0?[13578])(-)(3[01]|[12][0-9]|0?[1-9])$)|(^((1[8-9]\d{2})|([2-9]\d{3}))(-)(11|0?[469])(-)(30|[12][0-9]|0?[1-9])$)|(^((1[8-9]\d{2})|([2-9]\d{3}))(-)(0?2)(-)(2[0-8]|1[0-9]|0?[1-9])$)|(^([2468][048]00)(-)(0?2)(-)(29)$)|(^([3579][26]00)(-)(0?2)(-)(29)$)|(^([1][89][0][48])(-)(0?2)(-)(29)$)|(^([2-9][0-9][0][48])(-)(0?2)(-)(29)$)|(^([1][89][2468][048])(-)(0?2)(-)(29)$)|(^([2-9][0-9][2468][048])(-)(0?2)(-)(29)$)|(^([1][89][13579][26])(-)(0?2)(-)(29)$)|(^([2-9][0-9][13579][26])(-)(0?2)(-)(29)$))/;
				if (!t.test($(this).val())) {
					parent.Public.tips({
						type: 2,
						content: "日期格式有误！如：2012-08-08。"
					});
					$(this).val(e.dateValue)
				}
			});
			$(".grid-wrap").on("click", ".ui-icon-triangle-1-s", function() {
				setTimeout(function() {
					$(".storageAuto").trigger("click")
				}, 10)
			});
			Business.billsEvent(e, "otherWarehouse");
			$(".wrapper").on("click", "#save", function(t) {
				t.preventDefault();
				var i = THISPAGE.getPostData();
				if (i) {
					if ("edit" === originalData.stata) {
						i.id = originalData.id;
						i.stata = "edit"
					}
					//Public.ajaxPost("/scm/invOi.do?action=add&type=in", {
					Public.ajaxPost(invoi_in, {
						postData: JSON.stringify(i)
					}, function(t) {
						if (200 === t.status) {
							originalData.id = t.data.id;
							e.$_toolBottom.html('<a id="add" class="ui-btn ui-btn-sp mrb">新增</a><a id="edit" class="ui-btn mrb">保存</a><a href="/scm/invOi.do?action=toOiPdf&id=' + originalData.id + '" target="_blank" id="print" class="ui-btn">打印</a>');
							parent.Public.tips({
								content: "保存成功！"
							})
						} else parent.Public.tips({
							type: 1,
							content: t.msg
						})
					})
				}
			});
			$(".wrapper").on("click", "#edit", function(e) {
				e.preventDefault();
				if (Business.verifyRight("IO_UPDATE")) {
					var t = THISPAGE.getPostData();
					//t && Public.ajaxPost("/scm/invOi.do?action=updateOi&type=in", {
					t && Public.ajaxPost(invoi_inedit, {
						postData: JSON.stringify(t)
					}, function(e) {
						if (200 === e.status) {
							//originalData.id = e.data.id;
							parent.Public.tips({
								content: "修改成功！"
							})
						} else parent.Public.tips({
							type: 1,
							content: e.msg
						})
					})
				}
			});
			$(".wrapper").on("click", "#savaAndAdd", function(t) {
				t.preventDefault();
				var i = THISPAGE.getPostData();
				//i && Public.ajaxPost("/scm/invOi.do?action=addNew&type=in", {
				i && Public.ajaxPost(invoi_in+"?type=in", {					 
					postData: JSON.stringify(i)
				}, function(t) {
					if (200 === t.status) {
						e.$_number.text(t.data.billNo);
						$("#grid").clearGridData();
						$("#grid").clearGridData(!0);
						for (var i = 1; 8 >= i; i++) $("#grid").jqGrid("addRowData", i, {});
						e.newId = 9;
						e.$_note.val("");
						parent.Public.tips({
							content: "保存成功！"
						})
					} else parent.Public.tips({
						type: 1,
						content: t.msg
					})
				})
			});
			$(".wrapper").on("click", "#add", function(e) {
				e.preventDefault();
				Business.verifyRight("IO_ADD") && parent.tab.overrideSelectedTabItem({
					tabid: "storage-otherWarehouse",
					text: "入库",
					//url: "/scm/invOi.do?action=initOi&type=in"
					url: invoi_in
				})
			});
			$(".wrapper").on("click", "#print", function(e) {
				var id = this.getAttribute('rel');
				//alert(id);
				var url = api_invoi_print+'?id='+id;
				LODOP=getLodop();  
				LODOP.PRINT_INIT("打印控件功能演示_Lodop功能_按网址打印");
				LODOP.ADD_PRINT_URL(30,20,746,"100%",url);
				LODOP.SET_PRINT_STYLEA(0,"HOrient",3);
				LODOP.SET_PRINT_STYLEA(0,"VOrient",3);
		//		LODOP.SET_SHOW_MODE("MESSAGE_GETING_URL",""); //该语句隐藏进度条或修改提示信息
		//		LODOP.SET_SHOW_MODE("MESSAGE_PARSING_URL","");//该语句隐藏进度条或修改提示信息
				LODOP.PREVIEW();	
				return false											 
				Business.verifyRight("IO_PRINT") || e.preventDefault()
			});
			$("#prev").click(function(t) {
				t.preventDefault();
				if ($(this).hasClass("ui-btn-prev-dis")) {
					parent.Public.tips({
						type: 2,
						content: "已经没有上一张了！"
					});
					return !1
				}
				e.idPostion = e.idPostion - 1;
				0 === e.idPostion && $(this).addClass("ui-btn-prev-dis");
				loading = $.dialog.tips("数据加载中...", 1e3, "loading.gif", !0);
				//Public.ajaxGet("/scm/invOi.do?action=updateIn&type=in", {
				Public.ajaxGet(invoi_info+"?type=1", {			   
					id: e.salesListIds[e.idPostion]
				}, function(e) {
					THISPAGE.reloadData(e.data);
					$("#next").removeClass("ui-btn-next-dis");
					loading && loading.close()
				})
			});
			$("#next").click(function(t) {
				t.preventDefault();
				if ($(this).hasClass("ui-btn-next-dis")) {
					parent.Public.tips({
						type: 2,
						content: "已经没有下一张了！"
					});
					return !1
				}
				e.idPostion = e.idPostion + 1;
				e.idLength === e.idPostion + 1 && $(this).addClass("ui-btn-next-dis");
				loading = $.dialog.tips("数据加载中...", 1e3, "loading.gif", !0);
				//Public.ajaxGet("/scm/invOi.do?action=updateIn&type=in", {
				Public.ajaxGet(invoi_info+"?type=1", {			   
					id: e.salesListIds[e.idPostion]
				}, function(e) {
					THISPAGE.reloadData(e.data);
					$("#prev").removeClass("ui-btn-prev-dis");
					loading && loading.close()
				})
			})
		},
		resetData: function() {
			var e = this;
			$("#grid").clearGridData();
			for (var t = 1; 8 >= t; t++) {
				$("#grid").jqGrid("addRowData", t, {});
				$("#grid").jqGrid("footerData", "set", {
					qty: 0,
					amount: 0
				})
			}
			e.$_note.val("");
			e.$_discountRate.val(originalData.disRate);
			e.$_deduction.val(originalData.disAmount);
			e.$_discount.val(originalData.amount);
			e.$_payment.val(originalData.rpAmount);
			e.$_arrears.val(originalData.arrears)
		},
		calTotal: function() {
			for (var e = $("#grid").jqGrid("getDataIDs"), t = 0, i = 0, a = 0, r = e.length; r > a; a++) {
				var n = e[a],
					o = $("#grid").jqGrid("getRowData", n);
				o.qty && (t += parseFloat(o.qty));
				o.amount && (i += parseFloat(o.amount))
			}
			$("#grid").jqGrid("footerData", "set", {
				qty: t,
				amount: i
			})
		},
		
		
		_getEntriesData: function() {
			for (var e = [], t = $("#grid").jqGrid("getDataIDs"), i = 0, a = t.length; a > i; i++) {
				var r, n = t[i],
					o = $("#grid").jqGrid("getRowData", n);
				if ("" !== o.goods) {
					var s = $("#" + n).data("goodsInfo"),
						l = $("#" + n).data("storageInfo");
					r = {
						invId: s.id,
						invNumber: s.number,
						invName: s.name,
						invSpec: s.spec,
						unitId: s.unitId,
						mainUnit: s.unitName,
						qty: o.qty,
						price: o.price,
						amount: o.amount,
						description: o.description
						//locationId: l.id,
						//locationName: l.name
					};
					e.push(r)
				}
			}
			return e
		},
		getPostData: function() {
			var e = this,
				t = this;
			if (null !== curRow && null !== curCol) {
				$("#grid").jqGrid("saveCell", curRow, curCol);
				curRow = null;
				curCol = null
			}
/*			var i = t.$_customer.find("input");
			if ("" === i.val() || "(空)" === i.val()) {
				var a = {};
				a.id = 0;
				a.name = "(空)";
				t.$_customer.removeData("contactInfo")
			} else {
				var a = t.$_customer.data("contactInfo");
				if (null === a) {
					setTimeout(function() {
						i.focus().select()
					}, 15);
					parent.Public.tips({
						type: 2,
						content: "当前客户不存在！"
					});
					return !1
				}
			}*/
			var r = this._getEntriesData();
			if (r.length > 0) {
				e.calTotal();
				var n = {
					id: originalData.id,
					buId: 0,
					/*contactName: a.name,*/
					date: $.trim(e.$_date.val()),
					billNo: $.trim(e.$_number.text()),
					transTypeId: e.transTypeCombo.getValue(),
					transTypeName: e.transTypeCombo.getText(),
					entries: r,
					totalQty: $("#grid").jqGrid("footerData", "get").qty.replace(/,/g, ""),
					totalAmount: $("#grid").jqGrid("footerData", "get").amount.replace(/,/g, ""),
					description: $.trim(e.$_note.val())
				};
				return n
			}
			parent.Public.tips({
				type: 2,
				content: "物料信息不能为空！"
			});
			$("#grid").jqGrid("editCell", 1, 2, !0);
			return !1
		}
	},
	hasLoaded = !1,
	originalData;
if (urlParam.id) hasLoaded || Public.ajaxGet(invoi_info+"?type=1", {			
//if (urlParam.id) hasLoaded || Public.ajaxGet("/scm/invOi.do?action=updateIn&type=in", {
	id: urlParam.id
}, function(e) {
	if (200 === e.status) {
		originalData = e.data;
		THISPAGE.init(e.data);
		hasLoaded = !0
	} else parent.Public.tips({
		type: 1,
		content: msg
	})
});
else {
	originalData = {
		id: -1,
		status: "add",
		customer: 0,
		transType: 1,
		entries: [{
			id: "1"
		}, {
			id: "2"
		}, {
			id: "3"
		}, {
			id: "4"
		}, {
			id: "5"
		}, {
			id: "6"
		}, {
			id: "7"
		}, {
			id: "8"
		}],
		totalQty: 0,
		totalAmount: 0,
		disRate: 0,
		disAmount: 0,
		amount: "0.00",
		rpAmount: "0.00",
		arrears: "0.00"
	};
	THISPAGE.init(originalData)
}