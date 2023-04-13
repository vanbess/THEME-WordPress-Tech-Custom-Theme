<?php

/**
 * Template Name: Dashboard Login
 */

// Check if the user is already logged in
if (is_user_logged_in()) {

	// Redirect the user to their child site
	$user_id       = get_current_user_id();

	// get user data so that we can check roles
	$user_data  = get_userdata($user_id);
	$user_roles = $user_data->roles;

	// if valid roles present, redirect to dashboard, else redirect to dashboard login
	if (in_array('shop_manager', $user_roles) || in_array('shop_owner', $user_roles)) :

		$child_site_id = get_user_child_site_id($user_id);

		if (!empty($child_site_id)) :
			switch_to_blog($child_site_id);
			wp_safe_redirect(get_home_url() . '/dashboard/');
			exit;
		endif;

	endif;
}

// Check if the form has been submitted
if (isset($_POST['submit'])) {

	// Get the user's email address and password
	$user_email = sanitize_email($_POST['user_email']);
	$user_pass  = $_POST['user_pass'];

	// Get a list of all child sites
	$blog_ids = get_sites(array('fields' => 'ids'));

	// Loop through the child sites and authenticate the user on each site
	foreach ($blog_ids as $blog_id) {

		switch_to_blog($blog_id);
		$user = wp_authenticate($user_email, $user_pass);

		if (!is_wp_error($user)) {

			// Set the login cookies
			wp_set_auth_cookie($user->ID);
			do_action('wp_login', $user->user_login, $user);

			// Redirect the user to their child site
			$child_site_id = get_user_child_site_id($user->ID);

			if (!empty($child_site_id)) {
				switch_to_blog($child_site_id);
				wp_safe_redirect(get_home_url() . '/dashboard/');
				exit;
			}
		}
		restore_current_blog();
	}

	// If the user is not registered on any of the sites or if the login credentials are invalid, display an error message
	$error_message = '<div class="alert alert-danger">Sorry, that email address or password is incorrect.</div>';
}

// Get the header
get_header('blank');

global $post;

?>

<div id="dash-login-form-cont" class="container my-5 py-5" style="min-height: 68vh;">

	<div class="row mb-5 pb-5">

		<div class="col-6 offset-3">

			<!-- title -->
			<h1 class="text-center pb-4" style="font-size: 2.5rem;">
				<?php echo $post->post_title; ?>
			</h1>

			<p class="text-body text-body-emphasis text-center mb-5">If you have already <a href="/register/">registered</a>, enter your email address and password below to log into your shop's dashboard.</p>

			<!-- err msg -->
			<?php if (isset($error_message)) echo $error_message; ?>

			<!-- login form -->
			<form method="post">

				<!-- email address -->
				<div class="form-group mb-3">
					<input type="email" class="form-control" id="user_email" name="user_email" required placeholder="email address*">
				</div>

				<!-- password -->
				<div class="form-group mb-3">
					<input type="password" class="form-control" id="user_pass" name="user_pass" required placeholder="password*">
				</div>

				<!-- submit -->
				<button type="submit" class="btn btn-primary btn-lg w-100" name="submit">Login</button>
			</form>
		</div>
	</div>
</div>

<?php
// Get the footer
get_footer();

// Function to get the child site ID for a user
function get_user_child_site_id($user_id) {
	$blog_ids = get_sites(array('fields' => 'ids'));
	foreach ($blog_ids as $blog_id) {
		switch_to_blog($blog_id);
		if (is_user_member_of_blog($user_id, $blog_id)) {
			return $blog_id;
		}
		restore_current_blog();
	}
	return false;
}
