function initField() {console.log(rowData);
    rowData.id && $("#BOMName").val(rowData.BOMName) && $("#Desc").val(rowData.Desc)
    && $("#BOMModel").val(rowData.BOMModel)&& $("#IsVirt").val(rowData.IsVirt)

}

function initEvent() {
    var t = $("#BOMName");
    $("#manage-form").submit(function(t) {
        t.preventDefault();
        postData()
    });
    t.focus().select();
    initValidator();
}
function initPopBtns() {
    var t = "add" == oper ? ["保存", "关闭"] : ["确定", "取消"];
    api.button({
        id: "confirm",
        name: t[0],
        focus: !0,
        callback: function() {
            postData();
            return !1
        }
    }, {
        id: "cancel",
        name: t[1]
    })
}
function initValidator() {
    $("#manage-form").validate({
        rules: {
            BOMName: {
                required: !0
            }
        },
        messages: {
            BOMName: {
                required: "名称不能为空"
            }
        },
        errorClass: "valid-error"
    })
}
function postData() {
    if ($("#manage-form").validate().form()) {

        var attr_key=[];
        var attr_val=[];

        $("input[name='key']").each(function () {
            attr_key.push($(this).val());
        });

        $("input[name='val']").each(function () {
            attr_val.push($(this).val());
        });

        e = {
            id: rowData.id,
            BOMModel: $.trim($("#BOMModel").val()),
            BOMName: $.trim($("#BOMName").val()),
            Desc: $.trim($("#Desc").val()),
            BOMCat_ID1: cat1Combo.getValue(),
            BOMCat_ID1_Name: cat2Combo.getText(),
            BOMCat_ID2: cat2Combo.getValue(),
            BOMCat_ID2_Name: cat2Combo.getText(),
            IsVirt:$("#IsVirt").val(),
            fk_unitClass_id: unitCombo.getValue(),
            unitName: unitCombo.getText(),
            attr_key : attr_key,
            attr_val : attr_val
        },
            i = "add" == oper ? "新增物料" : "修改物料";
        Public.ajaxPost(bom_save+"?act=" + ("add" == oper ? "add" : "update"), e, function(t) {
            if (200 == t.status) {
                parent.parent.Public.tips({
                    content: i + "成功！"
                });
                callback && "function" == typeof callback && callback(t.data, oper, window)
            } else parent.parent.Public.tips({
                type: 1,
                content: i + "失败！" + t.msg
            })
        })
    } else $("#manage-form").find("input.valid-error").eq(0).focus()
}
function resetForm() {
    $("#manage-form").validate().resetForm();
    $("#BOMName").val("").focus().select();
    $("#Desc").val("");
    $("#BOMModel").val("");
    $("#IsVirt").val("");
    $("#BOMCat_ID1").val("");
    $("#BOMCat_ID2").val("");
    $("#FK_UnitClass_ID").val("");
}
var api = frameElement.api,
    oper = api.data.oper,
    rowData = api.data.rowData || {},
    callback = api.data.callback;

var unitCombo = $("#FK_UnitClass_ID").combo({
    text: "name",
    value: "id",
    width: 200,
    data: basedata_unit,
    defaultSelected: ["id", parseInt(rowData.FK_UnitClass_ID)] || void 0,

    ajaxOptions: {
        formatData: function(e) {
            e.data.items.unshift({
                userid: "",
                name: ""
            });
            return e.data.items
        }
    }

}).getCombo();
var cat1Combo = $("#BOMCat_ID1").combo({
    text: "name",
    value: "id",
    width: 200,
    data: basedata_cat1List,
     defaultSelected: ["id", parseInt(rowData.BOMCat_ID1)] || void 0,

    ajaxOptions: {
        formatData: function(e) {
            e.data.items.unshift({
                id: "",
                name: ""
            });
            return e.data.items
        }
    }

}).getCombo();

var cat2Combo = $("#BOMCat_ID2").combo({
    text: "name",
    value: "id",
    width: 200,
    data: basedata_cat2List,
     defaultSelected: ["id", parseInt(rowData.BOMCat_ID2)] || void 0,

    ajaxOptions: {
        formatData: function(e) {
            e.data.items.unshift({
                id: "",
                name: ""
            });
            return e.data.items
        }
    }

}).getCombo();
initPopBtns();
initField();
initEvent();