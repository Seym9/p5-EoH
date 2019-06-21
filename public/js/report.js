function onClickBtnReport(e){
    e.preventDefault();
    const url = this.href;


    axios.get(url).then(function (response) {
        console.log(response);
        if (response.data.message === "Reported") {
            window.alert("Vous avez bien signaler ce commentaire !")
        }else if(response.data.message === "TopicReported"){
            window.alert("Vous avez bien signaler ce sujet !")
        }
    }).catch(function (error) {
        if (error.response.data.message === "Already reported") {
            window.alert("Vous avez déjà signaler ce commentaire !")
        }else if(error.response.data.message ==="Unauthorized"){
            window.alert("Vous devez être connecter pour signalier un commentaire !")
        }else{
            window.alert("Une erreur s'est produite")
        }
    });
}

document.querySelectorAll('a.js-report').forEach(function (link) {
    link.addEventListener('click', onClickBtnReport);
})