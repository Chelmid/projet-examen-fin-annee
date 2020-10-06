//controle de la quantité dans la pasge de produit et bution est déactiver qsi il n'a pas de value

let quantityProduct = document.querySelectorAll('.quantity-product');
let btn = document.querySelector('.displayBtn')

quantityProduct.forEach((quantity) => {
    quantity.addEventListener('input', (e) => {
        if (!Number(e.target.value)) {
            e.target.value = ""
        }else{
            if(e.target.value >= 50){
                btn.disabled = false;
            }else{
                btn.disabled = true;
            }
        }
    })
})