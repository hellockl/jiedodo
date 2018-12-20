<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
<link rel="stylesheet" type="text/css" href="<?php echo(BASEURL)?>/css/index.css">
<link rel="stylesheet" href="<?php echo(BASEURL)?>/css/bootstrap.min.css">
<script type="text/javascript" src="/manage/js/layui/layui.js"></script>
<script src="<?php echo(BASEURL)?>/js/jquery-2.1.1.min.js"></script>
<script src="<?php echo(BASEURL)?>/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo(BASEURL)?>/js/index.js"></script>
<script src="/manage/js/vue.js"></script>
<style type="text/css">
td,th{
    font-size: 14px;
}
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th{
    border-top: none;
}
.table>thead>tr>th { border-bottom: none;}
.userDiv div ul li label{
    height: auto;
}
.userDiv div ul li label input{
     width: 500px;
 }
.userDiv div ul li label{
    width: 520px;
}
.userDiv div ul li label textarea{
    width: 500px;
}
.userDiv div ul li label select{
    width: 500px;
}
</style>
</head>
<body>
    <div class="layui-form" id="app">
          <div class="userDiv">
             <!--学生信息-->
              <input type="hidden" id="department" value="<?php echo isset($data->searchId)?$data->searchId:'all';?>"/>
              <input type="hidden" id="office" value="<?php echo isset($data->priceId)?$data->priceId:'all';?>"/>
              <form class="layui-form" action="/manage/registe/add" method="post" target="iframe">
                  <iframe name="iframe" frameborder=0 width=0 height=0></iframe>
                  <input type="hidden" name="id" value="<?php echo isset($data->id)?$data->id:'';?>" >
                  <div class="userlist ">

                      <ul>
                          <li>
                              <span>照 片：</span>
                              <label>
                                  <div>
                                      <img style="height:100px; width:100px" id="tupian"  src="<?php echo isset($data->logo)?$data->logo:'/manage/img/upload.png'; ?>">
                                      <br/><span style="">点击这里上传...</span>
                                  </div>

                                  <input type="file" id="files" onchange="encodeBase64(event,'tupian','logo')"  style="display: none;">
                                  <input type="hidden" name="logo" id="logo"  >
                              </label>
                          </li>
                            <li>
                                <span>名称：</span>
                                <label><input type="text" required name="shopName" value="<?php echo isset($data->shopName)?$data->shopName:''; ?>" ></label>
                            </li>
                            <li>
                                <span>分类：</span>
                                <label>
                                    <select class="form-control" required id="departmentId" name="searchId" v-model="couponSelected" @change="indexSelect($event)" >
                                        <option value="">--请选择--</option>
                                        <option v-for="v1 in depart" :value="v1.id">{{v1.title}}</option>
                                    </select>
                                </label>
                            </li>
                            <li>
                                <span>属性：</span>
                                <label>
                                    <select class="form-control" required  name="priceId" id="officeId" v-model="officeSelected">   
                                        <option value="">--请选择--</option>
                                        <option v-for="v2 in office" :value="v2.id" >{{v2.title}}</option>   
                                    </select>
                                </label>
                            </li>
                            <li>
                                <span>简介：</span>
                                <label><input type="text" required name="abstract" value="<?php echo isset($data->abstract)?$data->abstract:''; ?>" ></label>
                            </li>
                            <li>
                                <span>借款额度(小)：</span>
                                <label><input type="text" required name="minQuota" value="<?php echo isset($data->minQuota)?$data->minQuota:''; ?>" >元</label>
                            </li>
                            <li>
                                <span>借款额度(大)：</span>
                                <label><input type="text" required name="maxQuota" value="<?php echo isset($data->maxQuota)?$data->maxQuota:''; ?>" >元</label>
                            </li>
                            <li>
                                <span>周期：</span>
                                <label><input type="text" required name="cycle" value="<?php echo isset($data->cycle)?$data->cycle:''; ?>" id="cycle"></label>
                            </li>
                            <li>
                                <span>利息：</span>
                                <label><input type="text" required name="interest" value="<?php echo isset($data->interest)?$data->interest:''; ?>" id="interest">%</label>
                            </li>
                            <li>
                                <span>申请人数：</span>
                                <label><input type="text" required name="num" value="<?php echo isset($data->num)?$data->num:''; ?>" id="num"></label>
                            </li>
                            <li style="height:120px;">
                                <span>申请条件：</span>
                                <label>
                                    <textarea  rows="5" required id="condition" name="condition"><?php echo isset($data->condition)?$data->condition:''; ?></textarea>
                                </label>
                            </li>
                            <li style="height:120px;">
                                <span>申请流程：</span>
                                <label>
                                    <textarea rows="5 " required id="procedure" name="procedure"><?php echo isset($data->procedure)?$data->procedure:''; ?></textarea>
                                </label>
                            </li>
                            <li style="height:120px;">
                                <span>特别说明：</span>
                                <label><textarea  rows="5" id="remarks" required name="remarks"><?php echo isset($data->remarks)?$data->remarks:''; ?></textarea></label>
                            </li>
                             <li>
                              <span>成功率：</span>
                              <label>
                                  <input type="text" name="success" required value='<?php echo isset($data->success)?$data->success:'0'; ?>'  />
                              </label>
                            </li>
                            <li>
                              <span>放款速度：</span>
                              <label>
                                  <input type="text" name="speed" required value='<?php echo isset($data->speed)?$data->speed:'0'; ?>'  />
                              </label>
                            </li>
                            <li>
                                <span>排序：</span>
                                <label>
                                <input type="text" name="sort" required value='<?php echo isset($data->sort)?$data->sort:'0'; ?>'  />
                                </label>
                            </li>
                            <li>
                                <span>置顶：</span>
                                <label>
                                <input type="checkbox" name="hot" value='1' <?php if(isset($data->hot) && $data->hot==1){echo 'checked';}?> />
                                </label>
                            </li>
                            <li>
                                <span>注册链接：</span>
                                <label><input type="text" name="registerUrl" value="<?php echo isset($data->registerUrl)?$data->registerUrl:''; ?>" id="remarks"></label>
                            </li>
                            <li style="padding-bottom:80px;">
                                <button type="button" class="btn btn-default " onclick="parent.layer.closeAll();" style="margin-left:80px;"> 关 闭 </button>
                                <button type="submit" class="btn btn-success" > 保 存 </button>
                            </li>
                      </ul>
                  </div>
              </form>
          </div>
    </div>

