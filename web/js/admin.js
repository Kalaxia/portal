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

const createMachine = event => {
    event.preventDefault();
    let form = document.querySelector('#machine-form');
    if (form.classList.contains('valid') === false) {
        return;
    }
    let formData = new FormData(form.querySelector('form'));
    formData.append('is_local', form.querySelector('form > button:nth-child(3)').classList.contains('enabled'));
    
    return fetch('/admin/machines', {
        method: 'POST',
        body: formDataToJson(formData),
        credentials: 'include'
    }).then(response => response.json())
    .then(machine => {
        addMachine(machine);
        resetMachineForm(); 
    }).catch(err => console.log(err));
};

const addMachine = machine => {
    let list = document.querySelector('#server-form > form > #informations > header');
    
    let element = document.createElement('div');
    element.setAttribute('data-id', machine.id);
    element.addEventListener('click', selectMachine);
    element.innerHTML = 
        `<span>${ machine.id }</span>
        <span>${ machine.name }</span>
        <span class="${ machine.isLocal === false ? 'disabled' : '' }">Machine locale</span>`
    ;
    list.append(element);
};

const selectMachine = event => {
    let previous = document.querySelector('#server-form > form > #informations > header > .selected');
    if (previous !== null) {
        previous.classList.remove('selected');
    }
    event.currentTarget.classList.add('selected');
};
        
const resetMachineForm = () => {
    document.querySelector('#machine-form').classList.remove('valid');
    document.querySelector('#machine-form > form').reset();
    document.querySelector('#machine-form > form > button:nth-child(3)').classList.add('enabled');
    checkPublicKey('');
};

const toggleLocalMachine = event => {
    event.currentTarget.classList.toggle('enabled');
    document.querySelector('#machine-form > form > input[name="host"]').classList.toggle('display');
    checkMachineForm();
};

const openPublicKeyModal = () => {
    document.querySelector('#public-key-overlay').classList.add('visible');
};

const closePublicKeyModal = event => {
    if (event.target.id !== 'public-key-overlay') {
        return;
    }
    document.querySelector('#public-key-overlay').classList.remove('visible');
};

const setPublicKey = () => {
    let overlay = document.querySelector('#public-key-overlay');
    let publicKey = overlay.querySelector('section > textarea').value.trim();
    document.querySelector('#machine-form input[name="public_key"]').value = publicKey;
    overlay.classList.remove('visible');
    
    checkPublicKey(publicKey);
    checkMachineForm();
};

const extractPublicKeyHex = key => unescape(encodeURIComponent(
        key.replace("-----BEGIN PUBLIC KEY-----\n", '').replace("\n-----END PUBLIC KEY-----", ''))
    )
    .split('')
    .map(v => v.charCodeAt(0).toString(16))
    .join('')
;

const checkPublicKey = publicKey => {
    let keyContainer = document.querySelector('#machine-form > form > button:nth-child(5)');
    keyContainer.classList.remove('enabled', 'error');
    let keyIcon = keyContainer.querySelector('img');
    let pathParts = keyIcon.src.split('/');
    let path = '';
    let state = '';
    
    if (publicKey.length === 0) {
        path = 'disabled_key.svg';
        state = 'disabled';
    } else if (publicKey.indexOf('BEGIN PUBLIC KEY') === -1 || publicKey.indexOf('END PUBLIC KEY') === -1 || publicKey.length !== 799) {
        keyContainer.classList.add('error');
        path = 'error_key.svg';
        state = 'error';
    } else {
        keyContainer.classList.add('enabled');
        path = 'key.svg';
        state = 'success';
    }
    
    pathParts[pathParts.length - 1] = path;
    keyIcon.src = pathParts.join('/');
    displayPublicKey(publicKey, state);
};

const displayPublicKey = (publicKey, state) => {
    let hexKey = extractPublicKeyHex(publicKey);
    
    let firstKeyContainer = document.querySelector('#machine-form > .key-container:first-child');
    firstKeyContainer.innerHTML = '';
    let secondKeyContainer = document.querySelector('#machine-form > .key-container:nth-child(3)');
    secondKeyContainer.innerHTML = '';
    
    if (state === 'disabled') {
        return;
    } else if (state === 'error') {
        firstKeyContainer.classList.add('error');
        secondKeyContainer.classList.add('error');
    } else {
        firstKeyContainer.classList.remove('error');
        secondKeyContainer.classList.remove('error');
    }
    
    for (let i = 0, charsLength = hexKey.length; i < charsLength && i < 100; i += 2) {
        let element = document.createElement('span');
        element.innerHTML = hexKey.substring(i, i + 2);
        firstKeyContainer.append(element);
    }
    for (let i = 100, charsLength = hexKey.length; i < charsLength && i < 200; i += 2) {
        let element = document.createElement('span');
        element.innerHTML = hexKey.substring(i, i + 2);
        secondKeyContainer.append(element);
    }
};

const checkMachineForm = () => {
    let formData = new FormData(document.querySelector('#machine-form > form'));
    let isPublicKeyValid = document.querySelector('#machine-form > form > button:nth-child(5)').classList.contains('enabled');
    let isLocal = document.querySelector('#machine-form > form > button:nth-child(3)').classList.contains('enabled');
    
    if (formData.get('name').length > 0 && isPublicKeyValid === true && (isLocal === true || formData.get('host').length > 0)) {
        document.querySelector('#machine-form').classList.add('valid');
        document.querySelector('#machine-form > form > button:nth-child(5) > img').src = '/images/icons/valid_key.svg';
    } else {
        document.querySelector('#machine-form').classList.remove('valid');
    }
};

const selectFaction = event => event.currentTarget.classList.toggle('selected');

const createServer = event => {
    event.preventDefault();
    let server = formDataToObject(new FormData(event.currentTarget));
    server.factions = new Array();
    server.machine = parseInt(document.querySelector('#server-form > form > #informations > header > div.selected').getAttribute('data-id'));
    
    for (let faction of document.querySelectorAll('#server-form > form > #informations > section > div > .faction.selected')) {
        server.factions.push(parseInt(faction.getAttribute('data-id')));
    }
    fetch('/admin/servers', {
        method: 'POST',
        body: JSON.stringify(server),
        credentials: 'include'
    }).then(response => response.json())
    .then(server => {
        console.log(server);
        window.location = '/admin/dashboard';
    });
};