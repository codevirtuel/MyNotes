
//Template pour la liste des semestres

var tabs_transform = {'<>':'li','class':'tab col s3','html' : [
                        {'<>':'a','href':function(){
                                if(this.semestre === "Semestre 1") return('#s1');
                                if(this.semestre === "Semestre 2") return('#s2');
                                if(this.semestre === "Semestre 3") return('#s3');
                                if(this.semestre === "Semestre 4") return('#s4');
                            }, 'html':'${semestre}','class':'hide-on-small-only'},
                        {'<>':'a','href':function(){
                                if(this.semestre === "Semestre 1") return('#s1');
                                if(this.semestre === "Semestre 2") return('#s2');
                                if(this.semestre === "Semestre 3") return('#s3');
                                if(this.semestre === "Semestre 4") return('#s4');
                            }, 'html':function(){
                                var sem_array = this.semestre.split(" ");
                                var semestre = sem_array[0].charAt(0)+sem_array[1].charAt(0);
                                return(semestre);
                            },'class':'hide-on-med-and-up'}
                    ]};


//Template pour créer une note de matière (pratique, théorique)
var note_transform = {'<>':'li','class':'collection-item row','html':[
        {'<>':'div','class':'col s5 m5','html':'${description}'},
        {'<>':'div','class':'col s5 m5','html':[
                {'<>':'span','data-badge-caption':'/ 20','class':function(){
                    if(parseFloat(this.value) > 10) return ('note-green badge z-depth-1');
                    else if(parseFloat(this.value) === 10) return ('note-orange badge z-depth-1');
                    else return ('note-red badge z-depth-1');
                    },'html':'${value}'}
            ]},
    ]};


var matiere_transform = {'<>':'div','html':[
        //Entête
        {'<>':'div','class':'row card-panel orange','style':'color:white','html':[
                {'<>':'div','class':'col','html':[
                        {'<>':'i','class':'material-icons valign-wrapper','html':'class'},
                    ]},
                {'<>':'div','class':'col s5 m5','html':[
                        {'<>':'span','style':'font-weight: bold','html':'${intitule}'}
                    ]},
                {'<>':'div','class':'col s5 m5','html':[
                        {'<>':'span','style':'background-color: white','data-badge-caption':'/ 20','class':function(){
                                if(parseFloat(this.moyenne) > 10) return ('note-green badge z-depth-1');
                                else if(parseFloat(this.moyenne) === 10) return ('note-orange badge z-depth-1');
                                else return ('note-red badge z-depth-1');
                            },'html':'${moyenne}','style':function(){
                                if(this.moyenne === "") return('visibility: hidden');
                                else return('visibility: visible');
                            }}
                    ]}
            ]},
        //Notes
        {'<>':'ul','class':'collection','html':function(){
                var filtered = this.notes.filter(function(value, index, arr){
                    return value.value != null;
                });
                return(json2html.transform(filtered,note_transform));
            }}
    ]};

var UE_transform = {'<>':'div','html':[
        //Entête
        {'<>':'div','class':'row card-panel red lighten-1','style':'color:white','html':[
                {'<>':'div','class':'col','html':[
                        {'<>':'i','class':'material-icons valign-wrapper','html':'view_list'}
                    ]},
                {'<>':'div','class':'col','html':[
                        {'<>':'span','style':'font-weight: bold','html':'${intitule}'}
                    ]}
            ]},
        //Matières
        {'<>':'div','html':function(){
                return(json2html.transform(this.matieres,matiere_transform));
            }}

    ]};

var semestre_transform = {'<>':'div','class':'col s12','id':function(){
        var sem_array = this.intitule.split(" ");
        if(sem_array[0]+" "+sem_array[1] === "Semestre 1") return('s1');
        if(sem_array[0]+" "+sem_array[1] === "Semestre 2") return('s2');
        if(sem_array[0]+" "+sem_array[1] === "Semestre 3") return('s3');
        if(sem_array[0]+" "+sem_array[1] === "Semestre 4") return('s4');
    },'html':function(){
        return(json2html.transform(this.content,UE_transform));
}};

function generateTabs(json){
    var tabs_data = new Array();
    var result = '<div class="row">' +
        '<div class="col s12">\n' +
        '<ul class="tabs">';

    //Generate tabs
    for(annee in json){
        var year = json[annee];
        for(semestre in year){
            var sem = year[semestre];
            var sem_array = sem['intitule'].split(" ");
            tabs_data.push({'semestre':sem_array[0]+" "+sem_array[1]});
        }
    }

    result += json2html.transform(tabs_data,tabs_transform);
    result += '</ul></div>';

    for(annee in json){
        var year = json[annee];
        for(semestre in year){
            var sem = year[semestre];
            var sem_array = sem['intitule'].split(" ");

            result += json2html.transform(sem,semestre_transform);
        }
    }

    result += '</div>';
    return result;
}

function load(element) {
    $.getJSON("../php/getUserNotesJson.php",function(data){
        document.getElementById(element).innerHTML = generateTabs(data);
        //$('.tabs').tabs();
        var elems = document.querySelectorAll('.tabs');
        var instances = M.Tabs.init(elems);
        instances[0].select('s1');
        instances[0].updateTabIndicator();
    });
}