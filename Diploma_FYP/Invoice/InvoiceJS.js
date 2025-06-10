document.addEventListener("DOMContentLoaded", function() {
    const accordionButtons = document.querySelectorAll('.accordion-button');

    accordionButtons.forEach(button => {
        button.addEventListener('click', function() {
            const content = this.nextElementSibling;

            // Toggle the "active" class to highlight the button
            this.classList.toggle('active');

            // Check if the accordion content is already open
            if (content.style.maxHeight) {
                // Close the accordion content
                content.style.maxHeight = null;
            } else {
                // Open the accordion content
                content.style.maxHeight = content.scrollHeight + 'px';
            }
        });
    });
});
