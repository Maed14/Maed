// Admin_dashboard_JS.js

// Wait for the DOM to fully load before executing any JavaScript
document.addEventListener("DOMContentLoaded", function() {
    // Select the button element
    const postButton = document.getElementById("postButton");

    // Attach a click event listener to the button
    postButton.addEventListener("click", function() {
        // Select the textarea element
        const postMessage = document.getElementById("postMessage");

        // Get the value entered in the textarea
        const message = postMessage.value.trim();

        // Select the success and error message elements
        const successMessage = document.getElementById("successMessage");
        const errorMessage = document.getElementById("errorMessage");

        // Check if the message is not empty
        if (message !== "") {
            // Show the success message
            successMessage.style.display = "flex";
            errorMessage.style.display = "none";

            // Hide the success message after 3 seconds
            setTimeout(function() {
                successMessage.style.display = "none";
            }, 3000);

            // Optionally, clear the textarea after posting
            postMessage.value = "";
        } else {
            // Show the error message
            errorMessage.style.display = "flex";
            successMessage.style.display = "none";

            // Hide the error message after 3 seconds
            setTimeout(function() {
                errorMessage.style.display = "none";
            }, 3000);
        }
    });
});
