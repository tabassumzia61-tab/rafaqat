<!DOCTYPE html>

<html
  lang="en"
  class="light-style customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="<?php echo base_url(); ?>assets/new_theme/assets/" 
  data-template="vertical-menu-template-free">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>

    <title>Login : Manuta</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo base_url(); ?>assets/new_theme/assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet"/>

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

    <script src="<?php echo base_url(); ?>assets/new_theme/assets/js/config.js"></script>
  </head>

  <body>
    <!-- Content -->

    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Register -->
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
              <h4 class="mb-2">Welcome to Manuta! 👋</h4>
              <p class="mb-4">Please sign-in to your account and start the adventure</p>
              <?php
                if (isset($error_message)) {
                    echo "<div class='alert alert-danger'>" . $error_message . "</div>";
                }
                ?>
                <?php
                if ($this->session->flashdata('message')) {
                    echo "<div class='alert alert-success'>" . $this->session->flashdata('message') . "</div>";
                    $this->session->unset_userdata('message'); 
                };
                ?>
                <?php
                if ($this->session->flashdata('disable_message')) {
                    echo "<div class='alert alert-danger'>" . $this->session->flashdata('disable_message') . "</div>";
                    $this->session->unset_userdata('disable_message'); 
                };
              ?>
              
              <?php //echo site_url('superadmin/login') ?>
              <form method="post" action="<?php echo site_url('user/login.html'); ?>" class="mb-3" >
                <?php echo $this->customlib->getCSRF(); ?>
                <div class="mb-3">
                  <label for="email" class="form-label"><?php echo $this->lang->line('username'); ?></label>
                  <input
                    type="text"
                    name="username"
                    placeholder="<?php echo $this->lang->line('username'); ?>"
                    value="<?php echo set_value('username') ?>"
                    class="form-username form-control" 
                    id="form-username"
                  />
                </div>
                <div class="mb-3 form-password-toggle">
                  <div class="d-flex justify-content-between">
                    <label class="form-label" for="password"><?php echo $this->lang->line('password'); ?></label>
                    <a href="<?php echo site_url('user/forgot-password.html') ?>">
                      <small><?php echo $this->lang->line('forgot_your_password'); ?> ?</small>
                    </a>
                  </div>
                  <div class="input-group input-group-merge">
                    <input
                      type="password"
                      value="<?php echo set_value('password') ?>"
                      name="password"
                      placeholder="<?php echo $this->lang->line('password'); ?>"
                      class="form-password form-control"
                      id="form-password"
                      aria-describedby="password"
                    />
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                  </div>
                </div>
                
                <div class="mb-3">
                  <button class="btn btn-primary d-grid w-100" type="submit"><?php echo $this->lang->line('login'); ?> &nbsp;</button>
                </div>
              </form>

              <!-- <p class="text-center">
                <span>New on our platform?</span>
                <a href="auth-register-basic.html">
                  <span>Create an account</span>
                </a>
              </p> -->
            </div>
          </div>
          <!-- /Register -->
        </div>
      </div>
    </div>

    <script src="<?php echo base_url(); ?>assets/new_theme/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="<?php echo base_url(); ?>assets/new_theme/assets/vendor/libs/popper/popper.js"></script>
    <script src="<?php echo base_url(); ?>assets/new_theme/assets/vendor/js/bootstrap.js"></script>
    <script src="<?php echo base_url(); ?>assets/new_theme/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
 
    <script src="<?php echo base_url(); ?>assets/new_theme/assets/vendor/js/menu.js"></script>
   
    <script src="<?php echo base_url(); ?>assets/new_theme/assets/js/main.js"></script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>
