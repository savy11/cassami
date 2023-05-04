</div>
<footer class="footer">
    <form method="post" class="newsletter-banner form-validate" name="sub-frm" id="sub-frm" autocomplete="off" data-ajax="true" data-url="process" data-action="subscribe" data-recid="modal">
        <div class="container">
            <div class="row justify-content-center align-self-center h-100">
                <div class="col-12 col-xl-10">
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-8 col-lg-6 my-auto">
                            <label>Sign up For <strong>Newsletter:</strong></label>
                        </div>
                        <div class="col-12 col-md-8 col-lg-6 my-auto">
                            <div class="input-group">
                                <input type="email" name="sub[email]" id="sub-email" placeholder="Your email" class="form-control" required/>
                                <div class="input-group-append">
                                    <input type="hidden" name="token" value="<?php echo $fn->post_token(); ?>"/>
                                    <button class="btn btn-secondary" type="submit">Sign Up</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="block-navigation">
        <a href="javascript:;" class="btn btn-secondary back-to-top"><i class="fas fa-angle-up"></i></a>
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-4">
                    <div class="logo">
                        <a href="<?php echo $fn->permalink(); ?>"><img src="<?php echo $fn->permalink('assets/images/logo-light.png'); ?>" alt="<?php echo app_name; ?>"/></a>
                    </div>
                    <p class="info">
                        <?php echo $fn->company['footer_msg']; ?>
                    </p>
                    <?php if ($fn->socials) { ?>
                        <ul class="social">
                            <?php foreach ($fn->socials as $social) { ?>
                                <li><a href="<?php echo $social['url']; ?>" target="_blank" title="<?php echo $social['title']; ?>"><i class="fab <?php echo $social['class']; ?>" aria-hidden="true"></i></a></li>
                            <?php } ?>
                        </ul>
                    <?php } ?>
                </div>
                <div class="col-12 col-lg-8">
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <div class="heading">Categories</div>
                            <?php if ($fn->common['cats']) { ?>
                                <ul>
                                    <?php
                                    $i = 0;
                                    foreach ($fn->common['cats'] as $v) {
                                        if (($i + 1) % 10 == 0) {
                                            break;
                                        }
                                        ?>
                                        <li><a href="<?php echo $fn->permalink('ad-cat', $v); ?>"><?php echo $v['category_name']; ?></a></li>
                                        <?php
                                        $i++;
                                    } ?>
                                    <li><a href="<?php echo $fn->permalink('ads'); ?>">View All</a></li>
                                </ul>
                            <?php } ?>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="heading">Help</div>
                            <?php if ($fn->common['help']) { ?>
                                <ul>
                                    <li><a href="mailto:<?php echo $fn->varv('support', $fn->company['email']) ?>"><?php echo $fn->varv('support', $fn->company['email']) ?></a></li>
                                    <?php foreach ($fn->common['help'] as $v) { ?>
                                        <li><a href="<?php echo $fn->permalink($v['page_url']); ?>"><?php echo $v['page_title']; ?></a></li>
                                    <?php } ?>
                                </ul>
                            <?php } ?>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="heading">Quick Links</div>
                            <?php if ($fn->common['quick']) { ?>
                                <ul>
                                    <?php foreach ($fn->common['quick'] as $v) { ?>
                                        <li><a href="<?php echo $fn->permalink($v['page_url']); ?>"><?php echo $v['page_title']; ?></a></li>
                                    <?php } ?>
                                </ul>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="block-copyright">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <?php echo date('Y'); ?> &copy; <?php echo app_name; ?> by <a href="https://www.xamaranoict.com" target="_blank" rel="nofollow">XAMARANO CONCEPT</a>
                </div>
            </div>
        </div>
    </div>

</footer>

<?php if ($fn->company['whatsapp_no'] != '') { ?>
    <div class="wp-btn">
        <a href="https://wa.me/<?php echo $fn->company['whatsapp_no']; ?>"><i class="fab fa-whatsapp"></i></a>
    </div>
<?php } ?>
<div class="modal fade" id="modal" tabindex="-1" role="dialog"></div>