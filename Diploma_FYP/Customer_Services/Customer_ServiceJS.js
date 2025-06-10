document.addEventListener('DOMContentLoaded', function() {
    const userList = document.getElementById('userList');
    const messagesContainer = document.getElementById('messages');
    const messageInput = document.getElementById('messageInput');
    const sendButton = document.getElementById('sendButton');

    const users = [
        { id: 1, name: 'Jack' },
        { id: 2, name: 'John' },
        { id: 3, name: 'Joon Li' }
    ];

    const messages = {
        1: ['User 1: Hi, I need help with my account.'],
        2: ['User 2: How can I reset my password?'],
        3: ['User 3: Where can I find my invoices?']
    };

    function renderUserList() {
        users.forEach(user => {
            const li = document.createElement('li');
            li.textContent = user.name;
            li.dataset.userId = user.id;
            userList.appendChild(li);
        });
    }

    function renderMessages(userId) {
        messagesContainer.innerHTML = '';
        messages[userId].forEach(message => {
            const p = document.createElement('p');
            p.textContent = message;
            messagesContainer.appendChild(p);
        });
    }

    userList.addEventListener('click', function(event) {
        if (event.target.tagName === 'LI') {
            const userId = event.target.dataset.userId;

            // Remove active class from all users
            document.querySelectorAll('.user-list ul li').forEach(li => {
                li.classList.remove('active');
            });

            // Add active class to the selected user
            event.target.classList.add('active');

            renderMessages(userId);
        }
    });

    sendButton.addEventListener('click', function() {
        const selectedUser = document.querySelector('.user-list ul li.active');
        if (selectedUser) {
            const userId = selectedUser.dataset.userId;
            const message = messageInput.value;
            if (message) {
                messages[userId].push(`Admin: ${message}`);
                renderMessages(userId);
                messageInput.value = '';
            }
        } else {
            alert('Please select a user to send a message to.');
        }
    });

    renderUserList();
});
