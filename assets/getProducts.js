let mesArticles = document.getElementsByClassName('js-articles-table')[0];
let searchButton = document.getElementsByClassName('search')[0];

function display(data){
    let core = '';
    let pages = '';
    console.log(data);
    let i = 0;
    Object.values(data).forEach(val =>{
        if(typeof val !== 'string' && !(val instanceof String)){
            if(i%2){
                core = core.concat('<div class=\'row col-12 g-0 col bg-info justify-content-around bg-opacity-50 text-center\'>');
            }else{
                core = core.concat('<div class=\'row col-12 g-0 col bg-light justify-content-around text-center\'>');
            }
            core = core.concat('<p class=\'col-2 col-md-1 m-auto\'>', val['id'], '</p><p class=\'col-sm-3 m-auto\' my-2>', val['nom'], '</p><p class=\'col-sm-3 m-auto\'>', val['date']['date'].slice(0,10), '</p><div class=\'row col-md-5 g-0\'><a class=\'col text-decoration-none m-sm-2\' href=\'/article/', val['id'], '\'><button type=\'button\' class=\'btn btn-primary mx-1\'>Plus d\'infos</button></a><a class=\'col text-decoration-none m-sm-2\' href=\'/article/delete/', val["id"], '\'><button type=\'button\' class=\'btn btn-danger mx-1\'>Supprimer</button></a></div>');
            i++;
        }
    })
    $('.js-articles-table').html(core);
}

function search(value){
    if(value == ''){
        value = 'reset';
    }
    $.ajax({
        url: '/article',
        type: 'POST',
        dataType:'json',
        data: {
            search : value
        },
        async:true,
        success: display
    });
    return false;
}

searchButton.addEventListener('click', () => search(searchButton.previousElementSibling.value));