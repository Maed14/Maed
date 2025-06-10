// Sample data for users and their events
const users = [
    { id: 1, name: 'John Doe', events: [{ name: 'Concert', price: 'RM 50.00' }, { name: 'Workshop', price: 'RM 30.00' }] },
    { id: 2, name: 'Jane Smith', events: [{ name: 'Conference', price: 'RM 100.00' }] },
    { id: 3, name: 'Michael Johnson', events: [{ name: 'Festival', price: 'RM 75.00' }, { name: 'Seminar', price: 'RM 20.00' }] },
];

// Function to populate user list
function populateUserList() {
    const userList = document.getElementById('user-list');
    userList.innerHTML = ''; // Clear existing users
    users.forEach(user => {
        const userBox = document.createElement('div');
        userBox.classList.add('user-box');
        
        const userBoxContent = document.createElement('div');
        userBoxContent.classList.add('user-box-content');

        const userName = document.createElement('span');
        userName.textContent = user.name;
        userBoxContent.appendChild(userName);

        const deleteButton = document.createElement('button');
        deleteButton.classList.add('delete-button');
        deleteButton.textContent = 'Delete';
        deleteButton.addEventListener('click', (event) => {
            event.stopPropagation();
            deleteUser(user.id);
        });
        userBoxContent.appendChild(deleteButton);

        userBox.appendChild(userBoxContent);
        userBox.addEventListener('click', () => toggleEventDetails(user, userBox));
        userList.appendChild(userBox);
    });
}

// Function to delete a user
function deleteUser(userId) {
    const userIndex = users.findIndex(user => user.id === userId);
    if (userIndex !== -1) {
        users.splice(userIndex, 1);
        populateUserList(); // Refresh the user list
    }
}

// Function to toggle event details for a user
function toggleEventDetails(user, userBox) {
    // Close any open event details
    const openDetails = document.querySelector('.event-details.show');
    if (openDetails) {
        openDetails.classList.remove('show');
        openDetails.style.display = 'none';
    }

    // Check if the clicked user's details are already open
    const eventDetails = userBox.querySelector('.event-details');
    if (eventDetails && eventDetails.classList.contains('show')) {
        eventDetails.classList.remove('show');
        eventDetails.style.display = 'none';
    } else {
        // Create event details if they don't exist
        if (!eventDetails) {
            const detailsBox = document.createElement('div');
            detailsBox.classList.add('event-details');
            const detailsTitle = document.createElement('h3');
            detailsTitle.textContent = 'Event Details';
            detailsBox.appendChild(detailsTitle);
            user.events.forEach(event => {
                const eventBox = document.createElement('div');
                eventBox.classList.add('event-box');
                eventBox.textContent = `${event.name} - ${event.price}`;
                detailsBox.appendChild(eventBox);
            });
            userBox.appendChild(detailsBox);
            detailsBox.classList.add('show');
            detailsBox.style.display = 'block';
        } else {
            // Show existing event details
            eventDetails.classList.add('show');
            eventDetails.style.display = 'block';
        }
    }
}

// Initialize user list on page load
document.addEventListener('DOMContentLoaded', populateUserList);
