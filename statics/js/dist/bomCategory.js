function initDom() {
    function e(e, t, i) {
        conditions.typeNumber = e;
        conditions.name = "物料";
        i || $("#grid").setGridParam({
            postData: conditions
        }).trigger("reloadGrid");
        parent.$("li.l-selected a").eq(0).text("物料" + "类别")
    }
    var t, i = $(".ui-tab").on("click", "li", function() {
            var t = $(this),
                i = 1,
                r = t.html(),
                a = conditions.typeNumber,
                n = conditions.name;
            conditions.typeNumber = i;
            conditions.name = r;
            if (verifyRight(rightsAction.query)) {
                $(".cur").removeClass("cur");
                t.addClass("cur");
                $("#custom-assisting").getCombo().selectByIndex(0, !1);
                e(i, r)
            } else {
                conditions.typeNumber = a;
                conditions.name = n
            }
        }),
        r = [],
        a = {
            trade: "物料"
        };
    for (var n in a) r.push('<li data-id="' + n + '">' + a[n] + "</li>");
    i.append(r.join(""));
    var o = $("#assisting-category-select li[data-id=" + typeNumber + "]");
    if (1 == o.length) {
        o.addClass("cur");
        t = 0
    } else {
        t = ["number", typeNumber];
        $("#custom-assisting").parent().addClass("cur")
    }
    e(typeNumber, a[typeNumber], !0);
    $("#custom-assisting").combo({
        //data: "../basedata/assist.do?action=getAssistType",
        data: basedata_category_type,
        text: "name",
        value: "number",
        width: 170,
        ajaxOptions: {
            formatData: function(e) {
                var e = e.data.items;
                e.unshift({
                    number: "",
                    name: "选择其他类别"
                });
                for (var t = 0, i = e.length; i > t; t++) {
                    e[t].name = e[t].name.replace("类别", "");
                    if (a[e[t].number]) {
                        e.splice(t, 1);
                        t--;
                        i--
                    }
                }
                e.length > 1 && $("#custom-assisting").parent().show();
                return e
            }
        },
        defaultSelected: t,
        defaultFlag: !1,
        callback: {
            onChange: function(t) {
                if (t.number) {
                    var i = t.number,
                        r = t.name;
                    $("#assisting-category-select li").removeClass("cur");
                    $("#custom-assisting").parent().addClass("cur");
                    e(i, r)
                } else $("#custom-assisting").getCombo().selectByValue(conditions.typeNumber, !1)
            },
            beforeChange: function(e) {
                var t = e.number,
                    i = e.name;
                _oType = conditions.typeNumber, _oName = conditions.name;
                conditions.typeNumber = t;
                conditions.name = i;
                if (verifyRight(rightsAction.query)) return !0;
                conditions.typeNumber = _oType;
                conditions.name = _oName;
                return !1
            }
        }
    })
}
function initEvent() {
    $("#btn-add").click(function(e) {
        e.preventDefault();
        verifyRight(rightsAction.add) && handle.operate("add")
    });
    $("#grid").on("click", ".operating .ui-icon-pencil", function(e) {
        e.preventDefault();
        if (verifyRight(rightsAction.update)) {
            var t = $(this).parent().data("id");
            handle.operate("edit", t)
        }
    });
    $("#grid").on("click", ".operating .ui-icon-trash", function(e) {
        e.preventDefault();
        if (verifyRight(rightsAction.del)) {
            var t = $(this).parent().data("id");
            handle.del(t)
        }
    });
    $("#btn-refresh").click(function(e) {
        e.preventDefault();
        $("#grid").trigger("reloadGrid")
    });
    $("#search").click(function(e) {
        e.preventDefault();
        var t = $.trim($("#matchCon").val());
        conditions.skey = "输入类别名称查询" == t ? "" : t;
        $("#grid").setGridParam({
            postData: conditions
        }).trigger("reloadGrid")
    });
    $("#matchCon").placeholder();
    $(window).resize(function() {
        Public.resizeGrid()
    })
};
function initGrid() {
    var e = [
        {
        name: "operate",
        label: "操作",
        width: 60,
        fixed: !0,
        align: "center",
        formatter: Public.operFmatter
    }, {
        name: "Name",
        label: "类别",
        width: 200,
        formatter: function(e, t, i) {
            for (var r = parseInt(i.level) - 1, a = "", n = 0; r > n; n++) a += "   ";
            return a + e
        }
    },{
        name:"bom_id",
        label:"bom编号",
        width:200
        },
        {
        name: "id",
        label: "id",
        hidden: !0
    }, {
        name: "level",
        label: "level",
        hidden: !0
    }, {
        name: "parentId",
        label: "parentId",
        hidden: !0
    }, {
        name: "parentName",
        label: "parentName",
        hidden: !0
    }];
    $("#grid").jqGrid({
        url: url,
        postData: conditions,
        datatype: "json",
        height: Public.setGrid().h,
        altRows: !0,
        gridview: !0,
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
        loadComplete: function(e) {
            if (e && 200 == e.status) {
                var t = {};
                e = e.data;
                for (var i = 0; i < e.items.length; i++) {
                    var r = e.items[i];
                    t[r.id] = r
                }
                showParentCategory = "trade" === conditions.typeNumber ? !0 : !1;
                for (var i = 0; i < e.items.length; i++) {
                    var r = e.items[i],
                        a = t[r.parentId] || {};
                    if (a.name) {
                        showParentCategory = !0;
                        t[r.id].parentName = a.name
                    }
                }
                parent.SYSTEM.categoryInfo = parent.SYSTEM.categoryInfo || {};
                parent.SYSTEM.categoryInfo[conditions.typeNumber] = e.items;
                $("#grid").data("gridData", t)
            } else {
                var n = 250 == e.status ? "没有" + conditions.name + "类别数据！" : "获取" + conditions.name + "类别数据失败！" + e.msg;
                parent.Public.tips({
                    type: 2,
                    content: n
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
function initValidator() {
    $("#manage-form").validate({
        rules: {
            category: {
                required: !0
            }
        },
        messages: {
            category: {
                required: "类别不能为空"
            }
        },
        errorClass: "valid-error"
    })
}
function postData(e) {
    if ($("#manage-form").validate().form()) {
        var t = $.trim($("#category").val()),
            i = $.trim($("#ParentCategory").val()),
            r = e ? "update" : "add",
            a = i ? $("#ParentCategory").data("PID") : "";
        if (a !== e) {
            var n = {
                    parentId: a,
                    id: e,
                    name: t
                },
                o = "add" == r ? "新增" + conditions.name + "类别" : "修改" + conditions.name + "类别";
            n.typeNumber = conditions.typeNumber;
            //Public.ajaxPost("../basedata/assist.do?action=" + r, n, function(e) {
            Public.ajaxPost(category_save+"?act=" + r, n, function(e) {
                if (200 == e.status) {
                    parent.parent.Public.tips({
                        content: o + "成功！"
                    });
                    handle.callback(e.data, r)
                } else parent.parent.Public.tips({
                    type: 1,
                    content: o + "失败！" + e.msg
                })
            })
        } else parent.parent.Public.tips({
            type: 2,
            content: "当前分类和上级分类不能相同！"
        })
    } else $("#manage-form").find("input.valid-error").eq(0).focus()
}
function resetForm() {
    $("#manage-form").validate().resetForm();
    $("#ParentCategory").val("");
    $("#category").val("").focus().select()
}
function verifyRight(e) {
    var t = rightsType[conditions.typeNumber];
    if (!t) return !0;
    switch (e) {
        case rightsAction.query:
            break;
        case rightsAction.add:
            break;
        case rightsAction.del:
            break;
        case rightsAction.update:
            break;
        default:
            return !1
    }
    return Business.verifyRight(t += e)
}
//var typeNumber, showParentCategory, url = "../basedata/assist.do?action=list&isDelete=2",
//	urlParam = Public.urlParam();
var typeNumber, showParentCategory, url = basedata_category1+"?isDelete=2",
    urlParam = Public.urlParam();
urlParam.typeNumber && (typeNumber = urlParam.typeNumber);
var conditions = {
        typeNumber: typeNumber,
        skey: "",
        name: ""
    },
    rightsType = {
        customertype: "BUTYPE",
        supplytype: "SUPPLYTYPE",
        trade: "TRADETYPE",
        raccttype: "RACCTTYPE",
        paccttype: "PACCTTYPE"
    },
    rightsAction = {
        query: "_QUERY",
        add: "_ADD",
        del: "_DELETE",
        update: "_UPDATE"
    },
    handle = {
        operate: function(e, t) {
            if ("add" == e) {
                var i = "新增" + conditions.name + "类别";
                ({
                    oper: e,
                    callback: this.callback
                })
            } else {
                var i = "修改" + conditions.name + "类别";
                ({
                    oper: e,
                    rowData: $("#grid").data("gridData")[t],
                    callback: this.callback
                })
            }
            var r = ['<form id="manage-form" action="">', '<ul class="mod-form-rows manage-wrap" id="manager">', '<li class="row-item" style="position:relative; display:none;">', '<div class="label-wrap"><label for="ParentCategory">上级分类:</label></div>', '<div class="ctn-wrap" style="position:relative;"><input type="text" value="" class="ui-input" name="ParentCategory" id="ParentCategory" readonly></div>', '<div class="dn hideFeild"></div>', "</li>", '<li class="row-item">', '<div class="label-wrap"><label for="category">类别:</label></div>', '<div class="ctn-wrap"><input type="text" value="" class="ui-input" name="category" id="category"></div>', "</li>", "</ul>", "</form>"],
                a = 90;
            showParentCategory && (a = 150);
            this.dialog = $.dialog({
                title: i,
                content: r.join(""),
                width: 400,
                height: a,
                max: !1,
                min: !1,
                cache: !1,
                lock: !0,
                okVal: "确定",
                ok: function() {
                    postData(t);
                    return !1
                },
                cancelVal: "取消",
                cancel: function() {
                    return !0
                },
                init: function() {
                    var i = $(".hideFeild"),
                        r = $("#ParentCategory"),
                        a = $("#category");
                    if (showParentCategory) {
                        r.closest("li").show();
                        $("#ParentCategory").click(function() {
                            if (!i.show().data("hasInit")) {
                                i.show().data("hasInit", !0);
                                Public.zTree.init(i, {
                                    defaultClass: "ztreeDefault"
                                }, {
                                    callback: {
                                        beforeClick: function(e, t) {
                                            r.val(t.name);
                                            r.data("PID", t.id);
                                            i.hide()
                                        }
                                    }
                                })
                            }
                        });
                        $(".ui_dialog").click(function() {
                            i.hide()
                        });
                        $("#ParentCategory").closest(".row-item").click(function(e) {
                            var t = e || window.event;
                            t.stopPropagation ? t.stopPropagation() : window.event && (window.event.cancelBubble = !0)
                        });
                        document.onclick = function() {
                            i.hide()
                        }
                    }
                    if ("add" != e) {
                        var n = $("#grid").data("gridData")[t];
                        a.val(n.name);
                        r.val(n.parentName);
                        r.data("PID", n.parentId)
                    }
                    initValidator()
                }
            })
        },
        del: function(e) {
            $.dialog.confirm("删除的" + conditions.name + "类别将不能恢复，请确认是否删除？", function() {
                Public.ajaxPost(category_del, {
                    id: e,
                    typeNumber: conditions.typeNumber
                }, function(t) {
                    if (t && 200 == t.status) {
                        parent.Public.tips({
                            content: "删除" + conditions.name + "类别成功！"
                        });
                        $("#grid").jqGrid("delRowData", e);
                        for (var i = parent.SYSTEM.categoryInfo[conditions.typeNumber].length, r = 0; i > r; r++) if (parent.SYSTEM.categoryInfo[conditions.typeNumber][r].id === e) {
                            parent.SYSTEM.categoryInfo[conditions.typeNumber].splice(r, 1);
                            r--;
                            i--
                        }
                    } else parent.Public.tips({
                        type: 1,
                        content: "删除" + conditions.name + "类别失败！" + t.msg
                    })
                })
            })
        },
        callback: function(e, t) {
            var i = $("#grid").data("gridData");
            if (!i) {
                i = {};
                $("#grid").data("gridData", i)
            }
            for (var r = parent.SYSTEM.categoryInfo[conditions.typeNumber].length, a = !0, n = 0; r > n; n++) if (parent.SYSTEM.categoryInfo[conditions.typeNumber][n].id === e.id) {
                parent.SYSTEM.categoryInfo[conditions.typeNumber][n] = e;
                a = !1
            }
            a && parent.SYSTEM.categoryInfo[conditions.typeNumber].push(e);
            i[e.id] = e;
            e.parentId && (i[e.id].parentName = i[e.parentId].name);
            if ("add" != t) {
                $("#grid").jqGrid("setRowData", e.id, e);
                this.dialog.close()
            } else {
                $("#grid").jqGrid("addRowData", e.id, e, "last");
                this.dialog.close()
            }
            $("#grid").setGridParam({
                postData: conditions
            }).trigger("reloadGrid")
        }
    };
initDom();
initEvent();
initGrid();
