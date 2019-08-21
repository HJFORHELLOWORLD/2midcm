          $(".wrapper").on("click", "#registerBtn", function(t) {
              t.preventDefault();
              var i = {
                  pk_area_id: $("#number").val(),
                  upArea_id: $("#id").val(),
                  name: $("#desc").val(),
                  creator_id: $("#founder").val()
              };
              console.log(typeof(JSON.stringify(i)));
              console.log(JSON.stringify(i));

              i && Public.ajaxPost(area_save + "?act=add", {
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

          });


