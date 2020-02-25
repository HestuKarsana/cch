<script>
    $('#propinsi').select2({
        ajax: {
            url: site_url + 'app/load_province',
            dataType: 'json',
            method:"POST",
            delay: 250,
            processResults: function (data) {
                return {
                    results : data
                }
            },
            cache: true
        },
        placeholder: 'Cari Propinsi..',
        minimumInputLength: 1,
    })

    $('#kota').select2({
        ajax: {
            url: site_url + 'app/load_city',
            dataType: 'json',
            data: function (params) {
                var query = {
                    search: params.term,
                    province: $('#propinsi').val()
                }
                return query;
            },
            method:"POST",
            delay: 250,
            processResults: function (data) {
                return {
                    results : data
                }
            },
            cache: true
        },
        placeholder: 'Cari kota..',
        minimumInputLength: 1,
    })


    $('#kecamatan').select2({
        ajax: {
            url: site_url + 'app/load_kodepos',
            dataType: 'json',
            data: function (params) {
                var query = {
                    search: params.term,
                    //province: $('#propinsi').val()
                }
                return query;
            },
            method:"POST",
            delay: 250,
            processResults: function (data) {
                
                $('#resultGetPosCode').datagrid({
                    //url: site_url + 'ccare/get_data_list',
                    data:data,
                    //title:'Data Ticket',
                    height: 400,
                    nowrap: false,
                    striped: true,
                    remoteSort: false,
                    singleSelect: true,
                    fitColumns: true,
                    pagination:false,
                    rownumbers:true,
                    //pageSize:25,
                    //pageList:[25,50,75,100],
                    //toolbar:"#toolbar",
                    queryParams: {
                    },
                    columns:[[
                        {field:'text',title:'Alamat',width:200},
                        {field:'id',title:'Kode Pos', width:100, sortable:false, align:'left'},
                    ]]
                })
                return {
                    results : data
                }
            },
            cache: true
        },
        placeholder: 'Cari kota..',
        minimumInputLength: 3,
    })



    $('.search-kodepos').on('click', function(){
        $.post(site_url + 'app/load_kecamatan',{search:$('#kecamatan_text').val(), parent:$('#kota').val()}, function(res){
            //$('#resultKantorPos').addClass('hidden');
            
            var prop = $('#propinsi').find(':selected');
            var kota = $('#kota').find(':selected');
            
            var ntext  = "";
                ntext += "Propinsi : "+prop[0].text;
                ntext += "Kota : "+kota[0].text;
                ntext += 'Kecamatan : '+$('#kecamatan_text').val();
            var txt = $('#complaint').val();
            $('#complaint').val(txt+"\n"+ntext);    
            
            

            $('#resultGetPosCode').datagrid({
                //url: site_url + 'ccare/get_data_list',
                data:res,
				//title:'Data Ticket',
				height: 400,
				nowrap: false,
				striped: true,
				remoteSort: false,
				singleSelect: true,
				fitColumns: true,
				pagination:false,
				rownumbers:true,
				pageSize:25,
				pageList:[25,50,75,100],
				//toolbar:"#toolbar",
				queryParams: {
				},
				columns:[[
					{field:'propinsi',title:'Propinsi', width:200, sortable:false, align:'left'},
					{field:'kota',title:'Kota',width:200},
					{field:'kecamatan',title:'Kecamatan',width:250, sortable:false},
					{field:'kelurahan',title:'Kelurahan',width:200, sortable:false, align:'left'},
					{field:'postalCode',title:'Kode POS',width:200, sortable:false, align:'left'},
                ]]
			})
        },"json");
    });
    
    $('#btn-check-kantorpos').on('click', function(){
        $.post ( site_url + 'app/kantor_pos',{kota:$('#kota_pos').val(), area:$('#area').val()}, function(res){

            
            var text = "";
                text += "Kota :"+$('#kota_pos').val();
                text += "Area : "+$('#area').val()
            
            var complaint = $('#complaint').val();
            $('#complaint').val(complaint+"\n"+text);
                
            //$('#resultGetPosCode').addClass('hidden');
            //$('#resultKantorPos').removeClass('hidden');
            $('#resultKantorPos').datagrid({
                //url: site_url + 'ccare/get_data_list',
                data:res,
                //title:'Data Ticket',
                height: 300,
                nowrap: false,
                striped: true,
                remoteSort: false,
                singleSelect: true,
                fitColumns: true,
                //toolbar:"#toolbar",
                queryParams: {
                },
                columns:[[
                    {field:'office_id',title:'Nama', width:100, sortable:false, align:'left'},
                    {field:'office_name',title:'Kantor Pos & Jadwal',width:300, align:'left',
                        formatter:function(v,r,i){
                            return v+" -  "+r.phone+"<br>"+r.address+"<br>"+r.schedule;
                        }
                    },
                    //{field:'phone',title:'Telepon', width:100, sortable:false, align:'left'},
                    {field:'type',title:'Jenis', width:100, sortable:false, align:'left'},
                    //{field:'kecamatan',title:'Kecamatan',width:250, sortable:false},
                    //{field:'kelurahan',title:'Kelurahan',width:200, sortable:false, align:'left'},
                    //{field:'postalCode',title:'Kode POS',width:200, sortable:false, align:'left'},
                ]],
                onDblClickRow: function(i, r){
                }
            })
        },"json");
    })
</script>

<section>
    <div class="rows">
        <div class="col-md-6">
            <h5> Kode POS </h5>
            <div class="row">
                <!--
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="propinsi"> Propinsi </label>
                        <select name="propinsi" class="form-control select2min" id="propinsi" style="width:100%">
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="kota"> Kota </label>
                        <select name="kota" class="form-control select2min" id="kota" style="width:100%">
                        </select>
                    </div>
                </div>
                -->
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="kecamatan"> Kota / Alamat </label>
                        <select name="kecamatan" class="form-control select2min" id="kecamatan" style="width:100%">
                        </select>
                        <!--
                        <div class="input-group">
                            <input type="text" id="kecamatan_text" name="kecamatan_text" class="form-control"/> 
                            <span class="input-group-btn">
                                <button class="btn btn-default search-kodepos" type="button"><i class="fa fa-search"></i>Cari</button>
                            </span>
                        </div>
                        -->
                    </div>

                </div>
                <table id="resultGetPosCode"></table>
            </div>
        </div>
        <div class="col-md-6">
            <h5> Cari Kantor POS</h5>
            <div class="form-group">
                <label for="kota">Kota</label>
                <input type="text" name="kota_pos" id="kota_pos" class="form-control">
            </div>

            <div class="form-group">
                <label for="area">Alamat</label>
                <input type="text" name="area" id="area" class="form-control">
            </div>

            <div class="form-group">
                <button id="btn-check-kantorpos" class="btn btn-primary" type="button">Cari Kantor Pos</button>
            </div>
            <table id="resultKantorPos"></table>
        </div>
    </div>
    <div class="rows">
        <div class="col-md-12">
            
            
            <textarea name="complaint" id="complaint" class="form-control hidden"></textarea>
        </div>
    </div>
</section>