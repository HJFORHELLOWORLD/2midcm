    var queryConditions = {
            matchCon: ""
        },
        SYSTEM = system = parent.SYSTEM,
        hiddenAmount = !1,
        billRequiredCheck = system.billRequiredCheck,
        qtyPlaces = Number(parent.SYSTEM.qtyPlaces),
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
                    //<a class="ui-icon ui-icon-pencil" title="修改"></a>
                    var a = '<div class="operating" data-id="' + i.id + '"><a class="ui-icon ui-icon-trash" title="删除"></a><a class="ui-icon ui-icon-pencil" title="修改"></a></div>';
                    return a
                }
                var i = Public.setGrid(),
                    a = this;
                queryConditions.beginDate = this.$_beginDate.val();
                queryConditions.endDate = this.$_endDate.val();
                a.markRow = [];
                $("#grid").jqGrid({
                    //url: "/scm/invPu.do?action=list",
                    url: database_area,
                    postData: queryConditions,
                    datatype: "json",
                    autowidth: !0,
                    height: i.h,
                    altRows: !0,
                    gridview: !0,
                    multiselect: !0,
                    colNames: ["操作", "地区编码", "上级区域","地区名称","创建人","创建时间","变更人","变更时间"],
                    colModel: [{
                        name: "operating",
                        width: 60,
                        fixed: !0,
                        formatter: t,
                        align: "center"
                    },{
                        name: "pk_area_id",
                        index: "pk_area_id",
                        width: 80,
                        align: "center"
                    }, {
                        name: "upArea_id",
                        index: "upArea_id",
                        width: 80,
                        align: "center"
                    }, {
                        name: "name",
                        index: "name",
                        width: 500,
                        align: "center"
                    },{
                        name: "creator_id",
                        index: "creator_id",
                        width: 60,
                        align: "center"
                    },{
                        name: "create_date",
                        index: "create_date",
                        width: 180,
                        align: "center"
                    }, {
                        name: "modify_id",
                        index: "modify_id",
                        width: 60,
                        align: "center",
                        formatter: "number",
                        formatoptions: {
                            decimalPlaces: qtyPlaces
                        }
                    },{
                        name: "modify_date",
                        index: "modify_date",
                        width: 180,
                        align: "center"
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
                    url: design_lists,
                    //url: "/scm/invPu.do?action=list",
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
                        tabid: "settings-settings",
                        text: "BOM设计",
                        url: design_edit+"?id=" + e + "&flag=list"
                        //url: "/purchase/purchase.jsp?id=" + e + "&flag=list" + a
                    });
                    $("#grid").jqGrid("getDataIDs");
                    parent.cacheList.purchaseId = $("#grid").jqGrid("getDataIDs")
                });

                // $(".grid-wrap").on("click", ".ui-icon-trash", function(t) {
                //     t.preventDefault();
                //     var e = $(this).parent().data("id");
                //     $.dialog.confirm("您确定要该BOM设计信息吗？", function() {
                //         //Public.ajaxGet("/scm/invPu.do?action=delete", {
                //         Public.ajaxGet(design_del, {
                //             id: e
                //         }, function(t) {
                //             if (200 === t.status) {
                //                 $("#grid").jqGrid("delRowData", e);
                //                 parent.Public.tips({
                //                     content: "删除成功！"
                //                 })
                //             } else parent.Public.tips({
                //                 type: 1,
                //                 content: t.msg
                //             })
                //         })
                //     })
                // });

                $("#search").click(function() {
                    queryConditions.matchCon = "请输入区域名称" === t.$_matchCon.val() ? "" : t.$_matchCon.val();
                    THISPAGE.reloadData(queryConditions)
                });
                $("#add").click(function(t) {
                    t.preventDefault();
                    parent.tab.addTabItem({
                        tabid: "storage",
                        text: "地区新增",
                        //url: "/scm/invPu.do?action=initPur"
                        url: area_add
                    })
                });

                $(window).resize(function() {
                    Public.resizeGrid()
                })
            }
        };
    THISPAGE.init();