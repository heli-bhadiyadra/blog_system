console.log('DELETE JS LOADED ');

document.addEventListener('click', (event) => {
    const btn = event.target.closest('.delete-btn');
    if (!btn) return;

    console.log('DELETE CLICKED');

    const form = btn.closest('form');

    if (confirm('Are you sure you want to delete this blog?')) {
        form.submit();
    }
});