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
    fetch('/' + ((card.getAttribute('data-type') === 'bug') ? 'bugs' : 'evolutions') + '/' + card.getAttribute('data-id'), {
        method: 'PUT', 
        body: JSON.stringify({
            status: event.target.getAttribute('data-status')
        }),
        credentials: 'include'
    }).then(response => {
        console.log(response);
    });
};