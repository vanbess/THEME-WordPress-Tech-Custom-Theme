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
        <div class="col-sm-12 ms-sm-0 col-md-8 ms-md-auto me-md-auto">

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

        <form id="registration_form" method="post" class="mb-5 pb-5" novalidate>

            <div class="col-sm-12 ms-sm-0 col-md-8 ms-md-auto me-md-auto">

                <!-- SHOP INFO -->
                <h4 class="mb-3">Shop info</h4>

                <!-- owner first name -->
                <p>

                    <label for="shop_owner_first" class="from-label fw-normal mb-1 fw-semibold"><i>Owner First Name*</i></label>
                    <input type="text" name="shop_owner_first" id="shop_owner_first" class="form-control shadow-sm">
                </p>

                <p>
                    <!-- owner last name -->
                    <label for="shop_owner_last" class="from-label fw-normal mb-1 fw-semibold"><i>Owner Last Name*</i></label>
                    <input type="text" name="shop_owner_last" id="shop_owner_last" class="form-control shadow-sm">
                </p>

                <p>
                    <!-- email -->
                    <label for="shop_owner_email" class="from-label fw-normal mb-1 fw-semibold"><i>Owner Email Address*</i></label>
                    <input type="email" name="shop_owner_email" id="shop_owner_email" class="form-control shadow-sm">
                </p>

                <p>
                    <!-- tel -->
                    <label for="shop_owner_email" class="from-label fw-normal mb-1 fw-semibold"><i>Owner Phone Number*</i></label>
                    <input type="tel" name="shop_owner_tel" id="shop_owner_tel" class="form-control shadow-sm">
                </p>

                <p>
                    <!-- franchise name -->
                    <label for="shop_franchise" class="from-label fw-normal mb-1 fw-semibold"><i>Shop Franchise*</i></label>
                    <input type="text" name="shop_franchise" id="shop_franchise" class="form-control shadow-sm">
                    <small class="text-muted"><i>Shop franchise, e.g. Engen, Caltex etc</i></small>
                </p>

                <p>
                    <!-- shop name -->
                    <label for="shop_name" class="from-label fw-normal mb-1 fw-semibold"><i>Shop Name*</i></label>
                    <input type="text" name="shop_name" id="shop_name" class="form-control shadow-sm">
                    <small class="text-muted"><i>The name of your shop, e.g. One Stop Braamfontein</i></small>
                </p>

                <p>
                    <!-- street name and number -->
                    <label for="shop_street_number" class="from-label fw-normal mb-1 fw-semibold"><i>Shop Street Name & Number*</i></label>
                    <input type="text" name="shop_street_number" id="shop_street_number" class="form-control shadow-sm">
                </p>

                <p>
                    <!-- suburb -->
                    <label for="shop_suburb" class="from-label fw-normal mb-1 fw-semibold"><i>Suburb*</i></label>
                    <input type="text" name="shop_suburb" id="shop_suburb" class="form-control shadow-sm">
                </p>

                <p>
                    <!-- city or town -->
                    <label for="shop_city" class="from-label fw-normal mb-1 fw-semibold"><i>City or Town*</i></label>
                    <input type="text" name="shop_city" id="shop_city" class="form-control shadow-sm">
                </p>

                <p>
                    <!-- province -->
                    <label for="shop_province" class="from-label fw-normal mb-1 fw-semibold"><i>Province*</i></label>
                    <select class="form-control shadow-sm" id="shop_province" name="shop_province">
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
                </p>

                <p>
                    <!-- postal code -->
                    <label for="shop_postal_code" class="from-label fw-normal mb-1 fw-semibold"><i>Postal Code*</i></label>
                    <input type="text" name="shop_postal_code" id="shop_postal_code" class="form-control mb-4 shadow-sm">
                </p>

                <h4 class="mb-3">Business Related Documentation</h4>

                <!-- instructions -->
                <p class="text-danger fw-medium"><i>Please upload the following documents (use CTRL/CMD + Click to select multiple documents for a given input):</i></p>

                <!-- business registration documents (multiple files) -->
                <p>
                    <label for="shop_bus_reg_docs" class="from-label fw-normal mb-1 fw-semibold">Business Registration Documentation:*</label>
                    <input type="file" name="shop_bus_reg_docs[]" id="shop_bus_reg_docs" class="form-control" multiple>
                    <small class="text-muted"><i>Please upload your valid business registration documentation here</i></small>
                </p>

                <!-- id documents of all directors -->
                <p>
                    <label for="shop_director_ids" class="from-label fw-normal mb-1 fw-semibold">Director(s) ID Document(s):*</label>
                    <input type="file" name="shop_director_ids[]" id="shop_director_ids" class="form-control" multiple>
                    <small class="text-muted"><i>Please upload copies of the ID documents of all the company directors here</i></small>
                </p>

                <!-- proof of bank account -->
                <p>
                    <label for="shop_bank_acc" class="from-label fw-normal mb-1 fw-semibold">Proof of Bank Account:*</label>
                    <input type="file" name="shop_bank_acc[]" id="shop_bank_acc" class="form-control" multiple>
                    <small class="text-muted"><i>Please upload proof of bank account into which payments should be made</i></small>
                </p>

                <!-- TERMS CHECKBOXES -->
                <h4 class="mb-3">Terms & Conditions</h4>

                <p class="text-danger fw-medium"><i>By registering your shop, you confirm that you have read through, understand and agree to the following policies: </i></p>

                <!-- terms and conditions -->
                <div class="form-check">
                    <input class="form-check-input shadow-sm" type="checkbox" value="" id="terms">
                    <label class="form-check-label" for="terms">
                        Terms & Conditions* &nbsp;
                        <a href="/terms-conditions" title="View Terms & Conditions" target="_blank">
                            <i class="bi bi-box-arrow-up-right"></i>
                        </a>
                    </label>
                </div>

                <!-- privacy -->
                <div class="form-check">
                    <input class="form-check-input shadow-sm" type="checkbox" value="" id="privacy">
                    <label class="form-check-label" for="privacy">
                        Privacy Policy* &nbsp;
                        <a href="/privacy-policy" title="View Privacy Policy" target="_blank">
                            <i class="bi bi-box-arrow-up-right"></i>
                        </a>
                    </label>
                </div>

                <!-- cookies -->
                <div class="form-check mb-5">
                    <input class="form-check-input shadow-sm" type="checkbox" value="" id="cookies">
                    <label class="form-check-label" for="cookies">
                        Cookie Policy* &nbsp;
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
            var filesAttached = true;

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

            // files
            $('input[type="file"]').each(function(index, element) {
                if ($.trim($(this).val()) == '') {
                    filesAttached = false;
                }
            });

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

            // manually validate email
            var email = $('#shop_owner_email').val();
            var atpos = email.indexOf("@");
            var dotpos = email.lastIndexOf(".");

            if (atpos < 1 || dotpos < atpos + 2 || dotpos + 2 >= email.length) {

                alert('Please enter a valid email address.');

                // scroll to top of form
                $('html, body').animate({
                    scrollTop: $('#registration_form_cont').offset().top
                }, 100);

                // add red border to input
                $('#shop_owner_email').addClass('border-danger');

                $('#register').val('Register');
                return false;
            }

            // if phone number input < 10 characters
            if ($('#shop_owner_tel').val().length < 10 || $('#shop_owner_tel').val().length > 10 || isNaN($('#shop_owner_tel').val())) {
                
                alert('Please enter a valid phone number.');

                // scroll to top of form
                $('html, body').animate({
                    scrollTop: $('#registration_form_cont').offset().top
                }, 100);


                // add red border to input
                $('#shop_owner_tel').addClass('border-danger');


                $('#register').val('Register');
                return false;
            }

            // if postal code input < 4 characters or > 4 characters
            if ($('#shop_postal_code').val().length < 4 || $('#shop_postal_code').val().length > 4 || isNaN($('#shop_postal_code').val())) {

                alert('Please enter a valid postal code.');

                // scroll to top of form
                $('html, body').animate({
                    scrollTop: $('#registration_form_cont').offset().top
                }, 100);

                // add red border to input
                $('#shop_postal_code').addClass('border-danger');

                $('#register').val('Register');
                return false;
            }
            
            // if no files are attached
            if (!filesAttached) {
                alert('Please attach all required files before attempting to register your shop.');
                $('#register').val('Register');
                return false;
            }

            // terms
            !$('#terms').is(':checked') ? termsChecked = false : termsChecked = true;
            !$('#privacy').is(':checked') ? termsChecked = false : termsChecked = true;
            !$('#cookies').is(':checked') ? termsChecked = false : termsChecked = true;

            // if inputs and checkboxes are empty/not checked
            if (!termsChecked) {
                alert('Please accept all terms.')
                $('#register').val('Register');
                return false;
            }

            var formData = new FormData(this);
            formData.append('action', 'process_shop_registration');
            formData.append('_ajax_nonce', '<?php echo wp_create_nonce('ex tech register shop') ?>');

            $.ajax({
                type: 'POST',
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {

                    // debug
                    // console.log(response);

                    // if response error, display error message and reload page
                    if (response.success === false) {
                        location.reload();
                        return false;

                        // if success, display success message and redirect to home page
                    } else {
                        alert(response.data);
                        setTimeout(function() {
                            window.location.href = '/';
                        }, 5000);
                        return false;
                    }

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