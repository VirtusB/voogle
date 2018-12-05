document.addEventListener('DOMContentLoaded', function() {
    Array.from(document.querySelectorAll('a.result')).forEach(function(link) {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            let url = link.getAttribute('href');
            let id = link.getAttribute('data-link-id');

            increaseLinkClicks(id, url);
        });
    });
});

function increaseLinkClicks(linkId, url) {
    let data = {linkToUpdate: linkId};

    fetch("ajax/updateLinkCount.php", {
        method: "POST",
        mode: "same-origin",
        credentials: "same-origin",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            "payload": data
        })
    }).then((response) => {
        location.href = url;
    });
}