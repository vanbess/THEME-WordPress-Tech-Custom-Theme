<?php

/**
 * Template Name: Password Reset
 */

// is user registered? (default == true)
$is_registered = true;

// Check if a password reset request has been submitted
if (isset($_POST['submit'])) :

	// Get the user's email address
	$user_email = sanitize_email($_POST['user_email']);

	// Generate a unique key for the password reset
	$key = wp_generate_password(20, false);

	// Save the key in the user's meta data on each child site
	$blog_ids = get_sites(array('fields' => 'ids'));

	foreach ($blog_ids as $blog_id) :

		switch_to_blog($blog_id);

		$user = get_user_by('email', $user_email);

		if ($user) :
			update_user_meta($user->ID, 'password_reset_key', $key);
		else :
			$is_registered = false;
		endif;

		restore_current_blog();
	endforeach;

	// Generate the password reset URL
	$child_site_id = get_user_child_site_id($user->ID);

	if (!empty($child_site_id)) :

		switch_to_blog($child_site_id);

		$password_reset_url = add_query_arg(array(
			'key' => $key,
			'email' => $user_email,
		), site_url('password-reset'));

		restore_current_blog();
	else :

		$password_reset_url = add_query_arg(array(
			'key' => $key,
			'email' => $user_email,
		), network_site_url('password-reset'));
	endif;

	// if user is not registered on any child site, setup error message
	if (!$is_registered) :
		$not_registered_msg = '<div class="alert alert-danger">There are no registered users with that email address. Do you still need to <a href="/register">register?</a></div>';
	endif;

	// Send the password reset email to the user
	$subject = 'Password Reset Request';
	$message = sprintf('Click the following link to reset your password: %s', $password_reset_url);

	wp_mail($user_email, $subject, $message);

	// Display a success message
	$success_message = '<div class="alert alert-success mb-3">A password reset email has been sent to your email address.</div>';
endif;

// Check if a password reset request has been submitted with a valid key
if (isset($_POST['reset_password'])) :

	// Get the user's email address, password reset key, and new password
	$user_email   = sanitize_email($_POST['user_email']);
	$key          = sanitize_text_field($_POST['key']);
	$new_password = $_POST['new_password'];

	// Verify that the password reset key is valid for the user on any of the child sites
	$blog_ids                 = get_sites(array('fields' => 'ids'));
	$password_reset_key_found = false;

	foreach ($blog_ids as $blog_id) :

		switch_to_blog($blog_id);

		$user               = get_user_by('email', $user_email);
		$password_reset_key = get_user_meta($user->ID, 'password_reset_key', true);

		if ($password_reset_key === $key) :
			$password_reset_key_found = true;
			break;
		endif;
		restore_current_blog();
	endforeach;

	// If the password reset key is valid, update the user's password on the appropriate child site
	if ($password_reset_key_found) :
		$child_site_id = get_user_child_site_id($user->ID);
		if (!empty($child_site_id)) :
			switch_to_blog($child_site_id);
			wp_set_password($new_password, $user->ID);
			delete_user_meta($user->ID, 'password_reset_key');
			restore_current_blog();
		else :
			wp_set_password($new_password, $user->ID);
			delete_user_meta($user->ID, 'password_reset_key');
		endif;

		// Display a success message
		$success_message = '<div class="alert alert-success mb-3">Your password has been reset.</div>';
	else :
		// Display an error message
		$error_message = '<div class="alert alert-danger mb-3">Invalid password reset key.</div>';
	endif;
endif;

// Get the header
get_header('blank');
?>

<div id="password-reset-cont" class="container my-5 py-5" style="min-height: 65vh;">
	<div class="row mb-4">
		<div class="col-6 offset-3">

			<h1 class="text-center pb-4" style="font-size: 2.5rem;">
				Password Reset
			</h1>

			<?php if (isset($not_registered_msg)) echo $not_registered_msg; ?>
			<?php if (isset($success_message)) echo $success_message; ?>
			<?php if (isset($error_message)) echo $error_message; ?>

			<?php if (!isset($_POST['reset_password'])) : ?>

				<p class="text-center">Forgot your password? Enter your email address below to receive a password reset email.</p>

				<form method="post">
					<div class="form-group mb-4">
						<input type="email" class="form-control" id="user_email" name="user_email" required placeholder="your email address*">
					</div>
					<button type="submit" class="btn btn-primary w-100" name="submit">Submit</button>
				</form>

			<?php else : ?>

				<p>Enter your new password below.</p>

				<form method="post">

					<div class="form-group mb-3">
						<input type="password" class="form-control" id="new_password" name="new_password" required placeholder="new password*">
					</div>

					<div class="form-group mb-4">
						<input type="password" class="form-control" id="confirm_password" name="confirm_password" required placeholder="confirm new password*">
					</div>

					<input type="hidden" name="user_email" value="<?php echo esc_attr($_POST['user_email']); ?>">
					<input type="hidden" name="key" value="<?php echo esc_attr($_POST['key']); ?>">

					<button type="submit" class="btn btn-primary w-100" name="reset_password">Reset Password</button>

				</form>
			<?php endif; ?>
		</div>
	</div>
</div>

<?php
// Get the footer
get_footer();
