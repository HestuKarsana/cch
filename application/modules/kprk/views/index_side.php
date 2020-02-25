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
$.post( site_url + 'kprk/load_sidebar',{ code : $('#show-code').val(), start:$('#start').val(), end:$('#end').val()}, function(res){
    kprk_inout.data = res.in_out;
    kprk_inout.update();
},"json");
</script>
<section id="ccare-index-sidebar">
    <div class="mail-container-header" style="margin-top:0px;">
        KPRK - <?php echo $row->fullname;?>
        <a href="javascript:void(0);" onclick="javascript:closeIndexSide();"><span class="fa fa-times pull-right"></span></a>
    </div>
    <form>
        <input type="hidden" id="show-code" value="<?php echo $row->code;?>">
        <div class="col-md-12">
            <canvas id="chart_inout"></canvas>
        </div>
    </form>
	<div class="col-md-12">
		<div class="form-group">
			<a name="tiket-page" class="btn btn-success btn-block" id="tiket-page" href="<?php echo site_url('krpk/d/'.$row->id);?>">Lihat Detail</a>
		</div>
	</div>
</section>