function initField() {
	rowData.id && $("#name").val(rowData.WC_Name) && $("#desc").val(rowData.Desc)
	&& $("#headName").val(rowData.headName) && $("#IsKey").val(rowData.IsKey)
}
function initEvent() {
	var t = $("#name");
	$("#manage-form").submit(function(t) {
		t.preventDefault();
		postData()
	});
	t.focus().select();
	initValidator()
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
		var t = $.trim($("#name").val()),
            d = $.trim($("#desc").val()),
			h = $.trim($("#headName").val()),	//后期改为head_id
            k = $.trim($("#IsKey").val()),
			n = $.trim($("#headName").val())
			e = {
				id: rowData.id,
				name: t,
				desc : d,
				head_id : h,
                IsKey : k,
				head_name : n
			},
			i = "add" == oper ? "新增往来单位类别" : "修改往来单位类别";
		Public.ajaxPost(workcenter_save+"?act=" + ("add" == oper ? "add" : "update"), e, function(t) {
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
    $("#desc").val("");
    $("#headName").val("");
    $("#IsKey").val("");
}
var api = frameElement.api,
	oper = api.data.oper,
	rowData = api.data.rowData || {},
	callback = api.data.callback;
initPopBtns();
initField();
initEvent();