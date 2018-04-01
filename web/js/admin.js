const searchUsers = event => {
    event.preventDefault();
    let data = {};
    let username = document.querySelector('#users-search input[name="username"]').value;
    if (username !== '') {
        data.username = username;
    }
    data.roles = Array.from(document
        .querySelectorAll('#users-search input[type="checkbox"]:checked'))
        .map((node) => node.getAttribute('name'))
    ;
    
    fetch('/admin/users/search', {
        method: 'POST',
        body: JSON.stringify(data),
        credentials: 'include'
    }).then(response => response.json())
    .then(users => {
        let list = document.querySelector('#users-list > section');
        list.innerHTML = '';
        let options = {day: 'numeric', month: 'numeric', year: 'numeric', hour: "numeric", minute: "numeric"};
        
        for (let user of users) {
            let lastLogin = new Date(user.lastLogin.date);
            let element = document.createElement('div');
            element.classList.add('user');
            element.innerHTML = `<strong>${user.username}</strong><em>${user.email}</em><em>${new Intl.DateTimeFormat({}, options).format(lastLogin)}</em><em>${user.roles.join(' ')}</em>`;
            list.appendChild(element);
        }
    });
};