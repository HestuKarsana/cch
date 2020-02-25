<section id="page-report-dashboard-nasional">
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
                <input type="hidden" id="pencapaian" name="pencapaian">
                <input type="hidden" id="regional_asal" name="regional_asal">
                <input type="hidden" id="regional_tujuan" name="regional_tujuan">
                <input type="hidden" id="jenis_produk" name="jenis_produk">
            </div>
        </div>
        <div class="row padding-dashboard">

            <div class="col-md-4">
                <div class="panel">
                    <div class="panel-heading">
                        <span class="panel-title">Pencapaian</span>
                    </div>
                    <div class="panel-body" style="padding:0;">
                        <div class="stat-panel">
                            <div class="stat-row">
                                <div class=" stat-cell padding-sm valign-middle">
                                    <div id="chart_kpi" style="height: 250px;"></div>
                                </div>
                            </div>	
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="panel">
                    <div class="panel-heading">
                        <span class="panel-title">Asal Pengaduan</span>
                    </div>
                    <div class="panel-body" style="padding:0;">
                        <div class="stat-panel">
                            <div class="stat-row">
                                <div class=" stat-cell padding-sm valign-middle">
                                    <div id="chart_kantor_asal" style="height: 250px;"></div>
                                </div>
                            </div>	
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="panel">
                    <div class="panel-heading">
                        <span class="panel-title">Tujuan Pengaduan</span>
                    </div>
                    <div class="panel-body" style="padding:0;">
                        <div class="stat-panel">
                            <div class="stat-row">
                                <div class=" stat-cell padding-sm valign-middle">
                                    <div id="chart_kantor_tujuan" style="height: 250px;"></div>
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
                        <span class="panel-title">Produk</span>
                    </div>
                    <div class="panel-body">
                        <div class="stat-panel">
                            <div class="stat-row">
                                <div class=" stat-cell padding-sm valign-middle">
                                    <div id="chart_product" style="height: 200px;"></div>
                                </div>
                            </div>	
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel">
                    <div class="panel-heading">
                        <span class="panel-title">Sebaran Masalah</span>
                    </div>
                    <div class="panel-body">
                        <div class="stat-panel">
                            <div class="stat-row">
                                <div class=" stat-cell padding-sm valign-middle">
                                    <div id="chart_masalah" style="height: 200px;"></div>
                                </div>
                            </div>	
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>