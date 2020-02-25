<script>
$('#btn-detail-tracking').on('click', function(res){
    $('#detail-tracking-modal #myModalLabel').text('Detail Resi : '+$('#no_resi').val());
    $('#detail-tracking-modal').modal({
        show:true,
        keyboard:false,
        backdrop:'static'
    });
    
    $.post( site_url + 'app/tracking', { resi : $('#no_resi').val() }, function(res){
        $('#resultTracking').datagrid({
                    //url: site_url + 'ccare/get_data_list',
                    data:res,
                    //title:'Data Ticket',
                    height: 400,
                    nowrap: false,
                    striped: true,
                    remoteSort: false,
                    singleSelect: true,
                    fitColumns: true,
                    //toolbar:"#toolbar",
                    queryParams: {
                    },
                    columns:[[
                        {field:'barcode',title:'Nama', width:100, sortable:false, align:'left'},
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
})
</script>
<section id="ccare-index-sidebar">
    <div class="mail-container-header" style="margin-top:0px;">
    Information
    <a href="javascript:void(0);" onclick="javascript:closeIndexSide();"><span class="fa fa-times pull-right"></span></a>
    </div>
    <form>
        <div class="col-md-12">
            <div class="form-group">
                <label>No Ticket</label>
                <input type="text" name="no_ticket" id="no_ticket" value="<?php echo $row->no_ticket;?>" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label>Subject</label>
                <input type="text" name="no_ticket" id="no_ticket" value="<?php echo $row->subject;?>" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label>Status</label>
                <input type="text" name="no_ticket" id="status" value="<?php echo $row->status_name;?>" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label>Tgl Pengaduan</label>
                <input type="text" name="no_ticket" id="status" value="<?php echo $row->date;?>" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label>No Resi</label>
                <div class="input-group">
                    <input type="text" name="no_resi" id="no_resi" value="<?php echo $row->awb;?>" class="form-control" readonly>
                    <span class="input-group-btn">
                        <button class="btn btn-default" id="btn-detail-tracking" type="button"><i class="fa fa-search"></i>.</button>
                    </span>
                </div>
            </div>
            <div class="form-group">
                <label>Status Resi Terakhir</label>
                <textarea name="no_ticket" id="status" rows="6" class="form-control" readonly><?php echo $tracking;?></textarea>
            </div>
        </div>
    </form>
</section>

<div class="modal fade" id="detail-tracking-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel" style="color:#000;"></h4>
      </div>
      <div class="modal-body">
            <table id="resultTracking"></table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>