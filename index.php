<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo get_the_title(); ?></title>
    <?php wp_head(); ?>
  </head>
  <body>
    <h1><?php echo get_the_title(); ?></h1>
    <?php wp_footer(); ?>
  </body>
</html>