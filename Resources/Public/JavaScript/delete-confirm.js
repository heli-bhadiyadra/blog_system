document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.js-confirm-delete').forEach(function (form) {

        form.addEventListener('submit', function (e) {

            let ok = confirm('Are you sure you want to delete this blog?');

            if (!ok) {
                e.preventDefault();
            }

        });

    });

});