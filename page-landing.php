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
    <div id="intro_banner" class="row p-5 mb-5 align-items-center shadow-lg" style="background-image: url(<?php echo get_field('intro_banner'); ?>);">

        <!-- intro text -->
        <div id="intro_banner_text" class="col-8 bg-black bg-opacity-75 p-5">
            <h1 class="text-light text-opacity-75" style="font-size: 3rem;">
                <?php echo get_field('intro_banner_text', $p_id); ?>
            </h1>
        </div>

        <!-- intro cta -->
        <div id="intro_banner_cta" class="col-4 text-center">
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
    <div id="about_us" class="row pt-5 pb-5 mt-5 mb-5">
        <div class="col">
            <h1 class="text-center pb-5">About Us</h1>

            <p class="text-center pb-5"><?php echo get_field('about_us_text', $p_id); ?></p>
        </div>
    </div>

    <!-- contact -->
    <div id="contact" class="row pt-5 pb-5 mt-5 mb-5">

        <!-- contact form -->
        <div id="landing-contact-form-cont" class="col-6 offset-3 mb-5">

            <h1 class="text-center pb-5">Questions? Contact Us</h1>

            <p class="text-center pb-5"><?php echo get_field('contact_us_text', $p_id); ?></p>

            <form action="#">

                <!-- first name -->
                <input type="text" name="contact_first_name" id="contact_first_name" class="form-text form-control mb-4" placeholder="your first name*">

                <!-- last name -->
                <input type="text" name="contact_last_name" id="contact_last_name" class="form-text form-control mb-4" placeholder="your last name*">

                <!-- email -->
                <input type="email" name="contact_email" id="contact_email" class="form-text form-control mb-4" placeholder="your email address*">

                <!-- tel -->
                <input type="tel" name="contact_tel" id="contact_tel" class="form-text form-control mb-4" placeholder="your contact number*">

                <!-- message -->
                <textarea name="contact_msg" id="contact_msg" cols="30" rows="10" class="form-text form-control mb-4" placeholder="your message*"></textarea>

                <!-- submiy -->
                <input type="submit" id="contact_submit" class="btn btn-lg btn-warning d-block w-100 mb-5" value="Send">

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