const sendMessageButton = document.getElementById("sendMessage");
const chat = document.getElementById("message_send");
const input = document.getElementById("sendMessageInput");
const privateMessageDiv = document.getElementById("privateMessageSend");
const privateMessageSuperDiv = document.getElementById("privateMessage");
let formShowed = false;
let flagScrollGlobal = false;
let flagScrollPrivate = false;
let privateMessageFlag = false;
let user2Conv;

//Get messages from Message Api
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
            <div id="separation"></div>
        `
        }
        //Scrool global chat to the maximum to see first the last inserted message
        if(!flagScrollGlobal){
            let div = document.getElementById("message")
            div.scrollTop = div.scrollHeight;
            flagScrollGlobal = true;
        }

        let messageLink = document.getElementsByClassName("sendMessageLink");
        //Set event at click on User Name
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

//Load message from chat and from private chat recursively
function timeOutRecur(){
    setTimeout(function(){
        loadMessage();
        //Only load private message recursively when its shown
        if(privateMessageFlag){
            showPrivateMessage(user2Conv);
        }
        timeOutRecur();
    },1000);
}

//Get the $_SESSION["user"] (the current user connected)
function getSessionUser(message){
    if(message.length > 0) {
        getUser(true, false, null, message);
    }

}

function getUser(send, get, user2, message = null){
    //need a sent flag because dunno why message are sent 2 times
    let xhr = new XMLHttpRequest();
        xhr.onreadystatechange = (e) => {

            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                let user = JSON.parse(xhr.responseText);
                console.log(user);
                //send message to global chat
                if(send){
                    sendMessageContent(message, user["user"]);
                }
                //show private messagerie
                else{
                    showMessagerie(user["user"], user2)
                }

            }
        };

        xhr.open('GET', '/api/Message?getUser=1');
        xhr.send();
}

//send message to global chat
function sendMessageContent(message,user){
    let xhr = new XMLHttpRequest();
    const messageData = {
        'user': user,
        'message': message,
    };
    flagScrollGlobal = false;
    xhr.open('POST', '/api/Message/index.php');
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.send(JSON.stringify(messageData));
}

//Show private messagerie
function showMessagerie(user1, user2){

    user1 = parseInt(user1);
    user2 = parseInt(user2);
    if(user1 !== user2){
        user2Conv = user2;
        showPrivateMessage(user2);
        privateMessageSuperDiv.style.display = "flex";
        privateMessageFlag = true;

    }
    else{
        privateMessageSuperDiv.style.display = "none";
        privateMessageDiv.innerHTML = "";
        //Delete private chat form
        privateMessageSuperDiv.removeChild(privateMessageSuperDiv.lastChild);
        privateMessageFlag = false;
        formShowed = false;
    }
}

function showPrivateMessage(user2){
    let xhr = new XMLHttpRequest();
    xhr.onload = function() {
        //Get all private messages between $_SESSION["user"] and user2
        const messages = JSON.parse(xhr.responseText);
        let topDiv = document.createElement("div");
        privateMessageDiv.innerHTML = "";
        topDiv.id = "privateMessageInfoDiv";
        if(messages.length > 0){
            topDiv.innerHTML = "<h2 id='privateMessageTo'>" + messages[messages.length - 1]["user2_id"] +"</h2>"
        }
        else{
            topDiv.innerHTML = "<h2 id='privateMessageTo'>Send message to see your recipients</h2>";
        }
        topDiv.innerHTML += "<div id='closePrivateChat'><i id='closePrivateChatButton' class=\"fas fa-times\"></i></div>";
        privateMessageDiv.appendChild(topDiv);
        for(let message of messages){
            if(message.sent === false){
                privateMessageDiv.innerHTML += `
                <div class="message_content">
                    <div class="messagePositionLeft">
                        <span>${message.message}</span>
                        <br>
                        <span class="date">${message.date}</span>
                    </div>
                </div>
                <div id="separation"></div>
            `
            }
            else{
                privateMessageDiv.innerHTML += `
                <div class="message_content">
                    <div class="messagePositionRight">
                        <span>${message.message}</span>
                        <br>
                        <span class="date">${message.date}</span>
                    </div>
                </div>
                <div id="separation"></div>
            `
            }

        }
        if(!flagScrollPrivate){
            privateMessageDiv.scrollTop = privateMessageDiv.scrollHeight;
            flagScrollPrivate = true;
        }
        let closeButton = document.getElementById("closePrivateChatButton");
        //try{} to not add event when there is no closeButton
        try{
            closeButton.addEventListener("click",function (){
                privateMessageFlag = false;
                privateMessageDiv.innerHTML = "";
                privateMessageSuperDiv.style.display = "none";
                privateMessageSuperDiv.removeChild(privateMessageSuperDiv.lastChild);
                formShowed = false;
            })
        }
        catch(e){}


        privateMessageShowForm();
    }

    xhr.open('GET', '/api/PrivateMessage?showMessage=1&id=' + user2);
    xhr.send();
}

//Private message Form to send message
function privateMessageShowForm(){
    if(!formShowed){
        let divForm = document.createElement('div');
        divForm.id += "privateMessageForm";
        let input = document.createElement("input");
        input.type = "text";
        input.placeholder = "message"
        let submit = document.createElement("input");
        submit.type = "submit";
        divForm.appendChild(input);
        divForm.appendChild(submit);
        divForm.style.display = "flex";
        privateMessageSuperDiv.appendChild(divForm);
        submit.addEventListener("click", function(e){
            e.preventDefault();
            //Send private message only if input have content
            if(input.value.length > 0){
                sendPrivateMessage(input.value);

            }
        })
        formShowed = true;
    }

}

function sendPrivateMessage(message){
    let xhr = new XMLHttpRequest();
    const messageData = {
        'user': user2Conv,
        'message': message,
    };
    xhr.open('POST', '/api/PrivateMessage/index.php');
    xhr.send(JSON.stringify(messageData));
    //flag set to false to allow scrolling to the last private message
    flagScrollPrivate = false;
}


//Send message event in global chat only when user is connected (when the input exist)
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


