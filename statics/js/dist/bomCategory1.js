
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
                url: bomCategory_lists,
                postData: queryConditions,
                datatype: "json",
                autowidth: !0,
                height: i.h,
                altRows: !0,
                gridview: !0,
                multiselect: !0,
                colNames: ["操作","物料号","物料名称","描述", "栏目名称", "上级栏目","创建人"],
                colModel: [{
                    name: "operating",
                    width: 60,
                    fixed: !0,
                    formatter: t,
                    align: "center"
                },{
                    name: "bom_id",
                    index: "bom_id",
                    width: 200,
                    align: "center"
                }, {
                    name: "Name",
                    index: "Name",
                    width: 80,
                    align: "center"
                }, {
                    name: "Desc",
                    index: "Desc",
                    width: 300,
                    align: "center"
                },{
                    name: "PK_BOMCat_ID1",
                    index: "PK_BOMCat_ID1",
                    width: 120,
                    align: "center"
                },{
                    name: "pid",
                    index: "pid",
                    width: 120,
                    align: "center"
                },{
                    name: "Creator_ID",
                    index: "Creator_ID",
                    width: 120,
                    align: "center"
                }
                ],
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
                url: bomCategory_lists,
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
                    url: det+"?id=" + e + "&flag=list"
                    //url: "/purchase/purchase.jsp?id=" + e + "&flag=list" + a
                });
                $("#grid").jqGrid("getDataIDs");
                parent.cacheList.purchaseId = $("#grid").jqGrid("getDataIDs")
            });

            $("#search").click(function() {
                queryConditions.matchCon = "请输入物料编号或物料名称" === t.$_matchCon.val() ? "" : t.$_matchCon.val();
                THISPAGE.reloadData(queryConditions)
            });
            $("#add").click(function(t) {
                t.preventDefault();
                parent.tab.addTabItem({
                    tabid: "storage",
                    text: "物料类别新增",
                    //url: "/scm/invPu.do?action=initPur"
                    url: bomCategory_add
                })
            });
            $(window).resize(function() {
                Public.resizeGrid()
            })
        }
    };
THISPAGE.init();