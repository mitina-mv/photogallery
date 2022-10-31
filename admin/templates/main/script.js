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

const getElement = (tagName, classNames, attrs, content) => {
	const element = document.createElement(tagName);

	if(classNames)
		element.classList.add(...classNames);

	if(attrs){
		for(let attr in attrs){
			element[attr] = attrs[attr];
		}
	}
	if(content)
		element.innerHTML = content;

	return element;
}

window.addEventListener('DOMContentLoaded', function(){
    // let authForm = document.querySelector('.auth-form');

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

    let postContainer = document.querySelector('.posts');
    if(postContainer){
        getData('/admin/api/photo')
            .then((data) => {
                data['records'].foreach(() => {
                    
                })
            })
    }
})