// Archives View More functionality
document.addEventListener('DOMContentLoaded', function() {
    const viewMoreButton = document.querySelector('.view-more-archives');
    const archivesList = document.querySelector('.archives-list');
    const archivesListFull = document.querySelector('.archives-list-full');
    
    if (viewMoreButton && archivesList && archivesListFull) {
        viewMoreButton.addEventListener('click', function() {
            // Toggle visibility
            if (archivesList.style.display === 'none') {
                // Show limited list, hide full list
                archivesList.style.display = 'block';
                archivesListFull.style.display = 'none';
                viewMoreButton.textContent = 'View More';
            } else {
                // Hide limited list, show full list
                archivesList.style.display = 'none';
                archivesListFull.style.display = 'block';
                viewMoreButton.textContent = 'View Less';
            }
        });
    }
});
