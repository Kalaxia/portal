const toggleNotifications = () => document.querySelector('#notifications-dropdown').classList.toggle('active');

const toggleNotification = event => {
    const notification = event.currentTarget.parentNode;
    
    notification.children[1].classList.toggle('active');
    
    if (!notification.classList.contains('unread')) {
        return;
    }
    fetch('/notifications/' + notification.getAttribute('data-id') + '/read', {
        method: 'PUT',
        credentials: 'include'
    }).then(response => {
        notification.classList.remove('unread');
    });
} 