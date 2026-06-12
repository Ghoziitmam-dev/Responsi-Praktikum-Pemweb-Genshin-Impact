var elements = document.querySelectorAll(".elements img");
var loadingText = document.getElementById("loadingText");
var index = 0;
var loop = 0;
var titik = 1;

setInterval(function(){
    if(titik == 1){
        loadingText.innerHTML = "Loading.";
    }
    else if(titik == 2){
        loadingText.innerHTML = "Loading..";
    }
    else{
        loadingText.innerHTML = "Loading...";
        titik = 0;
    }
    titik++;
},500);

function nyalakanElement(){
    if(index < elements.length){
        elements[index].classList.add("active");
        index++;
        setTimeout(nyalakanElement,250);
    }
    else{
        loop++;
        setTimeout(function(){
            for(var i=0; i<elements.length; i++){
                elements[i].classList.remove("active");
            }
            index = 0;
            if(loop < 3){
                setTimeout(nyalakanElement,100);
            }
            else{
                var logo = document.querySelector(".logo");
                logo.classList.add("logo-hide");
                setTimeout(function(){
                        document.body.classList.add("flash");
                },800);
                setTimeout(function(){
                        window.location.href = "../StartPage/start.html";
                },1000);
            }
        }, 1000);
    }
}
nyalakanElement();