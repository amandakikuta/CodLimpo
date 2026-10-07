//* Objeto JS para req AJAX
var xhttp = new XMLHttpRequest();

function favoritar(id){
    //* Define endereco que será requisitado assincronamente
    xhttp.open('GET', './../Controller/Viagem.ctrl.php?act=favoritar&id='+id, true);
    
    //* Função chamada quando a resposta voltar
    xhttp.onreadystatechange = function(){
        if (this.readyState === 4 && this.status === 200) {
            var item = document.getElementById(id);
            if(item.style.display == "none"){
                item.style.display = 'unset';
            }
            else if(item.style.display != "none"){
                item.style.display = 'none';
            }
        }
    };

    //* Envia requisição
    xhttp.send();
}

