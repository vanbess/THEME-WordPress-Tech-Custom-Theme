<?php

/**
 * Template Name: Dashboard
 */

get_header('dashboard');

global $post;

?>

<!-- Main content -->
<div id="dashboard-cont" class="container mt-5" style="min-height: 80vh;">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Sales</div>
                <div class="card-body">
                    <h5 class="card-title">$50,000</h5>
                    <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi blandit, lorem in auctor dapibus, sapien est auctor augue, id sagittis purus tortor vel lorem. Vivamus sed luctus elit.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Users</div>
                <div class="card-body">
                    <h5 class="card-title">1000</h5>
                    <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi blandit, lorem in auctor dapibus, sapien est auctor augue, id sagittis purus tortor vel lorem. Vivamus sed luctus elit.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer('dashboard'); ?>