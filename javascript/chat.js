const form = document.querySelector(".typing-area"),
incoming_id = form.querySelector(".incoming_id").value,
inputField = form.querySelector(".input-field"),
sendBtn = form.querySelector("button"),
chatBox = document.querySelector(".chat-box");


const sendButton = document.querySelector("#send-button");
const icon = document.querySelector("#icon");


form.onsubmit = (e)=>{
    e.preventDefault();
}

inputField.focus();
inputField.onkeyup = ()=>{
    if(inputField.value != ""){
        sendBtn.classList.add("active");
    }else{
        sendBtn.classList.remove("active");
    }
}

sendBtn.onclick = ()=>{
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "php/insert-chat.php", true);
    xhr.onload = ()=>{
      if(xhr.readyState === XMLHttpRequest.DONE){
          if(xhr.status === 200){
              inputField.value = "";
              scrollToBottom();
          }
      }
    }
    let formData = new FormData(form);
    xhr.send(formData);
}
chatBox.onmouseenter = ()=>{
    chatBox.classList.add("active");
}

chatBox.onmouseleave = ()=>{
    chatBox.classList.remove("active");
}

setInterval(() =>{
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "php/get-chat.php", true);
    xhr.onload = ()=>{
      if(xhr.readyState === XMLHttpRequest.DONE){
          if(xhr.status === 200){
            let data = xhr.response;
            chatBox.innerHTML = data;
            if(!chatBox.classList.contains("active")){
                scrollToBottom();
              }
          }
      }
    }
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("incoming_id="+incoming_id);
}, 500);

function scrollToBottom(){
    chatBox.scrollTop = chatBox.scrollHeight;
  }





  // Cargar mensajes del grupo
  setInterval(() => {
      fetch(`php/get-chat.php?group_id=${groupId}`)
          .then(response => response.json())
          .then(data => {
              const chatMessages = document.getElementById("chatMessages");
              chatMessages.innerHTML = '';  // Limpiar el área de mensajes
              data.messages.forEach(message => {
                  const messageDiv = document.createElement("div");
                  messageDiv.textContent = `${message.username}: ${message.msg}`;
                  chatMessages.appendChild(messageDiv);
              });
          });
  }, 2000);
  
  // Enviar mensaje
  document.getElementById("sendMessageForm").addEventListener("submit", function(e) {
      e.preventDefault();
  
      const message = document.getElementById("message").value;
  
      fetch("php/send-message.php", {
          method: "POST",
          headers: {
              "Content-Type": "application/json"
          },
          body: JSON.stringify({ groupId, message })
      })
      .then(response => response.json())
      .then(data => {
          if (data.success) {
              document.getElementById("message").value = '';  // Limpiar el campo de texto
          }
      });
  });
  // Función para abrir el modal
document.getElementById('createGroupBtn').addEventListener('click', function() {
    console.log("Botón presionado"); // Verifica si el evento se está activando
    document.getElementById('createGroupModal').style.display = 'flex';
 });
 
  
 // Redirección a PayPal al hacer clic en el botón de tres rayas
const hamburgerMenu = document.getElementById("hamburger-menu");

hamburgerMenu.addEventListener("click", () => {
  window.location.href = "https://www.paypal.com";
});





let isRecording = false;

// Cambiar ícono según el contenido del input
inputField.addEventListener("input", () => {
  if (inputField.value.trim() !== "") {
    icon.classList.remove("fa-microphone");
    icon.classList.add("fa-paper-plane");
  } else {
    icon.classList.remove("fa-paper-plane");
    icon.classList.add("fa-microphone");
  }
});

// Evento de clic en el botón
sendButton.addEventListener("click", () => {
  if (icon.classList.contains("fa-paper-plane")) {
    // Enviar mensaje de texto
    console.log("Mensaje enviado:", inputField.value);
    inputField.value = ""; // Limpiar el campo de entrada
    icon.classList.remove("fa-paper-plane");
    icon.classList.add("fa-microphone");
  } else if (icon.classList.contains("fa-microphone")) {
    // Grabar audio
    if (!isRecording) {
      console.log("Grabación iniciada...");
      isRecording = true;
      icon.classList.add("recording");
    } else {
      console.log("Grabación detenida y enviada.");
      isRecording = false;
      icon.classList.remove("recording");
    }
  }
});


