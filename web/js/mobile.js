/**
 * mobile.js
 *  
 */
function displayNav(id,direction)
{

    var x = document.getElementById('board_col_'+id) ;
    hide(x) ;

    if( direction === 'next')
    {
         var x2 = document.getElementById('board_col_'+ (id+1 )) ;
         show(x2) ;
    }
    else
    {
        var x3 = document.getElementById('board_col_'+ (id-1)) ;
        show(x3) ;
     }  
}

function hide(obj)
{
    obj.style.display = 'none' ;
}

function show(obj)
{
    obj.style.display = 'block' ;
}

function showMenu()
{
    document.getElementById("menupanel").style.display = "block";
    document.getElementById("overlay").style.display = "block";
}

function hideMenu()
{
    document.getElementById("menupanel").style.display = "none";
    document.getElementById("overlay").style.display = "none";
}

function move(destination)
{
    window.location = destination ;
}