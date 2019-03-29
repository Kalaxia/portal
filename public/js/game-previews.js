function previousPreview() {
    document.querySelectorAll('#game-preview .preview').forEach(function(preview) {
        const number = preview.getAttribute('data-number');
        preview.setAttribute('data-number', (number < 5) ? number + 1 : 1);
    });
}

function nextPreview() {
    document.querySelectorAll('#game-preview .preview').forEach(function(preview) {
        const number = preview.getAttribute('data-number');
        preview.setAttribute('data-number', (number > 1) ? number - 1 : 5);
    });
}