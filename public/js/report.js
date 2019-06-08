function onClickBtnReport(e){
    e.preventDefault();

    const url = this.href;
    const spanCount = this.querySelector('span.js-report');
    const icon = this.querySelector('i');

    axios.get(url).then(function (response) {
        spanCount.textContent = response.data.report;

        if (icon.classList.contains('fas')) {
            icon.classList.replace('fas', 'far')
        }else{
            icon.classList.replace('far', 'fas')
        }
    }).catch(function (error) {
        if (error.response.status === 403) {
            window.alert("Il faut être co pour like")
        }else{
            window.alert("Une erreur s'est produite")
        }
    });
}

document.querySelectorAll('a.js-report').forEach(function (link) {
    link.addEventListener('click', onClickBtnReport);
})