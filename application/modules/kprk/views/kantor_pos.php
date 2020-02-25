<script>
    $('#helper-cari-kantorpos').validate({
        rules:{
            kota: { required:true}
        },
        submitHandler: function(form) {
            $.post ( site_url + 'app/kantor_pos', $('#check-ongkir').serialize(), function(res){
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
        }
    });
</script>

<section>
    <div class="rows">
        <div class="col-md-12">
            <form id="helper-cari-kantorpos">
                <div class="form-group">
                    <label for="kota">Kota</label>
                    <input type="text" name="kota" id="kota" class="form-control">
                </div>

                <div class="form-group">
                    <label for="area">Alamat</label>
                    <input type="text" name="area" id="area" class="form-control">
                </div>

                <div class="form-group">
                    <button id="btn-check-kantorpos" class="btn btn-primary">Cari Kantor Pos</button>
                </div>
            </form>
        </div>
    </div>
    <div class="rows">
        <div class="col-md-12">
            <table id="resultKantorPos"></table>
        </div>
    </div>
</section>