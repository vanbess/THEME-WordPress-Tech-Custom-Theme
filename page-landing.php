<?php

/**
 * Template Name: Landing
 */

get_header();

global $post;

$p_id = $post->ID;

?>

<!-- container main -->
<div class="container-fluid">

    <!-- intro background -->
    <div id="intro_banner" class="row p-5 mb-5 align-items-center shadow-lg" style="background-image: url(<?php echo get_field('intro_banner'); ?>); background-size: cover; background-position-y: center;">

        <!-- intro text -->
        <div id="intro_banner_text" class="col-8 col-sm-12 col-xs-12 bg-black p-5" style="opacity: 0.85;">
            <h1 class="text-light text-opacity-75 text-sm-center" style="font-size: 3rem;">
                <?php echo get_field('intro_banner_text', $p_id); ?>
            </h1>
        </div>

        <!-- intro cta -->
        <div id="intro_banner_cta" class="col-4 col-sm-12 col-xs-12 text-center">
            <a href="<?php echo home_url('register'); ?>" id="intro_banner_register_btn" type="button" class="btn btn-lg btn-warning text-uppercase text-center shadow-lg" title="Register your shop today">
                <?php echo get_field('intro_banner_button_text', $p_id); ?>
            </a>
        </div>

    </div>

</div>

