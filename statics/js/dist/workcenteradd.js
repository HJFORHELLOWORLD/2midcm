!function(t) {
       $(".wrapper").on("click", "#registerBtn", function(t) {
        t.preventDefault();
        var i = {
            pk_wc_id:$("#number").val(),
            wc_name: $("#id").val(),
            desc:  $("#desc").val(),
            head_id:$("#realName").val(),
            creator_id: $("#founder").val()
        };
         console.log(typeof(JSON.stringify(i)));
         console.log(i);
         console.log(typeof(i));


        i && Public.ajaxPost(workcenter_save + "?act=add", {
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