'use strict'

window.addEventListener('DOMContentLoaded', () => {
    let photo = document.querySelector('.posts'),
        profile = document.querySelector('.profile'),
        login = window.location.href.split('/');

    login = login[login.length - 2];

    const postModal = new HystModal({
        linkAttributeName: "data-hystmodal",
        beforeOpen: showPostModal,
        afterClose: function(modal){
            modal.openedWindow.querySelector('.post-modal__body').innerHTML = `<div class="lds-spinner"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>`;
        },
    });

    getData('/admin/api/user/' + login)
        .then(data => {
            let user = data.user;

            getData('/admin/api/photo/' + user.login)
                .then((data) => {
                    for(let id in data.records){
                        let element = data.records[id];

                        let htmlel = generatePhoto(element);

                        photo.append(htmlel);
                    }
                })
                .catch(error => {
                    photo.innerHTML = `фото не найдены`
                })
        })
        .catch(error => {
            photo.innerHTML = `произошла ошибка.`
        })
})