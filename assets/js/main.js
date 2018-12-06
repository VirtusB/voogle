var timer;


document.addEventListener('DOMContentLoaded', function() {
    Array.from(document.querySelectorAll('a.result')).forEach(function(link) {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            let url = link.getAttribute('href');
            let id = link.getAttribute('data-link-id');

            increaseLinkClicks('site', id, url);
        });
    });

    var grid = document.querySelector('.image-results');
    
    window.msnry = new Masonry( grid, {
        itemSelector: '.grid-item',
        columnWidth: 200,
        gutter: 5,
        transitionDuration: '0.2s',
        isInitLayout: false
    });

    window.msnry.on('layoutComplete', function () {
        Array.from(document.querySelectorAll('.grid-item img')).forEach(function (img) {
            img.style.visibility = 'visible';
        });
    });

    Array.from(document.getElementsByClassName('lightgallery')).forEach(function (gl) {
        lightGallery(gl);
        let id = gl.getAttribute('data-image-id');

        gl.addEventListener('onAfterOpen', function(e){
            increaseLinkClicks('image', id);
        }, false);
    });


});

function loadImage(url, id) {
    let image = document.createElement('img');
    let container = document.querySelector(`[data-image-id='${id}']`);

    image.addEventListener('load', function () {
        container.querySelector('a').appendChild(image);
    });


    clearTimeout(timer);
    timer = setTimeout(function () {
        console.log('Masonry');
        window.msnry._init();
    }, 200);

    image.addEventListener('error', function () {
        container.remove();
        markImageAsBroken(id);
    });

    image.src = url;
}

function increaseLinkClicks(type, id, url) {
    let data = {idToUpdate: id, typeToUpdate: type};

    fetch("ajax/updateClicksCount.php", {
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
        if (type === 'site') {
            location.href = url;
        }
    });
}

function markImageAsBroken(id) {
    let data = {brokenImageId: id};

    fetch("ajax/markImageAsBroken.php", {
        method: "POST",
        mode: "same-origin",
        credentials: "same-origin",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            "payload": data
        })
    })
}