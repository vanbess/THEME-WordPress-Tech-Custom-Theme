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
      <a class="navbar-brand me-5" href="<?php echo get_home_url(); ?>">
        <h3 class="text-secondary">
          Excellerate Tech
        </h3>
      </a>

      <!-- nav toggler -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navLinks" aria-controls="navLinks" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navLinks">

        <ul class="navbar-nav me-auto mb-2 mb-lg-0">

          <!-- Home -->
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="<?php echo get_home_url(); ?>">Home</a>
          </li>

          <!-- What We Do -->
          <li class="nav-item">
            <a class="nav-link" href="#what_we_do">What We Do</a>
          </li>

          <!-- How It Works -->
          <li class="nav-item">
            <a class="nav-link" href="#how_it_works">How It Works</a>
          </li>

          <!-- About -->
          <li class="nav-item">
            <a class="nav-link" href="#about_us">About</a>
          </li>

          <!-- Contact -->
          <li class="nav-item">
            <a class="nav-link" href="#contact">Contact</a>
          </li>

        </ul>

        <div class="d-flex">
          <a href="<?php echo get_site_url() . '/dashboard/log-in/'; ?>" class="text-decoration-none text-light pt-2 pe-3">Already registered? Log in.</a>
          <a href="<?php echo get_site_url() . '/register'; ?>" id="navbar_register" class="btn btn-warning text-uppercase" title="Register your shop today">Register</a>
        </div>

      </div>
    </div>
  </nav>

  <script>
    $ = jQuery;

    $('.nav-link').click(function() {
      $('.nav-link').removeClass('active');
      $(this).addClass('active');
    });
  </script>