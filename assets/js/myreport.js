if($('#page-report-xray').length > 0)
{
    
   var regional_asal = Highcharts.chart('xray_asal_kirim', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie',
        },
        title: { text: '' },
        tooltip: { pointFormat: '<b>{point.percentage:.1f}%</b>' },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                },
                events: {
                    click: function (e) {
                        var path = e.point.name;
                        var old  = $('#regional_asal').val();    
                        var name = ( path != "" && path != old ) ? e.point.name : "";
                        $('#regional_asal').val(name);
                        reload_chart();
                    },
                }
            }
        },
        credits: { enabled: false },
        series: [{
            name: 'Regional Tujuan',
            colorByPoint: true,
        }]
    });
    var regional_tujuan = Highcharts.chart('xray_tujuan_kirim', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie',
            animation: {
                duration: 2000
            }
        },
        title: { text: '' },
        credits: { enabled: false },
        tooltip: { pointFormat: '<b>{point.percentage:.1f}%</b>' },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                },
                events: {
                    click: function (e) {
                        var path = e.point.name;
                        var old  = $('#regional_tujuan').val();    
                        var name = ( path != "" && path != old ) ? e.point.name : "";
                        $('#regional_tujuan').val(name);
                        reload_chart();
                    }
                }
            }
        },
        series: [{
            name: 'Regional Tujuan',
            colorByPoint: true,
        }]
    });

    var xray_kantor_asal = Highcharts.chart('xray_kantor_asal', {
        title: { text: '' },
        chart: { type: 'bar', },
        xAxis: {
            title: { text: null },
            visible:false
        },
        yAxis: {
            min: 0,
            title: { text: 'Total Aduan', align: 'high' },
            labels: { overflow: 'justify' }
        },
        tooltip: { valueSuffix: ' Aduan' },
        plotOptions: {
            bar: {
                allowPointSelect: true,
                dataLabels: {
                    enabled: true,
                    inside: true,
                    formatter: function() {return this.x + ': ' + this.y},
                },
                events: {
                    click: function (e) {
                        var path = e.point.category;
                        var old  = $('#kantor_asal').val();    
                        var name = ( path != "" && path != old ) ? e.point.category : "";
                        $('#kantor_asal').val(name);
                        reload_chart();
                    },
                }
            }
        },
        legend: { enabled:false },
        credits: { enabled: false },
        series: [{ column:'column', name: 'Total' }]
    });

    var xray_kantor_tujuan = Highcharts.chart('xray_kantor_tujuan', {
        title: { text: '' },
        chart: { type: 'bar', },
        xAxis: {
            title: { text: null },
            visible:false
        },
        yAxis: {
            min: 0,
            title: { text: 'Total Aduan', align: 'high' },
            labels: { overflow: 'justify' }
        },
        tooltip: { valueSuffix: ' Aduan' },
        plotOptions: {
            bar: {
                dataLabels: { enabled: true, inside: true, formatter: function() {return this.x + ': ' + this.y}, }
            }
        },
        legend: { enabled:false },
        credits: { enabled: false },
        series: [{ column:'column', name: 'Total' }]
    });

    var xray_daily = Highcharts.chart('xray_daily', {
        title: { text: '' },
        chart: { type: 'column', },
        xAxis: {
            type: 'category',
            labels: {
                rotation: -45,
                style: {
                    fontSize: '12px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        },
        yAxis: {
            min: 0,
            title: { text: 'Total Aduan', align: 'high' },
            labels: { overflow: 'justify' }
        },
        tooltip: { valueSuffix: ' Aduan' },
        plotOptions: {
            bar: {
                //dataLabels: { enabled: true, inside: true, formatter: function() {return this.x + ': ' + this.y}, }
            }
        },
        legend: { enabled:false },
        credits: { enabled: false },
        series: [{
            column:'column',
            name: 'Total',
            
        }]
    });

    var xray_cloud =  Highcharts.chart('xray_cloud', {
        series: [{ type: 'wordcloud', name: 'Jumlah' }],
        credits: { enabled: false },
        title: { text: '' }
    });

    $('.select2min').select2({minimumResultsForSearch:Infinity});
    $('#month').on('select2:select', function (e) {
        var data = e.params.data;
        reload_chart();
    });


    reload_chart();
    function reload_chart()
    {

        $.post( site_url + 'report/load_xray_chart',{month:$('#month').val(), year:$('#year').val(), regional_asal:$('#regional_asal').val(), regional_tujuan:$('#regional_tujuan').val(), kantor_asal:$('#kantor_asal').val(), kantor_tujuan:$('#kantor_tujuan').val()}, function(res){
            
            regional_asal.update({
                series: [{
                    data: res.regional_asal_kirim
                }]
            })
            
            regional_tujuan.update({
                series: [{
                    data: res.regional_tujuan_kirim
                }]
            })
            //regional_tujuan.series[0].setData(res.regional_tujuan_kirim);
            
            xray_kantor_asal.update({
                xAxis: {
                    categories: res.kantor_asal_kirim.labels
                },
                series: [{
                    data: res.kantor_asal_kirim.datasets[0].data               
                }]
            });

            xray_kantor_tujuan.update({
                xAxis: {
                    categories: res.kantor_tujuan_kirim.labels
                },
                series: [{
                    data: res.kantor_tujuan_kirim.datasets[0].data               
                }]
            });
           
            xray_daily.update({
                series: [{
                    data: res.harian
                }]
            })

            xray_cloud.update({
                series: [{
                    data: res.tag.datasets
                }],
            })
            
            $('#dgterbangan').datagrid({
                data:res.kantor_terbangan.rows,
                height: 150,
                nowrap: false,
                striped: true,
                remoteSort: true,
                singleSelect: true,
                fitColumns: true,
                pagination:false,
                rownumbers:false,
                pageSize:25,
                pageList:[25,50,75,100],
                //toolbar:"#toolbar",
                queryParams: {
                },
                columns:[[
                    {field:'_name',title:'Kantor Terbangan',width:200, sortable:true, align:'left'},
                    {field:'_total',title:'Total',width:100, sortable:true, align:'left'},
                    
                ]]
                
            })
            
        },'json');
    }
    
}


// 
if($('#page-report-dashboard-nasional').length > 0){
    
    var pencapaian = Highcharts.chart('chart_kpi', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie',
        },
        title: { text: '' },
        tooltip: { pointFormat: '<b>{point.percentage:.1f}%</b>' },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                },
                events: {
                    click: function (e) {
                        var path = e.point.name;
                        var old  = $('#pencapaian').val();    
                        var name = ( path != "" && path != old ) ? e.point.name : "";
                        $('#pencapaian').val(name);

                        reload_chart();
                    },
                }
            }
        },
        credits: { enabled: false },
        series: [{
            name: 'Pencapaian ',
            colorByPoint: true,
        }]
    });

    var asal_aduan = Highcharts.chart('chart_kantor_asal', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie',
        },
        title: { text: '' },
        tooltip: { pointFormat: '<b>{point.percentage:.1f}%</b>' },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    distance:10,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                },
                events: {
                    click: function (e) {
                        var path = e.point.name;
                        var old  = $('#regional_asal').val();    
                        var name = ( path != "" && path != old ) ? e.point.name : "";
                        $('#regional_asal').val(name);

                        reload_chart();
                    },
                }
            }
        },
        credits: { enabled: false },
        series: [{
            name: 'Regional Asal ',
            colorByPoint: true,
        }]
    });

    var tujuan_aduan = Highcharts.chart('chart_kantor_tujuan', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie',
        },
        title: { text: '' },
        tooltip: { pointFormat: '<b>{point.percentage:.1f}%</b>' },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    
                    enabled: true,
                    distance:10,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                },
                events: {
                    click: function (e) {
                        var path = e.point.name;
                        var old  = $('#regional_tujuan').val();    
                        var name = ( path != "" && path != old ) ? e.point.name : "";
                        $('#regional_tujuan').val(name);

                        reload_chart();
                    },
                }
            }
        },
        credits: { enabled: false },
        series: [{
            name: 'Regional Tujuan ',
            colorByPoint: true,
        }]
    });

    var product = Highcharts.chart('chart_product', {
        title: { text: '' },
        chart: { type: 'bar', },
        xAxis: {
            title: { text: null },
            visible:false
        },
        yAxis: {
            min: 0,
            title: { text: 'Total Aduan', align: 'high' },
            labels: { overflow: 'justify' }
        },
        tooltip: { valueSuffix: ' Aduan' },
        plotOptions: {
            bar: {
                allowPointSelect: true,
                colorByPoint: true,
                dataLabels: {
                    enabled: true,
                    inside: true,
                    formatter: function() {return this.x + ': ' + this.y},
                },
                events: {
                    click: function (e) {
                        var path = e.point.category;
                        var old  = $('#jenis_produk').val();    
                        var name = ( path != "" && path != old ) ? e.point.category : "";
                        $('#jenis_produk').val(name);
                    },
                }
            }
        },
        legend: { enabled:false },
        credits: { enabled: false },
        series: [{ column:'column', name: 'Total' }]
    });

    var masalah = Highcharts.chart('chart_masalah', {
        series: [{
            type: "treemap",
            layoutAlgorithm: 'stripes',
            alternateStartingDirection: true,
            levels: [{
                level: 1,
                layoutAlgorithm: 'sliceAndDice',
                dataLabels: {
                    enabled: true,
                    align: 'left',
                    verticalAlign: 'top',
                    style: {
                        fontSize: '15px',
                        fontWeight: 'bold'
                    }
                }
            }]
        }],
        title: {
            text: ''
        }
    });




    $.post( site_url + 'report/load_chart', function(res){

        pencapaian.series[0].setData(res.pencapaian);
        asal_aduan.series[0].setData(res.asal_aduan);
        tujuan_aduan.series[0].setData(res.tujuan_aduan);

        product.update({
            xAxis: {
                categories: res.product.labels
            },
            series: [{
                data: res.product.datasets[0].data               
            }]
        });

        masalah.series[0].setData(res.masalah);
    },"json");

    function reload_chart(){
        $.post ( site_url + 'report/reload_dashboard',{pencapaian:$('#pencapaian').val(), regional_asal:$('#regional_asal').val(), regional_tujuan:$('#regional_tujuan').val(), product:$('#produk').val()}, function(res){
            asal_aduan.series[0].setData(res.asal_aduan);
            tujuan_aduan.series[0].setData(res.tujuan_aduan);
            product.update({
                xAxis: {
                    categories: res.product.labels
                },
                series: [{
                    data: res.product.datasets[0].data               
                }]
            });
        },"json");
    }
}


