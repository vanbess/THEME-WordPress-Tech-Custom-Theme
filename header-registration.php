<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>
    <?php echo get_bloginfo('name'); ?> |
    <?php echo get_bloginfo('description'); ?>
  </title>
  <?php wp_head(); ?>
</head>

<body>

  <!-- contact bar top -->
  <div id="contact-bar-top" class="container-fluid bg-dark-subtle">

    <?php if (wp_is_mobile()) : ?>

      <div class="row py-1">
        <!-- phone -->
        <div class="col-sm-12 text-center py-1">
          <a href="tel:+27736336930" class="link-dark text-decoration-none" style="font-weight:500">
            <i class="bi bi-telephone-fill"></i>
            +27 73 633 6930
          </a>
        </div>

        <!-- email -->
        <div class="col-sm-12 text-center py-1">
          <a href="mailto:info@excelleratetech.com" class="link-dark text-decoration-none" style="font-weight:500">
            <i class="bi bi-envelope-at-fill"></i>
            info@excelleratetech.com
          </a>
        </div>
      </div>

    <?php else : ?>

      <div class="row py-1">
        <!-- phone -->
        <div class="col">
          <a href="tel:+27736336930" class="d-inline-block link-dark text-decoration-none" style="font-weight:500">
            <i class="bi bi-telephone-fill"></i>
            +27 73 633 6930
          </a>
        </div>

        <!-- email -->
        <div class="col text-end">
          <a href="mailto:info@excelleratetech.com" class="d-inline-block link-dark text-decoration-none" style="font-weight:500">
            <i class="bi bi-envelope-at-fill"></i>
            info@excelleratetech.com
          </a>
        </div>
      </div>

    <?php endif; ?>

  </div>

  <!-- navigation top -->
  <nav class="navbar navbar-expand-lg bg-dark shadow-lg sticky-top mb-0" data-bs-theme="dark">

    <div class="container-fluid">

      <?php if (wp_is_mobile()) : ?>

        <!-- brand and home link -->
        <a class="navbar-brand ms-auto me-auto mb-2" href="<?php echo get_home_url(); ?>">
          <img id="logoTop" class="w-100" src="<?php echo EXTECH_URI . '/assets/img/logo-sbs.png'; ?>" alt="Excellerate Convenience">
        </a>

        <div class="me-auto ms-auto pb-3">
          <a href="<?php echo get_site_url() . '/dashboard/log-in/' ?>" id="navbar_login" class="d-block text-light pt-2 pe-3 " title="Log into your dashboard">Already registered? Log in.</a>
        </div>

      <?php else : ?>

        <!-- brand and home link -->
        <a class="navbar-brand me-5" href="<?php echo get_home_url(); ?>">
          <img id="logoTop" src="<?php echo EXTECH_URI . '/assets/img/logo-sbs.png'; ?>" alt="Excellerate Convenience">
        </a>

        <div class="float-end">
          <a href="<?php echo get_site_url() . '/dashboard/log-in/' ?>" id="navbar_login" class="text-light pt-2 pe-3" title="Log into your dashboard">Already registered? Log in.</a>
        </div>
        <!-- brand and home link -->
        <a class="navbar-brand me-5" href="<?php echo get_home_url(); ?>">
          <img id="logoTop" src="<?php echo EXTECH_URI . '/assets/img/logo-sbs.png'; ?>" alt="Excellerate Convenience">
        </a>

        <div class="float-end">
          <a href="<?php echo get_site_url() . '/dashboard/log-in/' ?>" id="navbar_login" class="text-light pt-2 pe-3" title="Log into your dashboard">Already registered? Log in.</a>
        </div>

      <?php endif; ?>

    </div>
  </nav>