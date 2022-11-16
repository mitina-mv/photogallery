'use strict'

window.addEventListener('DOMContentLoaded', function() {
    let form = document.querySelector('.edit-profile'),
        errorBlock = form.querySelector('.error-block');
    
    form.addEventListener('submit', function(e){
        e.preventDefault();

        const fData = new FormData(this);

        postData('/admin/api/user/', fData, {})
            .then((data) => {
                showUserMessage('Успех', `Изменения сохранены, обновите страницу`, 'success');
            })
            .catch(error => {
                let errorMess = getCookieError();
                errorBlock.innerHTML = errorMess ? errorMess : 'Не удалось авторизоваться.' ;
            })
    })
})