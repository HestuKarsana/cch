<script type="text/javascript">
	
	function openModals(){
		$('#custModals').modal({
			show : true,
			backdrop:'static',
			keyboard: false
		}).on('shown.bs.modal', function (e) {
			$('#phone').val($('#requester').val());
			
			$("#name").rules("add", { required: true });
			$("#phone").rules("add", { required: true });
		})
	}
	
	function takeit(){
		$.post ( site_url + 'ticket/takeit', function(data){
			$('#assignee').select2('data', data).trigger('change');
		},'json');
	}
	
	function ccme(){
		$.post ( site_url + 'ticket/takeit', function(data){
			$('#ccs').select2('data', data).trigger('change');
		},'json');
	}
	
	function sendFile(file, editor, welEditable) {
		data = new FormData();
		data.append("file", file);
		$.ajax({
			data: data,
			type: "POST",
			dataType: "json",
			url: site_url + 'ticket/uploader',
			cache: false,
			contentType: false,
			processData: false,
			success: function(url) {
				editor.insertImage(welEditable, url.url);
			}
		});
	}
</script>

<section id="page-response">
	<form medthod="post" id="ticket-form" ng-controller="ticketDataForm" ng-init="init('<?php echo $this->uri->segment(3);?>')" >
		<div class="mail-nav" style="position:fixed;" ng-cloak >
			<div class="mail-container-header-left">Informasi Tiket</div>
			<div class="navigation">
				
				<div class="leftForm" style="padding-left:10px; padding-top:10px;">
					<div class="col-md-12">
						<div class="form-group row">
							<label class="col-md-6">Nama Pengadu</label>
							<p class="col-md-6">{{contact_name}}</p>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group row">
							<label class="col-md-6">Alamat Pengadu</label>
							<p class="col-md-6">{{address}}</p>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group row">
							<label class="col-md-6">Telp Pengadu</label>
							<p class="col-md-6">{{phone_number}}</p>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group row">
							<label class="col-md-6">No Barcode</label>
							<p class="col-md-6">{{awb}}</p>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group row">
							<label class="col-md-6">Produk</label>
							<p class="col-md-6">{{produk}}</p>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group row">
							<label class="col-md-6">Asal Aduan</label>
							<p class="col-md-6">{{asal_pengaduan}}</p>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group row">
							<label class="col-md-6">Tujuan Aduan</label>
							<p class="col-md-6">{{tujuan_pengaduan}}</p>
							
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group row">
							<label class="col-md-6">Tgl Entri</label>
							<p class="col-md-6">{{tgl_entry}}</p>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label for="notes-tambahan">Informasi Tambahan</label>
							<textarea name="notes-tambahan" class="form-control" id="notes-tambahan" readonly ng-model="notes"></textarea>
						</div>
					</div>
					

					<div class="col-md-12">
						<div class="form-group">
							<label for="notes-tambahan">Riwayat No Barcode / AWB</label>
							<div style="max-height:150px;  overflow:auto;">
							<ul class="list-unstyled" ng-repeat="hist in history" style="height:50px;">
								<li style="border-bottom:1px solid #888;">
									<a ng-href="{{hist.url_ticket}}" target="_blank">{{hist.no_ticket}} - {{hist.officename}}</a>
									<br/>{{hist.date}}
								</li>
							</ul>
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<a href="javascript:void(0);" class="btnClipboard btn-default btn-block btn" data-clipboard-target="#toCopy"><i class="fa fa-copy"></i> Salin Percakapan</a>
						<div id="toCopy" style="height:0 !important;"></div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="mail-container" ng-cloak>
			<div class="mail-container-header">Ticket No : {{no_ticket}} </div>
			
			<div class="new-mail-form" id="response-sroll-id" ng-controller="ticketResponse" ng-init="init('<?php echo $this->uri->segment(3);?>')">
				
				<div class="row" ng-show="status != 99 && user_type != 'guest'">
					<div class="col-md-12">
						<div class="form-group">
							<label for="response_val">Balasan *</label>
							<input type="hidden" name="response_val" id="response_val"/>
							<textarea class="form-control" id="response" name="response" placeholder="Your Response" rows="7"></textarea>
						</div>
					</div>
				</div>
				<div class="row" ng-show="status != 99 && user_type != 'guest'">
					<div class="col-md-12">
						<div class="form-group" id="list-media">
						</div>
						
					</div>
				</div>
				<div class="row" ng-show="status != 99 && user_type != 'guest'">
					<div class="col-md-6">
						<div class="row">
							<div class="col-md-12 statusSelect">
								<div class="form-group">

									<select name="request" id="request" class="form-control select2min">
										<option value="0">Normal</option>
										<option value="1">Mohon ditutup</option>
										
										<option value="2">Konfirmasi</option>
										<option value="3">Selesai</option>
									</select>
								</div>
							</div>
							<div class="col-md-6 selesaiOnly hidden">
								<select name="category_final" id="category_final" class="form-control select2min" style="width:100%">
									<option value="">Pilih Jenis Aduan</option>
									<option value="6">Keterlambatan</option>
									<option value="7">Kehilangan</option>
									
									<option value="8">Kiriman tidak utuh</option>
									<option value="9">Salah Serah</option>
									<option value="10">Retur Kiriman</option>
									<option value="11">Salah Salur</option>
									<option value="12">Salah Tempel Resi</option>
									
									<!--<option value="13">Pengaduan Layanan</option>
									<option value="14">Belum Terima</option>-->
									<option value="15">Permintaan P6 / POD</option>
									<!--<option value="16">Permintaan Data</option>-->
								</select>
							</div>
							<div class="col-md-6 selesaiOnly hidden">
								<select name="category_detail" id="category_detail" class="form-control select2min" style="width:100%">
									<option value="">Pilih Lokus Masalah</option>
									<option value="C">Collecting</option>
									<option value="P">Prosessing</option>
									<option value="T">Transporting</option>
									<option value="D">Delivery</option>
									<option value="R">Reporting</option>
									
								</select>
							</div>
						</div>
						
					</div>
					<div class="col-md-6">
						<input type="hidden" name="ticket_id" id="ticket_id" ng-value="id">
						<button class="btn btn-primary ld-ext-right" disabled="disabled" id="btn-process">
							Simpan <div class="ld ld-ring ld-spin"></div>
						</button>

						<span class="btn btn-primary fileinput-button">
							<span>Tambah Dokumen</span>
							<input id="fileupload" type="file" name="files[]">
						</span>

						<a href="javascript:void(0);" class="btnClipboard btn btn-default" data-clipboard-target="#contentPengaduan">Salin Percakapan</a>
						
					</div>
				</div>
					
					
					<!-- Response -->
				<div id="contentPengaduan">
					<div class="mail-response {{value.color}}" ng-repeat="value in ticket">
						<div class="mail-info">
							
							<ng-avatar class="avatar" auto-color="true" picture-format="jpeg" bind="true" round-shape="true" string="{{value.realname}}" style="width:40px; height:40px;" class="avatar" ></ng-avatar>
							
							<div class="from">
								<div class="name">{{value.realname}}</div>
								<div class="email">{{value.username}}</div>
							</div>

							<div class="date small">
								<strong><small>{{value.date}} ({{value.date_ago}})</small></strong>
								<br/>
								<span ng-if="value.total_file > 0"><a href="#lampiran-{{id}}">{{value.total_file}} Lampiran</a></span>
							</div>
						</div>
						<div class="mail-media" style="padding:0px 16px;">
							<h5 id="lampiran-{{id}}" ng-if="value.total_file > 0">Lampiran : </h5>
							<ul class="list-unstyled" ng-repeat="resfile in value.media">
								<li><a href="javascript:void(0);" ng-click="openFile(resfile.id)"><i class="fa {{resfile.icon}}"></i> {{resfile.file_name | limitTo:25}}</a></li>
							</ul>
						</div>

						<div class="mail-message-body" ng-bind-html="value.response"></div>
						
					</div>
					
					<!-- /end response -->
					
					
					<!-- First COmplaint -->
				
					<div class="mail-response {{class_first}}" ng-cloak>
						<div class="mail-info">
							<ng-avatar  round-shape="true" style="width:40px; height:40px;" class="avatar" auto-color="true" picture-format="jpeg" bind="true" string="{{user_create}}"></ng-avatar>
							<div class="from">
								<div class="name">{{user_create}} </div>
								<div class="email">{{username}}</div>
							</div>
							<div class="date" style="margin-top:0;">
								<strong><small>{{date_new}} ({{date_ago}})</small></strong>
								<br/>
								<span ng-if="total_file > 0"><a href="#lampiran-{{id}}">{{total_file}} Lampiran</a></span>
							</div>
						</div>
						<div class="mail-media" style="padding:0px 16px;">
							<h5 id="lampiran-{{id}}" ng-if="total_file > 0">Lampiran : </h5>
							<ul class="list-unstyled" ng-repeat="file in media">
								<li><a href="javascript:void(0);" ng-click="openFile(file.id)"><i class="fa {{file.icon}}"></i> {{file.file_name | limitTo:25}}</a></li>
							</ul>
						</div>
						<div class="mail-message-body" ng-bind-html="complaint"></div>
						
					</div>
				</div>
			</div>
		</div>
	
		
	</form>
</section>