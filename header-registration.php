<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo get_the_title(); ?></title>
  <?php wp_head(); ?>
</head>

<body>

  <!-- contact bar top -->
  <div id="contact-bar-top" class="container-fluid bg-dark-subtle">
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
  </div>

  <!-- navigation top -->
  <nav class="navbar navbar-expand-lg bg-dark shadow-lg sticky-top mb-0" data-bs-theme="dark">

    <div class="container-fluid">

      <!-- brand and home link -->
      <a class="navbar-brand me-5" href="<?php echo get_site_url(); ?>">
        <h3 class="text-secondary">
          Excellerate Tech
        </h3>
      </a>
      <div class="float-end">
        <a href="<?php echo get_site_url(). 'dashboard/log-in/' ?>" id="navbar_login" class="btn btn-warning text-uppercase fw-bold" title="Log into your dashboard">Already registered? Log in.</a>
      </div>
    </div>
  </nav>