/* FEEDER */

if($('#page-report-feeder').length > 0)
{
    var mp_daily = Highcharts.chart('marketplace_daily', {
        title: { text: '' },
        chart: { type: 'column', },
        xAxis: {
            type: 'category',
            labels: {
                rotation: -45,
                style: {
                    fontSize: '12px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        },
        yAxis: {
            min: 0,
            title: { text: 'Total Upload', align: 'high' },
            labels: { overflow: 'justify' }
        },
        tooltip: { valueSuffix: ' Upload' },
        plotOptions: {
            bar: {
                //dataLabels: { enabled: true, inside: true, formatter: function() {return this.x + ': ' + this.y}, }
            }
        },
        legend: { enabled:false },
        credits: { enabled: false },
        series: [{
            column:'column',
            name: 'Total',
            
        }]
    });

    var mp_upload = Highcharts.chart('marketplace_upload', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie',
        },
        title: { text: '' },
        tooltip: { pointFormat: '<b>{point.percentage:.1f}%</b>' },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                },
                events: {
                    click: function (e) {
                        var path = e.point.name;
                        var old  = $('#marketplace').val();    
                        var name = ( path != "" && path != old ) ? e.point.name : "";
                        $('#marketplace').val(name);
                        load_chart();
                    },
                }
            }
        },
        credits: { enabled: false },
        series: [{
            name: 'Upload',
            colorByPoint: true,
        }]
    });
    
    load_chart();
    function load_chart()
    {
        $.post( site_url + 'feeder/load_dashboard',{start:$('#start').val(), end:$('#end').val(), marketplace:$('#marketplace').val()}, function(res){
            
            mp_daily.update({
                series: [{
                    data: res.daily
                }]
            })

            mp_upload.update({
                series: [{
                    data: res.marketplace
                }]
            })
            
        },'json');
    }
    
}

/* END FEEDER */