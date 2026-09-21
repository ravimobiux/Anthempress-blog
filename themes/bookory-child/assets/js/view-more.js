document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.archives').forEach(function (archiveWidget) {
        const viewMoreButton = archiveWidget.querySelector('.view-more-archives');
        const archivesList = archiveWidget.querySelector('.archives-list');
        const archivesListFull = archiveWidget.querySelector('.archives-list-full');

        if (!viewMoreButton || !archivesList || !archivesListFull) {
            return;
        }

        viewMoreButton.addEventListener('click', function () {
            const showingFullList = archivesListFull.classList.contains('is-visible');

            archivesList.classList.toggle('is-hidden', !showingFullList);
            archivesListFull.classList.toggle('is-visible', !showingFullList);
            viewMoreButton.textContent = showingFullList ? 'View More' : 'View Less';
        });
    });
});
