<?php
 require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'autoload.php';
 $fn = new controllers\controller;
 $fn->cms_page('404');
 include_once app_path . 'inc' . ds . 'head.php';
 include_once app_path . 'inc' . ds . 'header.php';
?>
    <section class="bkg-light medium">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="boxed-container">
                        <div class="block">
                            <div class="row">
                                <div class="col-12">
                                    <div class="not-found">
                                        <div class="info">
                                            <h3>NOT FOUND (#404)</h3>
                                            <span>The requested page does not exist.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

<?php /*<section class="listing-products grid-4">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-header text-center">
                        <h3>Promoted Ads</h3>
                    </div>
                </div>
            </div>
            <div class="row row-list">
                <div class="col-12 col-md-6 col-lg-3 item">
                    <div class="item-wrapper">
                        <div class="image-wrapper">
                            <div class="promoted">
                                <span>Promoted</span>
                            </div>
                            <a href="view-listing.html" class="image">
                                <img class="lazyloaded" src="images/products/1000X675_vespa.jpg" data-src="#" alt="">
                            </a>
                            <div class="quick-actions">
                                <a href="404.html#quick-view" data-toggle="modal" class="btn btn-primary btn-circle"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                <a href="404.html#" class="btn btn-primary btn-circle add-to-fav"><i class="fa fa-heart" aria-hidden="true"></i></a>
                            </div>
                        </div>
                        <div class="info-wrapper">
                            <div class="info">
                                <div class="category">Auto, Moto</div>
                                <a href="view-listing.html" class="name">Vespa Moto</a>
                                <span class="price">$419.99</span>
                                <span class="location"><i class="fa fa-map-marker-alt" aria-hidden="true"></i> California, United States</span>
                                <span class="posted"><i class="far fa-clock" aria-hidden="true"></i> Posted 2 hours ago</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3 item">
                    <div class="item-wrapper">
                        <div class="image-wrapper">
                            <div class="promoted">
                                <span>Promoted</span>
                            </div>
                            <a href="view-listing.html" class="image">
                                <img class="lazyloaded" src="images/products/1000X675_samsung.jpg" data-src="#" alt="">
                            </a>
                            <div class="quick-actions">
                                <a href="404.html#quick-view" data-toggle="modal" class="btn btn-primary btn-circle"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                <a href="404.html#" class="btn btn-primary btn-circle add-to-fav"><i class="fa fa-heart" aria-hidden="true"></i></a>
                            </div>
                        </div>
                        <div class="info-wrapper">
                            <div class="info">
                                <div class="category">Electronics</div>
                                <a href="view-listing.html" class="name">Samsung Galaxy S9 </a>
                                <span class="price">$400</span>
                                <span class="location"><i class="fa fa-map-marker-alt" aria-hidden="true"></i> California, United States</span>
                                <span class="posted"><i class="far fa-clock" aria-hidden="true"></i> Posted Yesterday</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3 item">
                    <div class="item-wrapper">
                        <div class="image-wrapper">
                            <div class="promoted">
                                <span>Promoted</span>
                            </div>
                            <a href="view-listing.html" class="image">
                                <img class="lazyloaded" src="images/products/1000X675_vacation.jpg" data-src="#" alt="">
                            </a>
                            <div class="quick-actions">
                                <a href="404.html#quick-view" data-toggle="modal" class="btn btn-primary btn-circle"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                <a href="404.html#" class="btn btn-primary btn-circle add-to-fav"><i class="fa fa-heart" aria-hidden="true"></i></a>
                            </div>
                        </div>
                        <div class="info-wrapper">
                            <div class="info">
                                <div class="category">Vacations</div>
                                <a href="view-listing.html" class="name">Maldive, 7 days package</a>
                                <span class="price">$5,500</span>
                                <span class="location"><i class="fa fa-map-marker-alt" aria-hidden="true"></i> California, United States</span>
                                <span class="posted"><i class="far fa-clock" aria-hidden="true"></i> Posted Yesterday</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3 item">
                    <div class="item-wrapper">
                        <div class="image-wrapper">
                            <div class="promoted">
                                <span>Promoted</span>
                            </div>
                            <a href="view-listing.html" class="image">
                                <img class="lazyloaded" src="images/products/1000X675_laptop.jpg" data-src="#" alt="">
                            </a>
                            <div class="quick-actions">
                                <a href="404.html#quick-view" data-toggle="modal" class="btn btn-primary btn-circle"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                <a href="404.html#" class="btn btn-primary btn-circle add-to-fav"><i class="fa fa-heart" aria-hidden="true"></i></a>
                            </div>
                        </div>
                        <div class="info-wrapper">
                            <div class="info">
                                <div class="category">Electronics</div>
                                <a href="view-listing.html" class="name">Apple Macbook pro 13"</a>
                                <span class="price">$212.00</span>
                                <span class="location"><i class="fa fa-map-marker-alt" aria-hidden="true"></i> California, United States</span>
                                <span class="posted"><i class="far fa-clock" aria-hidden="true"></i> Posted 5 hours ago</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3 item">
                    <div class="item-wrapper">
                        <div class="image-wrapper">
                            <div class="promoted">
                                <span>Promoted</span>
                            </div>
                            <a href="view-listing.html" class="image">
                                <img class="lazyloaded" src="images/products/1000X675_iphone.jpg" data-src="#" alt="">
                            </a>
                            <div class="quick-actions">
                                <a href="404.html#quick-view" data-toggle="modal" class="btn btn-primary btn-circle"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                <a href="404.html#" class="btn btn-primary btn-circle add-to-fav"><i class="fa fa-heart" aria-hidden="true"></i></a>
                            </div>
                        </div>
                        <div class="info-wrapper">
                            <div class="info">
                                <div class="category">Electronics</div>
                                <a href="view-listing.html" class="name">Apple iphone 6S</a>
                                <span class="price">$435.00</span>
                                <span class="location"><i class="fa fa-map-marker-alt" aria-hidden="true"></i> Washington, Seattle</span>
                                <span class="posted"><i class="far fa-clock" aria-hidden="true"></i> Posted 2 days ago</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3 item">
                    <div class="item-wrapper">
                        <div class="image-wrapper">
                            <div class="promoted">
                                <span>Promoted</span>
                            </div>
                            <a href="view-listing.html" class="image">
                                <img class="lazyloaded" src="images/products/1000X675_nikon.jpg" data-src="#" alt="">
                            </a>
                            <div class="quick-actions">
                                <a href="404.html#quick-view" data-toggle="modal" class="btn btn-primary btn-circle"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                <a href="404.html#" class="btn btn-primary btn-circle add-to-fav"><i class="fa fa-heart" aria-hidden="true"></i></a>
                            </div>
                        </div>
                        <div class="info-wrapper">
                            <div class="info">
                                <div class="category">Electronics</div>
                                <a href="view-listing.html" class="name">Nikon DSLR Camera</a>
                                <span class="price">$1,500</span>
                                <span class="location"><i class="fa fa-map-marker-alt" aria-hidden="true"></i> Nevada, Las Vegas</span>
                                <span class="posted"><i class="far fa-clock" aria-hidden="true"></i> Posted on 11.06.2018</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3 item">
                    <div class="item-wrapper">
                        <div class="image-wrapper">
                            <div class="promoted">
                                <span>Promoted</span>
                            </div>
                            <a href="view-listing.html" class="image">
                                <img class="lazyloaded" src="images/products/1000X675_house.jpg" data-src="#" alt="">
                            </a>
                            <div class="quick-actions">
                                <a href="404.html#quick-view" data-toggle="modal" class="btn btn-primary btn-circle"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                <a href="404.html#" class="btn btn-primary btn-circle add-to-fav"><i class="fa fa-heart" aria-hidden="true"></i></a>
                            </div>
                        </div>
                        <div class="info-wrapper">
                            <div class="info">
                                <div class="category">Real Estate</div>
                                <a href="view-listing.html" class="name">Villa on the beach</a>
                                <span class="price">$358,000</span>
                                <span class="location"><i class="fa fa-map-marker-alt" aria-hidden="true"></i> Malibu, California</span>
                                <span class="posted"><i class="far fa-clock" aria-hidden="true"></i> Posted on 10.27.2018</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3 item">
                    <div class="item-wrapper">
                        <div class="image-wrapper">
                            <div class="promoted">
                                <span>Promoted</span>
                            </div>
                            <a href="view-listing.html" class="image">
                                <img class="lazyloaded" src="images/products/1000X675_pants.jpg" data-src="#" alt="">
                            </a>
                            <div class="quick-actions">
                                <a href="404.html#quick-view" data-toggle="modal" class="btn btn-primary btn-circle"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                <a href="404.html#" class="btn btn-primary btn-circle add-to-fav"><i class="fa fa-heart" aria-hidden="true"></i></a>
                            </div>
                        </div>
                        <div class="info-wrapper">
                            <div class="info">
                                <div class="category">Fashion</div>
                                <a href="view-listing.html" class="name">Lee Cooper Pants</a>
                                <span class="price">$180</span>
                                <span class="location"><i class="fa fa-map-marker-alt" aria-hidden="true"></i> Nevada, Las Vegas</span>
                                <span class="posted"><i class="far fa-clock" aria-hidden="true"></i> Posted on 11.03.2018</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>*/ ?>
<?php
 include_once app_path . 'inc' . ds . 'footer.php';
 include_once app_path . 'inc' . ds . 'foot.php';
?>