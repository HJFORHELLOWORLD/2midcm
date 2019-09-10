var curRow, curCol, loading, SYSTEM = system = parent.SYSTEM,
	billRequiredCheck = system.billRequiredCheck,
	requiredMoney = system.requiredMoney,
	taxRequiredCheck = system.taxRequiredCheck,
	taxRequiredInput = system.taxRequiredInput,
	hiddenAmount = !1,
	hideCustomerCombo = !1,
	urlParam = Public.urlParam(),
	disEditable = urlParam.disEditable,
	qtyPlaces = Number(parent.SYSTEM.qtyPlaces),
	pricePlaces = Number(parent.SYSTEM.pricePlaces),
	amountPlaces = Number(parent.SYSTEM.amountPlaces),
	THISPAGE = {
		init: function(t) {
			if (SYSTEM.isAdmin === !1 && !SYSTEM.rights.AMOUNT_INAMOUNT) {
				hiddenAmount = !0;
				$("#amountArea").hide()
			}
			this.loadGrid(t);
			this.initDom(t);
			this.initCombo();
			if (t.id > 0 && t.checked) this.disableEdit();
			else {
				this.editable = !0;
				$("#grid").jqGrid("setGridParam", {
					cellEdit: !0
				})
			}
			this.addEvent()
		},
		initDom: function(t) {
			var e = this;
			this.$_customer = $("#customer");
			this.$_date = $("#date").val(system.endDate);
			this.$_number = $("#number");
			this.$_classes = $("#classes");
			this.$_note = $("#note");
			this.$_discountRate = $("#discountRate");
			this.$_deduction = $("#deduction");
			this.$_discount = $("#discount");
			this.$_payment = $("#payment");
			this.$_arrears = $("#arrears");
			this.$_totalArrears = $("#totalArrears");
			this.$_toolTop = $("#toolTop");
			this.$_toolBottom = $("#toolBottom");
			this.$_paymentTxt = $("#paymentTxt");
			this.$_accountInfo = $("#accountInfo");
			this.$_userName = $("#userName");
            this.$_orderName = $("#orderName");
            this.$_paymentType = $("#paymentType");
			this.customerArrears = 0;
			this.customerCombo = Business.supplierCombo($("#customer"), {});
			if ("add" !== t.status || t.buId) {
				this.$_customer.data("contactInfo", {
					id: t.buId,
					name: t.contactName
				});
				this.customerCombo.input.val(t.contactName)
			} else;
			hideCustomerCombo && this.customerCombo.disable();
			this.$_date.datepicker({
				onSelect: function(t) {
					var i = t.format("yyyy-MM-dd");
					//e.$_number.text("");
					//Public.ajaxPost("/basedata/systemProfile.do?action=generateDocNo", {
//						billType: "PUR",
//						billDate: i
//					}, function(t) {
//						200 === t.status ? e.$_number.text(t.data.billNo) : parent.Public.tips({
//							type: 1,
//							content: t.msg
//						})
//					})
				}
			});
			this.classes = this.$_classes.cssRadio({
				callback: function(t) {
					e.$_paymentTxt.text("1" === t.find("input").val() ? "本次付款:" : "本次退款:")
				}
			});
			this.classes.setValue(1 === t.transType ? 0 : 1);
			this.$_note.val(t.description);
			this.$_discountRate.val(t.disRate);
			this.$_deduction.val(t.disAmount);
			this.$_discount.val(t.amount);
			this.$_payment.val(t.rpAmount);
			this.$_arrears.val(t.arrears);
            this.$_orderName.val(t.orderName);
            this.$_paymentType.val(t.paymentType);
			if (requiredMoney) {
				$("#accountWrap").show();
				this.accountCombo = SYSTEM.isAdmin !== !1 || SYSTEM.rights.SettAcct_QUERY ? Business.accountCombo($("#account"), {
					width: 200,
					height: 300,
					emptyOptions: !0,
					addOptions: {
						text: "多账户",
						value: -1
					},
					defaultSelected: ["id", t.accId],
					callback: {
						onChange: function() {
							if (-1 === this.getValue()) e.chooseAccount();
							else {
								var t = [];
								t.push({
									accId: this.getValue(),
									account: "",
									payment: e.$_payment.val(),
									wayId: 0,
									way: "",
									settlement: ""
								});
								e.$_accountInfo.data("accountInfo", t).hide();
								e.$_payment.removeAttr("disabled").removeClass("ui-input-dis")
							}
						}
					}
				}) : Business.accountCombo($("#account"), {
					width: 200,
					height: 300,
					data: [],
					editable: !1,
					disabled: !0,
					addOptions: {
						text: "(没有账户管理权限)",
						value: 0
					}
				})
			}//<a id="save" class="ui-btn">保存</a>
			var i = '<a id="savaAndAdd" class="ui-btn ui-btn-sp">保存并新增</a>',
				//a = '<a id="add" class="ui-btn ui-btn-sp">新增</a><a id="edit" class="ui-btn">保存</a><a href="/scm/invPu.do?action=toPdf&id=' + t.id + '" target="_blank" id="print" class="ui-btn">打印</a>',
				//r = '<a id="add" class="ui-btn ui-btn-sp">新增</a><a href="/scm/invPu.do?action=toPdf&id=' + t.id + '" target="_blank" id="print" class="ui-btn">打印</a>',
				//a = '<a id="add" class="ui-btn ui-btn-sp">新增</a><a id="edit" class="ui-btn">保存</a><a href="#" target="_blank" id="print" rel="'+ t.id +'" class="ui-btn">打印</a>',
                a = '<a id="edit" class="ui-btn">保存</a>',//<a href="#" rel="'+ t.id +'" target="_blank" id="print" class="ui-btn">打印</a>
				r = '<a id="add" class="ui-btn ui-btn-sp">新增</a>',//<a href="#" rel="'+ t.id +'" target="_blank" id="print" class="ui-btn">打印</a>
				n = "",
				o = "";
			if (billRequiredCheck) {
				n = '<a class="ui-btn" id="audit">审核</a>';
				o = '<a class="ui-btn" id="reAudit">反审核</a>'
			}
			var s = '<a class="ui-btn-prev" id="prev" title="上一张"><b></b></a><a class="ui-btn-next" id="next" title="下一张"><b></b></a>';
			this.btn_edit = a;
			this.btn_audit = n;
			this.btn_view = r;
			this.btn_reaudit = o;
			if (t.id.length > 0) {
				this.$_number.text(t.billNo);
				this.$_date.val(t.date);
				this.$_totalArrears.val(t.totalArrears);
				this.$_accountInfo.data("accountInfo", t.accounts);
				if (-1 === t.accId) {
					this.$_accountInfo.show();
					e.$_payment.attr("disabled", "disabled").addClass("ui-input-dis")
				}
				$("#grid").jqGrid("footerData", "set", {
					qty: t.totalQty,
					amount: t.totalAmount
				});
				"list" !== urlParam.flag && (s = "");
				if ("edit" === t.status) this.$_toolBottom.html("<span id=groupBtn>" + a + n + "</span>" + s);
				else if (t.checked) {
					$("#mark").addClass("has-audit");
					this.$_toolBottom.html('<span id="groupBtn">' + r + o + "</span>" + s)
				} else this.$_toolBottom.html('<span id="groupBtn">' + r + "</span>" + s);
				this.idList = parent.cacheList.purchaseId || [];
				this.idPostion = $.inArray(String(t.id), this.idList);
				this.idLength = this.idList.length;
				0 === this.idPostion && $("#prev").addClass("ui-btn-prev-dis");
				this.idPostion === this.idLength - 1 && $("#next").addClass("ui-btn-next-dis");
				this.$_userName.html(t.userName)
			} else {
				this.$_toolBottom.html(billRequiredCheck ? "<span id=groupBtn>" + i + n + "</span>" : '<span id="groupBtn">' + i + "</span>");
				this.$_userName.html(system.realName || "")
			}
			if (disEditable) {
				THISPAGE.disableEdit();
				this.$_toolBottom.hide()
			}
		},
		loadGrid: function(t) {
			function e(t) {
				if (taxRequiredCheck) {
					var e = $("#grid").jqGrid("getRowData", t),
						i = parseFloat(e.taxRate);
					if ($.isNumeric(i)) {
						var a = parseFloat(e.amount),
							r = a * i / 100,
							n = a + r;
						$("#grid").jqGrid("setRowData", t, {
							tax: r,
							taxAmount: n
						})
					}
				}
			}
			function i(t, e, i) {
				return t ? t : i.invNumber ? i.invSpec ? i.invNumber + " " + i.invName + "_" + i.invSpec : i.invNumber + " " + i.invName : "&#160;"
			}
			function a() {
				var t = $(".goodsAuto")[0];
				return t
			}
			function r(t, e, i) {
				if ("get" === e) {
					if ("" !== $(".goodsAuto").getCombo().getValue()) return $(t).val();
					var a = $(t).parents("tr");
					a.removeData("goodsInfo");
					return ""
				}
				"set" === e && $("input", t).val(i)
			}
			function n() {
				$("#initCombo").append($(".goodsAuto").val("").unbind("focus.once"))
			}
			function o() {
				var t = $(".storageAuto")[0];
				return t
			}
			function s(t, e, i) {
				if ("get" === e) {
					if ("" !== $(".storageAuto").getCombo().getValue()) return $(t).val();
					var a = $(t).parents("tr");
					a.removeData("storageInfo");
					return ""
				}
				"set" === e && $("input", t).val(i)
			}
			function l() {
				$("#initCombo").append($(".storageAuto").val(""))
			}
			var d = this;
			if (t.id) {
				var c = 8 - t.entries.length;
				if (c > 0) for (var u = 0; c > u; u++) t.entries.push({})
			}
			d.newId = 9;
			var p = !1;
			1 === SYSTEM.siType && (p = !0);
			var h = 1190,
				f = [{
					name: "operating",
					label: "操作",
					width: 40,
					fixed: !0,
					formatter: Public.billsOper,
					align: "center"
				}, {
					name: "goods",
					label: "物品",
					width: 200,
					classes: "ui-ellipsis",
					formatter: i,
					editable: !0,
					edittype: "custom",
					editoptions: {
						custom_element: a,
						custom_value: r,
						handle: n,
						trigger: "ui-icon-ellipsis"
					}
				}, {
                    name: "bomModel",
                    label: "型号",
                    width: 100,
                    title: !1
                }, {
					name: "mainUnit",
					label: "单位",
					width: 60,
					title: !1
				}, {
					name: "qty",
					label: "采购量",
					width: 80,
					align: "right",
					formatter: "number",
					formatoptions: {
						decimalPlaces: qtyPlaces
					},
					editable: !0
				}, {
					name: "price",
					label: "采购单价",
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
				}/*, {
					name: "discountRate",
					label: "折扣率(%)",
					hidden: hiddenAmount,
					width: 70,
					fixed: !0,
					align: "right",
					formatter: "integer",
					editable: !0
				}, {
					name: "deduction",
					label: "折扣额",
					hidden: hiddenAmount,
					width: 70,
					fixed: !0,
					align: "right",
					formatter: "currency",
					formatoptions: {
						showZero: !0,
						decimalPlaces: amountPlaces
					},
					editable: !0
				}*/, {
					name: "amount",
					label: "采购小计",
					hidden: hiddenAmount,
					width: 100,
					fixed: !0,
					align: "right",
					formatter: "currency",
					formatoptions: {
						showZero: !0,
						decimalPlaces: amountPlaces
					},
					editable: !1
				}];
			this.calAmount = "amount";
			if (taxRequiredCheck) {
				if (hiddenAmount === !1) {
					h = h + 70 + 70 + 100;
					$(".bills").width(h + 2)
				}
				f.pop();
				f.push({
					name: "amount",
					label: "金额",
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
				}, {
					name: "taxRate",
					label: "税率(%)",
					hidden: hiddenAmount,
					width: 70,
					fixed: !0,
					align: "right",
					formatter: "integer",
					editable: !0
				}, {
					name: "tax",
					label: "税额",
					hidden: hiddenAmount,
					width: 70,
					fixed: !0,
					align: "right",
					formatter: "currency",
					formatoptions: {
						showZero: !0,
						decimalPlaces: amountPlaces
					},
					editable: !0
				}, {
					name: "taxAmount",
					label: "价税合计",
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
				});
				this.calAmount = "taxAmount"
			}
			f.push(
				////   {
//				name: "locationName",
//				label: '仓库<small id="batchStorage">(批量)</small>',
//				width: 100,
//				editable: !0,
//				edittype: "custom",
//				editoptions: {
//					custom_element: o,
//					custom_value: s,
//					handle: l,
//					trigger: "ui-icon-triangle-1-s"e
//				}
//			}, 
			{
				name: "description",
				label: "商品备注",
				width: 150,
				classes: "ui-ellipsis",
				title: !0,
				editable: !0,
                hidden: !0
			}, {
				name: "srcOrderEntryId",
				label: "源单分录ID",
				width: 0,
				hidden: !0
			}, {
				name: "srcOrderId",
				label: "源单ID",
				width: 0,
				hidden: !0
			}, {
				name: "srcOrderNo",
				label: "源单号",
				width: 120,
				fixed: !0,
				hidden: p,
				formatter: function(t) {
					t && (hideCustomerCombo = !0);
					return t || "&#160;"
				}
			});
			$("#grid").jqGrid({
				data: t.entries,
				datatype: "clientSide",
				width: h,
				height: "100%",
				rownumbers: !0,
				gridview: !0,
				onselectrow: !1,
				colModel: f,
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
				loadComplete: function(t) {
					if (urlParam.id) {
						var e = t.rows,
							i = e.length;
						d.newId = i + 1;

						for (var a = 0; i > a; a++) {
							var r = a + 1,
								n = e[a];
							if ($.isEmptyObject(e[a])) break;
							$("#" + r).data("goodsInfo", {
								id: n.PK_BOM_ID,
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
				gridComplete: function() {},
				afterEditCell: function(t, e, i, a) {
					if ("goods" === e) {
						$("#" + a + "_logistics", "#grid").val(i);
						THISPAGE.goodsCombo.selectByText(i);
						THISPAGE.curID = t
					}
					"locationName" === e && $("#" + a + "_locationName", "#grid").val(i)
				},
				formatCell: function() {},
				beforeSubmitCell: function() {},
				afterSaveCell: function(t, i, a, r, n) {
					switch (i) {
					case "goods":
						var o = $("#" + t).data("goodsInfo");
						if (o) {
							var s = o.purPrice,
								l = {
									mainUnit: o.unitName,
									qty: 1,
									price: o.purPrice,
									discountRate: 0,
									deduction: 0,
									amount: o.purPrice,
									locationName: o.localtionName
								};
							if (taxRequiredCheck) {
								var d = taxRequiredInput,
									c = s * d / 100,
									u = s + c;
								l.taxRate = d;
								l.tax = c;
								l.taxAmount = u
							}
							var p = $("#grid").jqGrid("setRowData", t, l);
							p && THISPAGE.calTotal()
						}
						break;
					case "qty":
						var a = parseFloat(a),
							h = parseFloat($("#grid").jqGrid("getCell", t, n + 1)),
							f = parseFloat($("#grid").jqGrid("getCell", t, n + 2));
						if ($.isNumeric(h)) if ($.isNumeric(f)) var g = a * h * f / 100,
							s = a * h,
							p = $("#grid").jqGrid("setRowData", t, {
								deduction: g,
								amount: s
							});
						else var p = $("#grid").jqGrid("setRowData", t, {
							amount: a * h
						});
						e(t);
						p && THISPAGE.calTotal();
						break;
					case "price":
						var a = parseFloat(a),
							m = parseFloat($("#grid").jqGrid("getCell", t, n - 1)),
							f = parseFloat($("#grid").jqGrid("getCell", t, n + 1));
						if ($.isNumeric(m)) if ($.isNumeric(f)) var g = a * m * f / 100,
							s = a * m ,
							p = $("#grid").jqGrid("setRowData", t, {
								amount: s
							});
						else var p = $("#grid").jqGrid("setRowData", t, {
							amount: a * m
						});
						e(t);
						p && THISPAGE.calTotal();
						break;
					case "discountRate":
						var a = parseFloat(a),
							m = parseFloat($("#grid").jqGrid("getCell", t, n - 2)),
							h = parseFloat($("#grid").jqGrid("getCell", t, n - 1));
						if ($.isNumeric(m) && $.isNumeric(h)) var v = m * h,
							g = v * a / 100,
							s = v - g,
							p = $("#grid").jqGrid("setRowData", t, {
								deduction: g,
								amount: s
							});
						e(t);
						p && THISPAGE.calTotal();
						break;
					case "deduction":
						var a = parseFloat(a),
							m = parseFloat($("#grid").jqGrid("getCell", t, n - 3)),
							h = parseFloat($("#grid").jqGrid("getCell", t, n - 2));
						if ($.isNumeric(m) && $.isNumeric(h)) var v = m * h,
							s = v - a,
							f = v ? (100 * a / v).toFixed(amountPlaces) : 0,
							p = $("#grid").jqGrid("setRowData", t, {
								discountRate: f,
								amount: s
							});
						e(t);
						p && THISPAGE.calTotal();
						break;
					case "amount":
						var a = parseFloat(a),
							b = $("#grid").jqGrid("getRowData", t);
						if ($.isNumeric(a)) {
							var g = parseFloat(b.deduction),
								y = parseFloat(b.qty),
								h = (a + g) / y;
							if ($.isNumeric(y) && $.isNumeric(h)) {
								var v = y * h,
									f = v ? (100 * g / v).toFixed(amountPlaces) : 0;
								$("#grid").jqGrid("setRowData", t, {
									discountRate: f
								})
							}
							$("#grid").jqGrid("setRowData", t, {
								discountRate: f,
								price: h
							})
						}
						e(t);
						THISPAGE.calTotal();
						break;
					case "taxRate":
						var D = a,
							a = parseFloat(a),
							b = $("#grid").jqGrid("getRowData", t),
							s = parseFloat(b.amount);
						if ($.isNumeric(a)) {
							var c = s * a / 100,
								u = s + c,
								p = $("#grid").jqGrid("setRowData", t, {
									tax: c,
									taxAmount: u
								});
							p && THISPAGE.calTotal();
							break
						}
						if ("" === D) {
							var p = $("#grid").jqGrid("setRowData", t, {
								tax: "",
								taxAmount: s
							});
							p && THISPAGE.calTotal();
							break
						}
					case "tax":
						var a = parseFloat(a),
							b = $("#grid").jqGrid("getRowData", t);
						if ($.isNumeric(a)) {
							var s = parseFloat(b.amount),
								u = s + a,
								p = $("#grid").jqGrid("setRowData", t, {
									taxAmount: u
								});
							p && THISPAGE.calTotal()
						}
						break;
					case "taxAmount":
						var a = parseFloat(a),
							b = $("#grid").jqGrid("getRowData", t);
						if ($.isNumeric(a)) {
							var g = parseFloat(b.deduction),
								d = parseFloat(b.taxRate) / 100,
								s = a / (1 + d),
								y = parseFloat(b.qty),
								f = (100 * g / (s + g)).toFixed(amountPlaces),
								h = (s + g) / y,
								c = a - s,
								p = $("#grid").jqGrid("setRowData", t, {
									discountRate: f,
									price: h,
									amount: s,
									tax: c
								});
							p && THISPAGE.calTotal()
						}
					}
				},
				loadonce: !0,
				footerrow: !0,
				userData: {
					goods: "合计：",
					qty: t.totalQty,
					deduction: t.totalDiscount,
					amount: t.totalAmount,
					tax: t.totalTax,
					taxAmount: t.totalTaxAmount
				},
				userDataOnFooter: !0,
				loadError: function(t, e) {
					Public.tips({
						type: 1,
						content: "Type: " + e + "; Response: " + t.status + " " + t.statusText
					})
				}
			});
			$("#grid").jqGrid("setGridParam", {
				cellEdit: !0
			})
		},
		reloadData: function(t) {
			function e() {
				i.$_customer.data("contactInfo", {
					id: t.buId,
					name: t.contactName
				});
				i.customerCombo.input.val(t.contactName);
				i.$_date.val(t.date);
				i.$_number.text(t.PK_BOM_Pur_ID);
				i.classes.setValue(1 === t.transType ? 0 : 1);
				i.$_note.val(t.description);
				i.$_discountRate.val(t.disRate);
				i.$_deduction.val(t.disAmount);
				i.$_discount.val(t.amount);
				i.$_payment.val(t.rpAmount);
				//i.accountCombo.selectByValue(t.accId, !1);
				//i.$_accountInfo.data("accountInfo", t.accounts); - 1 === t.accId ? i.$_accountInfo.show() : i.$_accountInfo.hide();
				i.$_arrears.val(t.arrears);
				i.$_totalArrears.val(t.totalArrears);
				i.$_userName.html(t.userName);
                i.$_orderName.val(t.orderName);
                i.$_paymentType.val(t.paymentType);
			}
			$("#grid").clearGridData();
			var i = this;
			originalData = t;
			var a = 8 - t.entries.length;
			if (a > 0) for (var r = 0; a > r; r++) t.entries.push({});
			$("#grid").jqGrid("setGridParam", {
				data: t.entries,
				userData: {
					qty: t.totalQty,
					deduction: t.totalDiscount,
					amount: t.totalAmount,
					tax: t.totalTax,
					taxAmount: t.totalTaxAmount
				}
			}).trigger("reloadGrid");
			e();
			if ("edit" === t.status) {
				if (!this.editable) {
					i.enableEdit();
					$("#groupBtn").html(i.btn_edit + i.btn_audit);
					$("#mark").removeClass("has-audit")
				}
			} else if (this.editable) {
				i.disableEdit();
				$("#groupBtn").html(i.btn_view + i.btn_reaudit);
				$("#mark").addClass("has-audit")
			}
		},
		initCombo: function() {
			this.goodsCombo = Business.goodsCombo($(".goodsAuto"));
			Business.storageCombo($(".storageAuto"))
		},
		disableEdit: function() {
			this.customerCombo.disable();
			this.$_date.attr("disabled", "disabled").addClass("ui-input-dis");
			this.$_note.attr("disabled", "disabled").addClass("ui-input-dis");
			this.$_discountRate.attr("disabled", "disabled").addClass("ui-input-dis");
			this.$_deduction.attr("disabled", "disabled").addClass("ui-input-dis");
			this.$_payment.attr("disabled", "disabled").addClass("ui-input-dis");
			this.accountCombo.disable();
			$("#grid").jqGrid("setGridParam", {
				cellEdit: !1
			});
			this.editable = !1
		},
		enableEdit: function() {
			if (!disEditable) {
				!hideCustomerCombo && this.customerCombo.enable();
				this.$_date.removeAttr("disabled").removeClass("ui-input-dis");
				this.$_note.removeAttr("disabled").removeClass("ui-input-dis");
				this.$_discountRate.removeAttr("disabled").removeClass("ui-input-dis");
				this.$_deduction.removeAttr("disabled").removeClass("ui-input-dis");
				this.$_payment.removeAttr("disabled").removeClass("ui-input-dis");
				this.accountCombo.enable();
				$("#grid").jqGrid("setGridParam", {
					cellEdit: !0
				});
				this.editable = !0
			}
		},
		chooseAccount: function(t) {
			var e = this;
			e.$_accountInfo.show();
			e.$_payment.attr("disabled", "disabled").addClass("ui-input-dis");
			$.dialog({
				width: 670,
				height: 250,
				title: "多账户结算",
				content: "url:/settings/choose-account.jsp",
				data: {
					accountInfo: t,
					type: "purchase"
				},
				lock: !0,
				ok: function() {
					var t = this.content.callback();
					if (!t) return !1;
					e.$_payment.val(t.payment).trigger("keyup");
					e.$_accountInfo.data("accountInfo", t.accounts);
					e.accountCombo.blur()
				},
				cancel: function() {
					var t = e.$_accountInfo.data("accountInfo");
					t ? 1 === t.length && e.accountCombo.selectByValue(t[0].accId) : e.accountCombo.selectByValue(0)
				}
			})
		},
		addEvent: function() {
			var t = this;
			this.customerCombo.input.enterKey();
			this.$_date.bind("keydown", function(t) {
				13 === t.which && $("#grid").jqGrid("editCell", 1, 2, !0)
			}).bind("focus", function() {
				t.dateValue = $(this).val()
			}).bind("blur", function() {
				var e = /((^((1[8-9]\d{2})|([2-9]\d{3}))(-)(10|12|0?[13578])(-)(3[01]|[12][0-9]|0?[1-9])$)|(^((1[8-9]\d{2})|([2-9]\d{3}))(-)(11|0?[469])(-)(30|[12][0-9]|0?[1-9])$)|(^((1[8-9]\d{2})|([2-9]\d{3}))(-)(0?2)(-)(2[0-8]|1[0-9]|0?[1-9])$)|(^([2468][048]00)(-)(0?2)(-)(29)$)|(^([3579][26]00)(-)(0?2)(-)(29)$)|(^([1][89][0][48])(-)(0?2)(-)(29)$)|(^([2-9][0-9][0][48])(-)(0?2)(-)(29)$)|(^([1][89][2468][048])(-)(0?2)(-)(29)$)|(^([2-9][0-9][2468][048])(-)(0?2)(-)(29)$)|(^([1][89][13579][26])(-)(0?2)(-)(29)$)|(^([2-9][0-9][13579][26])(-)(0?2)(-)(29)$))/;
				if (!e.test($(this).val())) {
					parent.Public.tips({
						type: 2,
						content: "日期格式有误！如：2012-08-08。"
					});
					$(this).val(t.dateValue)
				}
			});
			this.$_note.enterKey();
			this.$_discount.enterKey();
			this.$_discountRate.enterKey();
			$(".grid-wrap").on("click", ".ui-icon-triangle-1-s", function() {
				setTimeout(function() {
					$(".storageAuto").trigger("click")
				}, 10)
			});
			Business.billsEvent(t, "purchase");
			this.$_deduction.keyup(function() {
				var e = Number($(this).val()),
					i = Number($("#grid").jqGrid("footerData", "get")[t.calAmount].replace(/,/g, "")),
					a = (i - e).toFixed(amountPlaces);
				if (i) {
					var r = e / i * 100,
						n = a - Number($.trim(t.$_payment.val()));
					THISPAGE.$_discountRate.val(r.toFixed(amountPlaces));
					THISPAGE.$_discount.val(a);
					THISPAGE.$_arrears.val(n)
				}
			}).on("keypress", function(t) {
				Public.numerical(t)
			}).on("focus", function() {
				this.select()
			});
			this.$_discountRate.keyup(function() {
				var e = Number($(this).val()),
					i = Number($("#grid").jqGrid("footerData", "get")[t.calAmount].replace(/,/g, "")),
					a = i * (e / 100),
					r = a.toFixed(amountPlaces),
					n = (i - r).toFixed(amountPlaces),
					o = n - Number($.trim(t.$_payment.val()));
				THISPAGE.$_deduction.val(r);
				THISPAGE.$_discount.val(n);
				THISPAGE.$_arrears.val(o)
			}).on("keypress", function(t) {
				Public.numerical(t)
			}).on("focus", function() {
				this.select()
			});
			this.$_payment.keyup(function() {
				var e = $(this).val() || 0,
					i = t.$_discount.val(),
					a = Number(parseFloat(i) - parseFloat(e)),
					r = Number(a + THISPAGE.customerArrears);
				THISPAGE.$_arrears.val(a.toFixed(amountPlaces));
				THISPAGE.$_totalArrears.val(r.toFixed(amountPlaces));
				var n = t.$_accountInfo.data("accountInfo");
				n && 1 === n.length && (n[0].payment = e)
			}).on("keypress", function(t) {
				Public.numerical(t)
			}).on("focus", function() {
				this.select()
			});
			$(".wrapper").on("click", "#save", function(e) {
				e.preventDefault();
				var i = THISPAGE.getPostData();
				if (i) {
					if ("edit" === originalData.stata) {
						i.id = originalData.id;
						i.stata = "edit"
					}
					//Public.ajaxPost("/scm/invPu.do?action=add", {
					Public.ajaxPost(invpu_add+"?act=add", {		
						postData: JSON.stringify(i)
					}, function(e) {
						if (200 === e.status) {
							originalData.id = e.data.id;
							t.$_toolBottom.html(billRequiredCheck ? '<span id="groupBtn">' + t.btn_edit + t.btn_audit + "</span>" : '<span id="groupBtn">' + t.btn_edit + "</span>");
							parent.Public.tips({
								content: "保存成功！"
							})
						} else parent.Public.tips({
							type: 1,
							content: e.msg
						})
					})
				}
			});
			$(".wrapper").on("click", "#edit", function(t) {
				t.preventDefault();
				if (Business.verifyRight("PU_UPDATE")) {
					var e = THISPAGE.getPostData();
					e && Public.ajaxPost(invpu_edit, {	
					//e && Public.ajaxPost("/scm/invPu.do?action=updateInvPu", {
						postData: JSON.stringify(e)
					}, function(t) {
						if (200 === t.status) {
							originalData.id = t.data.id;
							parent.Public.tips({
								content: "修改成功！"
							});
                            window.location.reload(true);
						} else parent.Public.tips({
							type: 1,
							content: t.msg
						})
					})
				}
			});
			$(".wrapper").on("click", "#audit", function(e) {
				e.preventDefault();
				if (Business.verifyRight("PU_CHECK")) {
					var i = THISPAGE.getPostData();
					i && Public.ajaxPost("/scm/invPu.do?action=checkInvPu", {
						postData: JSON.stringify(i)
					}, function(e) {
						if (200 === e.status) {
							originalData.id = e.data.id;
							$("#mark").addClass("has-audit");
							$("#edit").hide();
							t.disableEdit();
							$("#groupBtn").html(t.btn_view + t.btn_reaudit);
							parent.Public.tips({
								content: "审核成功！"
							})
						} else parent.Public.tips({
							type: 1,
							content: e.msg
						})
					})
				}
			});
			$(".wrapper").on("click", "#reAudit", function(e) {
				e.preventDefault();
				if (Business.verifyRight("PU_UNCHECK")) {
					var i = THISPAGE.getPostData();
					i && Public.ajaxPost("/scm/invPu.do?action=revsCheckInvPu", {
						postData: JSON.stringify(i)
					}, function(e) {
						if (200 === e.status) {
							$("#mark").removeClass();
							$("#edit").show();
							t.enableEdit();
							$("#groupBtn").html(t.btn_edit + t.btn_audit);
							parent.Public.tips({
								content: "反审核成功！"
							})
						} else parent.Public.tips({
							type: 1,
							content: e.msg
						})
					})
				}
			});
			$(".wrapper").on("click", "#savaAndAdd", function(e) {
				e.preventDefault();
				var i = THISPAGE.getPostData();
				//i && Public.ajaxPost("/scm/invPu.do?action=addNew", {
				i && Public.ajaxPost(invpu_add+"?act=addnew", {
					postData: JSON.stringify(i)
				}, function(e) {
					if (200 === e.status) {
/*						t.$_number.text(e.data.billNo);
						$("#grid").clearGridData();
						$("#grid").clearGridData(!0);
						for (var i = 1; 8 >= i; i++) $("#grid").jqGrid("addRowData", i, {});
						t.newId = 9;
						t.$_note.val("");
						t.$_discountRate.val(originalData.disRate);
						t.$_deduction.val(originalData.disAmount);
						t.$_discount.val(originalData.amount);
						t.$_payment.val(originalData.rpAmount);
						t.$_arrears.val(originalData.arrears);*/
						//t.accountCombo.selectByValue(0, !0);
						parent.Public.tips({
							content: "保存成功！"
						});
                        window.location.reload(true);
					} else parent.Public.tips({
						type: 1,
						content: e.msg
					})
				})
			});
			$(".wrapper").on("click", "#add", function(t) {
				t.preventDefault();
				Business.verifyRight("PU_ADD") && parent.tab.overrideSelectedTabItem({
					tabid: "purchase-purchase",
					text: "购货单",
					//url: "/scm/invPu.do?action=initPur"
					url: invpu_add
				})
			});
			$(".wrapper").on("click", "#print", function(t) {
				t.preventDefault();
				var id = this.getAttribute('rel');
				//alert(id);
				var url = api_invpu_print+'?id='+id;
				LODOP=getLodop();  
				LODOP.PRINT_INIT("打印控件功能演示_Lodop功能_按网址打印");
				LODOP.ADD_PRINT_URL(10,20,746,"100%",url);
				LODOP.SET_PRINT_STYLEA(0,"HOrient",3);
				LODOP.SET_PRINT_STYLEA(0,"VOrient",3);
				LODOP.PRINT();      //直接打印
		//		LODOP.SET_SHOW_MODE("MESSAGE_GETING_URL",""); //该语句隐藏进度条或修改提示信息
		//		LODOP.SET_SHOW_MODE("MESSAGE_PARSING_URL","");//该语句隐藏进度条或修改提示信息
				//LODOP.PREVIEW();	
				return false
				
				Business.verifyRight("PU_PRINT") && Public.print({
					title: "购货单列表",
					$grid: $("#grid"),
					pdf: "/scm/invPu.do?action=toPdf",
					billType: 10101,
					filterConditions: {
						id: originalData.id
					}
				})
			});
			this.$_accountInfo.click(function() {
				var e = $(this).data("accountInfo");
				t.chooseAccount(e)
			});
			$("#prev").click(function(e) {
				e.preventDefault();
				if ($(this).hasClass("ui-btn-prev-dis")) {
					parent.Public.tips({
						type: 2,
						content: "已经没有上一张了！"
					});
					return !1
				}
				t.idPostion = t.idPostion - 1;
				0 === t.idPostion && $(this).addClass("ui-btn-prev-dis");
				loading = $.dialog.tips("数据加载中...", 1e3, "loading.gif", !0);
				//Public.ajaxGet("/scm/invPu.do?action=update", {
				Public.ajaxGet(invpu_info, {
					id: t.idList[t.idPostion]
				}, function(e) {
					originalData.id = t.idList[t.idPostion];
					THISPAGE.reloadData(e.data);
					$("#next").removeClass("ui-btn-next-dis");
					loading && loading.close()
				})
			});
			$("#next").click(function(e) {
				e.preventDefault();
				if ($(this).hasClass("ui-btn-next-dis")) {
					parent.Public.tips({
						type: 2,
						content: "已经没有下一张了！"
					});
					return !1
				}
				t.idPostion = t.idPostion + 1;
				t.idLength === t.idPostion + 1 && $(this).addClass("ui-btn-next-dis");
				loading = $.dialog.tips("数据加载中...", 1e3, "loading.gif", !0);
				//Public.ajaxGet("/scm/invPu.do?action=update", {
				Public.ajaxGet(invpu_info, { 
					id: t.idList[t.idPostion]
				}, function(e) {
					originalData.id = t.idList[t.idPostion];
					THISPAGE.reloadData(e.data);
					$("#prev").removeClass("ui-btn-prev-dis");
					loading && loading.close()
				})
			})
		},
		resetData: function() {
			var t = this;
			$("#grid").clearGridData();
			for (var e = 1; 8 >= e; e++) {
				$("#grid").jqGrid("addRowData", e, {});
				$("#grid").jqGrid("footerData", "set", {
					qty: 0,
					amount: 0
				})
			}
			t.$_note.val("");
			t.$_discountRate.val(originalData.disRate);
			t.$_deduction.val(originalData.disAmount);
			t.$_discount.val(originalData.amount);
			t.$_payment.val(originalData.rpAmount);
			t.$_arrears.val(originalData.arrears)
		},
		calTotal: function() {
			for (var t = $("#grid").jqGrid("getDataIDs"), e = 0, i = 0, a = 0, r = 0, n = 0, o = 0, s = t.length; s > o; o++) {
				var l = t[o],
					d = $("#grid").jqGrid("getRowData", l);
				d.qty && (e += parseFloat(d.qty));
				d.deduction && (i += parseFloat(d.deduction));
				d.amount && (a += parseFloat(d.amount));
				d.tax && (r += parseFloat(d.tax));
				d.taxAmount && (n += parseFloat(d.taxAmount))
			}
			$("#grid").jqGrid("footerData", "set", {
				qty: e,
				deduction: i,
				amount: a,
				tax: r,
				taxAmount: n
			});
			if (taxRequiredCheck) var c = (n - Number(this.$_deduction.val())).toFixed(2);
			else var c = (a - Number(this.$_deduction.val())).toFixed(2);
			var u = (c - Number(this.$_payment.val())).toFixed(2);
			this.$_discount.val(c);
			this.$_arrears.val(u)
		},
		_getEntriesData: function() {
			for (var t = [], e = $("#grid").jqGrid("getDataIDs"), i = 0, a = e.length; a > i; i++) {
				var r, n = e[i],
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
						discountRate: o.discountRate,
						deduction: o.deduction,
						amount: o.amount,
						locationId: l.id,
						locationName: l.name,
						description: o.description,
						srcOrderEntryId: o.srcOrderEntryId,
						srcOrderId: o.srcOrderId,
						srcOrderNo: o.srcOrderNo
					};
					if (taxRequiredCheck) {
						r.taxRate = o.taxRate;
						r.tax = o.tax;
						r.taxAmount = o.taxAmount
					}
					t.push(r)
				}
			}
			return t
		},
		getPostData: function() {
			var t = this,
				e = this;
			if (null !== curRow && null !== curCol) {
				$("#grid").jqGrid("saveCell", curRow, curCol);
				curRow = null;
				curCol = null
			}
			var i = e.$_customer.find("input");
			if ("" === i.val()) {
				e.$_customer.removeData("contactInfo");
				parent.Public.tips({
					type: 2,
					content: "请选择供应商！"
				});
				return !1
			}
			var a = e.$_customer.data("contactInfo");
			if (!a || !a.id) {
				setTimeout(function() {
					i.focus().select()
				}, 15);
				parent.Public.tips({
					type: 2,
					content: "当前供应商不存在！"
				});
				return !1
			}
			var r = this._getEntriesData();
			if (r.length > 0) {
				var n = {
					id: originalData.id,
					buId: a.id,
					contactName: a.name,
					date: $.trim(t.$_date.val()),
					billNo: $.trim(t.$_number.text()),
					transType: t.classes.getValue(),
					entries: r,
					totalQty: $("#grid").jqGrid("footerData", "get").qty.replace(/,/g, ""),
					totalAmount: $("#grid").jqGrid("footerData", "get").amount.replace(/,/g, ""),
					description: $.trim(t.$_note.val()),
					disRate: $.trim(t.$_discountRate.val()),
					disAmount: $.trim(t.$_deduction.val()),
					amount: $.trim(t.$_discount.val()),
					rpAmount: $.trim(t.$_payment.val()),
					arrears: $.trim(t.$_arrears.val()),
                    orderName: $.trim(t.$_orderName.val()),
                    paymentType: $.trim(t.$_paymentType.val()),
					totalArrears: ""
				};
				if (taxRequiredCheck) {
					n.totalTax = $("#grid").jqGrid("footerData", "get").tax.replace(/,/g, "");
					n.totalTaxAmount = $("#grid").jqGrid("footerData", "get").taxAmount.replace(/,/g, "")
				}
				if (requiredMoney) {
					n.accId = t.accountCombo.getValue();
					n.accounts = t.$_accountInfo.data("accountInfo");
					if (0 !== Number(n.rpAmount) && 0 === n.accId) {
						parent.Public.tips({
							type: 1,
							content: "付款额不为空时，请选择结算账户！"
						});
						return !1
					}
					if (0 === Number(n.rpAmount) && 0 !== n.accId) {
						parent.Public.tips({
							type: 1,
							content: "结算账户不为空时，需要输入付款额！"
						});
						return !1
					}
					if (-1 === n.accId && !n.accounts) {
						parent.Public.tips({
							type: 1,
							content: "请检查账户信息是否正确！"
						});
						return !1
					}
				}
				return n
			}
			parent.Public.tips({
				type: 2,
				content: "商品信息不能为空！"
			});
			$("#grid").jqGrid("editCell", 1, 2, !0);
			return !1
		}
	},
	hasLoaded = !1,
	originalData;
if (urlParam.id) {
	if (!hasLoaded) {
		var $_bills = $(".bills").hide();
		urlParam.turn ? Public.ajaxGet("/scm/invPo.do?action=queryDetails", {
			id: urlParam.id
		}, function(t) {
			if (200 === t.status) {
				originalData = t.data;
				originalData.id = -1;
				originalData.orderId = t.data.id;
				originalData.orderNo = t.data.billNo;
				originalData.status = "add";
				THISPAGE.init(t.data);
				$_bills.show();
				hasLoaded = !0
			} else {
				parent.Public.tips({
					type: 1,
					content: t.msg
				});
				$_bills.show();
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
					description: "",
					totalQty: 0,
					totalDiscount: 0,
					totalAmount: 0,
					totalTax: 0,
					totalTaxAmount: 0,
					disRate: 0,
					disAmount: 0,
					amount: "0.00",
					rpAmount: "0.00",
					arrears: "0.00",
					accId: 0
				};
				THISPAGE.init(originalData)
			}
		//}) : Public.ajaxGet("/scm/invPu.do?action=update", {
		}) : Public.ajaxGet(invpu_info, {
			id: urlParam.id
		}, function(t) {
			if (200 === t.status) {
				originalData = t.data;
				THISPAGE.init(t.data);
				$_bills.show();
				hasLoaded = !0
			} else parent.Public.tips({
				type: 1,
				content: t.msg
			})
		})
	}
} else {
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
		description: "",
		totalQty: 0,
		totalDiscount: 0,
		totalAmount: 0,
		totalTax: 0,
		totalTaxAmount: 0,
		disRate: 0,
		disAmount: 0,
		amount: "0.00",
		rpAmount: "0.00",
		arrears: "0.00",
		accId: 0
	};
	THISPAGE.init(originalData)
}