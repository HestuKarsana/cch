
<input type="hidden" value="<?php echo $tpl;?>" id="tpl" name="tpl">
<?php 
if($tpl == 'ongkir'){
    $page = 'Cek Ongkos Kirim';
}else if($tpl == 'resi'){
    $page = 'Lacak No Resi';
}else if($tpl == 'kantor_pos'){
    $page = 'Lokasi Kantor Pos';
}else if($tpl == 'kodepos'){
    $page = 'Kode Pos';
}
?>
<div class="mail-container-header"> <?php echo $page;?> </div>
<div id="box-form" style="min-height:250px;">
</div>

<script>
    $.post ( site_url + 'ccare/load_form_helper_fix', {page:$('#tpl').val()}, function(e){
        $('#box-form').html(e);
    })
</script>