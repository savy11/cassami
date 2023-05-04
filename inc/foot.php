<script src="<?php echo $fn->permalink("assets/vendor/jquery/jquery.min.js"); ?>"></script>
<script src="<?php echo $fn->permalink("assets/vendor/popper.min.js"); ?>"></script>
<script src="<?php echo $fn->permalink("assets/vendor/bootstrap/js/bootstrap.min.js"); ?>"></script>

<script src="<?php echo $fn->permalink("assets/vendor/slick/slick.js"); ?>"></script>
<script src="<?php echo $fn->permalink("assets/vendor/ekko-lightbox/ekko-lightbox.js"); ?>"></script>

<script src="<?php echo $fn->permalink("assets/js/main.js"); ?>"></script>
<script type="text/javascript" src="<?php echo $fn->permalink("resources/vendor/moment/moment.js"); ?>"></script>
<script type="text/javascript" src="<?php echo $fn->permalink("resources/vendor/common.js"); ?>"></script>

<script type="text/javascript" src="<?php echo $fn->permalink("assets/js/custom.js"); ?>"></script>
<?php
 echo $fn->script;
 if ($fn->session('er')) {
  ?>
     <script type="text/javascript">
         $(function () {
             app.show_msg('<?php echo $fn->session('er', 'title'); ?>', '<?php echo $fn->session('er', 'message'); ?>', '<?php echo $fn->session('er', 'type'); ?>');
         });
     </script>
  <?php
  unset($_SESSION['er']);
 }
?>
</body>
</html>