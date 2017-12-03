const toggleNotifications = () => document.querySelector('#notifications-dropdown').classList.toggle('active');

const toggleNotification = event => {
    const notification = event.currentTarget.parentNode;
    const menuBadge = document.querySelector("#notifications .badge");
    const title = document.querySelector('title');
    var nbNotifications = parseInt(menuBadge.innerText);
    
    
    notification.children[1].classList.toggle('active');
    
    if (!notification.classList.contains('unread')) {
        return;
    }
    fetch('/notifications/' + notification.getAttribute('data-id') + '/read', {
        method: 'PUT',
        credentials: 'include'
    }).then(response => {
        notification.classList.remove('unread');
        let originalTitle = title.innerText.split(' ');
        nbNotifications--;
        if (nbNotifications === 0) {
            menuBadge.remove();
            originalTitle.pop();
        } else {
            menuBadge.innerText = nbNotifications;
            originalTitle[originalTitle.length - 1] = `(${nbNotifications})`;
        }
        title.innerText = originalTitle.join(' ');
    });
} 