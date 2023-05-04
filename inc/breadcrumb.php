<?php
 $header_image = $fn->permalink('assets/images/breadcrumb/breadcrumb_1.jpg');
 if ($fn->varv('header_image', $fn->cms)) {
  if ($fn->file_exists($fn->cms['header_image'])) {
   $header_image = $fn->get_file($fn->cms['header_image']);
  }
 }
?>
<div class="breadcrumb-section bg-h"<?php echo $header_image ? ' style="background-image: url(' . $header_image . ')"' : ''; ?>">
    <div class="overlay op-5"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2 text-center">
                <div class="breadcrumb-menu">
                    <h2><?php echo $fn->varv('page_heading', $fn->cms); ?></h2>
                    <span><a href="<?php echo $fn->permalink(); ?>">Home</a></span>
                 <?php
                  if (isset($breadcrumb) && $breadcrumb) {
                   foreach ($breadcrumb as $k => $v) {
                    ?>
                       <span><a href="<?php echo $v; ?>"><?php echo $k; ?></a></span>
                    <?php
                   }
                  }
                 ?>
                    <span><?php echo $fn->varv('page_heading', $fn->cms); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>
