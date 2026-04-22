document.addEventListener("DOMContentLoaded", function () {

    const search = document.getElementById("searchBlog");

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
            document.getElementById("blogResults").innerHTML = data;
        });

    });

});