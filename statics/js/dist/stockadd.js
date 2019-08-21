!  function() {
    $(".wrapper").on("click", "#registerBtn", function(t) {
        t.preventDefault();
        var i = {
            pk_stock_id:$("#number").val(),
            stock_name: $("#id").val(),
            desc:  $("#desc").val(),
            head_id: $("#realName").val(),
            creator_id: $("#founder").val()
        };

        i && Public.ajaxPost(stock_save + "?act=add", {
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