<?php use_helper('Network') ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
    <?php include_stylesheets() ?>
    <?php if(!is_local($_SERVER['REMOTE_ADDR'])) echo javascript_include_tag('analytics.js'); ?>
  </head>
  <body>
    <?php echo $sf_content ?>
    <?php include_javascripts() ?>
  </body>
</html>
