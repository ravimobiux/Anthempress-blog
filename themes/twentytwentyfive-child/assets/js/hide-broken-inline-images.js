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
        var container = image.closest('figure, p');

        if (container && !container.closest('.post-featured-image')) {
            var probe = container.cloneNode(true);
            probe.querySelectorAll('img, a').forEach(function (node) {
                node.remove();
            });

            var text = (probe.textContent || '').replace(/\s+/g, ' ').trim();

            if (!text || /^(?:©|\(c\)|copyright)\s*\d{4}\b/i.test(text)) {
                container.classList.add('twentytwentyfive-child-hidden-inline-image');
                return;
            }
        }

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
