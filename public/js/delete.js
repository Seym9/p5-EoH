function onClickBtnDelete(e){
    e.preventDefault();

    const choice =  confirm('Voulez vous vraiment effacer cet article ?');
    const url = this.href;
    const $targetEvent = $(e.target).parent().parent().parent().parent().parent();

    if (choice){
        axios.get(url).then(function (response) {
            if (response.status === 200) {
                $targetEvent.remove();
            }
        }).catch(function (error) {
            if (error.status === 403) {
                window.alert("Il faut être co pour like")
            }else{
                window.alert("Une erreur s'est produite");
                console.log(e);
            }
        });
    }
}
document.querySelectorAll('a.js-delete').forEach(function (link) {
    link.addEventListener('click', onClickBtnDelete);
})