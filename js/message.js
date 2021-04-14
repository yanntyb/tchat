const sendMessageButton = document.getElementById("sendMessage");
const chat = document.getElementById("message_send");
const input = document.getElementById("sendMessageInput");
const messageDiv = document.getElementById("message");
const privateMessageDiv = document.getElementById("privateMessage");
let scrolled = false;


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
    console.log("ok");
    if(user1 !== user2){
        privateMessageDiv.style.backgroundColor = "#708090"
        privateMessageDiv.innerHTML = user1 + " " + user2;
    }
}

function showPrivateMessage(user1, user2){
    let xhr = new XMLHttpRequest();
    xhr.onload = function() {
        const messages = JSON.parse(xhr.responseText);
        console.log(messages);
    }
    xhr.open('GET', '/api/Message?showMessage=1');
    xhr.send();
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

