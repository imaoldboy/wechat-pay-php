option = {
    title : {
        text: '设备运行状态总揽',
        subtext: '点击饼图查看详细',
        x:'center'
    },
    tooltip : {
        trigger: 'item',
        formatter: "{a} <br/>{b} : {c} ({d}%)"
    },
    legend: {
        x : 'center',
        y : 'bottom',
        data:['电压','电流','功率']
    },
    toolbox: {
        show : true,
        feature : {
            mark : {show: true},
            dataView : {show: true, readOnly: false},
            magicType : {
                show: true,
                type: ['pie', 'funnel']
            },
            restore : {show: true},
            saveAsImage : {show: true}
        }
    },
    calculable : true,
    series : [
        {
            name:'实验室一',
            type:'pie',
            radius : [30, 110],
            center : ['25%', '50%'],
            roseType : 'area',
            data:[
                {value:20, name:'电压'},
                {value:25, name:'电流'},
                {value:15, name:'功率'}
            ]
        },
        {
            name:'实验室二',
            type:'pie',
            radius : [30, 110],
            center : ['75%', '50%'],
            roseType : 'area',
            data:[
                {value:20, name:'电压'},
                {value:25, name:'电流'},
                {value:15, name:'功率'}
            ]
        }
    ]
};
