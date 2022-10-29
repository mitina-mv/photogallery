'use strict'

async function getData(url = '') 
{
    const response = await fetch(url);
    if (!response.ok) {
        throw new Error(`Ошибка по адресу ${url} статус ${response.status}`)
    }
    return await response.json();
}

async function postData(url = '', data = {}, headers = {'Content-Type': 'application/json'}) 
{
    const response = await fetch(url, {
        method: 'POST',
        mode: 'cors',
        cache: 'no-cache',
        credentials: 'same-origin',
        headers: headers,
        redirect: 'follow',
        referrerPolicy: 'no-referrer',
        body: JSON.stringify(data)
    });
    if (!response.ok) {
        throw new Error(`Ошибка по адресу ${url} статус ${response.status}`)
    }
    return await response.json();
}

window.addEventListener('DOMContentLoaded', function(){
    let authForm = document.querySelector('.auth-form');

    // authForm.addEventListener('submit', function(e){
    //     e.preventDefault();

    //     let data = new FormData(authForm);
    //     let postData = {};

    //     data.forEach(function(value, key){
    //         postData[key] = value;
    //     });
    //     postData = JSON.stringify(postData);
    //     console.log(postData);
    // })
})