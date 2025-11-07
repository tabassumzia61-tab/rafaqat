<!DOCTYPE html>
<html 

lang="en"
  class="layout-navbar-fixed light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="<?php echo base_url(); ?>assets/new_theme/assets/"
  data-template="vertical-menu-template-free"

<?php echo $this->customlib->getRTL(); ?>>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $this->customlib->getAppName(); ?></title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <meta http-equiv="Cache-control" content="no-cache">
        <meta name="theme-color" content="#424242" />
        <link href="<?php echo $this->customlib->getBaseUrl(); ?>uploads/system_content/admin_small_logo/<?php echo $this->setting_model->getAdminsmalllogo();?>" rel="shortcut icon" type="image/x-icon">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet"
        />

        <!-- Icons. Uncomment required icon fonts -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/new_theme/assets/vendor/fonts/boxicons.css" />
        <link href='https://cdn.boxicons.com/3.0.3/fonts/basic/boxicons.min.css' rel='stylesheet'>
        <link href='https://cdn.boxicons.com/3.0.3/fonts/brands/boxicons-brands.min.css' rel='stylesheet'>
        <!-- Core CSS -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/new_theme/assets/vendor/css/core.css" class="template-customizer-core-css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/new_theme/assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/new_theme/assets/css/demo.css" />

        <!-- Vendors CSS -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/new_theme/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/new_theme/assets/vendor/libs/apex-charts/apex-charts.css" />

        <script src="<?php echo base_url(); ?>assets/new_theme/assets/vendor/js/helpers.js"></script>
        <script src="<?php echo base_url(); ?>assets/new_theme/assets/js/config.js"></script>

        <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
      <style>
          table.dataTable thead th,
          table.dataTable tbody td {
              white-space: nowrap;
              text-overflow: ellipsis;
              max-width: 200px;   /* control width */
          }

          /* Reduce row height */
          table.dataTable tr {
              height: 15px;
          }

          /* Responsive fix */
          div.dataTables_wrapper {
              width: 100%;
              margin: 0 auto;
          }
      </style> -->
  </head>
    <body>
        <!-- Layout wrapper -->
        <div class="layout-wrapper layout-content-navbar">
            <div class="layout-container">

            <?php $this->load->view('layout/sidebar');?>

            <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->
          <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
            id="layout-navbar">
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
              <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
              </a>
            </div>

            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
              <!-- Search -->
              <div class="navbar-nav align-items-center">
                <div class="nav-item d-flex align-items-center">
                  <i class="bx bx-search fs-4 lh-0"></i>
                  <input
                    type="text"
                    class="form-control border-0 shadow-none"
                    placeholder="Search..."
                    aria-label="Search..."
                  />
                </div>
              </div>
              <!-- /Search -->

              <ul class="navbar-nav flex-row align-items-center ms-auto">
                <!-- Place this tag where you want the button to render. -->
                
                <!-- User -->
                <?php  //if (($this->module_lib->hasModule('multi_branch') && $this->module_lib->hasActive('multi_branch')) || $this->db->multi_branch) { ?>
                <!-- <li class="cal15" data-placement="bottom" data-toggle="tooltip" title="<?php echo $this->lang->line('switch_branch'); ?>"><a href="#" data-toggle="modal" data-target="#multiBranchSwitchModal"><i class="fa fa-exchange" aria-hidden="true"></i></a></li> -->
                <?php //} ?>
                <?php 
                    $file   = "";
                    $result = $this->customlib->getUserData();
                    $image = $result["image"];
                    $role  = $result["user_type"];
                    $id    = $result["id"];
                    if (!empty($image)) {
                        $file = "uploads/staff_images/" . $image . img_time();
                    } else {
                        if ($result['gender'] == 'Female') {
                            $file = "uploads/staff_images/default_female.jpg" . img_time();
                        } else {
                            $file = "uploads/staff_images/default_male.jpg" . img_time();
                        }

                    }
                ?>
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                  <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                      <img src="<?php echo $this->customlib->getBaseUrl(). $file; ?>" alt class="w-px-40 h-auto rounded-circle" />
                    </div>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                      <a class="dropdown-item" href="<?php echo base_url() . "admin/staff/profile/" . $id ?>">
                        <div class="d-flex">
                          <div class="flex-shrink-0 me-3">
                            <div class="avatar avatar-online">
                              <img src="<?php echo $this->customlib->getBaseUrl(). $file; ?>" alt class="rounded-circle" />
                            </div>
                          </div>
                          <div class="flex-grow-1">
                            <span class="fw-semibold d-block"><?php echo $this->customlib->getAdminSessionUserName(); ?></span>
                            <small class="text-muted"><?php echo $role; ?></small>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-divider"></div>
                    </li>
                    <li>
                      <?php // echo base_url() . "admin/staff/profile/" . $id ?>
                      <a class="dropdown-item" href="<?php echo base_url() . "admin/staff/profile/" . $id ?>">
                        <i class="bx bx-user me-2"></i>
                        <span class="align-middle">My Profile</span>
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-divider"></div>
                    </li>
                    <li>
                      <a class="dropdown-item" href="<?php echo site_url('user/logout.html'); ?>">
                        <i class="bx bx-power-off me-2"></i>
                        <span class="align-middle">Log Out</span>
                      </a>
                    </li>
                  </ul>
                </li>
                <!--/ User -->
              </ul>
            </div>
          </nav>

