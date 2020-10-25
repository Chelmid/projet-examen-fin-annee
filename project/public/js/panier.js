console.log('hello')

let colors = document.querySelectorAll('.multiColor')

colors.forEach(color => {
    console.log(color.dataset.color)
    let n_match = ntc.name(color.dataset.color);
    n_name = n_match[1];
    console.log(n_name)
    color.innerHTML = n_name
})

//let n_match = ntc.name("#6195ED");
//n_rgb = n_match[0]; // RGB value of closest match
//n_name = n_match[1]; // Text string: Color name
//n_exactmatch = n_match[2]; // True if exact color match


let quantity = document.querySelectorAll('.quantite')
let messageError = document.querySelectorAll('.messageError')

for (let i = 0; i < quantity.length; i++) {
    console.log(quantity[i]);
    quantity[i].addEventListener('change', e => {
        console.log(e.target.id)
        if(e.target.value != '' || e.target.value != 0){
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
        }else{
            messageError[i].innerHTML = 'Veuillez mettre une quantité'
            messageError[i].className = 'alert alert-danger'
        }
    })
}
