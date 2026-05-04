console.log('Backend JS Loaded');

document.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-my-button]');
    if (btn) {
        e.preventDefault();
        alert('Button clicked!');
    }
});

export default {};