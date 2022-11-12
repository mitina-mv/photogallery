'use strict'

window.addEventListener('DOMContentLoaded', function(){
    let form = document.querySelector('.reg-form'),
        errorBlock = form.querySelector('.error-block'),
        error = null;

    form.addEventListener('change', function(e){
        if(e.target.classList.contains('password-repeat') || e.target.classList.contains('password')){
            let password = form.querySelector('.password').value,
                passwordRepeat = form.querySelector('.password-repeat').value;

            const regexpMain = /[a-zA-Z0-9\-]{6,}/;
            const regexpW = /[a-zA-Z]{1,}/;
            
            if(!regexpMain.test(password) || !regexpW.test(password)){
                errorBlock.innerHTML = `Пароль должен содержать цифры и буквы и быть не менее 6 символов.`;
                error = 1;
            } else if(password != passwordRepeat && password != "" && passwordRepeat != "") {
                errorBlock.innerHTML = `Пароли не совпадают!`;
                error = 1;
            } else {
                error = 0;
                errorBlock.innerHTML = ``;
            }
        }
    })
    
    form.addEventListener('submit', function(e){
        e.preventDefault();

        if(error) return;

        const fData = new FormData(this);

        postData('/admin/api/user/reg', fData, {})
            .then((data) => {
                showUserMessage('Успех', `Пользовтаель зарегистирован`, 'success');
                form.reset();
                window.location.href = '/';
            })
            .catch(error => {
                let errorMess = getCookieError();
                errorBlock.innerHTML = errorMess ? errorMess : 'Не удалось зарегистрировать пользователя.' ;
            })
    })
})