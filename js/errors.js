

let login_error = {
    0: '',
    1: 'Identifiant non valide',
    2: 'Département non valide',
    3: 'Echec de récupération des notes, vérifiez votre identifiant et mot de passe'
};

function showError(){
    let url = new URL(window.location.href);
    let param = url.searchParams;
    let error_nb = param.get('error');
    let error_update = param.get('updateError');

    if(error_nb != null){
        M.toast({html: login_error[error_nb],class: 'red darken-1'});
        cleanURL();
    }
    if(error_update != null){
        M.toast({html: 'Temps restant avant de pouvoir re-actualiser : '+error_update,class: 'red darken-1'});
        cleanURL();
    }
}

function cleanURL(){
    var clean_uri = location.protocol + "//" + location.host + location.pathname;
    window.history.replaceState({}, document.title, clean_uri);
}