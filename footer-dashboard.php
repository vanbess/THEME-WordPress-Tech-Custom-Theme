<?php

/**
 * Footer template for dashboard
 */

wp_footer(); ?>

<div id="footer_cont" class="container-fluid bg-dark">

  <footer class="pt-3 mt-5 pb-2">

    <ul class="nav justify-content-center border-bottom border-dark-subtle border-opacity-25 pb-3 mb-3">

      <li class="nav-item">
        <a href="<?php echo esc_url(network_home_url('cookie-policy-za/')) ?>" class="nav-link px-2 text-secondary" target="_blank">Cookie Policy</a>
      </li>

      <li class="nav-item">
        <a href="<?php echo esc_url(network_home_url('privacy-policy-za/')) ?>" class="nav-link px-2 text-secondary" target="_blank">Privacy Policy</a>
      </li>

      <li class="nav-item">
        <a href="<?php echo esc_url(network_home_url('terms-and-conditions/')) ?>" class="nav-link px-2 text-secondary" target="_blank">Terms & Conditions</a>
      </li>

      <li class="nav-item">
        <a href="<?php echo esc_url(network_home_url('disclaimer/')) ?>" class="nav-link px-2 text-secondary" target="_blank">Disclaimer</a>
      </li>

    </ul>

    <p class="text-center text-secondary">&copy; <?php echo date('Y'); ?> Excellerate Tech (Pty) Ltd. All rights reserved.</p>

  </footer>
</div>

</body>

</html>