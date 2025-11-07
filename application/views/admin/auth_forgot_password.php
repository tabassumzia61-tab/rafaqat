<!DOCTYPE html>

<html
  lang="en"
  class="light-style customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="<?php echo base_url(); ?>assets/new_theme/assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>

    <title>Forgot Password : Manuta</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo base_url(); ?>assets/new_theme/assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/new_theme/assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/new_theme/assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/new_theme/assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/new_theme/assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/new_theme/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/new_theme/assets/vendor/css/pages/page-auth.css" />
    <!-- Helpers -->
    <script src="<?php echo base_url(); ?>assets/new_theme/assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="<?php echo base_url(); ?>assets/new_theme/assets/js/config.js"></script>
  </head>

  <body>
    <!-- Content -->

    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-4">
          <!-- Forgot Password -->
          <div class="card">
            <div class="card-body">
              <!-- Logo -->
              <div class="app-brand justify-content-center">
                <a href="<?php echo site_url('user/login.html'); ?>" class="app-brand-link gap-2">
                  <span class="app-brand-logo demo">
                     <img src="<?php echo base_url('uploads/system_content/logo/header-logo.jpeg'); ?>" alt="Header Logo" width="100">
                  </span>
                  <span class="app-brand-text demo text-body fw-bolder">Manuta</span>
                </a>
              </div>
              <!-- /Logo -->
              <h4 class="mb-2"><?php echo $this->lang->line('forgot_password'); ?> ? 🔒</h4>
              <p class="mb-4">Enter your email and we'll send you instructions to reset your password</p>

              <?php
              if (isset($error_message)) {
                  echo "<div class='alert alert-danger'>" . $error_message . "</div>";
              }
              ?>

              <?php //echo site_url('superadmin/forgotpassword') ?>
              <form action="<?php echo site_url('user/forgot-password.html') ?>" class="mb-3" method="POST">
              <?php echo $this->customlib->getCSRF(); ?>  
              <div class="mb-3">
                  <label for="email" class="form-label"><?php echo $this->lang->line('email'); ?></label>
                  <input
                    type="text"
                    class="form-username form-control"
                    id="form-username"
                    name="email"
                    placeholder="<?php echo $this->lang->line('email'); ?>"
                    autofocus />
                  <span class="text-danger"><?php echo form_error('email'); ?></span>
                </div>
                <button class="btn btn-primary d-grid w-100"> <?php echo $this->lang->line('send_reset_link'); ?> Send Reset Link</button>
              </form>
              <div class="text-center">
                <a href="<?php echo site_url('user/login.html') ?>" class="d-flex align-items-center justify-content-center">
                  <i class="bx bx-chevron-left scaleX-n1-rtl bx-sm"></i>
                  <?php echo $this->lang->line('login'); ?>
                </a>
              </div>
            </div>
          </div>
          <!-- /Forgot Password -->
        </div>
      </div>
    </div>

    <!-- / Content -->


    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="<?php echo base_url(); ?>assets/new_theme/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="<?php echo base_url(); ?>assets/new_theme/assets/vendor/libs/popper/popper.js"></script>
    <script src="<?php echo base_url(); ?>assets/new_theme/assets/vendor/js/bootstrap.js"></script>
    <script src="<?php echo base_url(); ?>assets/new_theme/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="<?php echo base_url(); ?>assets/new_theme/assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="<?php echo base_url(); ?>assets/new_theme/assets/js/main.js"></script>

    <!-- Page JS -->

    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>
