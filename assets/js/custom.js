app.share_popup = function () {
    var sp = $('.share-popup');
    if (!sp.length > 0)
        return;
    sp.unbind('click');
    sp.click(function (e) {
        e.preventDefault();
        var that = $(this);
        popup(that.attr('href'), that.attr('title'), 620, 280);
        return false;
    });

    function popup(url, title, w, h) {
        var d_left = (window.screenLeft != undefined ? window.screenLeft : screen.left),
            d_top = (window.screenTop != undefined ? window.screenTop : screen.top),
            width = (window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width),
            height = (window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height),
            left = ((width / 2) - (w / 2)) + d_left,
            top = ((height / 2) - (h / 2)) + d_top,
            new_window = window.open(url, title, 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
        if (window.focus) {
            new_window.focus();
        }
    }
}

app.blog_commenting = function () {
    var frm = $('#comment-frm'),
        btnr = $('.btn-reply'),
        btncr = $('.btn-cancel-reply'),
        header = 0;
    if (!btnr.length > 0)
        return;

    btnr.unbind('click');
    btnr.click(function () {
        var that = $(this),
            data = that.data();
        frm.find('input#comment-parent').val(data.parent);
        btncr.removeClass('d-none').data('id', data.id);

        var top = (frm.offset().top - 70);
        app.scroll(top);
    });

    btncr.unbind('click');
    btncr.click(function () {
        var that = $(this),
            data = that.data();
        that.addClass('d-none');
        frm.find('input#comment-parent').val(0);

        var top = ($('#comment-' + data.id).offset().top);
        app.scroll(top);
    });

}

app.scroll = function (top) {
    $('html,body').animate({
        scrollTop: top
    }, 'slow');
}

app.uploader = function () {
    var u = $('.uploader');
    if (!u.length > 0)
        return;
    var l = new loader();
    l.require(['resources/vendor/uploader/uploads.js'], function () {
        u.each(function () {
            var that = $(this),
                data = that.data(),
                uploader = new qq.FileUploader({
                    element: that.get(0),
                    uploadButtonText: (app.checkdata(data, 'uploadButtonText') ? data.uploadButtonText : 'Select Pictures'),
                    listElement: document.getElementById(data.listId),
                    action: hostname + 'uploads?token=' + token + '&rnd=' + Math.random() + '&type=' + data.type,
                    multiple: true,
                    hideShowDropArea: false,
                    template: '<div class="qq-uploader">' +
                        '<div class="qq-upload-drop-area qq-upload-button"><div class="drop-border"><i class="fa fa-upload"></i> <span>Drag & Drop or Click to Browse</span></div></div>' +
                        '</div>',
                });
        });
    }, true);
}
app.up_default = function () {
    var check_it = $('.check-it li div.attachment');
    if (!check_it.length > 0)
        return;
    var checked = $('.check-it li input:checked').length,
        data = $('.check-it').data();
    check_it.each(function () {
        var that = $(this);
        that.unbind('click');
        that.click(function () {
            var t = $(this),
                parent = t.parent('li');
            if (parent.hasClass('selected')) {
                checked--;
                parent.removeClass('selected').find('input[type="checkbox"]').prop('checked', false);
            } else {
                if (checked == data.checkedLimit) {
                    app.show_msg('Limit exceed!', 'You can\'t set more than ' + data.checkedLimit + ' images as default.', 'error');
                    return false;
                }
                checked++;
                parent.addClass('selected').find('input[type="checkbox"]').prop('checked', true);
            }
        });
    });
    if (checked == 0) {
        $('.check-it li:first .attachment').click();
    }
}

app.single_slider = function () {
    $('.slider-single').each(function () {
        $(this).slick({
            fade: ($(this).hasClass('animation-slide') ? false : true),
            autoplay: true,
            lazyLoad: 'ondemand',
            speed: 400,
            dots: ($(this).hasClass('control-nav') ? true : false),
            autoplaySpeed: $(this).data('time') || 5000,
            adaptiveHeight: ($(this).hasClass('height-auto') ? true : false),
            arrows: ($(this).hasClass('dir-nav') ? true : false)
        });

        if ($(this).hasClass('control-nav')) {
            $(this).on('beforeChange', function (event, slick, currentSlide, nextSlide) {
                $(".control-nav .slick-dots li").removeClass("slick-active");
                $(".control-nav .slick-dots li button").attr("aria-pressed", "false").focus(function () {
                    this.blur();
                });

            });
        }
    });
}

app.ajax_init = function () {
    app.c_ajax_init();
    app.share_popup();
};

$(function () {
    app.c_init();
    app.share_popup();
    app.blog_commenting();
    app.uploader();
    app.up_default();
    app.single_slider();
    $('[data-toggle="tooltip"]').tooltip();
});


