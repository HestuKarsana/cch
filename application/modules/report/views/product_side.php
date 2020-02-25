<script>
    var ctx = document.getElementById('chart_inout').getContext('2d');
    var kprk_inout = new Chart(ctx, {
        type: 'pie',
        options: {
            onClick: function(evt, activeElements) {
                var elementIndex = activeElements[0]._index;
                /*
                if( ($('#kpi_value').val() == '') || ($('#kpi_value').val() != this.data.labels[elementIndex]) ){
                    var org_color 	 = this.data.datasets[0].backgroundColor[elementIndex];
                    this.data.datasets[0].backgroundColor[elementIndex] = 'black';
                    $('#kpi_org_color').val(org_color);
                    $('#kpi_value').val(this.data.labels[elementIndex]);
                    this.update();
                }else{
                    this.data.datasets[0].backgroundColor[elementIndex] = $('#kpi_org_color').val();
                    $('#kpi_org_color').val('');
                    $('#kpi_value').val('');
                    this.update();
                }
                */
            },
            title : {
                display: true,
                position : 'top',
                text : 'Pengaduan Produk di Regional'
            },
            legend: {
                position : 'bottom',
                labels: { fontColor: 'black', boxWidth:20},			
            },
            tooltips: {
                callbacks: {
                    title: function(tooltipItem, data) {
                        var currentLabel = data.labels[tooltipItem[0].index];
                        //return currentLabel;
                    },
                    label: function(tooltipItem, data) {
                        
                        
                        var currentLabel = data.labels[tooltipItem.index]
                        //return percentage + " % ";
                        return ""+currentLabel+" :";
                        //var multistringText = ["Diselesaikan "+currentLabel+" :"];
                        //multistringText.push("Jumlah Aduan "+currentValue +"( "+percentage+"% )");
                        //return multistringText;
                    },
                    footer: function(tooltipItem, data) {
                        var dataset = data.datasets[tooltipItem[0].datasetIndex];
                        var total = dataset.data.reduce(function(previousValue, currentValue, currentIndex, array) {
                            return previousValue + currentValue;
                        });
                        var currentValue = dataset.data[tooltipItem[0].index];
                        var percentage = Math.floor(((currentValue/total) * 100)+0.5);         

                        var multistringText = ["Jumlah : "+currentValue+" "];
                        multistringText.push("Persentase : "+percentage+" %");
                        return multistringText;
                    }
                }
            }
        }
    });

    var ctx = document.getElementById('chart_product_kprk').getContext('2d');
    var kprk_product = new Chart(ctx, {
        type: 'pie',
        options: {
            onClick: function(evt, activeElements) {
                var elementIndex = activeElements[0]._index;
                /*
                if( ($('#kpi_value').val() == '') || ($('#kpi_value').val() != this.data.labels[elementIndex]) ){
                    var org_color 	 = this.data.datasets[0].backgroundColor[elementIndex];
                    this.data.datasets[0].backgroundColor[elementIndex] = 'black';
                    $('#kpi_org_color').val(org_color);
                    $('#kpi_value').val(this.data.labels[elementIndex]);
                    this.update();
                }else{
                    this.data.datasets[0].backgroundColor[elementIndex] = $('#kpi_org_color').val();
                    $('#kpi_org_color').val('');
                    $('#kpi_value').val('');
                    this.update();
                }
                */
            },
            title : {
                display: true,
                position : 'top',
                text : 'Pengaduan Produk di KPRK'
            },
            legend: {
                position : 'bottom',
                labels: { fontColor: 'black', boxWidth:20},			
            },
            tooltips: {
                callbacks: {
                    title: function(tooltipItem, data) {
                        var currentLabel = data.labels[tooltipItem[0].index];
                        //return currentLabel;
                    },
                    label: function(tooltipItem, data) {
                        
                        
                        var currentLabel = data.labels[tooltipItem.index]
                        //return percentage + " % ";
                        return ""+currentLabel+" :";
                        //var multistringText = ["Diselesaikan "+currentLabel+" :"];
                        //multistringText.push("Jumlah Aduan "+currentValue +"( "+percentage+"% )");
                        //return multistringText;
                    },
                    footer: function(tooltipItem, data) {
                        var dataset = data.datasets[tooltipItem[0].datasetIndex];
                        var total = dataset.data.reduce(function(previousValue, currentValue, currentIndex, array) {
                            return previousValue + currentValue;
                        });
                        var currentValue = dataset.data[tooltipItem[0].index];
                        var percentage = Math.floor(((currentValue/total) * 100)+0.5);         

                        var multistringText = ["Jumlah : "+currentValue+" "];
                        multistringText.push("Persentase : "+percentage+" %");
                        return multistringText;
                    }
                }
            }
        }
    });
$.post( site_url + 'report/load_sidebar_product',{ code : $('#show-code').val(), start:$('#start').val(), end:$('#end').val()}, function(res){
    if(isEmpty(res.regional.labels)){
        console.log('x');
    }else{
        console.log('aa');
    }
    kprk_inout.data = res.regional;
    kprk_inout.update();

    kprk_product.data = res.kprk;
    kprk_product.update();
},"json");
</script>
<section id="ccare-index-sidebar">
    <div class="mail-container-header" style="margin-top:0px;">
        Produk Detail - <?php echo $row->name;?>
        <a href="javascript:void(0);" onclick="javascript:closeIndexSide();"><span class="fa fa-times pull-right"></span></a>
    </div>
    <form>
        <input type="hidden" id="show-code" value="<?php echo $row->code;?>">
        <div class="row">
            <div class="col-md-12">
                <canvas id="chart_inout"></canvas>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <canvas id="chart_product_kprk"></canvas>
            </div>
        </div>
    </form>
    <!--
	<div class="col-md-12">
		<div class="form-group">
			<a name="tiket-page" class="btn btn-success btn-block" id="tiket-page" href="<?php echo site_url('krpk/d/'.$row->id);?>">Lihat Detail</a>
		</div>
	</div>
    -->
</section>