<script>
$('.select2min').select2({minimumResultsForSearch:Infinity});
$('#item_type').on('select2:select', function (e) {
    var data = e.params.data;
    if(data.id == 1){
        $('.input-packet').removeClass('hidden');
        $('.paket-required').rules("add", {required:true, number:true});

    }else{
        $('.input-packet').addClass('hidden');
        $('.paket-required').rules("remove", "required number");
    }
});

$('.myGetKodePos').on('click', function(){

    /*
    var id = $(this).attr('ngbtn');
    $('#ngbtn').val(id);

    $('#myGetKodePos').modal({
        show:true,
        backdrop:'static',
        keyboard:false
    })

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
        //escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
        minimumInputLength: 1,
        //templateResult: formatRepo,
        //templateSelection: formatRepoSelection
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
            url: site_url + 'app/load_kecamatan',
            dataType: 'json',
            data: function (params) {
                var query = {
                    search: params.term,
                    parent: $('#kota').val()
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
        placeholder: 'Cari Kecamatan..',
        minimumInputLength: 1,
    })

    $('.search-kodepos').on('click', function(){
        $.post(site_url + 'app/load_kecamatan',{search:$('#kecamatan_text').val(), parent:$('#kota').val()}, function(res){
            $('#resultGetPosCode').datagrid({
                //url: site_url + 'ccare/get_data_list',
                data:res,
				//title:'Data Ticket',
				height: 300,
				nowrap: true,
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
                ]],
                onDblClickRow: function(i, r){
                    var field   = $('#ngbtn').val();
                    $('#'+field).val(r.postalCode);

                    $('#myGetKodePos').modal('hide');
                    $('#resultGetPosCode').html('');
                }
			})
        },"json");
    });
    */
    $('#kodepos_asal').select2({
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
                return {
                    results : data
                }
            },
            cache: true
        },
        placeholder: 'Cari kota / alamat..',
        minimumInputLength: 3,
    })

    $('#kodepos_tujuan').select2({
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
                return {
                    results : data
                }
            },
            cache: true
        },
        placeholder: 'Cari kota / alamat..',
        minimumInputLength: 3,
    })

    
    $('#check-ongkir').validate({
        rules:{
            kodepos_asal : {required:true, number:true},
            //kodepos_tujuan : {required:true, number:true},
            berat:{required:true, number:true}
        },
        submitHandler: function(form) {
            $.post ( site_url + 'app/ongkir', $('#check-ongkir').serialize(), function(res){
                $('#complaint').val('');
                $('#complaint').val(res.text);
                $('#resultOngkir').datagrid({
                    //url: site_url + 'ccare/get_data_list',
                    data:res,
                    //title:'Data Ticket',
                    height: 200,
                    nowrap: true,
                    striped: true,
                    remoteSort: false,
                    singleSelect: true,
                    fitColumns: true,
                    //toolbar:"#toolbar",
                    queryParams: {
                    },
                    columns:[[
                        {field:'name',title:'Nama', width:200, sortable:false, align:'left'},
                        {field:'totalFee',title:'Ongkir',width:100, align:'right'},
                        //{field:'kecamatan',title:'Kecamatan',width:250, sortable:false},
                        //{field:'kelurahan',title:'Kelurahan',width:200, sortable:false, align:'left'},
                        //{field:'postalCode',title:'Kode POS',width:200, sortable:false, align:'left'},
                    ]],
                    onDblClickRow: function(i, r){
                    }
                })
            },"json");
        }
    })
})

</script>
<section>
<div class="rows">
    <form id="check-ongkir">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Kode Pos Asal</label>
                    <select name="kodepos_asal" class="form-control" id="kodepos_asal" style="width:100%">
                    </select>
                    <!--
                    <div class="input-group">
                        <input type="text" name="kodepos_asal" id="kodepos_asal" class="form-control">
                        <span class="input-group-btn">
                            <button class="btn btn-default myGetKodePos" ngbtn="kodepos_asal" id="btn-kodepos_asal" type="button"><i class="fa fa-search"></i>.</button>
                        </span>
                    </div>
                    -->
                </div>
                
            </div>
            <div class="col-md-6">
                
                <div class="form-group">
                    <label>Kode Pos Tujuan</label>
                    <select name="kodepos_tujuan" class="form-control" id="kodepos_tujuan" style="width:100%">
                    </select>
                    <!--
                    <div class="input-group">
                        <input type="text" name="kodepos_tujuan" id="kodepos_tujuan" class="form-control">
                        <span class="input-group-btn">
                            <button class="btn btn-default myGetKodePos" ngbtn="kodepos_tujuan" id="btn-kodepos_tujuan" type="button"><i class="fa fa-search"></i>.</button>
                        </span>
                    </div>
                    -->
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label>Jenis Kiriman</label>
                    <select name="item_type" id="item_type" class="form-control select2min">
                        <option value="0">Document</option>
                        <option value="1">Paket</option>
                    </select>
                </div>
            </div>

        </div>
        <div class="row input-packet hidden">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="panjang">Panjang</label>
                    <div class="input-group">
                        <input type="text" name="length" id="length" class="form-control paket-required">
                        <span class="input-group-addon" id="basic-addon2">cm</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="lebar">Lebar</label>
                    <div class="input-group">
                        <input type="text" name="width" id="width" class="form-control paket-required">
                        <span class="input-group-addon" id="basic-addon2">cm</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="tinggi">Tinggi</label>
                    <div class="input-group">
                        <input type="text" name="height" id="height" class="form-control paket-required">
                        <span class="input-group-addon" id="basic-addon2">cm</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="weight">Berat</label>
            <div class="input-group">
                <input type="text" name="weight" id="weight" class="form-control">
                <span class="input-group-addon" id="basic-addon2">gram</span>
            </div>
        </div>

        <div class="form-group">
            <button class="btn btn-primary" id="btn-ongkir">Cek Ongkos Kirim</button>
        </div>
    </div>
    </form>
    
</div>
<div class="rows">
    <div class="col-md-12">
        <textarea name="complaint" id="complaint" class="form-control hidden"></textarea>
        <table id="resultOngkir"></table>
    </div>
</div>
</section>
<!-- -->
<div class="modal fade" id="myGetKodePos" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Cari Kode Pos</h4>
      </div>
      <div class="modal-body">
        <div class="row">
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
            <div class="col-md-4">
                <div class="form-group">
                    <label for="kecamatan"> Kecamatan </label>
                    <!--
                    <select name="kecamatan" class="form-control select2min" id="kecamatan" style="width:100%">
                    </select>
                    -->
                    <div class="input-group">
                        <input type="text" id="kecamatan_text" name="kecamatan_text" class="form-control"/> 
                        <span class="input-group-btn">
                            <button class="btn btn-default search-kodepos" type="button"><i class="fa fa-search"></i>Cari</button>
                        </span>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-md-12">
                <table id="resultGetPosCode"></table>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" id="ngbtn" name="ngbtn">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>