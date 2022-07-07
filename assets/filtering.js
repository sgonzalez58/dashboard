let distance = document.getElementById('distance');
let surPlace = document.getElementById('surPlace');

let garantie = document.getElementById('garantie');
let nonGarantie = document.getElementById('nonGarantie');

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

garantie.addEventListener('change', ()=>{
    if(garantie.checked){
        window.location.replace(garantie.parentElement.getAttribute('href'));
    }else{
        window.location.replace(garantie.parentElement.parentElement.getAttribute('href'));
    }
})

nonGarantie.addEventListener('change', ()=>{
    if(nonGarantie.checked){
        window.location.replace(nonGarantie.parentElement.getAttribute('href'));
    }else{
        window.location.replace(nonGarantie.parentElement.parentElement.getAttribute('href'));
    }
})