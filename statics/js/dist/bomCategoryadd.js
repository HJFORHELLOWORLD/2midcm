!function(t) {
       $(".wrapper").on("click", "#registerBtn", function(t) {
        t.preventDefault();
        var i = {
            PK_BOMCat_ID1:$("#select").val(),
            PK_BOMCat_ID2:$("#define").val(),
            Name: $("#name").val(),
            bom_id: $("#id").val(),
            pid: $("#upId").val(),
            Desc:  $("#desc").val(),

        };
        // console.log(typeof(JSON.stringify(i)));
        // console.log(i);

        i && Public.ajaxPost(bomCategory_save + "?act=add", {
            data: JSON.stringify(i)
        }, function (t) {
            if (200 === t.status) {
                parent.Public.tips({
                    content: "保存成功！"
                });
                window.location.reload(true);
            } else parent.Public.tips({
                type: 1,
                content: t.msg
            })
        })
    })
}(jQuery);