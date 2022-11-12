'use strict'

window.addEventListener('DOMContentLoaded', function(){
    const postModal = new HystModal({
        linkAttributeName: "data-hystmodal",
        beforeOpen: showPostModal,
        afterClose: function(modal){
            modal.openedWindow.querySelector('.post-modal__body').innerHTML = `<div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>`;
        },
    });

    let postContainer = document.querySelector('.posts');

    if(postContainer)
    {
        getData('/admin/api/photo')
            .then((data) => {
                for(let id in data.records){
                    let element = data.records[id];

                    let htmlel = generatePhoto(element);

                    postContainer.append(htmlel);
                }
            })   
    }
})