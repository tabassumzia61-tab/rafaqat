<?php
$theme = $this->customlib->getCurrentTheme();

if ($this->customlib->getRTL() != "") {
    if ($theme == "white") {
        ?>
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/rtl/bootstrap-rtl/css/bootstrap-rtl.min.css"/>
        <!-- Theme RTL style -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/rtl/dist/css/white-rtl.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/rtl/dist/css/AdminLTE-rtl.min.css" />

        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/rtl/dist/css/skins/_all-skins-rtl.min.css" />

        <?php
} else {
        ?>
        <!-- Bootstrap 3.3.5 RTL -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/rtl/bootstrap-rtl/css/bootstrap-rtl.min.css"/>
        <!-- Theme RTL style -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/rtl/dist/css/AdminLTE-rtl.min.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/rtl/dist/css/ss-rtlmain.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/rtl/dist/css/skins/_all-skins-rtl.min.css" />
        <?php
}
}

if ($theme == "white") {
    ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/themes/white/skins/_all-skins.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/themes/white/ss-main.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/main.css">

    <?php
} elseif ($theme == "default") {
    ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/style-main.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/themes/default/skins/_all-skins.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/themes/default/ss-main.css">

    <?php
} elseif ($theme == "red") {
    ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/style-main.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/themes/red/skins/skin-red.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/themes/red/ss-main-red.css">
    <?php
} elseif ($theme == "blue") {
    ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/style-main.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/themes/blue/skins/skin-darkblue.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/themes/blue/ss-main-darkblue.css">
    <?php
} elseif ($theme == "gray") {
    ?>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/style-main.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/themes/gray/skins/skin-light.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/themes/gray/ss-main-light.css">
    <?php
}