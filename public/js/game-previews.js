function previousPreview() {
    document.querySelectorAll('#game-preview .preview').forEach(function(preview) {
        const number = parseInt(preview.getAttribute('data-number'));
        preview.setAttribute('data-number', (number < 5) ? number + 1 : 1);
    });
    selectPreviewPoint(document.querySelector('#game-preview .preview[data-number="3"]').getAttribute('data-id'));
}

function nextPreview() {
    document.querySelectorAll('#game-preview .preview').forEach(function(preview) {
        const number = parseInt(preview.getAttribute('data-number'));
        preview.setAttribute('data-number', (number > 1) ? number - 1 : 5);
    });
    selectPreviewPoint(document.querySelector('#game-preview .preview[data-number="3"]').getAttribute('data-id'));
}

function selectPreviewPoint(id) {
    document.querySelector('#game-preview .preview-point.active').classList.remove('active');
    const point = document.querySelector(`#game-preview .preview-point[data-number="${id}"]`);
    point.classList.add('active');

    document.querySelector('#game-preview .preview-points > footer > p').innerText = point.getAttribute('data-label');
}