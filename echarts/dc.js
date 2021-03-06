function randomData() {
    now = new Date(+now + oneDay);
    value = value + Math.random() * 21 - 10;
    return {
        name: now.toString(),
        value: [
            [now.getFullYear(), now.getMonth() + 1, now.getDate()].join('/'),
            Math.round(value)
        ]
    }
}



var data = [];
var now = +new Date();
var oneDay = 24 * 3600 * 1000;
var value = Math.random() * 1000;
//for (var i = 0; i < 1000; i++) {
 //   data.push(randomData());
//}

debugger;
$.ajax({
        type: "GET",
        dataType: "jsonp",
        url: "http://www.usertech.cn:3000/api/elecitems/0000000035998155",
        success: function(data){       
                alert(data);
                debugger;
                myChart.setOption({
                        xAxis: { 
                                data: data.dcData
                        },
                        series: [{ // 根据名字对应到相应的系列 
                                name: '销量', 
                                data: data.dcData }] 
                        }); 

                }
});
option = {
    title: {
        text: '实验室一 + 电压实时曲线'
    },
    tooltip: {
        trigger: 'axis',
        formatter: function (params) {
            params = params[0];
            var date = new Date(params.name);
            return date.getDate() + '/' + (date.getMonth() + 1) + '/' + date.getFullYear() + ' : ' + params.value[1];
        },
        axisPointer: {
            animation: false
        }
    },
    xAxis: {
        type: 'time',
        splitLine: {
            show: false
        }
    },
    yAxis: {
        type: 'value',
        boundaryGap: [0, '100%'],
        splitLine: {
            show: false
        }
    },
    series: [{
        name: '模拟数据',
        type: 'line',
        showSymbol: false,
        hoverAnimation: false,
        data: data
    }]
};

setInterval(function () {

    for (var i = 0; i < 5; i++) {
        data.shift();
        data.push(randomData());
    }

    myChart.setOption({
        series: [{
            data: data
        }]
    });
}, 1000);
