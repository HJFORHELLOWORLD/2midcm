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
    var t = ["操作","地区编号", "地区名称","上级区域"],
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
            name: "Name",
            index: "Name",
            width: 200
        },{
            name: "upareaName",
            index: "upareaName",
            width: 200
        }];
    $("#grid").jqGrid({
        //url: "../basedata/unit.do?action=list&isDelete=2",
        url: basedata_area,
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
        loadComplete: function(t) {
            if (t && 200 == t.status) {
                var e = {};
                t = t.data;
                for (var i = 0; i < t.items.length; i++) {
                    var a = t.items[i];
                    e[a.id] = a
                }
<<<<<<< HEAD
                var i = Public.setGrid(),
                    a = this;
                queryConditions.beginDate = this.$_beginDate.val();
                queryConditions.endDate = this.$_endDate.val();
                a.markRow = [];
                $("#grid").jqGrid({
                    //url: "/scm/invPu.do?action=list",
                    url: basedata_area,
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
                        name: "PK_Area_ID",
                        index: "PK_Area_ID",
                        width: 80,
                        align: "center"
                    }, {
                        name: "UpArea_ID",
                        index: "UpArea_ID",
                        width: 80,
                        align: "center"
                    }, {
                        name: "Name",
                        index: "Name",
                        width: 500,
                        align: "center"
                    },{
                        name: "Creator_ID",
                        index: "Creator_ID",
                        width: 60,
                        align: "center"
                    },{
                        name: "Create_Date",
                        index: "Create_Date",
                        width: 180,
                        align: "center"
                    }, {
                        name: "Modify_ID",
                        index: "Modify_ID",
                        width: 60,
                        align: "center",
                        formatter: "number",
                        formatoptions: {
                            decimalPlaces: qtyPlaces
                        }
                    },{
                        name: "Modify_Date",
                        index: "Modify_Date",
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
=======
                $("#grid").data("gridData", e)
            } else {
                var r = 250 == t.status ? "没有地区分类数据！" : "获取地区分类数据失败！" + t.msg;
                parent.Public.tips({
                    type: 2,
                    content: r
>>>>>>> 75b3f7b9f9287a303b937a199d246c39842cc7d5
                })
            }
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
        if ("add" == t) var i = "新增地区分类",
            a = {
                oper: t,
                callback: this.callback
            };
        else var i = "修改地区分类",
            a = {
                oper: t,
                rowData: $("#grid").data("gridData")[e],
                callback: this.callback
            };
        $.dialog({
            title: i,
            content: "url:"+settings_area_manage,
            data: a,
            width: 400,
            height: 250,
            max: !1,
            min: !1,
            cache: !1,
            lock: !0
        })
    },
    del: function(t) {
        $.dialog.confirm("删除的地区分类将不能恢复，请确认是否删除？", function() {
            Public.ajaxPost(area_del, {
                id: t
            }, function(e) {
                if (e && 200 == e.status) {
                    parent.Public.tips({
                        content: "删除地区分类成功！"
                    });
                    $("#grid").jqGrid("delRowData", t)
                } else parent.Public.tips({
                    type: 1,
                    content: "删除地区分类失败！" + e.msg
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