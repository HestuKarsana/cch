<script>
    //$('#form-tracking-resi').validate({
    //    rules:{
    //        resi:{required:true}
    //    },
    //    submitHandler: function(form) {

		$('#fileupload').fileupload({
                url: site_url + 'app/do_upload_tmp',
                dataType: 'json',
                done: function (e, data) {
                    $.each(data.result.files, function (index, file) {
                        $('<p/>').text(file.name).appendTo('#files');
                    });
                },
                progressall: function (e, data) {
                    var progress = parseInt(data.loaded / data.total * 100, 10);
                    $('#progress .progress-bar').css('width', progress + '%');
                    $('#progress #percentValue').text(progress + '% Complete');
                }
            });
            $('#fileupload').bind('fileuploadsend', function (e, data) {
                /*
				$('#modal_progress').modal({
                  keyboard: false,
                  backdrop : 'static',
                  show : true
                });
				*/
                //Cookies.set('sap-upload',0);
            }).bind('fileuploaddone', function (e, data) {
                
                if(data.status){
                    
                    load_media();
                    
                }else{
                    swal("Sorry",data.message, "error");
                }
                //console.log(data.result[0].status);
                
            })
		load_media();
		function load_media(){
			$.post( site_url + 'app/load_media_uploaded', function(res){
				var lfile = '';
				lfile += '<ul class="list-unstyled">';
				$.each(res, function( key, value ) {
					lfile += '<li><a href="javascript:void(0);" onclick="javascript:openFile(\''+value.id+'\')"><i class="fa '+value.icon+'"></i> '+value.file_name+'</a> <a href="javascript:void(0);" onclick="javascript:removeFile(\''+value.id+'\');"><i class="fa fa-times" title="Hapus"></i></a></li>';
				});
				lfile += '</ul>';
				$('#list-media').html(lfile);
			},"json");
		}
		
		function openFile(id){
			$.post( site_url + 'app/download_media', {mid : id}, function(res){
				if(res.status){
					SaveToDisk(res.path, res.name);
				}else{
					swal("Kesalahan",res.message, "error");
				}
			},"json");
		}
		
		function removeFile(id){
			swal({
				title: "Yakin anda akan menghapus dokumen ini ?",
				text: "Sekali anda menghapus maka tidak akan bisa dikembalikan lagi",
				icon: "warning",
				buttons: true,
				dangerMode: true,
			}).then((willDelete) => {
				if (willDelete) {
					$.post( site_url + 'app/remove_media',{mid : id}, function(res){
						if(res.status){
							swal("Berhasil! file telah dihapus!", {
								icon: "success",
								timer: 2000
							});
							load_media();
						}else{
							swal("Kesalahan",res.message, "error");
						}
					},"json");
				} else {
					//swal("Your imaginary file is safe!");
				}
			});
			
		}
		load_kntrpos_selectbox();
		$('.iradio-exim').on('change', function(e){
			//$('.input-search-negara')
			$('.label-import, .label-export').addClass('hidden');
			$('#receiver_name_manual, #sender_name_manual').val("").trigger('change');
			if($(this).val() == 'import'){
				$('.label-import').removeClass('hidden');
				$('#sender_name_manual').addClass('input-search-negara').removeClass('input-search-kantorpos');
				$('#receiver_name_manual').removeClass('input-search-negara').addClass('input-search-kantorpos');
				$('#sender_name_manual').select2('destroy');
				load_negara_selectbox();
				load_kntrpos_selectbox();
			}else{
				$('.label-export').removeClass('hidden');
				$('#receiver_name_manual').addClass('input-search-negara').removeClass('input-search-kantorpos');
				$('#sender_name_manual').removeClass('input-search-negara').addClass('input-search-kantorpos');
				$('#receiver_name_manual').select2('destroy');
				load_negara_selectbox();
				load_kntrpos_selectbox();
			}
		})
		

		$('.select2min').select2({minimumResultsForSearch:Infinity});
		$('.newticket').addClass('hidden');
		$('.services_type').on('change', function(e){
			//console.log($(this).val());
			
			$('.additional').addClass('hidden');
			$('#additionalnotes').val('');
			$('#note').removeAttr('disabled').val('');
			
			$('.keuanganonly').addClass('hidden');

			$('#jenis_layanan').rules('remove','required');
			$('#jenis_layanan_intl').rules('remove','required');
			$('#jenis_layanan_keuangan').rules('remove','required');

			//$('#btn-')

			if($(this).val() == 'domestik'){
				$('.manual').addClass('hidden');
				$('#jenis_layanan').rules('add',{'required':true});
				$('.api').removeClass('hidden');
			}else if($(this).val() == 'internasional'){
				$('#jenis_layanan_intl').rules('add',{'required':true});

				$('.manual').removeClass('hidden');
				$('.internationalonly').removeClass('hidden');
				$('.api').addClass('hidden');
			}else if($(this).val() == 'keuangan'){
				$('#jenis_layanan_keuangan').rules('add',{'required':true});
				$('.manual').removeClass('hidden');
				$('.keuanganonly').removeClass('hidden');
				$('.internationalonly').addClass('hidden');
				$('.api').addClass('hidden');
			}
		});

		$('#btn-checkmanual').on('click', function(){
			// Import & Keuangan Cek Resi
			var jenis_kiriman = $("input[name='services_type']:checked").val();
			console.log(jenis_kiriman);
			$.post( site_url + 'app/check_ticket',{jenis_kiriman : jenis_kiriman, resi:$('#resi_manual').val()},function(res){
				if(res.new_ticket){
					if(res.xray_ticket){
						$('#note').val(res.xray_ticket_info).attr('disabled','disabled');
						if(res.ticket_recreate){
							$('.recreate').removeClass('hidden');
						}
					}else{
						var opt = '<option value="'+res.kantor_tujuan_kirim+'" selected>'+res.kantor_tujuan_kirim_name+' '+res.kantor_tujuan_kirim+'</option>'
						$('#receiver_name').html(opt);
						$('.newticket').removeClass('hidden');
						$('#note').val(res.tracking_ticket);
					}
					
				}else{
					$('.newticket').addClass('hidden');
					var tnote = $('#note').val();
					if(!res.ticket_recreate){
						$('.additional').removeClass('hidden');
						$('#additionalnotes').val(res.addons_value);	
					}else{
						$('.recreate').removeClass('hidden');
					}
					
					$('#note').val(res.ticket_info).attr('disabled','disabled');
					$('#tid').val(res.ticket_id);
					$('#link-to-ticket').removeClass('hidden');
					$('#link-to-ticket > a').attr('href', site_url + 'ticket/d/' + res.ticket_id);
				}
			},"json")
		})

        $('#form-helper-btn-check').on('click', function(){
			$('.additional, .recreate, #link-to-ticket').addClass('hidden');
			$('#link-to-ticket > a').attr('href','javascript:void(0);');
			$('#recreate').prop("checked", false);

			$('#additionalnotes').val('');
			$('#note').removeAttr('disabled').val('');
			$.post ( site_url + 'app/check_ticket', {resi:$('#resi').val()}, function(res){
				if(res.new_ticket){
					
					
					if(res.xray_ticket){
						$('#note').val(res.xray_ticket_info).attr('disabled','disabled');
						if(res.ticket_recreate){
							$('.recreate').removeClass('hidden');
						}
					}else{
						var opt = '<option value="'+res.kantor_tujuan_kirim+'" selected>'+res.kantor_tujuan_kirim_name+' '+res.kantor_tujuan_kirim+'</option>'
						$('#receiver_name').html(opt);
						$('.newticket').removeClass('hidden');
						$('#note').val(res.tracking_ticket);
					}
					
				}else{
					$('.newticket').addClass('hidden');
					var tnote = $('#note').val();
					if(!res.ticket_recreate){
						$('.additional').removeClass('hidden');
						$('#additionalnotes').val(res.addons_value);	
					}else{
						$('.recreate').removeClass('hidden');
					}
					
					$('#note').val(res.ticket_info).attr('disabled','disabled');
					$('#tid').val(res.ticket_id);
					$('#link-to-ticket').removeClass('hidden');
					$('#link-to-ticket > a').attr('href', site_url + 'ticket/d/' + res.ticket_id);
				}
			},"json");

			$('#recreate').on('change', function(e){
				if($(this).is(':checked')){
					$('#note').removeAttr('disabled');
					$('.newticket').removeClass('hidden');
				}else{
					$('#note').attr('disabled','disabled');
					$('.newticket').addClass('hidden');
				}
			})


			
			/*
            $.post ( site_url + 'app/tracking', {resi:$('#resi').val()}, function(res){
				
				var note = '';
				var tnote = $('#note').val();
				$.each( res.rows, function( k, v ) {
					note += '------------ \n';
					note += 'Barcode : '+v.barcode+'\n';
					note += v.eventDate+' - '+v.eventName+'\n';
					note += 'Office : '+v.office+'\n';
					note += 'Deskripsi : '+v.description+'\n';
				});
				$('#note').val(tnote + note);
				
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
			*/
        })
            
    //    }
    //})
	/*
	$('#tujuan_pengaduan').select2({
		tags: true,
		multiple:true,
        ajax: {
            url: site_url + 'app/kantor_posdd_new',
            dataType: 'json',
            method:"POST",
            delay: 250,
            data: function (params) {
                var query = {
                    city: params.term
                }
                return query;
            },
            processResults: function (data) {
                return {
                    results : data
                }
            },
            cache: true
        },
        placeholder: 'Cari Kantor Pos',
        minimumInputLength: 1,
    })
	*/

	function load_negara_selectbox(){
		$('.input-search-negara').select2({
			ajax: {
				//url: site_url + 'app/kantor_posdd_new',
				url: site_url + 'app/get_negara',
				dataType: 'json',
				method:"POST",
				delay: 250,
				data: function (params) {
					var query = {
						city: params.term
					}
					return query;
				},
				processResults: function (data) {
					return {
						results : data
					}
				},
				cache: true
			},
			placeholder: 'Cari Negara',
			minimumInputLength: 1,
		})
	}
	/*
	$('.input-search-negara').select2({
		ajax: {
            //url: site_url + 'app/kantor_posdd_new',
            url: site_url + 'app/get_negara',
            dataType: 'json',
            method:"POST",
            delay: 250,
            data: function (params) {
                var query = {
					city: params.term
                }
                return query;
            },
            processResults: function (data) {
                return {
                    results : data
                }
            },
            cache: true
        },
        placeholder: 'Cari Kantor Pos',
        minimumInputLength: 1,
	})
	*/
	function load_kntrpos_selectbox(){
		$('.input-search-kantorpos').select2({
			ajax: {
				//url: site_url + 'app/kantor_posdd_new',
				url: site_url + 'app/get_kantor_pos',
				dataType: 'json',
				method:"POST",
				delay: 250,
				data: function (params) {
					var query = {
						city: params.term
					}
					return query;
				},
				processResults: function (data) {
					return {
						results : data
					}
				},
				cache: true
			},
			placeholder: 'Cari Kantor Pos',
			minimumInputLength: 1,
		})
	}
