let mesArticles = document.getElementsByClassName('js-articles-table')[0];

function recupererArticles(...$conditions){
    $.ajax({
        url: '/articles/ajax',
        type: 'get',
        data: {
            $conditions : arguments
        },
        success: function(response){
            mesArticles.innerHTML = response;
        }
    })
}