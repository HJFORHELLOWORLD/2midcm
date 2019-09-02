function initEvent() {
    $("#btn-add").click(function(t) {
        t.preventDefault();
        handle.operate("add")
    });
    $("#grid").on("click", ".operating .ui-icon-pencil", function(t) {
        t.preventDefault();
        var e = $(this).parent().data("id");
        handle.operate("edit", e)
    });
    $("#grid").on("click", ".operating .ui-icon-trash", function(t) {
        t.preventDefault();
        var e = $(this).parent().data("id");
        handle.del(e)
    });
    $("#btn-refresh").click(function(t) {
        t.preventDefault();
        $("#grid").trigger("reloadGrid")
    });
    $(window).resize(function() {
        Public.resizeGrid()
    })
}
function initGrid() {
    var t = ["操作","仓库编号", "仓库名称","描述","负责人"],
        e = [{
            name: "operate",
            width: 60,
            fixed: !0,
            align: "center",
            formatter: Public.operFmatter
        },{
            name: "id",
            index: "id",
            width: 100
        },{
            name: "Stock_Name",
            index: "Stock_Name",
            width: 200
        },{
            name: "Desc",
            index: "Desc",
            width: 500
        },{
            name: "headName",
            index: "headName",
            width: 100
        }];
    $("#grid").jqGrid({
        //url: "../basedata/unit.do?action=list&isDelete=2",
        url: stock_lists,
        datatype: "json",
        height: Public.setGrid().h,
        altRows: !0,
        gridview: !0,
        colNames: t,
        colModel: e,
        autowidth: !0,
        viewrecords: !0,
        cmTemplate: {
            sortable: !1,
            title: !1
        },
        page: 1,
        pager: "#page",
        rowNum: 2e3,
        shrinkToFit: !1,
        scroll: 1,
        jsonReader: {
            root: "data.items",
            records: "data.totalsize",
            repeatitems: !1,
            id: "id"
        },
<<<<<<< HEAD
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
                url: stock_lists,
                postData: queryConditions,
                datatype: "json",
                autowidth: !0,
                height: i.h,
                altRows: !0,
                gridview: !0,
                multiselect: !0,
                colNames: ["操作", "仓库编号", "仓库名称", "描述","负责人","创建人","创建时间","变更人","变更时间"],
                colModel: [{
                    name: "operating",
                    width: 60,
                    fixed: !0,
                    formatter: t,
                    align: "center"
                },{
                    name: "PK_Stock_ID",
                    index: "PK_Stock_ID",
                    width: 80,
                    align: "center"
                }, {
                    name: "Stock_Name",
                    index: "Stock_Name",
                    width: 80,
                    align: "center"
                }, {
                    name: "Desc",
                    index: "Desc",
                    width: 500,
                    align: "center"
                },{
                    name: "Head_ID",
                    index: "Head_ID",
                    width: 60,
                    align: "center"
                },{
                    name: "Creator_ID",
                    index: "Creator_ID",
                    width: 60,
                    align: "center"
                },{
                    name: "Create_Date",
                    index: "Create_date",
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
                url: stock_lists,
                //url: "/scm/invPu.do?action=list",
                datatype: "json",
                postData: t
            }).trigger("reloadGrid")
=======
        loadComplete: function(t) {
            if (t && 200 == t.status) {
                var e = {};
                t = t.data;
                for (var i = 0; i < t.items.length; i++) {
                    var a = t.items[i];
                    e[a.id] = a
                }
                $("#grid").data("gridData", e)
            } else {
                var r = 250 == t.status ? "没有仓库数据！" : "获取仓库数据失败！" + t.msg;
                parent.Public.tips({
                    type: 2,
                    content: r
                })
            }
>>>>>>> 75b3f7b9f9287a303b937a199d246c39842cc7d5
        },
        loadError: function() {
            parent.Public.tips({
                type: 1,
                content: "操作失败了哦，请检查您的网络链接！"
            })
        }
    })
}
var handle = {
    operate: function(t, e) {
        if ("add" == t) var i = "新增仓库",
            a = {
                oper: t,
                callback: this.callback
            };
        else var i = "修改仓库",
            a = {
                oper: t,
                rowData: $("#grid").data("gridData")[e],
                callback: this.callback
            };
        $.dialog({
            title: i,
            content: "url:"+settings_stock_manage,
            data: a,
            width: 400,
            height: 300,
            max: !1,
            min: !1,
            cache: !1,
            lock: !0
        })
    },
    del: function(t) {
        $.dialog.confirm("删除的仓库将不能恢复，请确认是否删除？", function() {
            Public.ajaxPost(stock_del, {
                id: t
            }, function(e) {
                if (e && 200 == e.status) {
                    parent.Public.tips({
                        content: "删除仓库成功！"
                    });
                    $("#grid").jqGrid("delRowData", t)
                } else parent.Public.tips({
                    type: 1,
                    content: "删除仓库失败！" + e.msg
                })
            })
        })
    },
    callback: function(t, e, i) {
        var a = $("#grid").data("gridData");
        if (!a) {
            a = {};
            $("#grid").data("gridData", a)
        }
        a[t.id] = t;
        if ("edit" == e) {
            $("#grid").jqGrid("setRowData", t.id, t);
            i && i.api.close()
        } else {
            $("#grid").jqGrid("addRowData", t.id, t, "last");
            i && i.resetForm(t)
        }
    }
};
initEvent();
initGrid();