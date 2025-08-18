<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="<?php echo e($locale); ?>">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title><?php if (isset($title)){ echo $title; } ?></title>
	<?php echo compile_theme_css(); ?>
	<script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js'); ?>"></script>
	<?php app_customers_head(); ?>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap" rel="stylesheet">
<style>
body{font-family: "Poppins", serif !important;}

header {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 9999;
    background: #ffffff;
    padding: 0 15px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, .1);
	display: flex;
    justify-content: space-between;
    align-items: center;
}
.logo.img-responsive.logo {
    width: 200px;
    display: inline-block;
}
nav.header .navbar-collapse{
	position: fixed;
	width: 240px !important;
	top: 0;
	height: 100vh !important;
	background: #002a46 !important;
	padding: 100px 15px 15px;
	overflow-y: auto;
	
}
.user-nav {
    padding: 15px 0;
    margin: 0;
}
body .dropdown-menu:not(.colorpicker){
	left: auto; right: 0
}
.navbar+div#wrapper {
    margin-left: 240px;
	padding-top: 83px
}
div#wrapper div#content {
    padding: 15px;
}
body:not(.customers_login) .navbar-default .navbar-nav > li:not(.customers-nav-item-login) > a {
    color: #fff;
    border-radius: 3px;
    background: rgba(220, 220, 220, 0.1);
    margin-right: 0;
    margin-top: 0;
    margin-bottom: 5px;
    display: block;
    line-height: 1em;
    padding: 13px 15px;
}
footer{
	margin-left: 240px;
	width: auto
}

@media (max-width: 767px){
	.logo.img-responsive.logo {
    	width: 150px;
	}
	nav.header .navbar-collapse{
		margin-left: -240px;
		z-index: 1;
		transition: 0.3s ease-in-out;
	}
	nav.header .navbar-collapse.collapse.in{
		margin-left: 0
	}
	.navbar-header {
		display: flex;
		align-items: center;
	}
	.navbar-toggle .icon-bar{
		background: #000
	}
	.navbar+div#wrapper{
		margin: 0
	}
	body:not(.customers_login) .navbar-default .navbar-nav > li:not(.customers-nav-item-login) > a{
		padding: 15px 10px !important
	}
}
@media (min-width: 768px) {
	.navbar-nav, .navbar-nav>li{float: unset}
}


</style>
</head>
<body class="customers <?php echo strtolower($this->agent->browser()); ?><?php if(is_mobile()){echo ' mobile';}?><?php if(isset($bodyclass)){echo ' ' . $bodyclass; } ?>" <?php if($isRTL == 'true'){ echo 'dir="rtl"';} ?>>
	<?php hooks()->do_action('customers_after_body_start'); ?>
