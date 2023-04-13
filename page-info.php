<?php get_header();

global $post;

/**
 * Template Name: PHP Info
 */

?>

<!-- container main -->
<div id="base_page_template" class="container">


    <!-- title block -->
    <div class="row">
        <div class="col">
            <h1>
                <?php echo $post->post_title; ?>
            </h1>
        </div>
    </div>

    <!-- content block -->
    <div class="row">
        <div class="col">
            <?php phpinfo() ?>
        </div>
    </div>

</div>

<?php get_footer(); ?>