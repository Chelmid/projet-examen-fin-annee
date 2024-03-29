//convertion de hex color en nom des color
let colors = document.querySelectorAll('.multiColor')

colors.forEach(color => {
    console.log(color.dataset.color)
    let n_match = ntc.name(color.dataset.color);
    n_name = n_match[1];
    console.log(n_name)
    color.innerHTML = n_name
})

// gestion des erreur dans le panier
let quantity = document.querySelectorAll('.quantite')
let messageError = document.querySelectorAll('.messageError')
let marquage = document.querySelector('.marquage')

//parcouri le nombre de item
for (let i = 0; i < quantity.length; i++) {
    //event le champs change
    quantity[i].addEventListener('change', e => {
        //
        if (marquage != null) {
            if (e.target.value == '' || e.target.value == 0 || e.target.value < 50 ) {
                messageError[i].innerHTML = 'La quantité est incorrect pi inferieur à 50'
                messageError[i].className = 'alert alert-danger'
            } else {
                axios.post('/cart/update/' + e.target.id + "/" + e.target.value, {
                    id: e.target.id,
                    quantity: e.target.value
                })
                    .then(function (response) {
                        messageError[i].innerHTML = 'La quantité va être modifier'
                        messageError[i].className = 'alert alert-success'
                        window.location.reload()
                    })
                    .catch(function (error) {
                        messageError[i].innerHTML = 'La quantité est superieur au stock'
                        messageError[i].className = 'alert alert-danger'
                    });
            }
        } else {
            if (e.target.value == '' || e.target.value == 0 ) {
                messageError[i].innerHTML = 'La quantité est incorrect'
                messageError[i].className = 'alert alert-danger'
            } else {
                axios.post('/cart/update/' + e.target.id + "/" + e.target.value, {
                    id: e.target.id,
                    quantity: e.target.value
                })
                    .then(function (response) {
                        console.log(response);
                        messageError[i].innerHTML = 'La quantité va être modifier'
                        messageError[i].className = 'alert alert-success'
                        window.location.reload()
                    })
                    .catch(function (error) {
                        messageError[i].innerHTML = 'La quantité est superieur au stock'
                        messageError[i].className = 'alert alert-danger'
                        console.log(error);
                    });
            }
        }
    })
}

//parcouri le nombre de item
for (let i = 0; i < quantity.length; i++) {
    //event le champs change
    quantity[i].addEventListener('input', e => {
        //string == false
        if(isNaN(e.target.value) == true){
            e.target.value = ''
        }
    })
}
