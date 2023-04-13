<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo get_the_title(); ?></title>
  <?php wp_head(); ?>

  <!-- Custom CSS -->
  <style>
    body {
      background-color: #EFEFEF;
      background-image: linear-gradient(45deg, #DFDFDF, #EFEFEF);
    }
  </style>

</head>

<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Dashboard</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">

          <!-- home -->
          <li class="nav-item">
            <a class="nav-link active" href="<?php echo get_site_url() ?>/dashboard/">Home</a>
          </li>

          <!-- QR code -->
          <li class="nav-item">
            <a class="nav-link" href="<?php echo get_site_url() ?>/dashboard/qr-code/">QR Code</a>
          </li>

          <!-- account -->
          <li class="nav-item">
            <a class="nav-link" href="<?php echo get_site_url() ?>/dashboard/account/">Account</a>
          </li>

          <!-- users -->
          <li class="nav-item">
            <a class="nav-link" href="<?php echo get_site_url() ?>/dashboard/users/">Users</a>
          </li>

          <!-- settings -->
          <li class="nav-item">
            <a class="nav-link" href="<?php echo get_site_url() ?>/dashboard/products/">Products</a>
          </li>

          <!-- log out -->
          <?php if (is_user_logged_in()) : 
            
            // setup logout nonce, redirect url and logout url
            $rd_url = get_site_url().'dashboard/log-in/';
            $lo_url = wp_logout_url($rd_url);
            
            ?>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo esc_url($lo_url); ?>">Logout</a>
            </li>
          <?php endif; ?>

        </ul>
      </div>
    </div>
  </nav>