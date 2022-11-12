'use strict'

window.addEventListener('DOMContentLoaded', function(){
    let form = document.querySelector('.auth-form'),
        errorBlock = form.querySelector('.error-block');
    
    form.addEventListener('submit', function(e){
        e.preventDefault();

        const fData = new FormData(this);

        postData('/admin/api/user/auth', fData, {})
            .then((data) => {
                showUserMessage('Успех', `Пользовтаель авторизован`, 'success');
                form.reset();
                window.location.href = '/';
            })
            .catch(error => {
                let errorMess = getCookieError();
                errorBlock.innerHTML = errorMess ? errorMess : 'Не удалось авторизоваться.' ;
            })
    })
})