
<section id="page-report-xray">
    <div class="dashboard setMargin">
        <div class="row padding-dashboard">
            <div class="col-md-4 col-md-offset-8">
                <div class="row">
                    <div class="col-md-6">
                        <select name="month" id="month" class="form-control select2min">
                            <option value="">Semua</option>
                            <option value="07">Jul</option>
                            <option value="08">Aug</option>
                        </select>
                        
                    </div>
                    <div class="col-md-6">
                        <select name="year" id="year" class="form-control select2min">
                            <option value="2019">2019</option>
                        </select>
                        
                    </div>
                </div>
                <input type="hidden" id="regional_asal" name="regional_asal">
                <input type="hidden" id="regional_tujuan" name="regional_tujuan">
                <input type="hidden" id="kantor_asal" name="kantor_asal">
                <input type="hidden" id="kantor_terbangan" name="kantor_terbangan">
                <input type="hidden" id="kantor_tujuan" name="kantor_tujuan">
                <input type="hidden" id="harian" name="harian">
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