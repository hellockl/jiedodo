var main = new Vue({
    name: 'main',
    el: '#main',
    data: function(){
        return {
            head: {
                number: '23893214398172432',
                time: '2018-09-18'
            },
            personInfo: {       // 个人信息
                img: '/manage/img/tou.png',
                name: '李晓红',
                age: '28',
                horoscope: '处女座',
                birthplace: '杭州',
                idcard: '331088197001013349',
                phone: '15900012345',
                operator: '中国移动',
                city: '杭州',
                accesstime: '22个月',
                level: 5
            },
            analysed: {         // 稳定性分析
                phone: '是',
                list: [
                    {
                        per: 0.2, number: 5, deg: 360 * .455
                    },
                    {
                        per: 0.255, number: 6, deg: 360 * 0.255
                    },
                    {
                        per: 0.6, number: 7, deg: 360 * 0.6
                    }
                ]
            },
            key: {              // 关键信息匹配与校验
                shiming: true,
                jiaoyan1: false,
                jiaoyan2: false,
                id2tel: [2, 2, 3],
                tel2id: [3, 3, 4]
            },
            duotou:{
                p7:0,
                p30:0,
                p90:0,
                x7:0,
                x30:0,
                x90:0,
                y7:0,
                y30:0,
                y90:0,
                f7:0,
                f30:0,
                f90:0
            },
            tonghua:{
                jingmo:0,
                yinhang:0,
                aomen:0,
                y110:0,
                y120:0,
                ye:0,
                yebi:0,
                yuehuchu:0,
                yuehuru:0,
            },

            calltime: {         // 通话时间段比例
                option: {
                    series: [
                        {
                            type:'pie',
                            radius: ['35%', '55%'],
                            label: {
                                normal: {
                                    formatter: '{b|{b}\n}{per|{d}%}',
                                    rich: {
                                        b: {
                                            color: 'gray',
                                            lineHeight: 22,
                                            align: 'left',
                                            lineWidth: 200
                                        }
                                    }
                                }
                            },
                            data:[
                                {
                                    value:zao, name:'7:00-11:00', itemStyle: { color: '#009de6'}
                                },
                                {
                                    value:zhong, name:'11:00-14:00', itemStyle: { color: '#8c4ce2'}
                                },
                                {
                                    value:wu, name:'14:00-20:00', itemStyle: { color: '#e24c68'}
                                },
                                {
                                    value:wan, name:'20:00-7:00', itemStyle: { color: '#e2b94c'}
                                }
                            ],
                            itemStyle: {
                                shadowBlur: 20,
                                shadowOffsetX: 0,
                                shadowOffsetY: 0,
                                shadowColor: 'rgba(0, 0, 0, .3)'
                            }
                        }
                    ]
                }
            },
            top3: [], // 通话次数城市top
            zone: [],// 地域统计结果
            trip: [
                {
                    start: '2018-10-01', end: '2018-10-07', startAddr: '北京', endAddr: '上海', stayTime: '6天'
                }
            ],// 出行报告
            caller: [           // 通话人统计
                {
                    tel: '13511223322', city: '北京', netSign: '未标识', inNum: 33, inTime: 2212, outNum: 44, outTime: 3434, total: 333, totalTime: 34535
                },
                {
                    tel: '13511223322', city: '北京', netSign: '快递', inNum: 33, inTime: 2212, outNum: 44, outTime: 3434, total: 333, totalTime: 34535
                }
            ],
            addressbook: [      // 通讯录匹配情况
                {
                    tel: '13522212232', telTime: 332, telNum: 99, hostNum: 343, hostTime: 434, guestTime: 2342, guestNum: 356, lastTel: '2018-10-01 11:12'
                }
            ],
            check: {            // 月账单
                option: {
                    tooltip : {
                        trigger: 'axis',
                        axisPointer: {
                            label: {
                                backgroundColor: '#6a7985'
                            }
                        }
                    },
                    grid: {
                        left: '3%',
                        right: '4%',
                        bottom: '3%',
                        containLabel: true
                    },
                    xAxis: {
                        type: 'category',
                        boundaryGap: false,
                        data: monthVal
                    },
                    yAxis: {
                        type: 'value'
                    },
                    series: [{
                        name: '月账单',
                        data: monthData,
                        type: 'line',
                        smooth: true,
                        color: '#1eaced',
                        areaStyle: {
                            color: 'rgba(0, 231, 201, .5'
                        }
                    }]
                }
            },
            debit: [            // 用户借贷行为
                {
                    date: '7天内', per: .455, number: 5, deg: 360 * .455, sf: true, renshu: 8, yidu: 4, erdu: 4, erdu1: 4, erdu2: 4
                }
            ],
            urge: [             // 用户催收行为
                {
                    date: '30天内', zhishu: 40, num: [6, 6, 6], call:[33, 33, 33], time: [99, 99, 99]
                }
            ],
            social: [           // 社会分析
                {zhishu: 88.8},
                {zhishu: 88.8},
                {zhishu: 88.8},
                {zhishu: 88.8},
                {zhishu: 88.8},
                {zhishu: 88.8}
            ]
        }
    },
    methods:{
        getfengkong:function(){
            var _this=this;
            var id=$("#id").val();
            $.ajax({
                url: '/manage/risk/getfengkong',
                type: 'get',
                datatype: 'json',
                data:{id:id},
                success: function (e) {
                    var tmp = JSON.parse(e).data;
                    _this.head.time=tmp.risk.createdTime;
                    _this.head.number=tmp.user.id;
                    _this.personInfo={       // 个人信息
                            img: tmp.user.images[0]?tmp.user.images[0]:'',
                            name: tmp.user.name,
                            age: tmp.risk.userBasicInfo.age,
                            horoscope:tmp.user.constellation,
                            birthplace:tmp.user.jiguan,
                            idcard:tmp.user.card,
                            phone:tmp.user.mobile,
                            operator: tmp.risk.userBasicInfo.operator,
                            city: tmp.user.location,
                            accesstime: tmp.user.ruwang+'个月',
                    };
                    if( tmp.risk.userBasicInfo.match_name=='MATCH'){
                        _this.key.jiaoyan1=true;
                    }
                    if( tmp.risk.userBasicInfo.match_idcard=='MATCH'){
                        _this.key.jiaoyan2=true;
                    }
                    _this.duotou={
                        p7:tmp.risk.p7,
                        p30:tmp.risk.p30,
                        p90:tmp.risk.p90,
                        x7:tmp.risk.x7,
                        x30:tmp.risk.x30,
                        x90:tmp.risk.x90,
                        y7:tmp.risk.y7,
                        y30:tmp.risk.y30,
                        y90:tmp.risk.y90,
                        f7:tmp.risk.f7,
                        f30:tmp.risk.f30,
                        f90:tmp.risk.f90,
                    }
                    _this.tonghua={
                            jingmo:tmp.risk.userBehaviorCheck.max_call_days_gap,
                            yinhang:0,
                            aomen:tmp.risk.userBehaviorCheck.call_count_macau,
                            y110:tmp.risk.userBehaviorCheck.call_count_110,
                            y120:tmp.risk.userBehaviorCheck.call_count_120,
                            ye:0,
                            yebi:tmp.risk.userBehaviorCheck.night_call_pct,
                            yuehuru:tmp.user.yuehuru,
                            yuehuchu:tmp.user.yuehuchu,
                    }
                    _this.top3= [             // 通话次数城市top3
                        {
                            level: 1,
                            city: tmp.risk.userBehaviorCheck.call_place_top1,
                            callNum: tmp.risk.userBehaviorCheck.top1tonghua,
                            callPer: tmp.risk.userBehaviorCheck.top1tonghuabi+'%',
                            oftenNum: 222,
                            oftenPer: '55%',
                            personNum: 322,
                            personPer: '22%'
                        },
                        {
                            level: 2,
                            city: tmp.risk.userBehaviorCheck.call_place_top2,
                            callNum: tmp.risk.userBehaviorCheck.top2tonghua,
                            callPer: tmp.risk.userBehaviorCheck.top2tonghuabi+'%',
                            oftenNum: 222,
                            oftenPer: '55%',
                            personNum: 322,
                            personPer: '22%'
                        },
                        {
                            level: 3,
                            city: tmp.risk.userBehaviorCheck.call_place_top3?tmp.risk.userBehaviorCheck.call_place_top3:'无',
                            callNum: tmp.risk.userBehaviorCheck.top3tonghua?tmp.risk.userBehaviorCheck.top3tonghua:0,
                            callPer: tmp.risk.userBehaviorCheck.top3tonghuabi?tmp.risk.userBehaviorCheck.top3tonghuabi+'%':0+'%',
                            oftenNum: 222,
                            oftenPer: '55%',
                            personNum: 322,
                            personPer: '22%'
                        },
                    ]
                    _this.zone=tmp.zone;
                    _this.trip=tmp.trip;
                    _this.debit=[            // 用户借贷行为
                        {
                            date: '7天内', per: (tmp.risk.p7/7).toFixed(2), number: 5, deg: 360 * .455, sf: tmp.risk.p7>1?true:false, renshu: 8, yidu: 4, erdu: 4, erdu1: 4, erdu2: 4
                        },
                        {
                            date: '30天内', per: (tmp.risk.p30/30).toFixed(2), number: 6, deg: 360 * 0.255, sf: tmp.risk.p30>1?true:false, renshu: 8, yidu: 4, erdu: 4, erdu1: 4, erdu2: 4
                        },
                        {
                            date: '90天内', per:(tmp.risk.p90/90).toFixed(2), number: 7, deg: 360 * 0.6, sf: tmp.risk.p90>1?true:false, renshu: 8, yidu: 4, erdu: 4, erdu1: 4, erdu2: 4
                        }
                    ]
                    _this.urge= [             // 用户催收行为
                        {
                            date: '30天内', zhishu: 40, num: [tmp.user.n ], call:[tmp.user.n], time: [tmp.user.nn,]
                        },
                        {
                            date: '90天内', zhishu: 55, num: [tmp.user.n1], call:[tmp.user.n1], time: [tmp.user.nn1]
                        },
                        {
                            date: '180天内', zhishu: 67, num: [tmp.user.n], call:[tmp.user.n2], time: [tmp.user.nn2]
                        }
                    ]
                    _this.caller= tmp.caller;
                    _this.addressbook=tmp.addressbook;
                }
            });
        }
    },
    mounted:function() {
        var ring = echarts.init(document.getElementById('ring'));
        var check = echarts.init(document.getElementById('check'));
        ring.setOption(this.calltime.option);
        check.setOption(this.check.option);
        this.getfengkong();

    }
})