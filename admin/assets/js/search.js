'use strict'

window.addEventListener('DOMContentLoaded', () => {
    let block = document.querySelector('.search-result');
    let form = document.querySelector('.search-form');
    


    form.addEventListener('submit', function(e){
        e.preventDefault();

        const searchText = form.querySelector('#search-text').value.replace(/[^\w\s а-я]/gi, '');

        block.innerHTML = "";

        getData('/admin/api/user?v=' + searchText)
            .then((data) => {
                if(data.length == 0) {
                    block.innerHTML = `<div class='error'>Поиск не дал результатов. Попробуте другой запрос.</div>`;
                    return;
                }

                for(let user of data){
                    let userPhoto = user.photo ? `<img class='user-item__photo user-photo' src="${user.photo}" alt="фото пользователя">` : `<div class='user-item__photo_no-pict user-photo_no-pict user-photo'  style='background: ${getRandomColor()};'>${user.lastname[0]}</div>`;

                    let el = getElement('a', ['user-item'], {
                        href: "/profile/" + user.login + "/",
                        innerHTML: `
                        ${userPhoto}

                        <div class="user-item__info">
                            <span class='user-name'>${user.firstname} ${user.lastname}</span>
                            <span class='user-login'>${user.login}</span>
                        </div>
                        `
                    });

                    block.append(el);
                }
            })
            .catch(error => {
                block.innerHTML = `<div class='error'>Не удалось произвести поиск. Проблема в работе.</div>`;
            })
    })
})