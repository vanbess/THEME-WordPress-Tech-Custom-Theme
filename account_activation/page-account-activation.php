<?php

/**
 * Template Name: Account Activation
 */

get_header('registration');

global $post;
?>

<!-- container main -->
<div id="account_confirmation" class="container my-5 py-5" style="min-height: 67vh;">

    <!-- title block -->
    <div class="row mb-4">
        <div class="col-6 offset-3">

            <h1 class="text-center pb-4" style="font-size: 2.5rem;">
                <?php echo $post->post_title; ?>
            </h1>

            <?php
            // if user id or acc activation key or child site id is absent for some reason
            if (!isset($_GET['oid']) || !isset($_GET['akey']) || !isset($_GET['sid'])) : ?>

                <p class="text-center text-danger bg-danger-subtle fw-semibold p-3 rounded-2">
                    There appears to be an issue with activating your account. Please <a href="mailto:info@excelleratetech.com?subject=NOTICE:%20Shop%20Registration%20Failure" title="click to send us an email">get in touch with us</a>, providing your registration details if you think this is a mistake so that we can investigate.
                </p>

            <?php else : ?>

                <p id="page_load_msg" class="text-center text-success bg-success-subtle fw-semibold p-3 rounded-2 mb-4">
                    Welcome! Go ahead and set your password below to activate your account. <br><br>We've gone ahead and generated a password for you, but if you'd like to use your own, please set and confirm your password below:
                </p>

                <p id="pass_conf_err" class="text-danger bg-danger-subtle p-3 text-center rounded-2 d-none">
                    Your password could not be set due to a technical error. <br> Please reload the page and try again. <br> If the problem persists, please <a href="mailto:info@excelleratetech.com?subject=Excellerate%20Tech%20Account%20Activation%20Password%20Definition%20Failing"> send us an email.</a>
                </p>

                <p id="pass_conf_success" class="text-center text-success bg-success-subtle fw-semibold p-3 rounded-2 mb-4 d-none">
                    Your password has been successfully set. Great! Please check your email for confirmation and login instructions.
                </p>

                <?php
                // generate strong password
                $strong_pass = wp_generate_password(15, true);
                ?>

                <div id="acc_pass_inputs">

                    <!-- pass -->
                    <label for="acc_pass" class="form-label">Your password*</label>
                    <input type="password" id="acc_pass" name="acc_pass" class="form-control mb-3" placeholder="enter your password here" value="<?php echo $strong_pass; ?>" required>

                    <!-- confirm pass -->
                    <label for="acc_pass_conf" class="form-label">Confirm your password*</label>
                    <input type="password" id="acc_pass_conf" name="acc_pass_conf" class="form-control mb-3" placeholder="confirm your password here" value="<?php echo $strong_pass; ?>" required>

                    <!-- show passes -->
                    <input type="checkbox" class="form-check-inline me-1" id="show_passwords">
                    <label for="show_passwords" class="form-check-label">Show Passwords</label>

                    <!-- passes mismatch -->
                    <p id="passes_mismatch" class="d-none text-danger text-center rounded-1 mt-3 p-1 bg-danger-subtle">Your passwords do not match</p>

                    <!-- passes match -->
                    <p id="passes_match" class="d-none text-success text-center rounded-1 mt-3 p-1 bg-success-subtle">Your passwords match</p>

                    <!-- submit -->
                    <button id="save-acc-pass" class="btn btn-primary w-100 mt-4">Save Your Password</button>

                </div>

            <?php endif; ?>

        </div>
    </div>

</div>

<script id="acc_activation">
    jQuery(document).ready(function($) {

        // show passwords
        $('#show_passwords').click(function() {
            if ($(this).is(':checked')) {
                $('#acc_pass').attr('type', 'text');
                $('#acc_pass_conf').attr('type', 'text');
            } else {
                $('#acc_pass').attr('type', 'password');
                $('#acc_pass_conf').attr('type', 'password');
            }
        });

        // check passwords match
        $('#acc_pass, #acc_pass_conf').on('keyup', function() {

            var password = $('#acc_pass').val();
            var confirmPassword = $('#acc_pass_conf').val();

            if (password == confirmPassword) {
                $('#passes_match').removeClass('d-none');
                $('#passes_mismatch').addClass('d-none');
                $('#save-acc-pass').removeClass('disabled');

            } else {
                $('#passes_match').addClass('d-none');
                $('#passes_mismatch').removeClass('d-none');
                $('#save-acc-pass').addClass('disabled');
            }
        });

        // send request
        $('#save-acc-pass').click(function(e) {
            e.preventDefault();

            $(this).text('Processing...');

            // make sure we bail early if this has class disabled
            if ($(this).hasClass('disabled')) {
                return;
            }

            // ajax
            data = {
                '_ajax_nonce': '<?php echo wp_create_nonce('extech activate account update password') ?>',
                'action': 'extech_acc_activation',
                'pass': $('#acc_pass_conf').val(),
                'oid': '<?php echo $_GET['oid'] ?>',
                'akey': '<?php echo $_GET['akey'] ?>',
                'sid': '<?php echo $_GET['sid'] ?>',
            }

            $.post('<?php echo admin_url('admin-ajax.php'); ?>', data, function(response) {
                if (response.success) {
                    $('#page_load_msg').addClass('d-none');
                    $('#pass_conf_success').removeClass('d-none');
                    $('#acc_pass_inputs').remove();
                } else {
                    $('#page_load_msg').addClass('d-none');
                    $('#pass_conf_err').removeClass('d-none');
                    $('#acc_pass_inputs').remove();
                }
            })
        });


    });
</script>

<?php get_footer(); ?>