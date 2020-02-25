<section id="page-dg-customer-care">
	<div class="mail-containers">
		<div class="mail-container-header">
			Customer Care <span id="page-loading"></span>
			<div class="pull-right col-md-8">
				<form id="searchGrid">
					<div class="row">
						<div class="col-md-6">
							<!--
							<input type="text" class="form-control" placeholder="Search..." name="date" id="date" >
							-->
							<div class="input-daterange input-group" id="datepicker">
								<span class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</span>
								<input type="date" class="form-control text-center input-sm" name="start" id="start" style="padding:0 10px;" />
								<span class="input-group-addon mid">to</span>
								<input type="date" class="form-control text-center input-sm" name="end" id="end" style="padding:0 10px;" />
								<span class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</span>
							</div>
						</div>
						
						<div class="col-md-2">
							<select name="product" class="form-control input-sm" id="product">
								<option value="">Semua Produk</option>
							</select>
						</div>
						<!--
						<div class="col-md-2">
							
							<select name="category" class="form-control input-sm" id="category" >
								<option value="">ALL Category</option>
							</select>
							
						</div>-->
						<div class="col-md-4">
							<div class="input-group input-group-sm">
								<input type="text" class="form-control" placeholder="Search..." name="s" id="key" >
								
								<span class="input-group-btn">
									<button class="btn" type="button" id="btn-search">
										<span class="fa fa-search"></span>
									</button>
									<a href="<?php echo site_url('ccare/form');?>" class="btn btn-primary"><i class="fa fa-plus"></i>Buat Pengaduan</a>
								</span>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="row" id="grid-body">
			<div class="col-md-12">
				<table id="dgContact" class="dgResize"></table>
			</div>
			
		</div>
	</div>
</section>