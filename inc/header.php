<div class="page-loader loading">
    <div class="loader">
        <i class="fas fa-spinner fa-spin" aria-hidden="true"></i>
    </div>
</div>
<div class="loader">
    <div class="spinner">
        <img src="<?php echo $fn->permalink('assets/images/loader.svg'); ?>" alt="Ajax Loader" style="width: 150px; height: 150px"/>
    </div>
</div>
<div class="overlay-nav"></div>

<header class="header<?php echo stripos($fn->server('SCRIPT_FILENAME'), 'index') !== false ? ' floating' : ''; ?>">
    <div class="top-bar">
        <div class="container">
            <div class="row justify-content-center align-self-center h-100">
                <div class="col-md-6 col-12 my-auto">
                    <ul>
                        <li><i class="fa fa-map-marker-alt"></i> <span class="d-none d-sm-inline-block"><?php echo $fn->varv('country', $fn->ip); ?></span></li>
                        <li><a href="mailto:<?php echo $fn->varv('company', $fn->company['email']); ?>"><i class="fa fa-envelope"></i> <span class="d-none d-sm-inline-block"><?php echo $fn->varv('company', $fn->company['email']); ?></span></a></li>
                        <li><a href="tel:<?php echo $fn->varv('phone_no', $fn->company); ?>"><i class="fa fa-phone"></i> <span class="d-none d-sm-inline-block"><?php echo $fn->varv('phone_no', $fn->company); ?></span></a></li>
                    </ul>
                </div>
                <div class="col-md-6 col-12 my-auto text-right">
                    <ul>
                        <?php if ($fn->validate_login()) { ?>
                            <li><a href="<?php echo $fn->permalink('account/dashboard'); ?>"><i class="fa fa-user"></i> <span class="d-none d-sm-inline-block">My Dashboard</span></a></li>
                            <li><a href="<?php echo $fn->permalink('logout'); ?>"><i class="fa fa-sign-out-alt"></i> <span class="d-none d-sm-inline-block">Logout</span></a></li>
                        <?php } else { ?>
                            <li><a href="<?php echo $fn->permalink('login'); ?>"><i class="fa fa-sign-in-alt"></i> <span class="d-none d-sm-inline-block">Login</span></a></li>
                            <li><a href="<?php echo $fn->permalink('register'); ?>"><i class="fa fa-user-plus"></i> <span class="d-none d-sm-inline-block">Register</span></a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="main-menu-wrapper">
        <nav>
            <div class="main-menu">
                <div class="container">
                    <div class="row">
                        <div class="col-7 col-sm-7 col-lg-2">
                            <div class="hamburger"><a href="<?php echo $fn->permalink(); ?>"><span><span></span></span></a></div>
                            <div class="logo">
                                <a href="<?php echo $fn->permalink(); ?>">
                                    <img src="<?php echo $fn->permalink('assets/images/logo.png'); ?>" alt="<?php echo app_name; ?>"/>
                                </a>
                            </div>
                            <ul class="main-nav small-screen">
                                <li<?php echo stripos($fn->server('SCRIPT_FILENAME'), 'index') !== false ? ' class="active"' : ''; ?>><a href="<?php echo $fn->permalink(); ?>">Home</a></li>
                                <li<?php echo $fn->get('page_url') == 'ads' || stripos($fn->server('SCRIPT_FILENAME'), 'ad/') !== false ? ' class="active"' : ''; ?>><a href="<?php echo $fn->permalink('ads'); ?>">Ads</a>
                                    <?php if ($fn->common['cats']) { ?>
                                        <ul>
                                            <?php foreach ($fn->common['cats'] as $k => $v) { ?>
                                                <li><a href="<?php echo $fn->permalink('ad-cat', $v); ?>"><?php echo $v['category_name']; ?></a></li>
                                            <?php } ?>
                                        </ul>
                                    <?php } ?>
                                </li>
                                <li<?php echo $fn->get('for') == 'account' ? ' class="active"' : ''; ?>><a href="<?php echo $fn->permalink('account'); ?>">My account</a>
                                    <ul>
                                        <?php if ($fn->validate_login()) { ?>
                                            <li><a href="<?php echo $fn->permalink('account/dashboard'); ?>">My Dashboard</a></li>
                                            <li><a href="<?php echo $fn->permalink('account/profile'); ?>">My Profile</a></li>
                                            <li><a href="<?php echo $fn->permalink('account/my-ads'); ?>">My Ads</a></li>
                                            <li><a href="<?php echo $fn->permalink('account/favourites'); ?>">Favorites</a></li>
                                            <?php /*<li><a href="<?php echo $fn->permalink('account/inbox'); ?>">Inbox</a></li>*/ ?>
                                            <li><a href="<?php echo $fn->permalink('logout'); ?>">Logout</a></li>
                                        <?php } else { ?>
                                            <li><a href="<?php echo $fn->permalink('login'); ?>">Login</a></li>
                                            <li><a href="<?php echo $fn->permalink('register'); ?>">Register</a></li>
                                            <li><a href="<?php echo $fn->permalink('forgot'); ?>">Forgot Password</a></li>
                                        <?php } ?>
                                    </ul>
                                </li>
                                <li<?php echo $fn->get('page_url') == 'blog' || stripos($fn->server('SCRIPT_FILENAME'), 'blog/') !== false ? ' class="active"' : ''; ?>><a href="<?php echo $fn->permalink('blog'); ?>">Our Blog</a></li>
                                <li<?php echo $fn->get('page_url') == 'contact-us' ? ' class="active"' : ''; ?>><a href="<?php echo $fn->permalink('contact-us'); ?>">Contact</a></li>
                            </ul>
                        </div>
                        <div class="col-12 col-sm-1 col-lg-8 d-none d-sm-block">
                            <ul class="main-nav">
                                <li <?php echo stripos($fn->server('SCRIPT_FILENAME'), 'index') !== false ? ' class="active"' : ''; ?>><a href="<?php echo $fn->permalink(); ?>">Home</a></li>
                                <li<?php echo $fn->get('page_url') == 'ads' || stripos($fn->server('SCRIPT_FILENAME'), 'ad_detail') !== false ? ' class="active"' : ''; ?>><a href="<?php echo $fn->permalink('ads'); ?>">Ads</a>
                                    <?php if ($fn->common['cats']) { ?>
                                        <ul>
                                            <?php foreach ($fn->common['cats'] as $k => $v) { ?>
                                                <li><a href="<?php echo $fn->permalink('ad-cat', $v); ?>"><?php echo $v['category_name']; ?></a></li>
                                            <?php } ?>
                                        </ul>
                                    <?php } ?>
                                </li>
                                <li<?php echo $fn->get('for') == 'account' || in_array($fn->get('page_url'), ['login', 'register', 'forgot']) ? ' class="active"' : ''; ?>><a href="<?php echo $fn->permalink('account'); ?>">My account</a>
                                    <ul>
                                        <?php if ($fn->validate_login()) { ?>
                                            <li><a href="<?php echo $fn->permalink('account/dashboard'); ?>">My Dashboard</a></li>
                                            <li><a href="<?php echo $fn->permalink('account/profile'); ?>">My Profile</a></li>
                                            <li><a href="<?php echo $fn->permalink('account/my-ads'); ?>">My Ads</a></li>
                                            <li><a href="<?php echo $fn->permalink('account/favourites'); ?>">Favorites</a></li>
                                            <?php /*<li><a href="<?php echo $fn->permalink('account/inbox'); ?>">Inbox</a></li>*/ ?>
                                            <li><a href="<?php echo $fn->permalink('logout'); ?>">Logout</a></li>
                                        <?php } else { ?>
                                            <li><a href="<?php echo $fn->permalink('login'); ?>">Login</a></li>
                                            <li><a href="<?php echo $fn->permalink('register'); ?>">Register</a></li>
                                            <li><a href="<?php echo $fn->permalink('forgot'); ?>">Forgot Password</a></li>
                                        <?php } ?>
                                    </ul>

                                </li>
                                <li<?php echo $fn->get('page_url') == 'blog' || stripos($fn->server('SCRIPT_FILENAME'), 'blog_detail') !== false ? ' class="active"' : ''; ?>><a href="<?php echo $fn->permalink('blog'); ?>">Our Blog</a></li>
                                <li<?php echo $fn->get('page_url') == 'contact-us' ? ' class="active"' : ''; ?>><a href="<?php echo $fn->permalink('contact-us'); ?>">Contact</a></li>
                            </ul>
                        </div>
                        <div class="col-5 col-sm-4 col-lg-2">
                            <div class="right-block text-right">
                                <a href="<?php echo $fn->permalink('post-ad'); ?>" class="btn btn-secondary"><i class="fa fa-plus" aria-hidden="true"></i><span>Post an Ad</span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</header>
<div class="content">