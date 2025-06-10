// Event_Search.js

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const stateSelect = document.getElementById('state-select');
    const resultsSection = document.querySelector('.results-section');

    // Event listener for search button
    document.querySelector('.search-bar button').addEventListener('click', function() {
        const keyword = searchInput.value.trim();
        const state = stateSelect.value;

        // AJAX request to PHP script
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'search_events.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                const response = JSON.parse(xhr.responseText);
                displayResults(response);
            }
        };
        xhr.send(`keyword=${keyword}&state=${state}`);
    });

    // Function to display filtered results
    function displayResults(events) {
        resultsSection.innerHTML = ''; // Clear previous results
        events.forEach(function(event) {
            const eventDiv = document.createElement('div');
            eventDiv.classList.add('event');

            const eventInfo = document.createElement('div');
            eventInfo.classList.add('event-info');
            eventInfo.innerHTML = `
                <h3>${event.Event_Title}</h3>
                <p>${event.Event_Date}</p>
                <p>${event.Event_Location}</p>
            `;

            const eventImage = document.createElement('div');
            eventImage.classList.add('event-image');
            eventImage.innerHTML = `
                <img src="${event.Event_Image}" alt="${event.Event_Title}">
            `;

            eventDiv.appendChild(eventInfo);
            eventDiv.appendChild(eventImage);
            resultsSection.appendChild(eventDiv);
        });
    }
});
