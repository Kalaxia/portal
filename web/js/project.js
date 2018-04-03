const dragstart_handler = event => {
    // Add the target element's id to the data transfer object
    event.dataTransfer.setData("text/plain", event.target.id);
    event.dropEffect = "move";
};

const dragover_handler = event => {
    event.preventDefault();
    // Set the dropEffect to move
    event.dataTransfer.dropEffect = "move";
};

const drop_handler = event => {
    event.preventDefault();
    // Get the id of the target and add the moved element to the target's DOM
    var card = document.getElementById(event.dataTransfer.getData("text"));
    event.target.appendChild(card);
    fetch(`/feedbacks/${card.getAttribute('data-id')}`, {
        method: 'PUT', 
        body: JSON.stringify({
            status: event.target.getAttribute('data-status')
        }),
        credentials: 'include'
    }).then(response => {
        console.log(response);
    });
};

const edit_description = (id, type) => {
    var descriptionElement = document.querySelector('#feedback .description-content');
    
    if (descriptionElement.firstChild.tagName === 'textarea') {
        return;
    }
    var textArea = document.createElement('textarea');
    
    textArea.setAttribute('name', 'description-update');
    textArea.innerHTML = descriptionElement.innerText;
    textArea.style.height = descriptionElement.style.height;
    descriptionElement.innerHTML = textArea.outerHTML;
    
    document.querySelector('#description-old').innerHTML = textArea.innerText ;
    document.querySelector('#confirm-description-changes').style.display = 'block';
    document.querySelector('#edit-description-button').style.display = 'none';
};

const update_description = (id, type) => {
    fetch(`/feedbacks/${id}`, {
        method: 'PUT', 
        body: JSON.stringify({
            description: document.querySelector('#feedback .description-content > textarea').value
        }),
        credentials: 'include'
    }).then(response => {
        if (response.ok) {
            return response.json();
        }
        throw 'Error';
    }).then(data => {
        document.querySelector('#feedback .description-content').innerHTML = data.description;
        document.querySelector('#confirm-description-changes').style.display = 'none';
        document.querySelector('#edit-description-button').style.display = 'inline';
    }).catch(error => console.log(error));
};

const cancel_update_description = (alert_text) => {
  var result = confirm(alert_text) ;
  if(result)
  {
      document.querySelector('#feedback .description-content').innerHTML = document.querySelector('#description-old').innerHTML;
      document.querySelector('#confirm-description-changes').style.display = 'none';
      document.querySelector('#edit-description-button').style.display = 'inline';
  }   
};

const remove_feedback = id => {
    if (!confirm('are you sure ?')) {
        return false;
    }
    return fetch(`/feedbacks/${id}`, {
        method: 'DELETE',
        credentials: 'include'
    }).then(response => {
        window.location = '/board';
    });
};

const search_feedbacks = event => fetch('/feedbacks/search', {
    method: 'POST',
    body: JSON.stringify({
        title: event.currentTarget.value
    }),
    credentials: 'include'
}).then(response => response.json()).
then(data => {
    let options = {day: 'numeric', month: 'numeric', year: 'numeric', hour: "numeric", minute: "numeric"};
    document.querySelectorAll('.board-column > section > a').forEach(node => node.remove());
    for (let feedback of data) {
        console.log(feedback);
        let id = (feedback.slug != '') ? feedback.slug : feedback.id;
        let date =
            (feedback.created_at.date === feedback.updated_at.date)
            ? 'Créé le ' + new Intl.DateTimeFormat({}, options).format(new Date(feedback.created_at.date))
            : 'Mis à jour le ' + new Intl.DateTimeFormat({}, options).format(new Date(feedback.created_at.date))
        ;
        let card = document.createElement('a');
        card.id = `feedback-${feedback.id}`;
        card.href= `/feedbacks/${id}`;
        card.classList.add('board-card');
        card.setAttribute('data-id', id);
        card.setAttribute('data-type', feedback.type);
        card.setAttribute('draggable', true);
        card.addEventListener('dragstart', dragstart_handler);
        card.innerHTML =
            `<header><h5>${feedback.title}</h5><div class="labels">${feedback.labels.map(label => {
                return `<div class="tooltip" style="background-color: ${label.color }">
                    <span class="tooltip-text">${label.name}</span>
                </div>`;
            })}</div></header>
            <section><div class="feedback-info"><div class="author">${feedback.author}</div></div><p class="date">${date}</p></section>`
        ;
        document.querySelector(`.board-column > section[data-status="${feedback.status}"]`).appendChild(card);
    }
});

const create_comment = id => {
    var textArea = document.querySelector('textarea[name="comment-content"]');
    var content = textArea.value;
    if (content.length === 0) return false;
    
    fetch(`/feedbacks/${id}/comments`, {
        method: 'POST', 
        body: JSON.stringify({
            content: content
        }),
        credentials: 'include'
    }).then(response => {
        textArea.value = '';
        if (response.ok) {
            return response.json();
        }
        throw 'Erreur';
    }).then(data => {
        console.log(data);
        
        var commentsBox = document.querySelector('.comments');
        var comment = document.createElement('div');
        comment.classList.add('comment'); 
        comment.classList.add('speech-bubble');
        
        var authorElement = document.createElement('div');
        authorElement.classList.add('author');
        authorElement.innerHTML = `${data.feedback.author}, ${data.created_at_string}`;
        comment.appendChild(authorElement);
        
        var contentElement = document.createElement('div');
        contentElement.classList.add('content');
        contentElement.innerHTML = data.feedback.content;
        comment.appendChild(contentElement);
        
        commentsBox.appendChild(comment);
    }).catch(error => console.log(error));
};

const toggle_label = (event, feedbackId, feedbackType) => {
    var label = event.currentTarget;
    fetch(`/feedbacks/${feedbackId}/labels/${label.getAttribute('data-id')}`, {
        method: label.classList.contains('active') ? 'DELETE' : 'POST',
        credentials: 'include'
    }).then(response => {
        label.classList.toggle('active');
    });
};