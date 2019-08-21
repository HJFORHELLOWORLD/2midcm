var queryConditions = {
        matchCon: ""
    },
    SYSTEM = system = parent.SYSTEM,
    hiddenAmount = !1,
    billRequiredCheck = system.billRequiredCheck,
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
            function t(t, e, i) {
/*                if(0 == i.import) {
                    var a = '<div class="operating" data-id="' + i.planId + '" data-time="' + i.create_time + '"><!--<a class="ui-icon ui-icon-search" title="查看">--><a class="ui-icon ui-icon-pencil" title="查看修改"></a><a class="ui-icon ui-icon-copy" title="生成购货单"></a></div>';
                }
                if (1 == i.import){
                    var a = '<div class="operating" data-id="' + i.planId + '" data-time="' + i.create_time + '"><a class="ui-icon ui-icon-search" title="查看"></a><span style="color: red">已生成</span></div>';
                }*/

                var a = '<div class="operating" data-id="' + i.PK_BOM_Pur_ID + '" data-time="' + i.Create_Date + '"><!--<a class="ui-icon ui-icon-search" title="查看">--><a class="ui-icon ui-icon-search" title="查看"></a><a class="ui-icon ui-icon-copy" title="生成采购单"></a></div>';
                return a
            }
            var i = Public.setGrid(),
                a = this;
            queryConditions.beginDate = this.$_beginDate.val();
            queryConditions.endDate = this.$_endDate.val();
            a.markRow = [];
            $("#grid").jqGrid({
                //url: "/scm/invPu.do?action=list",
                url: invpu_purchasePlanInfo,
                postData: queryConditions,
                datatype: "json",
                autowidth: !0,
                height: i.h,
                altRows: !0,
                gridview: !0,
                multiselect: !0,
                colNames: ["操作", "采购计划编号", "采购计划生成时间"],
                colModel: [{
                    name: "operating",
                    width: 100,
                    fixed: !0,
                    formatter: t,
                    align: "center"
                }, {
                    name: "PK_BOM_Pur_ID",
                    index: "PK_BOM_Pur_ID",
                    width: 200,
                    align: "center"
                },{
                    name: "Create_Date",
                    index: "Create_Date",
                    width: 200,
                    align: "center"
                },],
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
                    $("#" + t).find(".ui-icon-search").trigger("click")
                }
            })
        },
        reloadData: function(t) {
            this.markRow = [];
            $("#grid").jqGrid("setGridParam", {
                url: invpu_purchasePlanInfo,
                //url: "/scm/invPu.do?action=list",
                datatype: "json",
                postData: t
            }).trigger("reloadGrid")
        },
        addEvent: function() {
            var t = this;
/*            $(".grid-wrap").on("click", ".ui-icon-search", function(t) {
                t.preventDefault();
                var e = $(this).parent().data("id"),
                    i = $("#grid").jqGrid("getRowData", e),
                    a = 1 == i.disEditable ? "&disEditable=true" : "";
                parent.tab.addTabItem({
                    tabid: "purchase-purchase",
                    text: "采购计划信息",
                    url: invpu_purchasePlanInfo+"?id=" + e + "&flag=list"
                    //url: "/purchase/purchase.jsp?id=" + e + "&flag=list" + a
                });
                $("#grid").jqGrid("getDataIDs");
                parent.cacheList.purchaseId = $("#grid").jqGrid("getDataIDs")
            });*/


            $(".grid-wrap").on("click", ".ui-icon-search", function(t) {
                t.preventDefault();
                var t = $(this).parent().data("id");
                var time = $(this).parent().data("time");
                Business.forPurchasePlanSearch(t, time)
/*                t.preventDefault();
                var e = $(this).parent().data("id"),
                    i = $("#grid").jqGrid("getRowData", e),
                    a = 1 == i.disEditable ? "&disEditable=true" : "";
                parent.tab.addTabItem({
                    tabid: "purchase-purchase",
                    text: "采购计划单",
                    url: invpu_planEdit+"?type=read&id=" + e + "&flag=list"
                    //url: "/purchase/purchase.jsp?id=" + e + "&flag=list" + a
                });
                $("#grid").jqGrid("getDataIDs");
                parent.cacheList.purchaseId = $("#grid").jqGrid("getDataIDs")*/
            });


            $(".grid-wrap").on("click", ".ui-icon-copy", function(t) {
                t.preventDefault();
                var e = $(this).parent().data("id");
                $.dialog.confirm("您确定要生成购货单吗？", function() {
                    //Public.ajaxGet("/scm/invPu.do?action=delete", {
                    Public.ajaxPost(invpu_planImport, {
                        planId: e
                    }, function(t) {
                        if (200 === t.status) {
                            parent.Public.tips({
                                content: "生成成功！"
                            });
                            THISPAGE.reloadData();
                        } else parent.Public.tips({
                            type: 1,
                            content: t.msg
                        })
                    })
                })
            });
            $(".grid-wrap").on("click", ".ui-icon-pencil", function(t) {
                t.preventDefault();
                var e = $(this).parent().data("id"),
                    i = $("#grid").jqGrid("getRowData", e),
                    a = 1 == i.disEditable ? "&disEditable=true" : "";
                parent.tab.addTabItem({
                    tabid: "purchase-purchase",
                    text: "采购计划单",
                    url: invpu_planEdit+"?id=" + e + "&flag=list"
                    //url: "/purchase/purchase.jsp?id=" + e + "&flag=list" + a
                });
                $("#grid").jqGrid("getDataIDs");
                parent.cacheList.purchaseId = $("#grid").jqGrid("getDataIDs")
            });

            $(".grid-wrap").on("click", ".ui-icon-trash", function(t) {
                t.preventDefault();
                if (Business.verifyRight("PU_DELETE")) {
                    var e = $(this).parent().data("id");
                    $.dialog.confirm("您确定要删除该购货记录吗？", function() {
                        //Public.ajaxGet("/scm/invPu.do?action=delete", {
                        Public.ajaxGet(invpu_del, {
                            id: e
                        }, function(t) {
                            if (200 === t.status) {
                                $("#grid").jqGrid("delRowData", e);
                                parent.Public.tips({
                                    content: "删除成功！"
                                })
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
                Business.verifyRight("PU_PRINT") && Public.print({
                    title: "购货单列表",
                    $grid: $("#grid"),
                    pdf: "/scm/invPu.do?action=toPdf",
                    billType: 10101,
                    filterConditions: queryConditions
                })
            });

            $(".grid-wrap").on("click", ".ui-icon-copy", function(t) {
                t.preventDefault();
                var e = $(this).parent().data("id"),
                    i = $("#grid").jqGrid("getRowData", e),
                    a = 1 == i.disEditable ? "&disEditable=true" : "";
                $.dialog.confirm("您确定要生成购货单吗？", function() {
                    //Public.ajaxGet("/scm/invPu.do?action=delete", {
                    Public.ajaxGet(invpu_planImport, {
                        planId: e
                    }, function(t) {
                        if (200 === t.status) {
                            parent.Public.tips({
                                content: "生成购货单成功！请清空缓存刷新页面！"
                            })
                        } else parent.Public.tips({
                            type: 1,
                            content: t.msg
                        })
                    })
                })
            });

            if (billRequiredCheck) {
                {
                    $("#audit").css("display", "inline-block"), $("#reAudit").css("display", "inline-block")
                }
                $(".wrapper").on("click", "#audit", function(t) {
                    t.preventDefault();
                    var e = $("#grid").jqGrid("getGridParam", "selarrrow"),
                        i = e.join();
                    i ? Public.ajaxPost("/scm/invPu.do?action=batchCheckInvPu", {
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
                    i ? Public.ajaxPost("/scm/invPu.do?action=rsBatchCheckInvPu", {
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
                queryConditions.beginDate = t.$_beginDate.val();
                queryConditions.endDate = t.$_endDate.val();
                THISPAGE.reloadData(queryConditions)
            });
            $("#update").click(function() {
/*                $.ajax({
                    url: "/index.php/invpu/purchasePlan",
                    type: "post",
                    data: {
                        "type" : 2
                     },
                    success: function (data) {
                        THISPAGE.reloadData()
                    }
                });*/
                Public.ajaxPost(invpu_purchasePlan, {
                    type : 2
                }, function(e) {
                    if (200 === e.status) {
                        THISPAGE.reloadData()
                    } else parent.Public.tips({
                        type: 1,
                        content: e.msg
                    })
                })
            });

            $(window).resize(function() {
                Public.resizeGrid()
            })
        }
    };
THISPAGE.init();