
function updateBackground(){
    var tab = {
        'INFO' : './images/INFO.jpg',
        'GB' : './images/GB.jpg',
        'TC' : './images/blank.png',
        'MMI' : './images/MMI.jpg',
        'IUT' : './images/IUT.jpg'
    };

    var dep = document.getElementById("dep_select").value;
    var background_top = document.getElementsByClassName("top");
    var background_bottom = document.getElementsByClassName("bottom");
    var actual_img = background_bottom[0].src;

    //Set top
    background_top[0].style.transition = "opacity 0s ease-out";
    background_top[0].src = actual_img;
    background_top[0].style.background = "no-repeat center center";
    background_top[0].style.backgroundSize = "cover";
    background_top[0].style.opacity = 1;

    //Set new bottom
    background_bottom[0].src = tab[dep];
    background_bottom[0].style.background = "no-repeat center center";
    background_bottom[0].style.backgroundSize = "cover";
    setTimeout(function(){
        background_top[0].style.transition = "opacity 1s ease-in-out";
        background_top[0].style.opacity = 0;
    }, 1000);
}

function checkCookie(){
    $.getJSON('php/getUserInfo.php',function (data) {
        if(data.length !== 0){
            window.location.href = 'notes.html';
        }
    });
}

function getCookie(name)
{
    var re = new RegExp(name + "=([^;]+)");
    var value = re.exec(document.cookie);
    return (value != null) ? unescape(value[1]) : null;
}