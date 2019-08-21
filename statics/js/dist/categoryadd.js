 ! function(t) {
     $(".wrapper").on("click", "#registerBtn", function(t) {
         t.preventDefault();
         var i = {
             "pk_industry_id":$("#number").val(),
             "name": $("#id").val(),
             "desc": $("#desc").val(),
             "creator_id": $("#realName").val(),
             "create_date": $("#founder").val()
         };

         i && Public.ajaxPost(category_save + "?act=add", {
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