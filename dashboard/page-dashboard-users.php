<?php

/**
 * Template Name: Dashboard Users Page
 */

get_header('dashboard');

global $post;

// get users
$users = get_users();

// fetch shop meta
$shop_meta = fetch_shop_meta();

// get main user first and last name
$main_user_first_last = $shop_meta['shop_owner_first_last'][0];

?>

<!-- Main content -->
<div id="dashboard-cont" class="container mt-5 pt-5" style="min-height: 90vh;">
    <div class="row users">
        <div class="col-md-12">

            <p class="bg-success-subtle p-2 rounded-2 shadow-sm text-center mb-4">The table below contains all users currently registered for your store.</p>

            <table class="table table-bordered table-striped">

                <!-- user table-->
                <thead class="bg-dark-subtle text-center rounded-1 mb-2">
                    <tr>
                        <th scope="col" class="user-table-th fw-semibold">User ID</th>
                        <th scope="col" class="user-table-th fw-semibold">Username</th>
                        <th scope="col" class="user-table-th fw-semibold">First & Last Name</th>
                        <th scope="col" class="user-table-th fw-semibold">Email</th>
                        <th scope="col" class="user-table-th fw-semibold">Delete User</th>
                    </tr>
                </thead>

                <tbody id="user-list-body" class="text-center">
                    <?php foreach ($users as $user) : ?>
                        <tr class="align-bottom">
                            <td><?php echo $user->ID; ?></td>
                            <td><?php echo $user->user_login; ?></td>
                            <td><?php echo $user->display_name; ?></td>
                            <td><?php echo $user->user_email; ?></td>
                            <td><?php echo $user->display_name === $main_user_first_last ? 'Not allowed <span class="user_info" title="the user which created this account cannot be deleted">?</span>' : '<button class="btn btn-danger del_user" title="click to delete this user">Delete</button>'; ?></td>
                        </tr>
                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>
    </div>

    <div class="row add-new-user pt-3 mb-5">
        <div id="add-new-user-col" class="offset-3 col-6 mb-5">
            <button class="btn btn-primary w-100" title="click to add new user" onclick="userInputs()">Add New User</button>

            <!-- user inputs -->
            <div id="new-user-form-cont" class="d-none p-3 mt-3 bg-light rounded-3 shadow-sm mb-5">
                <form method="post">

                    <p class="bg-success-subtle p-2 rounded-2 text-center shadow-sm">Enter new user's info below. Note that all fields are required.</p>

                    <!-- first name -->
                    <div class="mb-3">
                        <label for="firstName" class="form-label">First Name*</label>
                        <input type="text" class="form-control" id="firstName" placeholder="Enter first name" required>
                    </div>

                    <!-- last name -->
                    <div class="mb-3">
                        <label for="lastName" class="form-label">Last Name*</label>
                        <input type="text" class="form-control" id="lastName" placeholder="Enter last name" required>
                    </div>

                    <!-- email address -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address*</label>
                        <input type="email" class="form-control" id="email" placeholder="Enter email address" required>
                    </div>

                    <!-- password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Password*</label>
                        <input type="password" class="form-control" id="password" placeholder="Enter password" required>
                    </div>

                    <!-- confirm password -->
                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label">Confirm Password*</label>
                        <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm password" required>
                    </div>

                    <!-- passes match/do not match -->
                    <div id="user-pass-match-cont" class="mt-3 mb-3">
                        <span id="user-pass-match" class="bg-success-subtle p-2 rounded-2 d-none">Passwords match!</span>
                        <span id="user-pass-mismathc" class="bg-danger-subtle p-2 rounded-2 d-none">Passwords do not match!</span>
                    </div>

                    <!-- role selection -->
                    <div class="mb-3">
                        <label for="role" class="form-label">Select role*</label>
                        <select class="form-select" id="role" required>
                            <option value="manager">Manager</option>
                            <option value="attendant">Attendant</option>
                        </select>
                    </div>

                    <!-- submit -->
                    <button type="submit" class="btn btn-primary w-100">Add User</button>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
    $ = jQuery;

    function userInputs() {
        $('#new-user-form-cont').toggleClass('d-none');
    }
</script>

<style>
    .user_info {
        cursor: pointer;
        border: 1px solid #ddd;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        line-height: 1.2em;
        text-align: center;
        display: inline-block;
        background: #ffdada;
        font-size: 13px;
        vertical-align: text-bottom;
    }
</style>

<?php get_footer('dashboard'); ?>