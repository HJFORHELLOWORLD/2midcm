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
    var t = ["操作","工作中心编号", "工作中心名称","描述","负责人","是否关键"],
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
            name: "WC_Name",
            index: "WC_Name",
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
            name: "IsKey",
            index: "IsKey",
            width: 50
        }];
    $("#grid").jqGrid({
        //url: "../basedata/unit.do?action=list&isDelete=2",
        url: workcenter_lists,
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
                $("#grid").data("gridData", e)
            } else {
                var r = 250 == t.status ? "没有工作中心数据！" : "获取工作中心数据失败！" + t.msg;
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
        if ("add" == t) var i = "新增工作中心",
            a = {
                oper: t,
                callback: this.callback
            };
        else var i = "修改工作中心",
            a = {
                oper: t,
                rowData: $("#grid").data("gridData")[e],
                callback: this.callback
            };
        $.dialog({
            title: i,
            content: "url:"+settings_workcenter_manage,
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
        $.dialog.confirm("删除的工作中心将不能恢复，请确认是否删除？", function() {
            Public.ajaxPost(workcenter_del, {
                id: t
            }, function(e) {
                if (e && 200 == e.status) {
                    parent.Public.tips({
                        content: "删除工作中心成功！"
                    });
                    $("#grid").jqGrid("delRowData", t)
                } else parent.Public.tips({
                    type: 1,
                    content: "删除工作中心失败！" + e.msg
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