<script type="text/javascript">
      $(function () {
          $('#week').highcharts({
           title: {
               text: '全部微博数量按周分析',
			    x: -20 //center
			},
			xAxis: {
               categories: ["Sun",'Mon' , "Tue", "Wed", "Thu", "Fri", "Sat"]
           },
           yAxis: {
               title: {
                text: '数量 (个)'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
           valueSuffix: '个'
       },
       legend: {
           layout: 'vertical',
           align: 'right',
           verticalAlign: 'middle',
           borderWidth: 0
       },
       series: [{
           name: '所有用户',
           data: <{$weeks}>
       }]
   });
          $('#day').highcharts({
           title: {
               text: '全部微博数量按小时分析',
			    x: -20 //center
			},
			xAxis: {
               categories: ['0', '1', "2", "3", "4", "5", "6",'7',"8", "9", "10", "11", "12", "13",'14',"15", "16", "17", "18", "19", "20",'21',"22", "23"]
           },
           yAxis: {
               title: {
                text: '数量 (个)'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
           valueSuffix: '个'
       },
       legend: {
           layout: 'vertical',
           align: 'right',
           verticalAlign: 'middle',
           borderWidth: 0
       },
       series: [{
           name: '所有用户',
           data: <{$hours}>
       }]
   });
})
</script>