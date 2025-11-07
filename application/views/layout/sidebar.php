<?php 
  $file   = "";
  $result = $this->customlib->getUserData();

  $image  = $result["image"];
  $role   = $result["user_type"];
  $name   = $result["name"].' '.$result["surname"];
  $id     = $result["id"];

  if (!empty($image))
  {
    $file = "uploads/staff_images/" . $image;
  }
  else
  {
      if($result['gender']=='Female')
      {
        $file= "uploads/staff_images/default_female.jpg";
      }
      else
      {
        $file ="uploads/staff_images/default_male.jpg";
      }
  }
?>
<!-- Menu -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <div class="app-brand demo">
    <a href="<?php echo base_url(); ?>admin/dashboard.html" class="app-brand-link">
      <span class="app-brand-logo demo">
        <img src="<?php echo base_url('uploads/system_content/logo/header-logo.jpeg'); ?>" alt="Header Logo" width="100">
      </span>
      <span class="app-brand-text demo menu-text fw-bolder ms-2">Manuta <?php // echo $name; ?></span>
    </a>
    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
      <i class="bx bx-chevron-left bx-sm align-middle"></i>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">
    <!-- Dashboard -->
    <li class="menu-item active">
      <a href="<?php echo base_url(); ?>admin/dashboard.html" class="menu-link">
        <i class="menu-icon tf-icons bx bx-home-circle"></i>
        <div data-i18n="Analytics"><?php echo $this->lang->line('dashboard'); ?> Dashboard</div>
      </a>
    </li>

    <!-- Layouts -->

    <?php
      $side_list = side_menu_list(1);
      if (!empty($side_list))
      {
        foreach ($side_list as $side_list_key => $side_list_value)
        {
          $module_permission = access_permission_sidebar_remove_pipe($side_list_value->access_permissions);
          $module_access     = false;
          if (!empty($module_permission))
          {
            foreach ($module_permission as $m_permission_key => $m_permission_value)
            {
              $cat_permission = access_permission_remove_comma($m_permission_value);
              //print_r($cat_permission);
              if ($this->rbac->hasPrivilege($cat_permission[0], $cat_permission[1]))
              {
                $module_access = true;
                break;
              }
            }
          }
          //echo $module_access;

          if ($module_access)
          {
            if ($this->module_lib->hasModule($side_list_value->short_code) && $this->module_lib->hasActive($side_list_value->short_code))
            {
              ?>
              <li class="menu-item <?php echo activate_main_menu($side_list_value->activate_menu); ?>">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                  <i class="menu-icon <?php echo $side_list_value->icon; ?>"></i>
                  <div data-i18n="Layouts"><?php echo $this->lang->line($side_list_value->lang_key); ?></div>
                </a>
                
                <?php
                if(!empty($side_list_value->submenus))
                {
                  ?>
                  <ul class="menu-sub">
                    <?php
                      foreach ($side_list_value->submenus as $submenu_key => $submenu_value)
                      {
                        $sidebar_permission = access_permission_sidebar_remove_pipe($submenu_value->access_permissions);
                        $sidebar_access     = false;

                        if (!empty($sidebar_permission))
                        {
                          foreach ($sidebar_permission as $sidebar_permission_key => $sidebar_permission_value)
                          {
                            $sidebar_cat_permission = access_permission_remove_comma($sidebar_permission_value);
                            if ($submenu_value->addon_permission != "")
                            {
                              if ($this->rbac->hasPrivilege($sidebar_cat_permission[0], $sidebar_cat_permission[1])
                                    && $this->auth->addonchk($submenu_value->addon_permission, false))
                              {
                                $sidebar_access = true;
                                break;
                              }
                            }
                            else
                            {
                              if ($this->rbac->hasPrivilege($sidebar_cat_permission[0], $sidebar_cat_permission[1]))
                              {
                                $sidebar_access = true;
                                break;
                              }
                            }
                          }
                        }

                        if ($sidebar_access)
                        {
                          if (!empty($submenu_value->permission_group_id))
                          {
                            if (!$this->module_lib->hasActive($submenu_value->short_code))
                            {
                              continue;
                            }
                          }
                          
                          ?>
                          <li class="menu-item <?php echo activate_submenu($submenu_value->activate_controller, explode(',', $submenu_value->activate_methods)); ?>">
                            <a href="<?php echo site_url($submenu_value->url); ?>" class="menu-link">
                              <div data-i18n="Without menu"><?php echo $this->lang->line($submenu_value->lang_key); ?></div>
                            </a>
                          </li>
                          <?php
                        }
                      }
                    ?>
                  </ul>
                  <?php
                }
                ?>
              </li>
              <?php
            }     
          }  
        }
      }
    ?>
  </ul>
</aside>
<!-- / Menu -->