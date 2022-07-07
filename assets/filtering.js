let distance = document.getElementById('distance');
let surPlace = document.getElementById('surPlace');

distance.addEventListener('change', ()=>{
    if(distance.checked){
        window.location.replace(distance.parentElement.getAttribute('href'));
    }else{
        window.location.replace(distance.parentElement.parentElement.getAttribute('href'));
    }
})

surPlace.addEventListener('change', ()=>{
    if(surPlace.checked){
        window.location.replace(surPlace.parentElement.getAttribute('href'));
    }else{
        window.location.replace(surPlace.parentElement.parentElement.getAttribute('href'));
    }
})