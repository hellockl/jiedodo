var taskList = new Vue({
    el: '#task',
    data: {
        page:1,//分页码
        keyword:'',//
        cardList:[],//制卡名单数组
        schoolList:window.schoolList,
        checkUserAll:false,//选择群体 全部
        schoolName:'',//选择学校
        schoolId:'',//获取学校Id
        schoolText:'',
        contractNumber:'',
        contractList:[],//合同数据
        templateList:[],//选择模板弹框列表数据
        backImg:'',//模板背面图
        frontImg:'',//模板正面图
        seletedTemplate:{}, //被选中的模板对象
        userList:[],//获取全体人员数据
        userList1:[],//获取全体人员数据
        selectedUser:{},//选中的人员名单
        pitch:'',//选中状态
        selectedName:[], //选中的学校类型名称
        taskTypeId:"",//类型id
        isOpenUser:true,
        isOpenUserId:true,
        cardListIndex:0,
        schoolListId:[],
        schoolListNumber:[],
        schoolListName:[],
        schoolListIdarr:[],
        unbind:true,
        dataIdlist:[],
        shu:'',
        tmpNumber:'',
        taskType:{},
        arrt:0,
        te:'',
        err:'',
        num:'0',
    },
    methods: {
        initialData: function() {//初始加载数据
          var _this = this;
          _this.cardList = []
        },
        taskSpread:function(){//选择类型添加模块
          if(this.schoolId == ''){//如果没有选择学校提示
            var school = layer.alert('请选择学校！',{                
                skin:'layer-ext-moon'
            }); 
            return false;
         }
          var _this = this;
          layer.open({
            type: 1
            ,title:'选择类型' //不显示标题栏
            ,closeBtn: false
            ,area: '300px;'
            ,shade: 0.8
            ,id: 'LAY_layuipro' //设定一个id，防止重复弹出
            ,btn: ['关闭']
            ,btnAlign: 'c'
            ,moveType: 1 //拖拽模式，0或者1
            ,content: '<div class="frame" id="task"><ul><li data-id="1"><a href="javascript:;">全卡</a></li><li data-id="2"><a href="javascript:;">白卡</a></li><li data-id="3"><a href="javascript:;">亚克力</a></li></div>'
            ,success: function(layero){

            }
          });
         $(".frame ul li").click(function(){
            layer.closeAll();
            var taskTypeName = $(this).find("a").text();
            _this.taskTypeId = $(this).attr("data-id");
            _this.taskType = {"taskTypeId":_this.taskTypeId,"num":'0',"taskTypeName":taskTypeName,"selectedUser":[],"templateId":'0',"templateName":'',userNumber:'',"xlsId":''};
            _this.cardList.push(_this.taskType);
         });
        },
        removeModule:function(index){//删除模块功能
          var _this = this;
          var prompt = layer.alert('是否要删除！',{
            skin:'layer-ext-moon',
            btn: ['是','否'],
            yes:function () {
              console.log(_this.cardList[index].templateId)
              layer.close(prompt);
              if(index > -1) {
                   _this.cardList.splice(index, 1);
              }
              console.log(_this.cardList)
            },
            no:function(){
                layer.close(prompt);
            }
          }); 
        },
        selectionPopulation:function(index){//选择群体功能
          var _this = this;
          _this.cardListIndex = index;
          if(this.schoolId == ''){//如果没有选择学校提示
              var school = layer.alert('请选择学校！',{                
                skin:'layer-ext-moon'
              }); 
              return false;
           }
          var lay = layer.open({
            type: 1,
            title: '选择群体',
            shadeClose: true, //点击遮罩关闭层
            area : ['800px' , '600px'],
            content: $("#taskSelection"),
            btn: ['确定','取消'],
            success:function(){
              $.ajax({
                url: '/manage/task/doUserList',
                type: 'post',
                datatype: 'json',
                data:{
                  index:index,
                  cardList:_this.cardList,
                  userList:_this.userList1
                },
                success: function (e) {
                  var tmp = JSON.parse(e);
                  _this.userList = tmp.data;
                  setTimeout(function(){
                    if($("#taskSelection ul li").length == 0){
                       // layer.close(lay);
                       var school = layer.alert('没有可选择的用户！',{                
                        skin:'layer-ext-moon',
                        yes:function(){
                          layer.close(lay);
                          layer.close(school);
                        }
                       }); 
                    }
                  },0)
                  if(tmp.err_code == -1){
                    var school = layer.alert('暂无数据！',{                
                      skin:'layer-ext-moon'
                    }); 
                  }
                }
              });

              // if(_this.isOpenUser){
                setInterval(function(){
                  _this.showAll();
                },100)
                
                // _this.isOpenUser = false;
              // }
            },
            yes:function () {
              layer.close(lay);
              //[{"schoolType":"1","gradeList":[{"gradeId":"2012","type":[{"id":1},{"id":2}]}]}]
              var tmp = [];
              var sldName = '';
              var userNumber = 0;
              _this.userList.forEach(function(schoolType){
                if(schoolType.checked){
                  if(sldName.length == 0){
                    sldName = schoolType.schoolTypeNmae;
                  }else{
                    sldName = sldName + "，" + schoolType.schoolTypeNmae;
                  }
                  
                  var gradeList = [];
                  schoolType.gradeList.forEach(function(grade){
                    if(grade.checked){  
                      var typeList = [];
                      grade.type.forEach(function(type){
                        if(type.checked){
                          userNumber = Number(userNumber) + Number(type.number);
                          typeList.push({"id":type.id});
                        }
                      });
                      gradeList.push({"gradeId":grade.gradeId,type:typeList});
                    }
                  });
                  tmp.push({"schoolType":schoolType.schoolType,"gradeList":gradeList});
                }
              });
              _this.cardList[index].selectedUser = tmp;
              _this.cardList[index].userNumber = userNumber + _this.tmpNumber;
              _this.shu = userNumber;
              //$.ajax({url:"/manage/task/jsonTest",type:'post',data:{"json":_this.cardList}});
              if(_this.selectedName[index] == undefined){
                _this.selectedName.push(sldName);
              }else{
                _this.$set(_this.selectedName,index,sldName);
              }
            },
            no:function(){
                layer.close(lay);
            }
          });
            
        },
        showAll:function(){//选中效果
          var _this = this;
          $("#taskSelection p").find("em").off("click");
          $("#taskSelection p").find("input").off("click");
          $("#taskSelection p").each(function(){
            $(this).find("em").click(function(){
                $(this).parent().parent().find("ul").slideToggle();
                $(this).find("img").toggle();
            });
           $(this).find("input").click(function(){
              if($(this).is(':checked')){
                $(this).parent().parent().next("ul").slideDown();
                $(this).parent().parent().next("ul").find("ul").slideDown();
                $(this).parent().prev("em").find("img").hide();  
              }else{
                $(this).parent().parent().next("ul").slideUp();
                $(this).parent().parent().next("ul").find("ul").slideUp();
                $(this).parent().prev("em").find("img").show();  
              }
            });
          }); 
          $(".selectSchool p").find("em").off("click");
          $(".selectSchool p").find("i").off("click");
          $(".selectSchool p").each(function(){
            $(this).find("em").on("click",function(){
              if($(this).parent().next("ul").css("display") == 'none'){
                $(this).parent().next("ul").slideDown();
                $(this).find("img").hide();
              }else{
                $(this).parent().next("ul").slideUp();
                $(this).find("img").show();
              }    

            });

            $(this).find(".spanlist").click(function(){
                  $(this).parent().next("ul").slideToggle();
                  $(this).prev("em").find("img").toggle();         
            });
            $(this).find("i").click(function(){
                $(this).parent().next("ul").slideToggle();
                $(this).parent().find("em").find("img").toggle(); 
            });
            $(this).find(".All").click(function(){
              if($(this).is(':checked')){
                $(this).parent().parent().next("ul").slideDown();
                $(this).parent().parent().next("ul").find("ul").slideDown();
                $(this).parent().prev("em").find("img").hide();  
              }else{
                $(this).parent().parent().next("ul").slideUp();
                $(this).parent().parent().next("ul").find("ul").slideUp();
                $(this).parent().prev("em").find("img").show();  
              }
            });
          }); 
          $("input[name=checkAll]").click(function(){
            if($(this).is(':checked')){
              $(this).parent().parent().next("ul").find("ul").slideDown();
            }else{
              $(this).parent().parent().next("ul").find("ul").slideUp();
            }   
          })
        },
        selectTemplate:function(index){//选择模板功能
          //this.cardListIndex = index;
          var _this = this;
          if(_this.schoolId == ''){//如果没有选择学校提示
            var school = layer.alert('请选择学校！',{                
              skin:'layer-ext-moon'
            }); 
            return false;
           }else{
            $.ajax({
            url: '/manage/api/templateList',
            type: 'get',
            datatype: 'json',
            data:{
              schoolId:_this.schoolId,
            },
            success: function (e) {
              var tmp = JSON.parse(e);
              _this.templateList = tmp.data;
              _this.templateList.forEach(function(template,i){
                if(template.id == _this.cardList[index].templateId){
                  _this.backImg = template.backImg;
                  _this.frontImg = template.frontImg;
                 $(".TemplateLeft ul li").eq(i).addClass("hoverTemp").siblings("li").removeClass("hoverTemp");
                 $(".TemplateLeft ul li").eq(i).find("i").css("display","block");
                 $(".TemplateLeft ul li").eq(i).siblings("li").find("i").css("display","none");
                }
                if(_this.cardList[index].templateId == 0){
                  _this.templateImg(0);
                  $(".TemplateLeft ul li").eq(0).addClass("hoverTemp").siblings("li").removeClass("hoverTemp");
                  $(".TemplateLeft ul li").eq(0).find("i").css("display","block");
                  $(".TemplateLeft ul li").eq(0).siblings("li").find("i").css("display","none");
                }
              });
              }
              });
              $(".TemplateLeft ul li").click(function(){
                $(this).addClass("hoverTemp").siblings("li").removeClass("hoverTemp");
                $(this).find("i").css("display","block");
                $(this).siblings("li").find("i").css("display","none");
              })
              // if($(".TemplateLeft ul li").hasClass("hoverTemp")){
              //   $(".TemplateLeft ul li").css("background","");
              // }
           }
            var template = layer.open({
            type: 1,
            title: '选择模板',
            shadeClose: true, //点击遮罩关闭层
            area : ['800px' , '600px'],
            content: $("#templateList"),
            btn: ['确定','取消'],
            yes:function () {
              _this.cardList[index].templateId = _this.seletedTemplate.id;
              _this.cardList[index].templateName = _this.seletedTemplate.name;
              layer.close(template);
            },
            no:function(){
              layer.close(template);
            }
            });

        },
        obtainUser:function(){//获取全体人员
          var _this = this;
          $.ajax({
            url: '/manage/api/userList',
            type: 'get',
            datatype: 'json',
            data:{
              schoolId:_this.schoolId,
            },
            success: function (e) {
             var tmp = JSON.parse(e);
             _this.userList = tmp.data;
             _this.userList1 = tmp.data;
             _this.err = tmp.err_code;
            }
          })
        },
        selectSchool:function(){//选择学校
          var _this = this;
          var Reject = layer.open({
            type: 1,
            title: '选择学校',
            shadeClose: true, //点击遮罩关闭层
            area : ['80%' , '70%'],
            content: $("#selectSchool"),
            btn: ['确定','取消'],
            yes:function () {
            _this.schoolName = _this.schoolText;
            //选择学校获取学校id执行接口获取合同数据
            _this.obtainUser();
            $.ajax({
              url: '/manage/api/contractList',
              type: 'get',
              datatype: 'json',
              data:{
                schoolId:_this.schoolId,
              },
              success: function (e) {
                var tmp = JSON.parse(e);
                _this.contractList = tmp.data;
                if(tmp.err_code == -1){
                    var school = layer.alert('此学校没有合同数据！',{                
                      skin:'layer-ext-moon'
                    }); 
                    return false;
                }
                _this.obtainUser();
                if(_this.err == -1){
                  _this.schoolName = '';
                  _this.schoolId = '';
                  var school = layer.alert('此学校没有人员数据！',{                
                    skin:'layer-ext-moon'
                  }); 
                  return false;
                  }
                  $.ajax({
                    url: '/manage/api/templateList',
                    type: 'get',
                    datatype: 'json',
                    data:{
                      schoolId:_this.schoolId,
                    },
                    success: function (e) {
                      var tmp = JSON.parse(e);
                      _this.templateList = tmp.data;
                       if(tmp.err_code == -1){
                       var school = layer.alert('此学校没有模板数据！',{                
                          skin:'layer-ext-moon'
                        }); 
                        return false;
                       }else{
                         layer.close(Reject);
                       }
                      }
                  });
                
              }
            })
            
              
              },
              no:function(){
                layer.close(Reject);
              }
          })
        },
        templateImg:function(index){//点击获取模板图片并换掉
           this.backImg = this.templateList[index].backImg;
           this.frontImg = this.templateList[index].frontImg;
           this.seletedTemplate = {"id":this.templateList[index].id,"name":this.templateList[index].name};
           $(".TemplateLeft ul li").click(function(){
                $(this).addClass("hoverTemp").siblings("li").removeClass("hoverTemp");
                $(this).find("i").css("display","block");
                $(this).siblings("li").find("i").css("display","none");
           })
        },
        checkAll:function(){
          if(this.checkUserAll == false){
            this.userList.forEach(function(schoolType){
              schoolType.checked = true;
              schoolType.gradeList.forEach(function(grade){
                grade.checked = true;
                grade.type.forEach(function(type){
                  type.checked = true;
                });
              });
            });
          }else{
            this.userList.forEach(function(schoolType){
              schoolType.checked = false;
              schoolType.gradeList.forEach(function(grade){
                grade.checked = false;
                grade.type.forEach(function(type){
                  type.checked = false;
                });
              });
            });
          };
        },
        checkSchoolType:function (index) {
          if(this.userList[index].checked == false){
            this.userList[index].checked = true;
            var data = this.userList[index].gradeList;
            data.forEach(function(grade){
              grade.checked = true;
              grade.type.forEach(function(type){
                type.checked = true;
              });
            });
          }else{
            this.userList[index].checked = false;
            var data = this.userList[index].gradeList;
            data.forEach(function(grade){
              grade.checked = false;
              grade.type.forEach(function(type){
                type.checked = false;
              });
          });
          }
          
        },
        checkGrade:function(schoolIndex,gradeIndex){
          if(this.userList[schoolIndex].gradeList[gradeIndex].checked == false){
            this.userList[schoolIndex].checked = true;
            this.userList[schoolIndex].gradeList[gradeIndex].checked = true;
            var data = this.userList[schoolIndex].gradeList[gradeIndex].type;
            data.forEach(function(type){
              type.checked = true;
            });
          }else{
            var i = 0;
            this.userList[schoolIndex].gradeList.forEach(function(tmp){
              if(tmp.checked) i++;
            });
            if(i == 1) this.userList[schoolIndex].checked = false;
            this.userList[schoolIndex].gradeList[gradeIndex].checked = false;
            var data = this.userList[schoolIndex].gradeList[gradeIndex].type;
            data.forEach(function(type){
              type.checked = false;
            });
          }
        },
        checkTyp:function(schoolIndex,gradeIndex,index){
           if(this.userList[schoolIndex].gradeList[gradeIndex].type[index].checked == false){
            this.userList[schoolIndex].checked = true;
            this.userList[schoolIndex].gradeList[gradeIndex].checked = true;
            this.userList[schoolIndex].gradeList[gradeIndex].type[index].checked = true;
            var data = this.userList[schoolIndex].gradeList[gradeIndex].type;
          }else{
            var i = 0;
            this.userList[schoolIndex].gradeList[gradeIndex].type.forEach(function(tmp){
              if(tmp.checked) i++;
            });
            if(i == 1){
              var j = 0;
              this.userList[schoolIndex].gradeList.forEach(function(tmp){
                if(tmp.checked) j++;
              });
              if(j == 1) this.userList[schoolIndex].checked = false;
              this.userList[schoolIndex].gradeList[gradeIndex].checked = false;
              this.userList[schoolIndex].gradeList[gradeIndex].type[index].checked = false;
            }else{
              this.userList[schoolIndex].gradeList[gradeIndex].type[index].checked = false;
            }
          }
        },
        taskUpdate:function(){//提交所有  发布任务
          var _this = this;
          if(_this.schoolId == ''){
            var school = layer.alert('请选择学校！'); 
            return false;
          }
          if($("#taskName").val() == ''){
            $("#taskName").click(function(){
              $("#taskName").css("border","#cccccc solid 1px");
            });
            $("#taskName").css("border","#ea1212 solid 1px");
            $("#taskName").focus();
            $("#taskName").keydown(function(){
              $("#taskName").css("border","#cccccc solid 1px");
            });
            return false;
          }
          if($("#address").val() == ''){
            $("#address").click(function(){
              $("#address").css("border","#cccccc solid 1px");
            });
            $("#address").css("border","#ea1212 solid 1px");
            $("#address").focus();
            $("#address").keydown(function(){
              $("#address").css("border","#cccccc solid 1px");
            });
            return false;
          }
          if(_this.contractNumber == ''){
            $(".contract").click(function(){
              $(".contract").css("border","#cccccc solid 1px");
            });
            $(".contract").css("border","#ea1212 solid 1px");
            $(".contract").focus();
            $(".contract").keydown(function(){
              $(".contract").css("border","#cccccc solid 1px");
            });
            return false;
          }

          var contractOk = false;
          if(_this.contractList != undefined){
          _this.contractList.forEach(function(contract){
            if(contract.number == _this.contractNumber){
              contractOk = true;
              return;
            }
          });
          }
          if(!contractOk){
            layer.alert('无效合同编号，请重新填写合同编号');
            return false;
          }
          if($("#express").val() == ''){
            $("#express").click(function(){
              $("#express").css("border","#cccccc solid 1px");
            });
            $("#express").css("border","#ea1212 solid 1px");
            $("#express").focus();
            $("#express").keydown(function(){
              $("#express").css("border","#cccccc solid 1px");
            });
            return false;
          }
          if(_this.cardList.length == 0){
            var school = layer.alert('请选择人员和模板！'); 
            return false;
          }
           var cardListYes = false;
          _this.cardList.forEach(function(card,i){
            //if (card.selectedUser.length == 0 && card.xlsId == '') {
            //   if(card.taskTypeId == 1 || card.taskTypeId == 3){
            //     var lay = layer.alert('请选择群体！');
            //     cardListYes = false;
            //     return false;
            //   }
            // }else{
            //   cardListYes = true;
            // }

            // if (card.templateId == '0') {
            //   layer.alert('请选择模板！');
            //   cardListYes = false;
            //   return false;
            // }else{
            //   cardListYes = true;
            // }
            if(card.taskTypeId == 2){
              for(var i=0;i<$(".taskBoxlist").length;i++){
                if($(".taskBoxlist").eq(i).find(".taskTypeName").text() === '白卡'){
                    var is = $(".taskBoxlist").eq(i).find(".shuzi").val();
                    _this.cardList[i].num = is;
                }
              }
            }
            
             for(var i=0;i<$(".taskBoxlist").length;i++){
                if(card.taskTypeId == 1 || card.taskTypeId == 3){
                  if(_this.cardList[i].selectedUser.length == 0 && card.xlsId == ''){
                    if(_this.cardList[i].taskTypeId == 1 || _this.cardList[i].taskTypeId == 3){
                      var lay = layer.alert('请选择群体！');
                      cardListYes = false;
                      return false;
                    }
                  }else{
                    cardListYes = true;
                  }
                 }
                  if(_this.cardList[i].templateId == 0){
                      layer.alert('请选择模板！');
                      cardListYes = false;
                      return false;
                  }else{
                    cardListYes = true;
                  }
               
                if(card.taskTypeId == 2){
                  if($(".taskBoxlist").eq(i).find(".taskTypeName").text() === '白卡'){
                   if($(".taskBoxlist").eq(i).find(".shuzi").val() == ''){
                    $(".taskBoxlist").eq(i).find(".shuzi").focus();
                     layer.alert('请输入制卡人数！');
                     cardListYes = false;
                     return false;
                  }
                  }
                }
                 if($(".taskBoxlist").eq(i).find(".control").val() == ''){
                        layer.alert('请选择模板！');
                        cardListYes = false;
                        return false;
                    }else{
                      cardListYes = true;
                    }
             }
          });
          for(var i=0;i<$(".taskBoxlist").length;i++){
            if($(".taskBoxlist").eq(i).find(".shuzi").val() == ''){
                return false;
            }
            if($(".taskBoxlist").eq(i).find(".mi").val() == ''){
                if(_this.cardList[i].xlsId != ''){
            
                 }else{
                  return false;
                 }
            }
          }
          if(!cardListYes){
            return false;
          }
           for(var u=0;u<_this.cardList.length;u++){
             _this.cardList[u].type = 1;
             if(_this.cardList[u].selectedUser instanceof Array){
                _this.cardList[u].type = 0;
             }
           }
           $.ajax({
            url: '/manage/task/taskUpdate',
            type: 'post',
            datatype: 'json',
            data:{
             id:$("#taskId").val(),
             schoolId:_this.schoolId,
             taskName:$("#taskName").val(),
             address:$("#address").val(),
             contract:$(".contract").val(),
             express:$("#express").val(),
             cardList:_this.cardList,
            },
            success: function (e) {
              var tmp = JSON.parse(e);
               if(tmp.err_code == 0){
                window.location.href = '/manage/task'
               }
            }
           })
        },
        createTemplate:function(){//创建模板
          var _this = this;
          var o = '';
          _this.schoolListName = [];
          _this.schoolListNumber = [];
          $("#school").val(o);
          $("#schoolId").val(o);
          $("#templateName").val(o);
         $.ajax({
            url: '/manage/api/schoolList',
            type: 'get',
            datatype: 'json',
            success: function (e) {
              var tmp = JSON.parse(e);
              _this.schoolListId = tmp.data
            }
          })
         setTimeout(function(){
            $("input[name=school]").click(function(){
            var id = $(this).val();
            $.each($("input[name=school]"), function(index, val) {
                if($(val).val() !== id) {
                   $(val).attr('checked', false);
                }
            });
          });
            $(".i").css("display","block");
         },800)
         
          var create = layer.open({
            type: 1,
            title: '创建模板',
            shadeClose: true, //点击遮罩关闭层
            area : ['610px' , '650px'],
            content: $("#templateBoxDiv")
          });
        },
        choiceSchool:function(){//模板弹框选择学校
          var _this = this;
          var o = '';
          var schoolength = $(".selectSchool .pr").length;
          // _this.schoolListName = [];
          // _this.schoolListNumber = [];
          // $("#school").val(o);
          // $("#schoolId").val(o);
          for(e=0;e<schoolength;e++){
             if($(".selectSchool .pr").eq(e).find("li").find("input[name='school']").is(":checked")){
                $(".selectSchool .pr").eq(e).find("ul").css("display","block");
                $(".selectSchool .pr").eq(e).find("em").find("img").hide();
                //$(".selectSchool .pr").eq(e).siblings("li").find("ul").css("display","none");
             }
          } 
          
          var Reject = layer.open({
          type: 1,
          title: '选择学校',
          shadeClose: true, //点击遮罩关闭层
          area : ['80%' , '70%'],
          content: $("#selectSchool"),
          btn: ['确定','取消'],
          yes:function () {
            $("#schoolId").val( _this.schoolListNumber);
            $("#school").val(_this.schoolListName)
            $("#sch").val(_this.schoolListName)
            $("#oolId").val( _this.schoolListNumber);
            if($("#souSuo").val() == '1'){
              var xiang='';
              var hou='';
              var status='';
              if($(".checkboxInput").is(':checked')){
                xiang='&xiang=1';
              }
              if($(".checkboxInput1").is(':checked')){
                hou='&hou=1';
              }
              if($("#status").val()!=''){
                status='&status='+$("#status").val();
              }
              window.location.href='/manage/task?schoolId='+_this.schoolListNumber+xiang+hou+status;
            }

            layer.close(Reject); 
            _this.getIds();
           },
           no:function(){
            layer.close(Reject);
           }
          });
            if(_this.isOpenUserId){
             _this.showAll();
              _this.isOpenUserId = false;
             }else{
              _this.isOpenUserId = true;
               _this.showAll();
            }

          
          
          
        },
        dlid:function (e,id,name) {
          var input = e.target
          var a = $(input).is(':checked')
          if (!a) {
          var index = this.schoolListNumber.indexOf(id);
          if (index > -1) {
          this.schoolListNumber.splice(index, 1);
          this.dataIdlist.splice(index, 1);
          this.schoolListName.splice(index, 1);
          }
          } else {
          this.schoolListNumber.push(id);
          this.schoolListName.push(name);
          this.dataIdlist.push(id);                           
          }
        },
        newlyIncreased:function(){//新增
         var _this = this;
         $(".ShadeBox-foot .list").eq(1).hide();
         $(".ShadeBox-foot .list").eq(0).show();
         var o = '';
          _this.schoolListName = [];
          _this.schoolListNumber = [];
          $("#school").val(o);
          $("#schoolId").val(o);
          $.ajax({
          url: '/manage/api/schoolList',
          type: 'get',
          datatype: 'json',
          success: function (e) {
          var tmp = JSON.parse(e);
          _this.schoolListId = tmp.data
          }
          })
          layer.open({
          type: 1,
          title: '选择类型',
          shadeClose: true, //点击遮罩关闭层
          area : ['520px' , '490px'],
          content: $("#ShadeBox")
          });
        },
        taskEditor:function(id,typeId){//编辑账号
          $("#xuexiao1").show();
          var lenTr = $(".editorIndex").length;
          var _this = this;
          _this.te = typeId;
          _this.isOpenUser = false;
          var o = '';
          _this.schoolListName = [];
          _this.schoolListNumber = [];
          $("#sch").val(o);
          $("#oolId").val(o);
          $.ajax({
          url: '/manage/admin/getAdmin?id='+id,
          type: 'get',
          datatype: 'json',
          success: function (e) {
          var tmp = JSON.parse(e);
          $("#sch").val(tmp.data.school);
          $("#username").val(tmp.data.username);
          $("#oolId").val(tmp.data.unit);
          }
          });
          if(typeId == '2'){
          $("#zhlx").val('需求方');
          $("#zhlx").attr("data-id","2");
          $.ajax({
          url: '/manage/admin/updateSchool?id='+id,
          type: 'get',
          datatype: 'json',
          success: function (e) {
          var tmp = JSON.parse(e);
          _this.schoolListId = tmp.data;
          }
          });
          }else{
          $.ajax({
          url: '/manage/api/schoolList?id='+id,
          type: 'get',
          datatype: 'json',
          success: function (e) {
          var tmp = JSON.parse(e);
          _this.schoolListId = tmp.data;
          }
          });               
          }
          if(typeId == 1){
            $("#zhlx").val('管理员');
            $("#zhlx").attr("data-id","1");
            $("#xuexiao1").hide();
          }else{
             $("#colg").css("display","block");
          }
          if(typeId == '3'){
            $("#zhlx").val('工厂');
            $("#zhlx").attr("data-id","3");
          }
          if(typeId == '4'){
            $("#zhlx").val('商务');
            $("#zhlx").attr("data-id","4");
            setInterval(function(){
              $(".selectSchool i").attr("style","");
            })
          }
          if(typeId == '5'){
            $("#zhlx").val('售后');
            $("#zhlx").attr("data-id","5");
            setInterval(function(){
              $(".selectSchool i").attr("style","");
            })
          }
          var editor = layer.open({
            type: 1,
            title: '编辑账号',
            shadeClose: true, //点击遮罩关闭层
            area : ['520px' , '300px'],
            content: $("#taskEditor"),
            btn: ['保存','取消'],
            yes:function(){
              if(typeId == '1'){

              }else{
              if($("#sch").val() == ''){
              $("#sch").click(function(){
              $("#sch").css("border","#cccccc solid 1px");
              });
              $("#sch").css("border","#ea1212 solid 1px");
              $("#sch").focus();
              $("#sch").keydown(function(){
              $("#sch").css("border","#cccccc solid 1px");
              });
              return false;
              }
              }
              if($("#username").val() == ''){
              $("#username").click(function(){
              $("#username").css("border","#cccccc solid 1px");
              });
              $("#username").css("border","#ea1212 solid 1px");
              $("#username").focus();
              $("#username").keydown(function(){
              $("#username").css("border","#cccccc solid 1px");
              });
              return false;
              }
              $.ajax({
              url: '/manage/admin/newlyAdmin',
              type: 'POST',
              datatype: 'json',
              data:{
              id:id,
              school:$("#oolId").val(),
              username:$("#username").val(),
              },
              success: function (e) {
              window.location.reload();
              }
              });
              layer.close(editor);
            },
            no:function(){

            }
           });
        },
        foundContract:function(){//创建合同
          var _this = this;
          var o = '';
          _this.schoolListName = [];
          _this.schoolListNumber = [];
          $("#school").val(o);
          $("#schoolId").val(o);
          $.ajax({
          url: '/manage/api/schoolList',
          type: 'get',
          datatype: 'json',
          success: function (e) {
          var tmp = JSON.parse(e);
          _this.schoolListId = tmp.data
          }
          })
          layer.open({
          type: 1,
          title: '创建合同',
          shadeClose: true, //点击遮罩关闭层
          area : ['520px' , '300px'],
          content: $("#contractId")
          });
          },
          renovate:function(){
          setInterval(function(){
          window.location.reload();
          },200)
        },
        switchSchool:function(){//切换学校
          var _this = this;
          $.ajax({
          url: '/manage/api/schoolList',
          type: 'get',
          datatype: 'json',
          success: function (e) {
          var tmp = JSON.parse(e);
          _this.schoolListId = tmp.data;
          }
          });
        },
        After:function(){
          var _this = this;
          if(_this.dataIdlist.length == 0){
          layer.alert('请选择用户！');
          }else{
          _this.getitemsId();
          window.location.href = '/manage/sale/AfterSaleCard?id='+_this.dataIdlist;
          }
        },
        daochu:function(){
          var _this = this;
          if(_this.dataIdlist.length == 0){
          layer.alert('请选择用户！');
          }else{
          _this.getitemsId();
          window.location.href = "/manage/sale/xls?id="+_this.dataIdlist.toString();
          } 
        },
        taskAfterUpdate:function(){//创建售后
          var _this = this;
          if($("#taskName").val() == ''){
          $("#taskName").click(function(){
          $("#taskName").css("border","#cccccc solid 1px");
          });
          $("#taskName").css(
          "border","#ea1212 solid 1px"
          );
          $("#taskName").focus();
          $("#taskName").keydown(function(){
          $("#taskName").css("border","#cccccc solid 1px");
          });
          return false;
          }
          if($("#express").val() == ''){
          $("#express").click(function(){
          $("#express").css("border","#cccccc solid 1px");
          });
          $("#express").css(
          "border","#ea1212 solid 1px"
          );
          $("#express").focus();
          $("#express").keydown(function(){
          $("#express").css("border","#cccccc solid 1px");
          });
          return false;
          }
          if($("#address").val() == ''){
          $("#address").click(function(){
          $("#address").css("border","#cccccc solid 1px");
          });
          $("#address").css(
          "border","#ea1212 solid 1px"
          );
          $("#address").focus();
          $("#address").keydown(function(){
          $("#address").css("border","#cccccc solid 1px");
          });
          return false;
          }
          $.ajax({
          url: '/manage/sale/taskAdd',
          type: 'post',
          datatype: 'json',
          data:{
          schoolId:$("#schoolId").val(),
          taskName:$("#taskName").val(),
          address:$("#address").val(),
          express:$("#express").val(),
          id:$("#id").val(),
          },
          success: function (e) {
          var tmp = JSON.parse(e);
          if(tmp.err_code == 0){
          window.location.href = '/manage/sale/customerService'
          }

          }
          })
        },
        selectFile:function(){//上传文件
          $("#file").trigger("click");
        },
        uploadFile:function(index){//上传文件
          var _this = this;  
          var i = $(".userNumber").text();
          var myform = new FormData();
          myform.append('file',$('#load_xls')[0].files[0]);
          $.ajax({
          url: "/manage/task/taskExcel?schoolId=" +$(".schoolId").val() ,
          type: "POST",
          data: myform,
          contentType: false,
          processData: false,
          success: function (data) {
          var tmp = JSON.parse(data);
          _this.tmpNumber = Number(tmp.data.length);
          $(".hiddenInput").eq(index).val(tmp.data);
          var xlsId = $(".hiddenInput").eq(index).val();
          if(xlsId == ''){

          }else{
          _this.cardList[index].xlsId = xlsId;
          _this.cardList[index].userNumber = Number(tmp.data.length) + Number(_this.shu)
          }
          },
          error:function(data){
          }
          });
        },
        getIds:function(){
          var _this = this; 
          var ids = "";
          var text = '';
          var checkClass = $("input[name='school']:checked");
          checkClass.each(function() {
          ids+=$(this).val();
          text+=$(this).next("span").text();
          ids+=",";
          text+=",";
          });
          ids = ids.substr(0, ids.lastIndexOf(","));
          text = text.substr(0, text.lastIndexOf(","));
          _this.schoolListNumber = [];
          _this.schoolListName = [];
          _this.dataIdlist = [];
          _this.schoolListNumber.push(ids);
          _this.schoolListName.push(text);
          _this.dataIdlist.push(ids);
          $("#oolId").val(_this.schoolListNumber);
          $("#sch").val(_this.schoolListName);
          $("#school").val(_this.schoolListName);
          $("#schoolId").val(_this.schoolListNumber);
          return ids;
        },
        getitemsId:function(){
          var _this = this; 
          var ids = "";
          var text = '';
          var checkClass = $("input[name='items']:checked");
          checkClass.each(function() {
          ids+=$(this).val();
          ids+=",";
          });
          ids = ids.substr(0, ids.lastIndexOf(","));
          _this.dataIdlist = [];
          _this.dataIdlist.push(ids);
          return ids;
        },
        demandSide:function(){//需求方
          $.ajax({
          url: '/manage/api/schoolList?type=1',
          type: 'get',
          datatype: 'json',
          success: function (e) {
          var tmp = JSON.parse(e);
          taskList.schoolListId = tmp.data;
          }
          });  
        },
        factory:function(){//工厂
          $.ajax({
          url: '/manage/api/schoolList',
          type: 'get',
          datatype: 'json',
          success: function (e) {
          var tmp = JSON.parse(e);
          taskList.schoolListId = tmp.data;
          }
          }); 
        },

    },
    mounted: function(){
      var _this = this
      this.initialData();
      $("input[name=school]").click(function(){
      var id = $(this).val();
      _this.schoolText = $(this).next().text();
      _this.schoolId = $(this).val();
      $.each($("input[name=school]"), function(index, val) {
      if($(val).val() !== id) {
      $(val).attr('checked', false);
      }
      })
      });
      $(".selectSchool p").each(function(){
      $(this).click(function(){
      $(this).next("ul").slideToggle();
      $(this).find("em img").toggle();
      });
      });
     
     if($("#taskId").val() != '' && $("#taskId").val() > 0){//编辑任务
      $("#taskName").attr("readonly","readonly");
      $.ajax({
      url: '/manage/task/taskAddInfo',
      type: 'get',
      datatype: 'json',
      data:{
        id:$("#taskId").val()
      },
      success: function (e) {
         var tmp = JSON.parse(e);
         var arr = Object.keys(tmp.data.item);
         _this.schoolId = tmp.data.schoolId;
         _this.obtainUser();
         _this.schoolName = tmp.data.schoolName;
         if(_this.schoolId != ''){
          $.ajax({
          url: '/manage/api/contractList',
          type: 'get',
          datatype: 'json',
          data:{
          schoolId:tmp.data.schoolId,
          },
          success: function (e) {
          var tmp = JSON.parse(e);
          _this.contractList = tmp.data;
          if(tmp.err_code == -1){
          layer.alert('暂无数据！');
          }
          }
          });
         }
         $("#taskName").val(tmp.data.taskName);
         $("#address").val(tmp.data.address);
         _this.contractNumber = tmp.data.contractNo;  
         $("#express").val(tmp.data.courier);
         for(var t=0;t<arr.length;t++){
          _this.cardList.push({});
          _this.selectedName.push(tmp.data.item[arr[t]].grade);
          _this.cardList[t].templateName = tmp.data.item[arr[t]].name;
          _this.cardList[t].taskTypeName = tmp.data.item[arr[t]].cardType;
          _this.cardList[t].selectedUser = tmp.data.item[arr[t]].studentId;
          _this.cardList[t].templateId = tmp.data.item[arr[t]].templateId;
          _this.cardList[t].taskTypeId = tmp.data.item[arr[t]].cardTypeId;
          _this.cardList[t].userNumber = tmp.data.item[arr[t]].count;
          }
          }     
          })
     }else{
       _this.unbind = false;
     }
        $("#allAndNotAll").click(function() { 
        if($(this).is(":checked")){
        $("input[name='items']").prop("checked",true);
        $("input[name='items']:checked").each(function(){ 
        _this.dataIdlist = []; 
        _this.dataIdlist.push($(this).val()); 
        });
        }else{
        $("input[name='items']").prop("checked",false);
        _this.dataIdlist = []; 
        }
        });
        $("input[name='items']").click(function(){
        if($(this).is(":checked")){

        }else{
        _this.dataIdlist.splice($(this).index(), 1);
        }
        });

        var set = setInterval(function(){
        $(".All").click(function() {
        if($(this).is(":checked")){
        $(this).parent().parent().parent().find("input[name='school']").prop("checked",true);
        $(this).parent().parent().parent().find("input[name='city']").prop("checked",true); 
        $(this).parent().parent().parent().find("input[name='countyName']").prop("checked",true);
        }else{
        $(this).parent().parent().parent().find("input[name='school']").prop("checked",false);
        $(this).parent().parent().parent().find("input[name='city']").prop("checked",false);
        $(this).parent().parent().parent().find("input[name='countyName']").prop("checked",false);
        }
        });
        $("input[name=school]").click(function() {
        if($(this).is(":checked")){
        $(this).parent().parent().parent().parent().prev("p").find("input[name='countyName']").prop("checked",true);
        $(this).parent().parent().parent().parent().parent().parent().prev("p").find("input[name='city']").prop("checked",true);
        $(this).parent().parent().parent().parent().parent().parent().parent().parent().prev("p").find("input[name='All']").prop("checked",true);
        }else{
        if($("input[name=school]").is(":checked")){
        if($(this).parent().parent().parent().parent().find("input").is(":checked")){
        $(this).parent().parent().parent().parent().prev("p").find("input[name='countyName']").prop("checked",true);   
        }else{
        $(this).parent().parent().parent().parent().prev("p").find("input[name='countyName']").prop("checked",false);
        }
        $(this).parent().parent().parent().parent().parent().parent().parent().parent().prev("p").find("input[name='All']").prop("checked",true);
        $(this).parent().parent().parent().parent().parent().parent().prev("p").find("input[name='city']").prop("checked",true);
        }else{
        $(this).parent().parent().parent().parent().prev("p").find("input[name='countyName']").prop("checked",false);
        $(this).parent().parent().parent().parent().parent().parent().prev("p").find("input[name='city']").prop("checked",false);
        $(this).parent().parent().parent().parent().parent().parent().parent().parent().prev("p").find("input[name='All']").prop("checked",false);
        }
        }
        });
        $("input[name=countyName]").click(function() {
        if($(this).is(":checked")){
        $(this).parent().parent().parent().parent().prev("p").find("input[name='city']").prop("checked",true);
        }else{
        if($("input[name=countyName]").is(":checked")){
        $(this).parent().parent().parent().parent().prev("p").find("input[name='city']").prop("checked",true);
        }else{
        $(this).parent().parent().parent().parent().prev("p").find("input[name='city']").prop("checked",false);
        }
        }
        if($("input[type='countyName']").is(":checked") || $("input[type='city']").is(":checked") || $("input[name=school]").is(":checked")){
        $(this).parent().parent().parent().parent().parent().parent().prev("p").find("input[name='All']").prop("checked",true);
        }else{
        $(this).parent().parent().parent().parent().parent().parent().prev("p").find("input[name='All']").prop("checked",false);
        }
        });
        $("input[name=city]").click(function() {
        if($(this).is(":checked")){
        $(this).parent().parent().parent().parent().prev("p").find("input[name='All']").prop("checked",true);
        }else{
        $(this).parent().parent().parent().parent().prev("p").find("input[name='All']").prop("checked",false);
        }
        })
        },100);
   var list = setInterval(function(){
        $("#ShadeBox-list ul li").click(function(){
        if($("#typeId").val() == '1'){
        $("input[name=school]").click(function(){
        _this.schoolListNumber = [];
        _this.schoolListName = [];
        _this.schoolListNumber.push($(this).val());
        _this.schoolListName.push($(this).next("span").text());
        });
        }
        });
  },100);
         
 $(".btnedi").on("click",function(){
      var srt = setInterval(function(){
      if(_this.te == 2){
      $("input[name=school]").click(function(){
      _this.schoolListNumber = [];
      _this.schoolListName = [];
      _this.schoolListNumber.push($(this).val());
      _this.schoolListName.push($(this).next("span").text());
      });
      }
      },100);
   });  
   
    },
    computed: {
      selectNameArr: function() {
        
      }
    },
});