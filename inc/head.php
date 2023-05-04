<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta name="robots" content="noindex,follow"/>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="keywords" content="<?php echo $fn->varv('meta_keywords', $fn->cms); ?>"/>
    <meta name="description" content="<?php echo $fn->varv('meta_desc', $fn->cms); ?>"/>
    <meta name="author" content="<?php echo app_name; ?>"/>
    <title><?php echo $fn->varv('page_title', $fn->cms) != '' ? trim($fn->varv('page_title', $fn->cms) . ' - ' . app_name, ' - ') : app_name; ?></title>

    <link rel="canonical" href="<?php echo $fn->current_url(); ?>"/>
 <?php $pre = [$fn->permalink(), 'https://fonts.gstatic.com', 'https://www.googletagmanager.com', 'https://www.google-analytics.com'];
  foreach ($pre as $p) {
   ?>
      <link rel='dns-prefetch' href='<?php echo $p; ?>'/>
      <link href='<?php echo $p; ?>' crossorigin rel='preconnect'/>
  <?php } ?>

    <!-- FAVICON : START -->
    <link rel="apple-touch-icon-precomposed" sizes="57x57" href="<?php echo $fn->permalink('assets/images/favicon/apple-touch-icon-57x57.png'); ?>"/>
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo $fn->permalink('assets/images/favicon/apple-touch-icon-114x114.png'); ?>"/>
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $fn->permalink('assets/images/favicon/apple-touch-icon-72x72.png'); ?>"/>
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo $fn->permalink('assets/images/favicon/apple-touch-icon-144x144.png'); ?>"/>
    <link rel="apple-touch-icon-precomposed" sizes="60x60" href="<?php echo $fn->permalink('assets/images/favicon/apple-touch-icon-60x60.png'); ?>"/>
    <link rel="apple-touch-icon-precomposed" sizes="120x120" href="<?php echo $fn->permalink('assets/images/favicon/apple-touch-icon-120x120.png'); ?>"/>
    <link rel="apple-touch-icon-precomposed" sizes="76x76" href="<?php echo $fn->permalink('assets/images/favicon/apple-touch-icon-76x76.png'); ?>"/>
    <link rel="apple-touch-icon-precomposed" sizes="152x152" href="<?php echo $fn->permalink('assets/images/favicon/apple-touch-icon-152x152.png'); ?>"/>
    <link rel="icon" type="image/png" href="<?php echo $fn->permalink('assets/images/favicon/favicon-196x196.png'); ?>" sizes="196x196"/>
    <link rel="icon" type="image/png" href="<?php echo $fn->permalink('assets/images/favicon/favicon-96x96.png'); ?>" sizes="96x96"/>
    <link rel="icon" type="image/png" href="<?php echo $fn->permalink('assets/images/favicon/favicon-32x32.png'); ?>" sizes="32x32"/>
    <link rel="icon" type="image/png" href="<?php echo $fn->permalink('assets/images/favicon/favicon-16x16.png'); ?>" sizes="16x16"/>
    <link rel="icon" type="image/png" href="<?php echo $fn->permalink('assets/images/favicon/favicon-128.png'); ?>" sizes="128x128"/>
    <!-- FAVICON : END -->

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="<?php echo $fn->permalink('assets/vendor/bootstrap/css/bootstrap.min.css'); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $fn->permalink('assets/vendor/font-awesome/css/all.css'); ?>"/>

    <link rel="stylesheet" type="text/css" href="<?php echo $fn->permalink('assets/vendor/slick/slick.css'); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $fn->permalink('assets/vendor/ekko-lightbox/ekko-lightbox.css'); ?>"/>

    <link rel="stylesheet" type="text/css" href="<?php echo $fn->permalink('assets/css/style.css'); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $fn->permalink('assets/css/custom.css'); ?>"/>
 <?php echo $fn->style; ?>
    <script type="text/javascript">
        var root = '<?php echo $fn->permalink(); ?>', hostname = '<?php echo $fn->permalink(); ?>',
            token = '<?php echo $fn->get_token(); ?>';
    </script>
</head>

<body>