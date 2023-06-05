<?php

/**
 * Template Name: Registration
 */

get_header('registration');

global $post;

?>

<!-- container main -->
<div id="registration" class="container my-5 py-5">

    <!-- title block -->
    <div class="row mb-4">
        <div class="col-6 offset-3">

            <h1 class="text-center pb-4" style="font-size: 2.5rem;">
                <?php echo $post->post_title; ?>
            </h1>

            <div class="p-3 bg-success-subtle rounded-2 shadow-sm">
                <!-- instructions -->
                <p class="text-center mb-0">
                    Fill out the form below to register your shop.
                </p>

                <p class="text-center text-danger mb-0">
                    <strong>Note that all fields are required.</strong>
                </p>
            </div>


            <p id="reg-msg" class="alert alert-info text-center d-none">

            </p>

        </div>
    </div>

    <!-- registration form block -->
    <div id="registration_form_cont" class="row mb-5 pb-5">

        <form id="registration_form" method="post" class="mb-5 pb-5">

            <div class="col-6 offset-3">

                <!-- SHOP INFO -->
                <h4 class="mb-3">Shop info</h4>

                <!-- owner first name -->
                <input type="text" name="shop_owner_first" id="shop_owner_first" class="form-control mb-3 shadow-sm" placeholder="owner first name*">

                <!-- owner last name -->
                <input type="text" name="shop_owner_last" id="shop_owner_last" class="form-control mb-3 shadow-sm" placeholder="owner last name*">

                <!-- email -->
                <input type="email" name="shop_owner_email" id="shop_owner_email" class="form-control mb-3 shadow-sm" placeholder="owner email address*">

                <!-- tel -->
                <input type="tel" name="shop_owner_tel" id="shop_owner_tel" class="form-control mb-3 shadow-sm" placeholder="owner contact number*">

                <!-- franchise name -->
                <input type="text" name="shop_franchise" id="shop_franchise" class="form-control mb-3 shadow-sm" placeholder="shop franchise, e.g. Engen, Caltex etc*">

                <!-- shop name -->
                <input type="text" name="shop_name" id="shop_name" class="form-control mb-3 shadow-sm" placeholder="the name of your shop (must be unique)*">

                <!-- street name and number -->
                <input type="text" name="shop_street_number" id="shop_street_number" class="form-control mb-3 shadow-sm" placeholder="street name and number*">

                <!-- suburb -->
                <input type="text" name="shop_suburb" id="shop_suburb" class="form-control mb-3 shadow-sm" placeholder="suburb*">

                <!-- city or town -->
                <input type="text" name="shop_city" id="shop_city" class="form-control mb-3 shadow-sm" placeholder="city or town*">

                <!-- province -->
                <select class="form-control mb-3 shadow-sm" id="shop_province" name="shop_province">
                    <option value="">select province</option>
                    <option value="Eastern Cape">Eastern Cape</option>
                    <option value="Free State">Free State</option>
                    <option value="Gauteng">Gauteng</option>
                    <option value="KwaZulu-Natal">KwaZulu-Natal</option>
                    <option value="Limpopo">Limpopo</option>
                    <option value="Mpumalanga">Mpumalanga</option>
                    <option value="North West">North West</option>
                    <option value="Northern Cape">Northern Cape</option>
                    <option value="Western Cape">Western Cape</option>
                </select>

                <!-- postal code -->
                <input type="text" name="shop_postal_code" id="shop_postal_code" class="form-control mb-5 shadow-sm" placeholder="postal code*">

                <!-- TERMS CHECKBOXES -->
                <h4 class="mb-3">Terms & Conditions</h4>

                <p class="text-danger"><i><b>By registering your shop, you confirm that you have read through, understand and agree to the following policies: </b></i></p>

                <!-- terms and conditions -->
                <div class="form-check">
                    <input class="form-check-input shadow-sm" type="checkbox" value="" id="terms">
                    <label class="form-check-label" for="terms">
                        Terms & Conditions &nbsp;
                        <a href="/terms-conditions" title="View Terms & Conditions" target="_blank">
                            <i class="bi bi-box-arrow-up-right"></i>
                        </a>
                    </label>
                </div>

                <!-- privacy -->
                <div class="form-check">
                    <input class="form-check-input shadow-sm" type="checkbox" value="" id="privacy">
                    <label class="form-check-label" for="privacy">
                        Privacy Policy &nbsp;
                        <a href="/privacy-policy" title="View Privacy Policy" target="_blank">
                            <i class="bi bi-box-arrow-up-right"></i>
                        </a>
                    </label>
                </div>

                <!-- cookies -->
                <div class="form-check mb-5">
                    <input class="form-check-input shadow-sm" type="checkbox" value="" id="cookies">
                    <label class="form-check-label" for="cookies">
                        Cookie Policy &nbsp;
                        <a href="/cookie-policy" title="View Cookie Policy" target="_blank">
                            <i class="bi bi-box-arrow-up-right"></i>
                        </a>
                    </label>
                </div>

                <!-- SUBMIT -->
                <input id="register" class="btn btn-primary btn-lg w-100 rounded-2 text-uppercase shadow-sm" type="submit" value="Register" />

            </div>

        </form>

    </div>

</div>

<script id="reg_form_submit">
    jQuery(document).ready(function($) {

        $('#registration_form').submit(function(event) {

            event.preventDefault();

            $('#register').val('Processing...');

            // check from validity
            var isValid = true;
            var termsChecked = true;

            // text inputs
            $('input[type="text"]').each(function(index, element) {
                if ($.trim($(this).val()) == '') {
                    isValid = false;
                }
            });

            // email inputs
            $('input[type="email]"').each(function(index, element) {
                if ($.trim($(this).val()) == '') {
                    isValid = false;
                }
            });

            // telephone inputs
            $('input[type="tel"]').each(function(index, element) {
                if ($.trim($(this).val()) == '') {
                    isValid = false;
                }
            });

            // terms
            !$('#terms').is(':checked') ? termsChecked = false : termsChecked = true;
            !$('#privacy').is(':checked') ? termsChecked = false : termsChecked = true;
            !$('#cookies').is(':checked') ? termsChecked = false : termsChecked = true;

            // if inputs and checkboxes are empty/not checked
            if (!isValid && !termsChecked) {
                alert('Please fill in all required fields and accept all policies and terms before attempting to register your shop.')
                $('#register').val('Register');
                return false;
            }

            // if inputs are empty
            if (!isValid) {
                alert('Please fill in all required fields (*).')
                $('#register').val('Register');
                return false;
            }

            // if any terms aren't checked
            if (!termsChecked) {
                alert('Please accept all our terms and conditions before attempting to register your shop.');
                $('#register').val('Register');
                return false;
            }

            var formData = new FormData(this);
            formData.append('action', 'process_shop_registration');
            formData.append('_ajax_nonce', '<?php echo wp_create_nonce('ex tech register shop') ?>');

            $('#registrationProcessing').modal('show');

            $.ajax({
                type: 'POST',
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#reg-msg').text(response).removeClass('d-none');
                    $('.bg-success-subtle').addClass('d-none');
                    $('#register').val('Register');
                    $('html, body').animate({
                        scrollTop: 0
                    }, 'fast');
                },
                error: function(xhr, status, error) {
                    $('#reg-msg').addClass('alert-warning').text('Error: ' + xhr.responseText).removeClass('d-none');
                    $('.bg-success-subtle').addClass('d-none');
                }
            });
        });
    });
</script>

<?php get_footer(); ?>