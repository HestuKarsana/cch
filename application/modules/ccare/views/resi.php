<script>
    $('#form-helper-btn-check').on('click', function(r){
        if($('#resi').val() != ''){
            $.post ( site_url + 'app/check_ticket',{resi:$('#resi').val()}, function(res){
                if(res.new_ticket){

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
                        ]],
                        onDblClickRow: function(i, r){
                        }
                    });

                    $('#complaint').val('');
                    $('#complaint').val(res.tracking_ticket);

                }
            },"json");
        }
    })
</script>

<section>
    <div class="col-md-12">
        <div class="form-group">
            <label> No Barcode / AWB</label>
            <div class="input-group">
                <input type="text" name="resi" id="resi" class="form-control">
                <span class="input-group-btn">
                    <button type="button" class="btn btn-default" id="form-helper-btn-check" type="button">Cek</button>				
                </span>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <textarea name="complaint" id="complaint" class="form-control hidden"></textarea>
        <table id="resultTracking"></table>
    </div>
</section>