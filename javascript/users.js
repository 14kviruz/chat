const searchBar = document.querySelector(".search input"),
searchIcon = document.querySelector(".search button"),
usersList = document.querySelector(".users-list");


const productsContainer = document.getElementById('products-container');
const userId = 1; // Cambia esto por el ID dinámico del usuario actual


searchIcon.onclick = ()=>{
  searchBar.classList.toggle("show");
  searchIcon.classList.toggle("active");
  searchBar.focus();
  if(searchBar.classList.contains("active")){
    searchBar.value = "";
    searchBar.classList.remove("active");
  }
}

searchBar.onkeyup = ()=>{
  let searchTerm = searchBar.value;
  if(searchTerm != ""){
    searchBar.classList.add("active");
  }else{
    searchBar.classList.remove("active");
  }
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "php/search.php", true);
  xhr.onload = ()=>{
    if(xhr.readyState === XMLHttpRequest.DONE){
        if(xhr.status === 200){
          let data = xhr.response;
          usersList.innerHTML = data;
        }
    }
  }
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send("searchTerm=" + searchTerm);
}

setInterval(() =>{
  let xhr = new XMLHttpRequest();
  xhr.open("GET", "php/users.php", true);
  xhr.onload = ()=>{
    if(xhr.readyState === XMLHttpRequest.DONE){
        if(xhr.status === 200){
          let data = xhr.response;
          if(!searchBar.classList.contains("active")){
            usersList.innerHTML = data;
          }
        }
    }const searchBar = document.querySelector(".search input"),
    searchIcon = document.querySelector(".search button"),
    usersList = document.querySelector(".users-list");
    
    searchIcon.onclick = ()=>{
      searchBar.classList.toggle("show");
      searchIcon.classList.toggle("active");
      searchBar.focus();
      if(searchBar.classList.contains("active")){
        searchBar.value = "";
        searchBar.classList.remove("active");
      }
    }
    
    searchBar.onkeyup = ()=>{
      let searchTerm = searchBar.value;
      if(searchTerm != ""){
        searchBar.classList.add("active");
      }else{
        searchBar.classList.remove("active");
      }
      let xhr = new XMLHttpRequest();
      xhr.open("POST", "php/search.php", true);
      xhr.onload = ()=>{
        if(xhr.readyState === XMLHttpRequest.DONE){
            if(xhr.status === 200){
              let data = xhr.response;
              usersList.innerHTML = data;
            }
        }
      }
      xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhr.send("searchTerm=" + searchTerm);
    }
    
    setInterval(() =>{
      let xhr = new XMLHttpRequest();
      xhr.open("GET", "php/users.php", true);
      xhr.onload = ()=>{
        if(xhr.readyState === XMLHttpRequest.DONE){
            if(xhr.status === 200){
              let data = xhr.response;
              if(!searchBar.classList.contains("active")){
                usersList.innerHTML = data;
              }
            }
        }
      }
      xhr.send();
    }, 500);
    
    
  }
  xhr.send();
}, 500);




// Función para abrir el modal
document.getElementById('createGroupBtn').addEventListener('click', function() {
  document.getElementById('createGroupModal').style.display = 'flex';
});

// Función para cerrar el modal
function closeModal() {
  document.getElementById('createGroupModal').style.display = 'none';
}




document.getElementById("createGroupForm").addEventListener("submit", function(e) {
  e.preventDefault();

  const groupName = document.getElementById("groupName").value;
  const users = Array.from(document.getElementById("users").selectedOptions).map(option => option.value);
  
  // Send data to the backend
  fetch("php/insert-group.php", {
      method: "POST",
      headers: {
          "Content-Type": "application/json"
      },
      body: JSON.stringify({ groupName, users })
  })
  .then(response => response.json())
  .then(data => {
      if (data.success) {
          // Redirect to the group page
          window.location.href = `php/grupo_chat.php?group_id=${data.group_id}`;
      }
  });
});


// Script para agregar usuarios al grupo al hacer doble clic
document.querySelectorAll('.user-item').forEach(item => {
  item.addEventListener('dblclick', function() {
    const userId = item.getAttribute('data-id');
    const userName = item.querySelector('span').textContent;

    // Crear un nuevo elemento para mostrar el usuario seleccionado
    const selectedUserDiv = document.createElement('div');
    selectedUserDiv.classList.add('selected-user');
    selectedUserDiv.setAttribute('data-id', userId);
    selectedUserDiv.textContent = userName;
    
    // Agregar el usuario al área de selección
    document.getElementById('selectedUsers').appendChild(selectedUserDiv);
  });
});






