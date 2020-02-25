<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>POS INDONESIA</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css');?>">
    <!-- Font Awesome CSS-->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/font-awesome.min.css');?>">
    <!-- Fontastic Custom icon font-->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/fontastic.css');?>">
    <!-- Google fonts - Roboto -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
    <!-- jQuery Circle-->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/grasp_mobile_progress_circle-1.0.0.min.css');?>">
    <!-- Custom Scrollbar-->
    <!-- theme stylesheet-->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/style.default.css');?>" id="theme-stylesheet">
    <!-- Favicon-->
    <link rel="shortcut icon" href="<?php echo base_url('assets/img/favicon.ico');?>">
    <!-- Tweaks for older IEs--><!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
	<script>
		var site_url = '<?php echo site_url();?>/';
	</script>
  </head>
  <body>
    <div class="page login-page">
      <div class="container-fluid" style="padding:0;">
        <div class="form-outer text-center d-flex align-items-center">
          <div class="form-inner">
            <div class="logo text-uppercase"><span>POS</span><strong class="text-primary">INDONESIA</strong></div>
            <p>CUSTOMER COMPLAINT HANDLING</p>
            <form method="post" class="text-left" id="form-login">
				      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>" />
              <div class="form-group-material">
                <input id="login-username" type="text" name="username" required data-msg="Please enter your username" class="input-material" autocomplete="off">
                <label for="login-username" class="label-material">Username</label>
              </div>
              <div class="form-group-material">
                <input id="login-password" type="password" name="password" required data-msg="Please enter your password" class="input-material" autocomplete="off">
                <label for="login-password" class="label-material">Password</label>
              </div>
              <div class="form-group text-center"><button id="submit" class="btn btn-primary" type="submit">Login</button>
                <!-- This should be submit button but I replaced it with <a> for demo purposes-->
              </div>
            </form>
			<!--
			<a href="#" class="forgot-pass">Forgot Password?</a><small>Do not have an account? </small><a href="register.html" class="signup">Signup</a>
			-->
          </div>
          <div class="copyrights text-center">
            <p>Theme by <a href="#" class="external" style="color:#FFF;">QNAN Theme</a></p>
            <!-- Please do not remove the backlink to us unless you support further theme's development at https://bootstrapious.com/donate. It is part of the license conditions. Thank you for understanding :)-->
          </div>
        </div>
      </div>
    </div>
    <!-- JavaScript files-->
    <script src="<?php echo base_url('assets/js/jquery.min.js');?>"></script>
    <script src="<?php echo base_url('assets/js/popper.min.js');?>"> </script>
    <script src="<?php echo base_url('assets/js/bootstrap.min.js');?>"></script>
    <script src="<?php echo base_url('assets/js/grasp_mobile_progress_circle-1.0.0.min.js');?>"></script>
    <script src="<?php echo base_url('assets/js/jquery.cookie.js');?>"> </script>
    <script src="<?php echo base_url('assets/js/jquery.validate.min.js');?>"></script>
	  <script src="<?php echo base_url('assets/js/sweetalert.min.js');?>"></script>
    <!-- Main File-->
    <script src="<?php echo base_url('assets/js/login.js');?>"></script>
  </body>
</html>