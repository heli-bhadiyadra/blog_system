document.addEventListener("DOMContentLoaded", function () {

    const search = document.getElementById("searchBlog");

    if (!search) return;   // Prevent error if element doesn't exist

    search.addEventListener("keyup", function () {

        let title = this.value;

        fetch("/index.php?type=123", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body:
                "tx_nsblogsystem_bloglist[action]=filter" +
                "&tx_nsblogsystem_bloglist[controller]=Blog" +
                "&tx_nsblogsystem_bloglist[title]=" + encodeURIComponent(title)
        })
        .then(response => response.text())
        .then(data => {
            const results = document.getElementById("blogResults");
            if (results) {
                results.innerHTML = data;
            }
        });

    });

});
