$(document).ready(function(){
    //窗口大小计算
    // var wid = $(window).width();
    // var hei = $(window).height();
    // var LeftWid = $(".LeftDiv").width();
    // var XGhei = $("#XGtop").height();
    // var RghtWid = (wid - LeftWid) + 'px';
    // $(".rghtDiv").css("width",RghtWid);
    // $(".LeftDiv").css("height",hei - XGhei);
    // $(".rghtDiv").css("height",hei - XGhei);
    // $(window).resize(function(){ 
    //     location.reload();
    // });

    //左侧导航点击效果
    var len = $(".LeftDiv ul li").length;
    $(".LeftDiv ul li").each(function(){
         $(".LeftDiv ul li").click(function(){
            $(this).addClass("hover");
            $(this).siblings("li").removeClass("hover");
            //$("#RenWu").text($(this).find("span").text());
         });
         for(var i=0;i<len;i++){
            $(".LeftDiv ul li").eq(i).click(function(){
                $(".rghtDiv .BoxList").eq($(this).index()).slideDown();
                $(".rghtDiv .BoxList").eq($(this).index()).siblings(".BoxList").slideUp();
            });
         }
    });

    //任务页面导航效果
    var lens = $(".nav li").length;
    $(".nav li").each(function(){
        $(".nav li").click(function(){
            $(this).addClass("active");
            $(this).siblings("li").removeClass("active");
         });
         for(var i=0;i<lens;i++){
            $(".nav li").eq(i).click(function(){
                // $(".rghtDiv .BoxList").eq($(this).index()).slideDown();
                // $(".rghtDiv .BoxList").eq($(this).index()).siblings(".BoxList").slideUp();
            });
         }
    });

    //账号新增时弹层
    $("#NewlyIncreased").click(function(){
        $(".ShadeBox").slideDown();
        $(".ShadeBox-top").find("span").text("账号类型");
    });
    $(".ShadeBox-top em").click(function(){
        $(".ShadeBox").slideUp();
        $(".ShadeBox-foot .list").eq(1).hide();
        $(".ShadeBox-foot .list").eq(2).hide();
        $(".ShadeBox-foot .list").eq(0).show();
        $("#ShadeBox-list ul li").find("i").hide();
    });
    var k = true;
    $("#ShadeBox-list ul li").click(function(){
        var s = '';
        $("#school").val(s);
        $("#schoolId").val(s);
        $(".ShadeBox-top").find("span").text("创建账号");
        $(".ShadeBox-foot .list").eq(0).slideUp();
        $(".ShadeBox-foot .list").eq(1).slideDown();
        $("#labelId").text($(this).text());
        $("#unit").val($(this).attr("id"));
        $("#typeId").val($(this).attr("data-type"));
        if($("#typeId").val() == '1'){
          // $("input[name=school]").attr('checked', false);
          // $("input[name=countyName]").attr('checked', false);
          // $("input[name=city]").attr('checked', false);
          // $("input[name=All]").attr('checked', false);
          $("#xuexiao").css("display","block");
          // $("input[name=school]").click(function(){
          //   var id = $(this).val();
          //   $.each($("input[name=school]"), function(index, val) {
          //       if($(val).val() !== id) {
          //          $(val).attr('checked', false);
          //       }
          //   });
          // });
          
          // var timeout=setTimeout(function () {
          //    if(taskList.isOpenUserId){
          //      taskList.showAll();
          //      taskList.isOpenUserId = false;
          //    }
          // }, 800);
        }
        if($("#typeId").val() == '2'){
          $("#xuexiao").css("display","none");
        }
        if($("#typeId").val() == '3'){
          $("#xuexiao").css("display","block");
          
        }
    });


    $("#default").click(function(){
       $(".ShadeBox-foot .list").eq(1).slideUp();
       $(".ShadeBox-foot .list").eq(0).slideDown();
    })
    $("#baoCun").click(function(){
              if($("#typeId").val() == '2'){

              }else{
                if($("#school").val() == ''){
                   $("#school").click(function(){
                        $("#school").css("border","#cccccc solid 1px");
                  });
                  $("#school").css(
                      "border","#ea1212 solid 1px"
                  );
                  $("#school").focus();
                   $("#school").keydown(function(){
                      $("#school").css("border","#cccccc solid 1px");
                   });
                  return false;
                }
              }
          
            if($("input[name=loginName]").val() == ''){
               $("input[name=loginName]").click(function(){
                    $("input[name=loginName]").css("border","#cccccc solid 1px");
              });
              $("input[name=loginName]").css(
                  "border","#ea1212 solid 1px"
              );
              $("input[name=loginName]").focus();
               $("input[name=loginName]").keydown(function(){
                  $("input[name=loginName]").css("border","#cccccc solid 1px");
               });
              return false;
            }
            if($("input[name=password]").val() == ''){
               $("input[name=password]").click(function(){
                    $("input[name=password]").css("border","#cccccc solid 1px");
              });
              $("input[name=password]").css(
                  "border","#ea1212 solid 1px"
              );
              $("input[name=password]").focus();
               $("input[name=password]").keydown(function(){
                  $("input[name=password]").css("border","#cccccc solid 1px");
               });
              return false;
            }
            if($("input[name=password]").val().length < 6){
                     layer.alert('密码长度必须大于六位！');
                    return false;
            }
            if($("input[name=username]").val() == ''){
               $("input[name=username]").click(function(){
                    $("input[name=username]").css("border","#cccccc solid 1px");
              });
              $("input[name=username]").css(
                  "border","#ea1212 solid 1px"
              );
              $("input[name=username]").focus();
               $("input[name=username]").keydown(function(){
                  $("input[name=username]").css("border","#cccccc solid 1px");
               });
              return false;
            }
            $("#baoCun").attr("type","submit");
    })
    $(".baoCun").click(function(){//合同

            if($("#school").val() == ''){
               $("#school").click(function(){
                    $("#school").css("border","#cccccc solid 1px");
              });
              $("#school").css(
                  "border","#ea1212 solid 1px"
              );
              $("#school").focus();
               $("#school").keydown(function(){
                  $("#school").css("border","#cccccc solid 1px");
               });
              return false;
            }
            if($("input[name=number]").val() == ''){
               $("input[name=number]").click(function(){
                    $("input[name=number]").css("border","#cccccc solid 1px");
              });
              $("input[name=number]").css(
                  "border","#ea1212 solid 1px"
              );
              $("input[name=number]").focus();
               $("input[name=number]").keydown(function(){
                  $("input[name=number]").css("border","#cccccc solid 1px");
               });
              return false;
            }
            if($("input[name=whiteNnm]").val() == ''){
               $("input[name=whiteNnm]").click(function(){
                    $("input[name=whiteNnm]").css("border","#cccccc solid 1px");
              });
              $("input[name=whiteNnm]").css(
                  "border","#ea1212 solid 1px"
              );
              $("input[name=whiteNnm]").focus();
               $("input[name=whiteNnm]").keydown(function(){
                  $("input[name=whiteNnm]").css("border","#cccccc solid 1px");
               });
              return false;
            }
            if($("input[name=acrylic]").val() == ''){
               $("input[name=acrylic]").click(function(){
                    $("input[name=acrylic]").css("border","#cccccc solid 1px");
              });
              $("input[name=acrylic]").css(
                  "border","#ea1212 solid 1px"
              );
              $("input[name=acrylic]").focus();
               $("input[name=acrylic]").keydown(function(){
                  $("input[name=acrylic]").css("border","#cccccc solid 1px");
               });

              return false;
            }
            $(".baoCun").attr("type","submit");
    })
    //人员弹框
    $(".UserBo").click(function(){
        if($(this).attr("data-type") == '0'){
            $(".userlist").eq(0).show();
            $(".userlist").eq(1).hide();
        }else{
            $(".userlist").eq(1).show();
            $(".userlist").eq(0).hide();
        }
    });
    $(".succes").click(function(){
            if($("input[name=name]").val() == ''){
               $("input[name=name]").click(function(){
                    $("input[name=name]").css("border","#cccccc solid 1px");
              });
              $("input[name=name]").css(
                  "border","#ea1212 solid 1px"
              );
              $("input[name=name]").focus();
               $("input[name=name]").keydown(function(){
                  $("input[name=name]").css("border","#cccccc solid 1px");
               });
              return false;
            }
            if($("#school").val() == ''){
               $("#school").click(function(){
                    $("#school").css("border","#cccccc solid 1px");
              });
              $("#school").css(
                  "border","#ea1212 solid 1px"
              );
              $("#school").focus();
               $("#school").keydown(function(){
                  $("#school").css("border","#cccccc solid 1px");
               });
              return false;
            }
            if($("#tupian1").attr("src") == '/manage/img/nav/image.png'){
                var school = layer.alert('请选择正面模板图！',{                
                  skin:'layer-ext-moon'
                }); 
                return false;
            }
            if($("#tupian").attr("src") == '/manage/img/nav/image.png'){
                var school = layer.alert('请选择反面模板图！',{                
                  skin:'layer-ext-moon'
                }); 
                return false;
            }
            $(".succes").attr("type","submit");
              setInterval(function(){
               window.location.reload();
            },200)
    })
     var reg = /^[0-9]+$/;
     $("#numberList").keydown(function(){
       if(!reg.test($("#numberList").val())){
        $("#numberList").val($("#numberList").val().substring(0,$("#numberList").val().length - 1));
       }
     })
     $("#numberList1").keydown(function(){
       if(!reg.test($("#numberList1").val())){
        $("#numberList1").val($("#numberList1").val().substring(0,$("#numberList1").val().length - 1));
       }
     })  
     $("#numberList2").keydown(function(){
       if(!reg.test($("#numberList2").val())){
        $("#numberList2").val($("#numberList2").val().substring(0,$("#numberList2").val().length - 1));
       }
     })      
    
            
 $("#submitp").click(function(){
     if($("#zhiwu").val() == ''){
       $("#zhiwu").click(function(){
            $("#zhiwu").css("border","#cccccc solid 1px");
      });
      $("#zhiwu").css(
          "border","#ea1212 solid 1px"
      );
      $("#zhiwu").focus();
       $("#zhiwu").keydown(function(){
          $("#zhiwu").css("border","#cccccc solid 1px");
       });
      return false;
    }
    if($("#gonghao").val() == ''){
       $("#gonghao").click(function(){
            $("#gonghao").css("border","#cccccc solid 1px");
      });
      $("#gonghao").css(
          "border","#ea1212 solid 1px"
      );
      $("#gonghao").focus();
       $("#gonghao").keydown(function(){
          $("#gonghao").css("border","#cccccc solid 1px");
       });
      return false;
    }
     if($("#ruxue").val() == ''){
       $("#ruxue").click(function(){
            $("#ruxue").css("border","#cccccc solid 1px");
      });
      $("#ruxue").css(
          "border","#ea1212 solid 1px"
      );
      $("#ruxue").focus();
       $("#ruxue").keydown(function(){
          $("#ruxue").css("border","#cccccc solid 1px");
       });
      return false;
    }
    if($("#zhuanye").val() == ''){
       $("#zhuanye").click(function(){
            $("#zhuanye").css("border","#cccccc solid 1px");
      });
      $("#zhuanye").css(
          "border","#ea1212 solid 1px"
      );
      $("#zhuanye").focus();
       $("#zhuanye").keydown(function(){
          $("#zhuanye").css("border","#cccccc solid 1px");
       });
      return false;
    }
    if($("#ban").val() == ''){
       $("#ban").click(function(){
            $("#ban").css("border","#cccccc solid 1px");
      });
      $("#ban").css(
          "border","#ea1212 solid 1px"
      );
      $("#ban").focus();
       $("#ban").keydown(function(){
          $("#ban").css("border","#cccccc solid 1px");
       });
      return false;
    }
    if($("#xuehao").val() == ''){
       $("#xuehao").click(function(){
            $("#xuehao").css("border","#cccccc solid 1px");
      });
      $("#xuehao").css(
          "border","#ea1212 solid 1px"
      );
      $("#xuehao").focus();
       $("#xuehao").keydown(function(){
          $("#xuehao").css("border","#cccccc solid 1px");
       });
      return false;
    }
    if($("#xingming").val() == ''){
       $("#xingming").click(function(){
            $("#xingming").css("border","#cccccc solid 1px");
      });
      $("#xingming").css(
          "border","#ea1212 solid 1px"
      );
      $("#xingming").focus();
       $("#xingming").keydown(function(){
          $("#xingming").css("border","#cccccc solid 1px");
       });
      return false;
    }
    $("#submitp").attr("type","submit");
    setInterval(function(){
               window.location.reload();
            },200)
 })

// $('.limit').each(function() {
//          var maxwidth = 30;
//          if($(this).text().length > maxwidth) {
//              $(this).text($(this).text().substring(0, maxwidth));
//              $(this).html($(this).html() + "...");
//          }
//     });
});