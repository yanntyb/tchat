const sendMessageButton = document.getElementById("sendMessage");
const chat = document.getElementById("message_send");
const input = document.getElementById("sendMessageInput");
const messageDiv = document.getElementById("message");
const privateMessageDiv = document.getElementById("privateMessageSend");
const privateMessageSuperDiv = document.getElementById("privateMessage");
const dataDiv = document.getElementById("data");
let formShowed = false;
let scrolled = false;
let privateMessageFlag = false;
let user2Conv;


function loadMessage(){
    const message = document.getElementsByClassName("message_content");

    let xhr = new XMLHttpRequest();
    xhr.onload = function() {
        const messages = JSON.parse(xhr.responseText);
        chat.innerHTML = "";
        for (let message of messages){
            chat.innerHTML += `
            <div class="message_content">
                    <div class="profile">
                        <a class="sendMessageLink" data-id="${message.user_id}">${message.user}</a> 
                        <span class="date">${message.date}</span>
                    </div> 
                    <div class="message_content_end">${message.message}</div>
                </div>
                
            </div>
        `
        }
        if(message.length > 0 && !scrolled){
            messageDiv.scrollTop = message[message.length - 1].offsetTop;
            scrolled = true;
        }
        let messageLink = document.getElementsByClassName("sendMessageLink");

        for(let link of messageLink){
            link.addEventListener("click", function(e){
                e.preventDefault();
                let id = link.getAttribute("data-id")
                getUser(false, true, id);

            })
        }
    }
    xhr.open('GET', '/api/Message');
    xhr.send();

}

function timeOutRecur(){
    setTimeout(function(){
        loadMessage();
        if(privateMessageFlag){
            //showPrivateMessage(user2Conv);
        }
        timeOutRecur();
    },1000);
}

function getSessionUser(message){
    if(message.length > 0) {
        getUser(true, false, null, message);
    }

}

function getUser(send, get, user2, message = null){
    let sended = false;
    let xhr = new XMLHttpRequest();
        xhr.onreadystatechange = (e) => {
            if (xhr.status === 200 && !sended) {
                let user = JSON.parse(xhr.responseText)
                if(send){
                    sendMessageContent(message, user["user"]);
                    sended = true;
                }
                else{
                    showMessagerie(user["user"], user2)
                    sended = true;
                }

            }
        };
        xhr.open('GET', '/api/Message?getUser=1');
        xhr.send();
}

function sendMessageContent(message,user){
    let xhr = new XMLHttpRequest();
    const messageData = {
        'user': user,
        'message': message,
    };
    xhr.open('POST', '/api/Message');
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.send(JSON.stringify(messageData));
}

function showMessagerie(user1, user2){

    user1 = parseInt(user1);
    user2 = parseInt(user2);
    user2Conv = user2;
    console.log("ok");
    if(user1 !== user2){
        showPrivateMessage(user2);
        privateMessageSuperDiv.style.display = "flex";
        privateMessageFlag = true;
    }
}

function showPrivateMessage(user2){
    let xhr = new XMLHttpRequest();
    xhr.onload = function() {
        const messages = JSON.parse(xhr.responseText);
        console.log(messages);
        privateMessageDiv.innerHTML = "";
        for(let message of messages){
            if(message.sended === false){
                privateMessageDiv.innerHTML += `
                <div class="message_content">
                    <div class="messagePositionLeft">
                        <span>${message.message}</span>
                    </div>
                </div>
            `
            }
            else{
                privateMessageDiv.innerHTML += `
                <div class="message_content">
                    <div class="messagePositionRight">
                        <span>${message.message}</span>
                    </div>
                </div>
            `
            }

        }
        privateMessageShowForm();
    }
    xhr.open('GET', '/api/Message?showMessage=1&id=' + user2);
    xhr.send();
}

function privateMessageShowForm(){
    if(!formShowed){
        let divForm = document.createElement('div');
        divForm.className += "privateMessageForm";
        let input = document.createElement("input");
        input.type = "text";
        let submit = document.createElement("input");
        submit.type = "submit";
        divForm.appendChild(input);
        divForm.appendChild(submit);
        dataDiv.appendChild(divForm);
        formShowed = true;
    }

}

try{
    sendMessageButton.addEventListener("click", function(e){
        e.preventDefault();
        const inputValue = input.value;
        getSessionUser(inputValue);
        input.value = "";
    })
}
catch(e){}


timeOutRecur();

