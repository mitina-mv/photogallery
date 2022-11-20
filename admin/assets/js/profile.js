'use strict'

window.addEventListener('DOMContentLoaded', () => {
    let photo = document.querySelector('.posts'),
        profile = document.querySelector('.profile'),
        login = window.location.href.split('/'),
        profileBG = document.querySelector('.profile-logo');

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

            let userphoto = user.photo ? `<div class='profile-avatar__photo' style='background-image: url(${user.photo});'></div>` : `<div class='profile-avatar__photo profile-avatar__photo_no_pict'>${user.lastname[0]}</div>`;

            if(user.bgimage){
                profileBG.style = `background-image: url(${user.bgimage});`;
            }

            let profileInfo = getElement('div', ['profile-avatar__info'], {
                innerHTML: `
                    ${userphoto}
                    <div class='info'>                        
                        <span class="avatar__main-name">${user.lastname} ${user.firstname}</span>
                        <span class="avatar__subname">${user.login}</span>
                    </div>
                `
            });
            
            profile.append(profileInfo);
        })
        .catch(error => {
            photo.innerHTML = `произошла ошибка.`
        })
})