<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" href="/manage/css/bootstrap.min.css">
    <script src="/manage/js/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="/manage/js/layer/layer.js"></script>
    <script src="/manage/js/vue.js"></script>
    <style>
        .row{
            padding-bottom:15px;
        }
    </style>
</head>
<body>
<div class="container"  id="box" style="width: 100%; padding-top: 20px;">
    <form class="form-horizontal"  action="/manage/type/save"  method="post" >
        <input type="hidden" name="id" id="id" value="<?php echo $this->input->get('id'); ?>" >
        <div class="form-group">
            <label for="firstname" class="col-sm-1 control-label">类型</label>
            <div class="col-sm-11">
                <input type="text" name="title" required class="form-control"  :value="money" v-model="money">
            </div>
        </div>
        <div class="form-group" v-for="v,index in arr">
            <label for="firstname" class="col-sm-1 control-label">金额</label>
            <div class="col-sm-10">
                <input type="text" name="day[]" required class="form-control"  :value="v.title" v-model="v.title">
            </div>
            <div class="col-sm-1">
                <button type="button" class="btn btn-success" v-if="index == 0"  @click="addButton()">添加</button>
                <button type="button" class="btn" v-if="index != 0"  @click="remButton(index)">删除</button>
            </div>
        </div>

        <div style="text-align: right;margin:50px 0 15px 0">
            <button type="submit" class="btn btn-success" > 保 存 </button>
            <button type="button" class="btn btn-default" onclick="parent.layer.closeAll();"> 关 闭 </button>
        </div>
    </form>
</div>
<script>
new Vue({
    el:'#box',
    data:{
        arr:[],
        money:'',
    },
    methods:{
        addButton:function () {
            this.arr.push({})
        },
        getList:function () {
            var _this=this;
            var id=$("#id").val();
            $.ajax({
                url: '/manage/type/details',
                type: 'get',
                datatype: 'json',
                data:{id:id},
                success: function (e) {
                    var tmp = JSON.parse(e);
                    _this.arr=[{}];
                    if(tmp.data!=undefined){
                        _this.arr = tmp.data.list;
                        console.log(_this.arr)
                        _this.money = tmp.data.title;
                    }
                }
            });
        },
        remButton:function(num){
            var _this=this;
            //删除数据
            layer.open({
                type: 1,
                content: '<br/>&nbsp;&nbsp;&nbsp;是否要删除该数据！',
                btn: ['是','否'],
                yes:function () {
                    _this.arr.splice(num,1);
                    layer.closeAll();
                },
                no: function () {
                    layer.closeAll();
                }
            });
        }
    },
    mounted: function(){
        //初始加载数据获取
        this.getList();

    },
});
</script>
</body>
</html>