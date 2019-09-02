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
    var t = ["操作","部门编号", "部门名称","描述","负责人","状态"],
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
            name: "Desc",
            index: "Desc",
            width: 500
        },{
            name: "headName",
            index: "headName",
            width: 100
        },{
            name: "StatusName",
            index: "StatusName",
            width: 50
        }];
    $("#grid").jqGrid({
        //url: "../basedata/unit.do?action=list&isDelete=2",
        url: department_lists,
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
                url: department_lists,
                postData: queryConditions,
                datatype: "json",
                autowidth: !0,
                height: i.h,
                altRows: !0,
                gridview: !0,
                multiselect: !0,
                colNames: ["操作", "部门编号", "部门名称", "描述","负责人","状态","创建人","创建时间","变更人","变更时间"],
                colModel: [{
                    name: "operating",
                    width: 60,
                    fixed: !0,
                    formatter: t,
                    align: "center"
                },{
                    name: "PK_Dept_ID",
                    index: "PK_Dept_ID",
                    width: 80,
                    align: "center"
                }, {
                    name: "Name",
                    index: "Name",
                    width: 80,
                    align: "center"
                }, {
                    name: "Desc",
                    index: "Desc",
                    width: 500,
                    align: "center"
                },{
                    name: "Header",
                    index: "Header",
                    width: 60,
                    align: "center"
                },{
                    name: "Status",
                    index: "Status",
                    width: 60,
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
=======
        loadComplete: function(t) {
            if (t && 200 == t.status) {
                var e = {};
                t = t.data;
                for (var i = 0; i < t.items.length; i++) {
                    var a = t.items[i];
                    e[a.id] = a
>>>>>>> 75b3f7b9f9287a303b937a199d246c39842cc7d5
                }
                $("#grid").data("gridData", e)
            } else {
                var r = 250 == t.status ? "没有部门数据！" : "获取部门数据失败！" + t.msg;
                parent.Public.tips({
                    type: 2,
                    content: r
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
        if ("add" == t) var i = "新增部门",
            a = {
                oper: t,
                callback: this.callback
            };
        else var i = "修改部门",
            a = {
                oper: t,
                rowData: $("#grid").data("gridData")[e],
                callback: this.callback
            };
        $.dialog({
            title: i,
            content: "url:"+settings_department_manage,
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
        $.dialog.confirm("删除的部门将不能恢复，请确认是否删除？", function() {
            Public.ajaxPost(department_del, {
                id: t
            }, function(e) {
                if (e && 200 == e.status) {
                    parent.Public.tips({
                        content: "删除部门成功！"
                    });
                    $("#grid").jqGrid("delRowData", t)
                } else parent.Public.tips({
                    type: 1,
                    content: "删除部门失败！" + e.msg
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