<div id="landing" class="container">

    <!-- what we do -->
    <div id="what_we_do" class="row pt-5 mt-5 pb-5 mt-5">
        <div class="col">
            <h1 class="text-center pb-5">What We Do</h1>

            <p class="text-center pb-5"><?php echo get_field('what_we_do_text', $p_id); ?></p>
        </div>
    </div>

    <!-- how it works -->
    <div id="how_it_works" class="row pt-5 mt-5 pb-5 mb-5">

        <div class="col">

            <h1 class="text-center pb-5">How It Works</h1>

            <?php $hiw_steps = get_field('how_it_works_steps', $p_id); ?>

            <!-- hiw steps -->
            <div class="row">

                <?php foreach ($hiw_steps as $step_arr) : ?>

                    <div class="col text-center">
                        <h3 class="mb-3"><?php echo $step_arr['step_title']; ?></h3>
                        <div class="landing-icon-div m-auto mt-5 mb-5">
                            <i class="<?php echo $step_arr['step_icon_class']; ?> text-primary"></i>
                        </div>
                        <p><?php echo $step_arr['step_description']; ?></p>
                    </div>

                <?php endforeach; ?>

            </div>

            <!-- cta -->
            <div id="hiw_register_cont" class="row mt-5">
                <div class="col text-center">
                    <a href="<?php echo home_url('register'); ?>" id="hiw_register_btn" class="btn btn-lg btn-warning w-25 text-uppercase shadow-sm">
                        <?php echo get_field('intro_banner_button_text', $p_id); ?>
                    </a>
                </div>
            </div>

        </div>
    </div>

    <!-- about -->
    <!-- <div id="about_us" class="row pt-5 pb-5 mt-5 mb-5">
        <div class="col">
            <h1 class="text-center pb-5">About Us</h1>

            <p class="text-center pb-5"><?php echo get_field('about_us_text', $p_id); ?></p>
        </div>
    </div> -->

    <!-- contact -->
    <div id="contact" class="row pt-5 pb-5 mt-5 mb-5">

        <!-- contact form -->
        <div id="landing-contact-form-cont" class="col-xl-6 offset-xl-3 col-lg-12 mb-5">

            <h1 class="text-center pb-5">Interested? Contact Us!</h1>

            <p class="text-center pb-5"><?php echo get_field('contact_us_text', $p_id); ?></p>

            <?php
            // process contact submission
            if (isset($_POST['contact_submit'])) :

                // insert contact post
                $contact_inserted = wp_insert_post([
                    'post_type'    => 'contact_request',
                    'post_status'  => 'publish',
                    'post_title'   => 'Contact request from ' . $_POST['first_name'] . ' ' . $_POST['last_name'],
                    'post_content' => ''
                ]);

                // on error
                if (is_wp_error($contact_inserted)) : ?>
                    <p id="formSubbed" style="font-size: 16px;" class="text-center bg-danger-subtle fw-semibold p-2 rounded-2 shadow-sm">
                        Contact form submission failed. Please reload the page and try again.
                    </p>
                <?php
                // on success
                else :

                    // update contact meta
                    update_post_meta($contact_inserted, 'first_name', sanitize_text_field($_POST['first_name']));
                    update_post_meta($contact_inserted, 'last_name', sanitize_text_field($_POST['last_name']));
                    update_post_meta($contact_inserted, 'email', sanitize_email($_POST['email']));
                    update_post_meta($contact_inserted, 'tel', sanitize_text_field($_POST['tel']));
                    update_post_meta($contact_inserted, 'message', sanitize_textarea_field($_POST['message']));

                    // send email
                    $subject = 'New contact request received from ' . sanitize_text_field($_POST['first_name']) . ' ' . sanitize_text_field($_POST['last_name']);
                    $toEmail = 'info@excelleratetech.com';
                    $respondTo = sanitize_email($_POST['email']);
                    $msg = '<h3>Good day</h3>';
                    $msg .= '<p>A new contact form submission was received on the Excellerate Convenience website.</p>';
                    $msg .= '<p><b><u>SUBMISSION DETAILS:</u></b></p>';
                    $msg .= '<p><b>First name: </b>' . sanitize_text_field($_POST['first_name']) . '</p>';
                    $msg .= '<p><b>Last name: </b>' . sanitize_text_field($_POST['last_name']) . '</p>';
                    $msg .= '<p><b>Email address: </b>' . sanitize_email($_POST['email']) . '</p>';
                    $msg .= '<p><b>Telephone: </b>' . sanitize_text_field($_POST['tel']) . '</p>';
                    $msg .= '<p><b><u>Message: </u></b></p>';
                    $msg .= sanitize_textarea_field($_POST['message']) . PHP_EOL;
                    $msg .= '<p></p>';
                    $msg .= '<p><b>You may respond to this person by simply replying to this email. Thanks!</b></p>';

                    $headers = [
                        'Content-Type: text/html; charset=UTF-8',
                        'From: Excellerate Convenience Website <website@excelleratetech.com>',
                        'Reply-To: Reply Address <' . sanitize_email($_POST['email']) . '>',
                    ];

                    wp_mail($toEmail, $subject, $msg, $headers);

                ?>

                    <p id="formSubbed" style="font-size: 16px;" class="text-center bg-success-subtle fw-semibold p-2 rounded-2 shadow-sm">
                        Contact form submission successful. We will be in touch within 48 hours. Thanks!
                    </p>

                <?php endif; ?>
                <script>
                    $ = jQuery;

                    $(document).ready(function() {
                        var elementID = 'formSubbed';
                        var offset = $('#' + elementID).offset().top - parseInt(150);

                        setTimeout(() => {
                            $('html, body').animate({
                                scrollTop: offset
                            }, 1500);
                        }, 1000);
                    });
                </script>

            <?php 
            unset($_POST);
            endif; ?>

            <form action="#" method="post">

                <!-- first name -->
                <input type="text" name="first_name" id="first_name" class="form-text form-control mb-4" placeholder="your first name*" required>

                <!-- last name -->
                <input type="text" name="last_name" id="last_name" class="form-text form-control mb-4" placeholder="your last name*" required>

                <!-- email -->
                <input type="email" name="email" id="email" class="form-text form-control mb-4" placeholder="your email address*" required>

                <!-- tel -->
                <input type="tel" name="tel" id="tel" class="form-text form-control mb-4" placeholder="your contact number*" required>

                <!-- message -->
                <textarea name="message" id="message" cols="30" rows="10" class="form-text form-control mb-4" placeholder="your message*" required></textarea>

                <!-- submiy -->
                <input type="submit" id="contact_submit" name="contact_submit" class="btn btn-lg btn-warning d-block w-100 mb-5" value="Send">

            </form>

        </div>

    </div>

</div>

<script>
    $ = jQuery;

    $(document).ready(function() {
        var icon_cont_height = $('.landing-icon-div').height();
        $('.landing-icon-div').width(icon_cont_height);
    });
</script>

<?php get_footer(); ?>