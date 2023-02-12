//Variable Global para el funcionamiento
var posi = 2;

//Funciones para los botones

async function scrolling(){
    if (posi != 1){
        posi = 1;
    }
}

async function cursors(){
    if (posi != 2){
        posi = 2;
    }
}

async function arriba(){
    if(posi == 1){
        await fetch("/api/sarriba")
        .then((response) => response.json())
        .then((data) => console.log(data));
    } else if( posi == 2){
        await fetch("/api/arriba")
        .then((response) => response.json())
        .then((data) => console.log(data));
    }
}

async function izqui(){
    if(posi != 1){
        await fetch("/api/izquierda")
        .then((response) => response.json())
        .then((data) => console.log(data));
    }
}

async function okey(addr){
    if(posi != 1){
        await fetch("/api/ok")
        .then((response) => response.json())
        .then((data) => console.log(data));
    }
    
    
}

async function dere(){
    if(posi != 1){
        await fetch("/api/derecha")
        .then((response) => response.json())
        .then((data) => console.log(data));
    }
}

async function abajo(){
    if(posi == 1){
        await fetch("/api/sabajo")
        .then((response) => response.json())
        .then((data) => console.log(data));
    } else if( posi == 2){
        await fetch("/api/abajo")
        .then((response) => response.json())
        .then((data) => console.log(data));
    }
}
