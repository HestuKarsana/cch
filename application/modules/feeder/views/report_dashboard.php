<script>
$.post( site_url + 'feeder/get_report', function(){
    
},"json");
</script>
<section id="page-report-feeder">
    <div class="dashboard setMargin">
        <div class="row padding-dashboard">
            <div class="col-md-4 col-md-offset-8">
                <div class="row">
                    <div class="col-md-6">
                        
                    </div>
                    <div class="col-md-6">
                        
                    </div>
                </div>
                <input type="hidden" id="marketplace" name="marketplace">
                <input type="hidden" id="regional_asal" name="regional_asal">
                <input type="hidden" id="regional_tujuan" name="regional_tujuan">
                <input type="hidden" id="kantor_asal" name="kantor_asal">
                <input type="hidden" id="kantor_terbangan" name="kantor_terbangan">
                <input type="hidden" id="kantor_tujuan" name="kantor_tujuan">
                <input type="hidden" id="harian" name="harian">
            </div>
        </div>
        <div class="row padding-dashboard">
            <div class="col-md-4">
                <div class="panel">
                    <div class="panel-heading bg-success">
                        <span class="panel-title">MARKET PLACE</span>
                    </div>
                    <div class="panel-body" style="padding:0;">
                        <div class="stat-panel">
                            <div class="stat-row">
                                <div class=" stat-cell padding-sm valign-middle">
                                    <div id="marketplace_upload" style="max-height:300px; height:300px;"></div>
                                </div>
                            </div>	
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="panel">
                    <div class="panel-heading">
                        <span class="panel-title">UPLOAD HARIAN</span>
                    </div>
                    <div class="panel-body"  style="padding:0;">
                        <div class="stat-panel">
                            <div class="stat-row">
                                <div class=" stat-cell padding-sm valign-middle">
                                    <div id="marketplace_daily" style="max-height:300px; height:300;"></div>
                                </div>
                            </div>	
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row padding-dashboard">

            <div class="col-md-6">
                <div class="panel">
                    <div class="panel-heading bg-success">
                        <span class="panel-title">REGIONAL ASAL KIRIM</span>
                    </div>
                    <div class="panel-body" style="padding:0;">
                        <div class="stat-panel">
                            <div class="stat-row">
                                <div class=" stat-cell padding-sm valign-middle">
                                    <div id="xray_asal_kirim" style="max-height:250px; height:250px;"></div>
                                </div>
                            </div>	
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="panel">
                    <div class="panel-heading bg-success">
                        <span class="panel-title">REGIONAL TUJUAN KIRIM</span>
                    </div>
                    <div class="panel-body" style="padding:0;">
                        <div class="stat-panel">
                            <div class="stat-row">
                                <div class=" stat-cell padding-sm valign-middle">
                                    <div id="xray_tujuan_kirim" style="max-height:250px; height:250px;"></div>
                                </div>
                            </div>	
                        </div>
                    </div>
                </div>
            </div>

	    </div>

        <div class="row padding-dashboard">
            <div class="col-md-4">
                <div class="panel">
                    <div class="panel-heading">
                        <span class="panel-title">KANTOR ASAL KIRIM</span>
                    </div>
                    <div class="panel-body" style="padding:0;">
                        <div class="stat-panel">
                            <div class="stat-row">
                                <div class=" stat-cell padding-sm valign-middle">
                                    <div dir="rtl" id="xray_kantor_asal" style="height: 200px;" ></div>
                                </div>
                            </div>	
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel">
                    <div class="panel-heading">
                        <span class="panel-title">KANTOR PENERBANGAN</span>
                    </div>
                    <div class="panel-body">
                        <div class="stat-panel">
                            <div class="stat-row">
                                <div class=" stat-cell padding-sm valign-middle">
                                    <table id="dgterbangan"></table>
                                </div>
                            </div>	
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel">
                    <div class="panel-heading">
                        <span class="panel-title">KANTOR TUJUAN KIRIM</span>
                    </div>
                    <div class="panel-body" style="padding:0;">
                        <div class="stat-panel">
                            <div class="stat-row">
                                <div class=" stat-cell padding-sm valign-middle">
                                    <div id="xray_kantor_tujuan" style="height: 200px; max-height:250px;"></div>
                                </div>
                            </div>	
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row padding-dashboard">

            <div class="col-md-6">
                <div class="panel">
                    <div class="panel-heading">
                        <span class="panel-title">XRAY HARIAN</span>
                    </div>
                    <div class="panel-body">
                        <div class="stat-panel">
                            <div class="stat-row">
                                <div class=" stat-cell padding-sm valign-middle">
                                    <div id="xray_daily" style="max-height:250px; height:200px;"></div>
                                </div>
                            </div>	
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="panel">
                    <div class="panel-heading">
                        <span class="panel-title">BARANG</span>
                    </div>
                    <div class="panel-body">
                        <div class="stat-panel">
                            <div class="stat-row">
                                <div class=" stat-cell padding-sm valign-middle">
                                    <div id="xray_cloud" style="max-height:250px; height:200px;"></div>
                                </div>
                            </div>	
                        </div>
                    </div>
                </div>
            </div>

	    </div>
</section>