// upload fichier image
let openFile = function (event) {

    let input = event.target;
    console.log(input)

    let reader = new FileReader();
    if (input.type != "application/pdf") {

    }

    reader.onload = function () {
        let dataURL = reader.result;
        console.log(reader.result)
        document.getElementById('data-file').value = reader.result;
        let output = document.getElementById('output');
        output.src = dataURL;
    };
    reader.readAsDataURL(input.files[0]);
};

let imgOriginal = document.querySelector('.imgOriginal');


if( imgOriginal.clientHeight != null ){
    console.log(imgOriginal.clientHeight);
    console.log(imgOriginal.clientWidth);
}













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

