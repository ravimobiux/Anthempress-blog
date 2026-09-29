(function () {
    'use strict';

    var inlineImageSelector = [
        '.wp-block-post-content img',
        '.entry-content img',
        '.post-content img'
    ].join(', ');

    function hideBrokenImage(image) {
        var figure = image.closest('figure.wp-block-image, figure.wp-block-gallery');
        var link = image.closest('a');

        image.classList.add('twentytwentyfive-child-broken-inline-image');

        if (figure && figure.querySelectorAll('img').length === 1) {
            figure.classList.add('twentytwentyfive-child-hidden-inline-image');
        }

        if (link && link.querySelectorAll('img').length === 1) {
            link.classList.add('twentytwentyfive-child-hidden-inline-image');
        }
    }

    function watchImage(image) {
        if (image.dataset.twentyTwentyFiveChildWatched) {
            return;
        }

        image.dataset.twentyTwentyFiveChildWatched = '1';
        image.addEventListener('error', function () {
            hideBrokenImage(image);
        }, { once: true });

        if (image.complete && image.naturalWidth === 0) {
            hideBrokenImage(image);
        }
    }

    function scanInlineImages() {
        document.querySelectorAll(inlineImageSelector).forEach(watchImage);
    }

    document.addEventListener('DOMContentLoaded', scanInlineImages);
}());
