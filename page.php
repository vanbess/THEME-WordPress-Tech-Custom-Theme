<?php get_header();

global $post;

?>

<!-- container main -->
<div id="base_page_template" class="container my-5 py-5" style="min-height: 79vh;">

    <!-- title block -->
    <div class="row">
        <div class="col">
            <h1 class="mb-5">
                <?php echo $post->post_title; ?>
            </h1>
        </div>
    </div>

    <!-- content block -->
    <div class="row">
        <div class="col">
            <?php echo $post->post_content; ?>
        </div>
    </div>

</div>

<?php get_footer(); ?>