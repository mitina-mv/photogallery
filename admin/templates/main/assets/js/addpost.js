'use strict'

window.addEventListener('DOMContentLoaded', () => {
    let form = document.querySelector('.add-post');
    
    form.addEventListener('submit', function(e){
        e.preventDefault();

        const fData = new FormData(this);

        postData('/admin/api/photo', fData, {})
            .then((data) => {
                // console.log(JSON.parse(data));
            })
    })
})