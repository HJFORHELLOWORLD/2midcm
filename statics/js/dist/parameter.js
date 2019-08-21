$("#save").click(function() {
	var url,data;
	url = settings_parameter;
	data="companyname="+encodeURIComponent($.trim($('#companyName').val()));
	data+="&companyaddress="+encodeURIComponent($.trim($('#companyAddress').val()));
	data+="&companytel="+encodeURIComponent($.trim($('#companyTel').val()));
	data+="&companyfax="+encodeURIComponent($.trim($('#companyFax').val()));
	data+="&postcode="+encodeURIComponent($.trim($('#postcode').val()));
	$.dialog.confirm("修改系统参数将要刷新页面，是否确认修改？", function() {
		$.ajax({
		type:"post",
		cache:false,
		url:url,
		data:data,
		dataType: "json",
		timeout:10000,
		success:function(t){
			if (200 === t.status) {
				parent.window.$.cookie("ReloadTips", "系统参数设置成功");
				parent.window.location.reload()
			}
		},
		error: function(e) {
			Public.tips({
				type: 1,
				content: "保存失败" + e
			})
		}
		});
	})		
});