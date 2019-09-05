function initField() {
    rowData.id && $("#name").val(rowData.name) && $("#remark").val(rowData.remark)
    && $("#phone").val(rowData.telephone) && $("#Taxrate").val(rowData.Taxrate)
    && $("#BU_Cat").val(rowData.BU_Cat)	&& $("#Status").val(rowData.Status)

}

function initEvent() {
    var t = $("#name");
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
            name: {
                required: !0
            }
        },
        messages: {
            name: {
                required: "名称不能为空"
            }
        },
        errorClass: "valid-error"
    })
}
function postData() {
    if ($("#manage-form").validate().form()) {
        var n = $.trim($("#name").val()),
            d = $.trim($("#remark").val()),
            a = areaCombo.getValue(),
            i = industryCombo.getValue(),
            an = areaCombo.getText(),
            iname = industryCombo.getText(),
            t = $.trim($("#Taxrate").val()),
            p = $.trim($("#phone").val()),
            b = $("#BU_Cat").val(),
            s = $("#Status").val();
        e = {
            id: rowData.id,
            name: n,
            desc : d,
            Area_ID : a,
            Industry_ID : i,
            BU_Cat : b,
            Taxrate : t,
            phone : p,
            status : s,
            areaname : an,
            industryname : iname
        },
            i = "add" == oper ? "新增往来单位类别" : "修改往来单位类别";
        Public.ajaxPost(betweenUnit_save+"?act=" + ("add" == oper ? "add" : "update"), e, function(t) {
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
    $("#name").val("").focus().select();
    $("#remark").val("");
    $("#Area_ID").val("");
    $("#BU_Cat").val("");
    $("#Industry_ID").val("");
    $("#Taxrate").val("");
    $("#Status").val("");
    $("#phone").val("");
}
var api = frameElement.api,
    oper = api.data.oper,
    rowData = api.data.rowData || {},
    callback = api.data.callback;

var industryCombo = $("#Industry_ID").combo({
    text: "Name",
    value: "id",
    width: 200,
    data: category_lists,
    defaultSelected: ["id", parseInt(rowData.Industry_ID)] || void 0,

    ajaxOptions: {
        formatData: function(e) {
            e.data.items.unshift({
                id: 0,
                Name: "其他"
            });
            return e.data.items
        }
    }

}).getCombo();

var areaCombo = $("#Area_ID").combo({
    text: "Name",
    value: "id",
    width: 200,
    data: basedata_area,
    defaultSelected: ["id", parseInt(rowData.Area_ID)] || void 0,

    ajaxOptions: {
        formatData: function(e) {
            e.data.items.unshift({
                id: "",
                Name: ""
            });
            return e.data.items
        }
    }

}).getCombo();
initPopBtns();
initField();
initEvent();