<script>
    new Vue({
        el:'#app',
        data:{
            depart:[],
            office:[],
            couponSelected:'',
            officeSelected:'',

        },
        methods:{
            indexSelect:function(event){
                this. A = event.target.value;
                this.office =[];
                if(this.A!='all'){
                    this.officeSelected='';
                    this.office = this.depart[this.A].office;
                }

            },
        },
        mounted: function(){
            //初始加载数据获取
            var _this=this;
            var id=$("#id").val();
            $.ajax({
                url: '/manage/registe/registetype',
                type: 'get',
                datatype: 'json',
                data:{},
                success: function (e) {
                    var tmp = JSON.parse(e);
                    _this.depart  =tmp.data;
                    var department=$("#department").val();
                    if(department!='all'){
                        _this.couponSelected =department;
                        _this.office = tmp.data[department].office;
                    }
                    if($("#office").val()!='all'){
                        _this.officeSelected =$("#office").val();
                    }
                }

            });

        },
    });
function encodeBase64(e,b,c){
    let uploadFile = e.target.files[0];
    let reader = new FileReader();
    reader.readAsDataURL(uploadFile);
    reader.onloadend = function() {
        let base64 = reader.result; // base64就是图片的转换的结果
        if (2100000 < base64.length) { //上传图片最大值(单位字节)（ 2 M = 2097152 B ）
            alert( '上传失败，请上传不大于2M的图片！');
            return;
        }
        var path=uploadFile.type;
        var imgtype=path.substring(path.lastIndexOf("/")+1,path.length);
        console.log(imgtype);
        if(imgtype=='png' || imgtype=='jpg' || imgtype=='jpeg' || imgtype=='gif'){
            document.getElementById(b).src =base64;
            document.getElementById(c).value =base64;
        }else{
            alert( '上传失败，请选择正确格式！');
            return;
        }

    };
}
</script>
</body>
</html>