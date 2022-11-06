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
        body: data
    });
    if (!response.ok) {
        throw new Error(`Ошибка по адресу ${url} статус ${response.status}`)
    }
    return await response.json();
}

const getElement = (tagName, classNames, attrs, dataAttrs, content) => {
	const element = document.createElement(tagName);

	if(classNames)
		element.classList.add(...classNames);

	if(attrs){
		for(let attr in attrs){
			element[attr] = attrs[attr];
		}
	}

	if(dataAttrs){
		for(let attr in dataAttrs){
            element.dataset[attr] = dataAttrs[attr];
		}
	}
    
	if(content)
		element.innerHTML = content;

	return element;
}

const getCookie = (cname) => {
  let name = cname + "=";
  let ca = document.cookie.split(';');
  for(let i = 0; i < ca.length; i++)
  {
    let c = ca[i].trim();

    if (c.indexOf(name) == 0) 
        return c.substring(name.length,c.length);
  }

  return null;
}

const showUserMessage = (title, info, status) => {
    let mes = getElement('div', ['message', 'message_' + status], {
        innerHTML: `<div class="message-head">${title}</div>
        <div class="message-info">${info}</div>
        <div class="message-close">x</div>
        `
    });

    document.body.append(mes);
    // setTimeout(() => mes.remove(), 3000);
}

const generateStar = (count) => {
    let curRating = "";

    for(let i = 5; i > 0; --i){
        curRating += `<span data-rating="${i}" class="post-star ${i <= Math.floor(count) ? 'ph-star' : 'ph-star-empty'}"></span>`
    }

    return curRating;
}

let postStarBlock = null;

const addPostRating = function(event, element, postID) {    
    console.log(event.target);
    console.log(element);
    console.log(postID);
}

const showPostModal = function(modal) {
    let modalStart = modal.starter;
    let modalBody = modal.openedWindow.querySelector('.post-modal__body');
    let photoId = modalStart.getAttribute('data-post-id');

    getData('/admin/api/detailpost/' + photoId)
            .then((data) => {
                let post = data.post,
                    user = data.user;

                let classPostrating = getCookie('user-token') == post.user ? "post-rating__change-rating_blocked" : "";                

                let userPhoto = user.photo ? `<img class='user-photo' src="${user.photo}" alt="фото пользователя">` : `<div class='user-photo_no-pict'>${user.firstname[0]}</div>`;

                modalBody.innerHTML = `
                    <div class="post-modal__photo" style='background-image:url(${post.path})' data-id='${post.id}'></div>

                    <div class="post-modal__info">
                        <div class="post-modal__author">
                            ${userPhoto}

                            <div class="post-modal__author-info">
                                <span class="post-modal__name">${user.lastname} ${user.firstname}</span>
                                <a class="post-modal__subname" href='/@${user.login}'>@${user.login}</a>
                            </div>
                        </div>

                        <div class="post-modal__date">${post.date}</div>

                        <div class="post-modal__description">${post.desc}</div>

                        <div class='post-modal__rating post-rating'>
                            <div class='post-rating__current-rating'>${post.rating}</div>

                            <div class='post-rating__change-rating ${classPostrating}'>
                                ${generateStar(post.rating)}
                            </div>
                        </div>
                    </div>
                `;

                postStarBlock = modalBody.querySelector('.post-rating__change-rating:not(.post-rating__change-rating_blocked)');

                if(postStarBlock && getCookie('user-token') == post.user) {
                    postStarBlock.addEventListener('click', function(e) {
                        addPostRating(e, this, post.id);
                    })
                }
            })
}

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

                    let htmlel = getElement('div', ['post-item'], {
                        id: "photo_" + element.id,
                        innerHTML: `
                        <div class='post-item__container'>
                            <div class='post-item__rating'>${element.rating}</div>
                            <div class='post-item__photo' style='background-image:url(${element.path})'
                        </div>`,
                        href: element.path,
                    }, {
                        'postId': element.id,
                        'hystmodal': '#post-detail'
                    })

                    postContainer.append(htmlel);
                }
            })   
    }
})