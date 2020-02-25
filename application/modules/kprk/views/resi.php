<script>
    $('#form-tracking-resi').validate({
        rules:{
            resi:{required:true}
        },
        submitHandler: function(form) {
            $.post ( site_url + 'app/tracking', $('#form-tracking-resi').serialize(), function(res){
                $('#resultTracking').datagrid({
                    //url: site_url + 'ccare/get_data_list',
                    data:res,
                    //title:'Data Ticket',
                    height: 200,
                    nowrap: false,
                    striped: true,
                    remoteSort: false,
                    singleSelect: true,
                    fitColumns: true,
                    //toolbar:"#toolbar",
                    queryParams: {
                    },
                    columns:[[
                        {field:'barcode',title:'Barcode / ID Kirim', width:100, sortable:false, align:'left'},
                        {field:'office',title:'Office',width:100, align:'left',
                            formatter:function(v,r,i){
                                return r.officeCode+' '+v;
                            }
                        },
                        {field:'eventName',title:'Event',width:200, align:'left'},
                        {field:'eventDate',title:'Waktu',width:100, align:'left'},
                        {field:'description',title:'Description',width:200, align:'left'},
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
</script>

<section>
    <form id="form-tracking-resi">
        <div class="col-md-12">
            <div class="form-group">
                <label> No Barcode / AWB</label>
                <div class="input-group">
                    <input type="text" name="resi" id="resi" class="form-control">
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-default" id="form-helper-btn-check" type="button">Cek</button>				
                    </span>
                </div>
            </div>
        </div>
    </form>

    <div class="col-md-12">
        <table id="resultTracking"></table>
    </div>
</section>