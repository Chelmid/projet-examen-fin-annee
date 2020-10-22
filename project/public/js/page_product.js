//controle de la quantité dans la pasge de produit et bution est déactiver qsi il n'a pas de value

let stockProduct = document.querySelectorAll('.stock');

let quantityProductpersonnalisation = document.querySelectorAll('.quantity-product-personnalisation');
let btnpersonnalisation = document.querySelector('.displayBtnPersonnalisation')

for (let i = 0; i < quantityProductpersonnalisation.length; i++) {
    quantityProductpersonnalisation[i].addEventListener('input', e => {
        if (!Number(e.target.value)) {
            e.target.value = ""
            btnpersonnalisation.disabled = true;
        } else {
            let integer = parseInt(stockProduct[i].textContent, 10);
            if (e.target.value < 0 || e.target.value == '' || e.target.value > integer) {
                e.target.value = ""
                btnpersonnalisation.disabled = true;
            } else {
                btnpersonnalisation.disabled = false;
            }
        }
    });
}

let quantityProduct = document.querySelectorAll('.quantity-product');
let btn = document.querySelector('.displayBtn')

for (let i = 0; i < quantityProduct.length; i++) {
    quantityProduct[i].addEventListener('input', e => {
        if (!Number(e.target.value)) {
            e.target.value = ""
            btn.disabled = true;
        } else {
            let integer = parseInt(stockProduct[i].textContent, 10);
            if (e.target.value < 0 || e.target.value == '' || e.target.value > integer) {
                e.target.value = ""
                btn.disabled = true;
            } else {
                btn.disabled = false;
            }
        }
    });
}



