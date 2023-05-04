<div class="account-nav">
    <ul class="subnav justify-content-center align-self-center h-100">
        <li class="my-auto<?php echo $fn->get('for') == 'account' && $fn->get('page_url') == 'dashboard' ? ' active' : ''; ?>"><a href="<?php echo $fn->permalink('account/dashboard'); ?>">My Dashboard</a></li>
        <li class="my-auto<?php echo $fn->get('for') == 'account' && $fn->get('page_url') == 'profile' ? ' active' : ''; ?>"><a href="<?php echo $fn->permalink('account/profile'); ?>">My Profile</a></li>
        <li class="my-auto<?php echo $fn->get('for') == 'account' && $fn->get('page_url') == 'my-ads' ? ' active' : ''; ?>"><a href="<?php echo $fn->permalink('account/my-ads'); ?>">My Ads</a></li>
        <li class="my-auto<?php echo $fn->get('for') == 'account' && $fn->get('page_url') == 'favourites' ? ' active' : ''; ?>"><a href="<?php echo $fn->permalink('account/favourites'); ?>">Favourites</a></li>
        <?php /*<li class="my-auto<?php echo $fn->get('for') == 'account' && $fn->get('page_url') == 'inbox' ? ' active' : ''; ?>"><a href="<?php echo $fn->permalink('account/inbox'); ?>">Inbox</a></li>*/ ?>
        <li class="my-auto"><a href="<?php echo $fn->permalink('logout'); ?>">Logout</a></li>
    </ul>
</div>
<div class="account-nav-mobile">
    <ul>
        <li class="<?php echo $fn->get('for') == 'account' && $fn->get('page_url') == 'dashboard' ? ' active' : ''; ?>"><a href="javascript:;" data-toggle="collapse" data-target="#menu-options" aria-expanded="false" class="collapsed">My Dashboard</a></li>
        <li class="<?php echo $fn->get('for') == 'account' && $fn->get('page_url') == 'profile' ? ' active' : ''; ?>"><a href="javascript:;" data-toggle="collapse" data-target="#menu-options" aria-expanded="false" class="collapsed">My Profile</a></li>
        <li class="<?php echo $fn->get('for') == 'account' && $fn->get('page_url') == 'my-ads' ? ' active' : ''; ?>"><a href="javascript:;" data-toggle="collapse" data-target="#menu-options" aria-expanded="false" class="collapsed">My Ads</a></li>
        <li class="<?php echo $fn->get('for') == 'account' && $fn->get('page_url') == 'favourites' ? ' active' : ''; ?>"><a href="javascript:;" data-toggle="collapse" data-target="#menu-options" aria-expanded="false" class="collapsed">Favourites</a></li>
        <?php /*<li class="<?php echo $fn->get('for') == 'account' && $fn->get('page_url') == 'inbox' ? ' active' : ''; ?>"><a href="javascript:;" data-toggle="collapse" data-target="#menu-options" aria-expanded="false" class="collapsed">Inbox</a></li>*/ ?>
    </ul>
    <div id="menu-options" class="menu-options collapse" aria-expanded="false">
        <ul>
            <li class="<?php echo $fn->get('for') == 'account' && $fn->get('page_url') == 'dashboard' ? ' active' : ''; ?>"><a href="<?php echo $fn->permalink('account/dashboard'); ?>">My Dashboard</a></li>
            <li class="<?php echo $fn->get('for') == 'account' && $fn->get('page_url') == 'profile' ? ' active' : ''; ?>"><a href="<?php echo $fn->permalink('account/profile'); ?>">My Profile</a></li>
            <li class="<?php echo $fn->get('for') == 'account' && $fn->get('page_url') == 'my-ads' ? ' active' : ''; ?>"><a href="<?php echo $fn->permalink('account/my-ads'); ?>">My Ads</a></li>
            <li class="<?php echo $fn->get('for') == 'account' && $fn->get('page_url') == 'favourites' ? ' active' : ''; ?>"><a href="<?php echo $fn->permalink('account/favourites'); ?>">Favourites</a></li>
            <?php /*<li class="<?php echo $fn->get('for') == 'account' && $fn->get('page_url') == 'inbox' ? ' active' : ''; ?>"><a href="<?php echo $fn->permalink('account/inbox'); ?>">Inbox</a></li>*/ ?>
            <li><a href="<?php echo $fn->permalink('logout'); ?>">Logout</a></li>
        </ul>
    </div>
</div>