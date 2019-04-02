var tab = {
    'INFO' : '../images/mini/INFO.jpg',
    'GB' : '../images/mini/GB.jpg',
    'TC' : '../images/blank.png',
    'MMI' : '../images/mini/MMI.jpg'
};

var tab_name = {
    'INFO' : 'DUT INFORMATIQUE',
    'GB' : 'DUT GÉNIE BIOLOGIQUE',
    'TC' : 'DUT TECHNIQUES DE COMMUNICATIONS',
    'MMI' : 'DUT MÉTIERS DU MULTIMÉDIA ET DU NUMÉRIQUE'
};

function updateSlider(){
    //Get user info
    $.getJSON('../php/getUserInfo.php',function(data){
        //Edit html
        document.getElementById('slider_name').innerHTML = data.id;
        document.getElementById('slider_dep').innerHTML = tab_name[data.dep];
        //Update slider image
        document.getElementById('slider_img').src = tab[data.dep];
    });
}