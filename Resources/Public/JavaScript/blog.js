document.addEventListener('click', function (e) {
    const link = e.target.closest('a.page-link');

    if (!link) return;

    e.preventDefault();
    e.stopImmediatePropagation();

    const url = link.href;

    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.text())
    .then(html => {
        // Parse response HTML
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');

        // Extract ONLY blogResults content
        const newContent = doc.querySelector('#blogResults');

        if (newContent) {
            document.querySelector('#blogResults').innerHTML = newContent.innerHTML;
        } else {
            console.error('blogResults not found in response');
        }
    })
    .catch(err => console.error(err));

    return false;
}, true);