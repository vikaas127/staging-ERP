<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<style>
    .header{display: none}
    .company-logo {
        width: 250px;
        margin: 0 auto;
        padding: 25px 10px;
    }
    .login-form h1 {
        margin-top: 0;
        font-family: 'Poppins';
        margin-bottom: 35px;
        font-size: 20px;
    }
    .login-form .panel-body {
        background: #012947;
        border-radius: 10px !important
    }
    .login-form .panel-body h1{
        color: #fff;
    }
    .login-form .panel-body label{
        color: #fff;
        font-weight: 300;
        font-family: 'Poppins';
    }
    .login-form .panel-body input, .login-form .panel-body select{
        outline: none !important;
        box-shadow: none !important;
        border: 1px solid #fff !important
    }
    .login-form .panel-body input:focus, .login-form .panel-body select:focus {
        border-color: #fff !important
    }
    .login-form .panel-body a {
        color: #39c529;
        font-family: 'Poppins';
    }
    .login-form .panel-body .checkbox label {
        color: #fff;
        font-weight: 300;
        font-family: 'Poppins';
    }

    .right-box{
        background: #012947;
        padding: 0;
        display: flex;
    }

    .carousel, .carousel-inner{
        display: flex
    }

    footer{
        margin-top: 0
    }

    .align-items-center{
        align-items: center
    }




    @media (min-width: 992px) {
        .row{
            display: flex
        }
        .right-box {
            min-height: calc(100vh - 50px);
        }
        .left-box{padding-right: 30px}
    }
    @media (min-width: 1200px) {
        .left-box{padding-right: 50px}
    }
</style>

<div class="col-md-5 col-lg-4 mtop40 left-box">
    <div class="company-logo ">
        <?php get_dark_company_logo(); ?>
    </div>
   <div class="login-form">
        <div class="panel_s">
            <div class="panel-body">
                <h1 class="tw-font-semibold text-center"><?php echo _l('customer_forgot_password_heading'); ?></h1>
                
                <?php echo form_open($this->uri->uri_string(), ['id' => 'forgot-password-form']); ?>
                <?php echo validation_errors('<div class="alert alert-danger text-center">', '</div>'); ?>
                <?php if ($this->session->flashdata('message-danger')) { ?>
                <div class="alert alert-danger">
                    <?php echo $this->session->flashdata('message-danger'); ?>
                </div>
                <?php } ?>
                <?php echo render_input('email', 'customer_forgot_password_email', '', 'email'); ?>
                <div class="form-group">
                    <button type="submit"
                        class="btn btn-success btn-block"><?php echo _l('customer_forgot_password_submit'); ?></button>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
<div class="col-md-7 col-lg-8 right-box">
    <div class="carousel fade-carousel slide" data-ride="carousel" data-interval="4000" id="bs-carousel">

        <!-- Wrapper for slides -->
        <div class="carousel-inner">
            <div class="item slides active">
                <img class="d-block w-100" style=" width: 100%; height: 100%; object-fit: cover;" src="https://img.freepik.com/free-photo/interior-view-steel-factory_1359-117.jpg?t=st=1739638735~exp=1739642335~hmac=73227c984ad6b57fe1f0a670a5bd232c66d160f81b5e3acab9f49fc4d75bc491&w=1800" alt="First slide">
            </div>
            <div class="item slides">
                <img class="d-block w-100" style=" width: 100%; height: 100%; object-fit: cover;" src="https://img.freepik.com/free-photo/interior-view-steel-factory_1359-117.jpg?t=st=1739638735~exp=1739642335~hmac=73227c984ad6b57fe1f0a670a5bd232c66d160f81b5e3acab9f49fc4d75bc491&w=1800" alt="Second slide">
            </div>
            <div class="item slides">
                <img class="d-block w-100" style=" width: 100%; height: 100%; object-fit: cover;" src="https://img.freepik.com/free-photo/interior-view-steel-factory_1359-117.jpg?t=st=1739638735~exp=1739642335~hmac=73227c984ad6b57fe1f0a670a5bd232c66d160f81b5e3acab9f49fc4d75bc491&w=1800" alt="Second slide">
            </div>
        </div> 
    </div>
</div>