</script>

<section>
    <!--<form id="form-tracking-resi">-->
		
		<fieldset class="scheduler-border">
			<legend class="scheduler-border">Jenis</legend>
			<div class="row">
				<div class="col-md-4">
					<div class="checkbox">
						<label>
							<input type="radio" id="domestik" name="services_type" checked="checked" required class="iradio services_type" value="domestik"> Domestik
						</label>
					</div>
				</div>
				<div class="col-md-4">
					<div class="checkbox">
						<label>
							<input type="radio" id="internasional" name="services_type" required class="services_type iradio" value="internasional"> Internasional
						</label>
					</div>
				</div>
				<div class="col-md-4">
					<div class="checkbox">
						<label>
							<input type="radio" id="keuangan" name="services_type" required class="services_type iradio" value="keuangan"> Keuangan
						</label>
					</div>
				</div>
				<label for="services_type"></label>
			</div>
		</fieldset>
		<div class="api">
			
			<div class="row">
				<div class="col-md-8">
					<div class="form-group">
						<label> No Barcode / AWB</label>
						<div class="input-group">
							<input type="text" name="resi" id="resi" class="form-control required">
							<span class="input-group-btn">
								<button type="button" class="btn btn-default" id="form-helper-btn-check">Cek</button>
							</span>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label for="jenis_layanan"> Jenis Layanan</label>
						<select name="jenis_layanan" id="jenis_layanan" class="form-control select2min required" style="width:100%">
							<option value=""> Pilih </option>
							<option value="PE"> Pos Express </option>
							<option value="SKH"> Surat Kilat Khusus </option>
							<option value="PPB"> Paket Pos Biasa </option>
						</select>
					</div>
				</div>
			</div>

			<div class="form-group form-check recreate hidden">
				<input type="checkbox" class="form-check-input" id="recreate" name="recreate" value="1">
				<label class="form-check-label" for="recreate">Buat Tiket Baru</label>
			</div>
			

			<div class="form-group newticket">
				<label for="tujuan_pengaduan"> Kantor Tujuan Pengaduan</label>
					<!--<input type="text" name="tujuan_pengaduan" id="tujuan_pengaduan" class="form-control input-search-kantorpos">-->
					<select style="width:100%" name="tujuan_pengaduan[]" id="tujuan_pengaduan" class="form-control input-search-kantorpos required">
					</select>
					
			</div>

			<div class="row newticket">
				<div class="col-md-6">
					<div class="form-group">
						<label for="sender_name">Kantor Asal Kirim</label>
							<!--<input type="text" class="form-control input-search-kantorpos" id="sender_name" name="sender_name" placeholder="Kantor Asal">-->
							<select style="width:100%" name="sender_name" id="sender_name" class="form-control input-search-kantorpos">
							</select>
							
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="receiver_name">Kantor Tujuan Kiriman</label>
							<!--<input type="text" class="form-control input-search-kantorpos" id="receiver_name" name="receiver_name" placeholder="Kantor Tujuan" >-->
							<select style="width:100%" name="receiver_name" id="receiver_name" class="form-control input-search-kantorpos">
							</select>
							
					</div>
				</div>
			</div>
			
			<fieldset class="scheduler-border newticket">
				<legend class="scheduler-border">Channel POS</legend>
				<div class="row">
					<div class="col-md-3">
						<div class="checkbox">
							<label>
								<input type="radio" id="jenis_pos_agen" name="jenis_pos" required class="iradio" value="agen_pos"> Agen
							</label>
						</div>
					</div>
					<div class="col-md-3">
						<div class="checkbox">
							<label>
								<input type="radio" id="jenis_pos_kantor" name="jenis_pos" required class="iradio" value="kantor_pos"> Loket
							</label>
						</div>
					</div>
					<div class="col-md-3">
						<div class="checkbox">
							<label>
								<input type="radio" id="jenis_pos_kantor" name="jenis_pos" required class="iradio" value="oranger"> Oranger
							</label>
						</div>
					</div>
					<div class="col-md-3">
						<div class="checkbox">
							<label>
								<input type="radio" id="jenis_pos_kantor" name="jenis_pos" required class="iradio" value="korporat"> Korporat
							</label>
						</div>
					</div>
					<label for="jenis_pos"></label>
				</div>
			</fieldset>
			<fieldset class="scheduler-border newticket">
				<legend class="scheduler-border">Jenis Customer</legend>
				<div class="row">
					<div class="col-md-6">
						<div class="checkbox">
							<label>
								<input type="radio" id="jenis_pengirim_ritel" name="jenis_customer" required class="iradio" value="ritel"> Ritel
							</label>
						</div>
					</div>
					<div class="col-md-6">
						<div class="checkbox">
							<label>
								<input type="radio" id="jenis_pengirim_korporat" name="jenis_customer" required class="iradio" value="korporate"> Korporat
							</label>
						</div>
					</div>
					<label for="jenis_customer"></label>
				</div>
			</fieldset>
			
			<fieldset class="scheduler-border newticket">
				<legend class="scheduler-border">Jenis Bisnis</legend>
				<div class="row">
					<div class="col-md-6">
						<div class="checkbox">
							<label>
								<input type="radio" id="jenis_bisnis_ecommerce" name="jenis_bisnis" required class="iradio" value="ecommerce"> e-Commerce
							</label>
						</div>
					</div>
					<div class="col-md-6">
						<div class="checkbox">
							<label>
								<input type="radio" id="jenis_bisnis_nonecommerce" name="jenis_bisnis" required class="iradio" value="non-ecommerce"> Non e-Commerce
							</label>
						</div>
					</div>
					<label for="jenis_pengirim"></label>
				</div>
			</fieldset>
		</div>
		
		<div class="manual hidden">
			<fieldset class="scheduler-border internationalonly hidden">
				<legend class="scheduler-border">Jenis Kiriman</legend>
				<div class="row">
					<div class="col-md-6">
						<div class="checkbox">
							<label>
								<input type="radio" id="jenis_kiriman_import" name="jenis_kiriman" required class="iradio iradio-exim" value="import"> Import
							</label>
						</div>
					</div>
					<div class="col-md-6">
						<div class="checkbox">
							<label>
								<input type="radio" id="jenis_kiriman_export" name="jenis_kiriman" required class="iradio iradio-exim" value="export"> Export
							</label>
						</div>
					</div>
					<label for="jenis_kiriman"></label>
				</div>
			</fieldset>
			<div class="row">
				<div class="col-md-8">
					<!--
					<div class="form-group">
						<label> No Barcode / AWB</label>
						<input type="text" name="resi_manual" id="resi_manual" class="form-control required" placeholder="No Barcode / AWB">
					</div>
					-->
					<div class="form-group">
						<label> No Barcode / AWB</label>
						<div class="input-group">
							<input type="text" name="resi_manual" id="resi_manual" class="form-control required">
							<span class="input-group-btn">
								<button type="button" class="btn btn-default" id="btn-checkmanual">Cek</button>
							</span>
						</div>
					</div>
				</div>
				<div class="col-md-4 internationalonly hidden">
					<div class="form-group">
					<label for="jenis_layanan_intl"> Jenis Layanan </label>
					<select name="jenis_layanan_intl" id="jenis_layanan_intl" class="form-control select2min" style="width:100%">
						<option value=""> Pilih </option>
						<option value="EMS"> Express Mail Services </option>
						<option value="PBI"> Paket Biasa Inter </option>
						<option value="PCI"> Paket Cepat Inter </option>
						<option value="SLN"> Standar LN </option>
						<option value="SMP"> Small Packet </option>
						<option value="EP"> ePaket </option>
						<option value="PE"> Pos Express </option>
					</select>
					</div>
				</div>

				<div class="col-md-4 keuanganonly hidden">
				<div class="form-group">
					<label for="jenis_layanan_keuangan"> Jenis Layanan </label>
					<select name="jenis_layanan_keuangan" id="jenis_layanan_keuangan" class="form-control select2min" style="width:100%">
						<option value=""> Pilih </option>
						<option value="WP"> Wesel Pos </option>
						<option value="WU"> Western Union </option>
					</select>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label for="tujuan_pengaduan"> Kantor Tujuan Pengaduan</label>
				<select style="width:100%" name="tujuan_pengaduan_manual" id="tujuan_pengaduan_manual" class="form-control input-search-kantorpos required" placeholder="Tujuan Aduan"></select>
			</div>

			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="sender_name"><span id="kantor_asal_exp" class="label-export">Kantor Asal Kirim</span><span id="negara_asal"class="hidden label-import">Negara Asal Kirim</span></label>
						<!--<input type="text" name="sender_name_manual" id="sender_name_manual" class="form-control" placeholder="Asal Kirim">-->
						<select style="width:100%" name="sender_name_manual" id="sender_name_manual" class="form-control input-search-kantorpos">
						</select>
						<input type="text" name="sender_name_import" id="sender_name_import" class="form-control hidden">
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="receiver_name"><span id="negara_tujuan" class="hidden label-export">Negara Tujuan Kirim</span><span id="kantor_tujuan_imp" class="label-import">Kantor Tujuan Kirim</span></label>
						<!--<input type="text" name="receiver_name_manual" id="receiver_name_manual" class="form-control" placeholder="Tujuan Kirim">-->
						<select style="width:100%" name="receiver_name_manual" id="receiver_name_manual" class="form-control input-search-kantorpos">
						</select>
						<input type="text" name="receiver_name_import" id="receiver_name_import" class="form-control hidden">
					</div>
				</div>
			</div>
		</div>
        <div class="form-group">
            <label for="note"> Catatan <span id="link-to-ticket" style="padding-left:20px;" class="hidden"><a href="javascript:void(0);">Lihat Tiket</a></span></label>
            <textarea name="note" id="note" class="form-control" rows="4"></textarea>
        </div>
		<div class="form-group additional hidden">
            <label for="additionalnotes"> Info tambahan</label>
            <textarea name="additionalnotes" id="additionalnotes" class="form-control" rows="3"></textarea>
        </div>
		<div class="form-group" id="list-media">
        </div>
		<span class="btn btn-primary fileinput-button">
			<span>Tambah Dokumen</span>
			<input id="fileupload" type="file" name="files[]">
		</span>
		
        
    <!--</form>-->
	
	<div class="row">
		<div class="col-md-12">
			<table id="resultTracking"></table>
		</div>
	</div>
</section>