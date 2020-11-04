// upload fichier image

// Loaded via <script> tag, create shortcut to access PDF.js exports.
let pdfjsLib = window['pdfjs-dist/build/pdf'];

// The workerSrc property shall be specified.
pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://mozilla.github.io/pdf.js/build/pdf.worker.js';

// les selecteur dans la page personnalisation
let upload = document.getElementById('upload')
let dataFile = document.getElementById('dataFile')
let output = document.getElementById('output');
let logoPersonnalisation = document.getElementById('logoPersonnalisation');
let pdfViewer = document.getElementById('pdfViewer');
let zoneMarguage = document.querySelector('.zone-marquage');
let test = document.getElementById('test')
let canvas = document.querySelector("canvas");
let context = canvas.getContext('2d');
let errorFormat = document.querySelector('.errorFormat');
let nomFile = document.querySelector('.nomFile');

//personnalisation
let logoWidth = document.querySelector('#logoWidth');
let logoHeight = document.querySelector('#logoHeight');
let logoTop = document.querySelector('#logoTop');
let logoLeft = document.querySelector('#logoLeft');
let nameFile = document.getElementById('nameFile')

// mouse pointeur
pdfViewer.style.cursor = 'all-scroll'

//information pour du logo (nom, taille, position ...)
let size = []

//upload du pdf ou image
upload.addEventListener("change", (e) => {
    let file = e.target.files[0]
    // la partie pour le pdf
    if (file.type == "application/pdf") {
        convertToBase64PDF(file)
        //affichage
        logoPersonnalisation.style.display = "block"
        pdfViewer.style.position = "relative"
        pdfViewer.style.display = "block"
        output.style.display = "none"
        pdfViewer.style.top = '0'
        pdfViewer.style.left = '0'
        nomFile.textContent = upload.value.replace('C:\\fakepath\\',"")
        //partie image
    }else if (file.type == "image/jpeg" || file.type == "image/png"){
        convertToBase64()
        //affichage
        logoPersonnalisation.style.display = "block"
        pdfViewer.style.position = "relative"
        pdfViewer.style.display = "block"
        output.style.display = "none"
        pdfViewer.style.top = '0'
        pdfViewer.style.left = '0'
        nomFile.textContent = upload.value.replace('C:\\fakepath\\',"")
    }else {
        //error format et extension
        errorFormat.className = 'alert alert-danger'
        errorFormat.innerHTML = 'Veuillez mettre un fichier JPEG / PNG / PDF'
    }
});

// convertir Base64
function convertToBase64() {
    //Lire le fichier
    let selectedFile = upload.files;

    //check le fichier n'est pas vide
    if (selectedFile.length > 0) {
        // Selectionner le premier de la liste
        let fileToLoad = selectedFile[0];
        // FileReader lire le fichier avec la function.
        let fileReader = new FileReader();
        let base64;
        // Onload le contenu du fichier
        fileReader.onload = function (fileLoadedEvent) {
            // image en base64, le resultat de l'evenement
            base64 = fileLoadedEvent.target.result;
            //afficher les données dans la console
            console.log(base64);
            //le champs prends la valeur de base64
            dataFile.value = base64

            //pour canvas
            let myImage = new Image(); // crée un objet image
            myImage.src = base64// assigner la base64 a l'objet image

            //chargement de l'image
            myImage.onload = function (ev) {
                pdfViewer.width = myImage.width; // assigner la longueur au canvas
                pdfViewer.height = myImage.height; // assigner la hauteur au canvas

                // Prepare canvas using image page dimensions
                //preparer le canvas avec les dimension de l'image
                let scale = '';
                // image depasse pas la taille de la zone de marquarge
                if (myImage.height > zoneMarguage.clientHeight || myImage.width > zoneMarguage.clientWidth) {
                    // calcul de scale
                    scaleY = zoneMarguage.clientWidth / myImage.width
                    scaleX = zoneMarguage.clientHeight / myImage.height

                    //comparaison des scales par rapport a X et Y
                    if (scaleY < 1) {
                        scaleY = scaleY
                    }
                    if (scaleX < 1) {
                        scaleX = scaleX
                    }
                    // comparer le scaleX et scaleY  (celui le plus petit)
                    if (scaleY > scaleX) {
                        scale = scaleX
                    } else {
                        scale = scaleY
                    }
                    //la taille original * le scale < 1
                    pdfViewer.width = scale * myImage.width
                    pdfViewer.height = scale * myImage.height
                }
                //draw image
                context.drawImage(myImage, 1, 1, pdfViewer.width, pdfViewer.height); // dessin l'image dans le canvas
                pdfViewer.toDataURL("image/jpeg"); // assigner l'image base64 dans le format

                //sauvegarder les données
                size['width'] = pdfViewer.width
                size['height'] = pdfViewer.height
                size['top'] = pdfViewer.style.top
                size['left'] = pdfViewer.style.left
                //les champs prend les données
                logoWidth.value = size['width']
                logoHeight.value = size['height']
                logoLeft.value = size['left'].replace('px', '')
                logoTop.value = size['top'].replace('px', '')
            }

        };
        // Convert data to base64
        errorFormat.className = 'alert alert-success' //message de confirmation
        errorFormat.innerHTML = 'Le fichier est bien chargé' //message de confirmation
        fileReader.readAsDataURL(fileToLoad); //lire le fichier
    }
}

//display du pdf
function convertToBase64PDF(event_target_files) {
    //instance libary pour le pdf
    let fileReader = new FileReader();
    fileReader.onload = function () {

        // Print data in console
        let pdfData = new Uint8Array(this.result);
        console.log(pdfData)
        // Using DocumentInitParameters object to load binary data.
        let loadingTask = pdfjsLib.getDocument({data: pdfData});

        loadingTask.promise.then(function (pdf) {
            console.log('PDF loaded');

            // afficher la 1er page
            let pageNumber = 1;
            pdf.getPage(pageNumber).then(function (page) {
                console.log('Page loaded');

                //resize du pdf
                let scale = 1;
                let viewport = page.getViewport({scale: scale});

                //preparer le canvas pour le pdf et resize le pdf
                while (viewport.height > zoneMarguage.clientHeight || viewport.width > zoneMarguage.clientWidth) {
                    scale -= 0.01
                    viewport = page.getViewport({scale: scale})
                }
                //les nouvelles dimension du pdf
                pdfViewer.height = viewport.height;
                pdfViewer.width = viewport.width;

                //sauvegarde pour des dimension est position du pdf
                size['width'] = pdfViewer.width
                size['height'] = pdfViewer.height
                size['top'] = pdfViewer.style.top
                size['left'] = pdfViewer.style.left
                console.log(logoWidth)
                logoWidth.value = size['width']
                logoHeight.value = size['height']
                logoLeft.value = size['left'].replace('px', '')
                logoTop.value = size['top'].replace('px', '')

                //mettre la page pdf dans le context canvas
                let renderContext = {
                    canvasContext: context,
                    viewport: viewport,
                    background: 'rgba(0,0,0,0)'
                };

                //pret a afficher
                let renderTask = page.render(renderContext);
                renderTask.promise.then(function () {
                    console.log('Page rendered');
                });
            });
        }, function (reason) {
            // PDF loading error
            console.error(reason);
        });
    };
    convertToBase64()
    //message de success
    errorFormat.className = 'alert alert-success'
    errorFormat.innerHTML = 'Le fichier est bien chargé'
    //lire le fichier
    fileReader.readAsArrayBuffer(event_target_files);
}

// position du pdf dans la zone de marquage
let pressing = false;
let offset = [0, 0];

//click bas de la souris
pdfViewer.addEventListener("mousedown", (e) => {
    console.log(size)
    pressing = true;
    //position de la souris au moment du click sur la page
    offset = [
        pdfViewer.offsetLeft - e.clientX,
        pdfViewer.offsetTop - e.clientY
    ];
})

// movement de la souris
pdfViewer.addEventListener("mousemove", (e) => {
    if (pressing === true) {
        //position de la souris en movement sur la page
        mousePosition = {
            x: e.clientX,
            y: e.clientY
        };

        //movement X dans de la souris dans la zone avec l'image
        if (mousePosition.x + offset[0] > 0 && mousePosition.x + offset[0] < zoneMarguage.clientWidth - logoWidth.value) {
            pdfViewer.style.left = mousePosition.x + offset[0] + 'px'
            logoLeft.value = pdfViewer.style.left.replace('px', '')
        }

        //movement Y dans de la souris dans la zone avec l'image
        if (mousePosition.y + offset[1] > 0 && mousePosition.y + offset[1] < zoneMarguage.clientHeight - logoHeight.value) {
            pdfViewer.style.top = mousePosition.y + offset[1] + 'px'
            logoTop.value = pdfViewer.style.top.replace('px', '')

        }
        console.log(zoneMarguage.clientWidth)
        console.log(pdfViewer.width)
    }
})

//déclick du bouton de la souris
window.addEventListener("mouseup", (e) => {
    pressing = false;
})

console.log(nameFile)
//verification si il n'a pas de nom du file charger
if (nameFile != null) {

    console.log(nameFile.value.search('.pdf'))
    // recherche de l'extension
    if (nameFile.value.search('.png') == -1 || nameFile.value.search('.jpg') == -1) {

        //objet image
        let img = new Image;
        pdfViewer.width = logoWidth.value; // Assigns image's width to canvas
        pdfViewer.height = logoHeight.value
        img.src = dataFile.value
        img.onload = function () {
            context.drawImage(img, 1, 1, pdfViewer.width, pdfViewer.height); // Or at whatever offset you like
        };

        logoPersonnalisation.style.display = "block"
        pdfViewer.style.position = "relative"
        pdfViewer.style.display = "block"
        output.style.display = "none"
        pdfViewer.style.top = logoTop.value + 'px'
        pdfViewer.style.left = logoLeft.value + 'px'
        nomFile.textContent = nameFile.value
    }

    // recherche de l'extension
    if (nameFile.value.search('.pdf') > 0) {

        let b64 = dataFile.value
        base64 = b64.replace('data:application/pdf;base64,', "")

        //convertion de Base64 en binary
        let binary = atob(base64);
        console.log(new Uint8Array(binary.length))
        let buffer = new Uint8Array(binary.length)
        let ba = new Uint8Array(buffer);
        for (let i = 0; i < binary.length; i++) {
            ba[i] = binary.charCodeAt(i);
        }
        //le fichier
        let file = new Blob([ba], {type: "application/pdf"});

        // instance de la librairy
        let fileReader = new FileReader();
        fileReader.onload = function () {

            // Print data in console
            let loadingTask = pdfjsLib.getDocument({data: ba});
            // Using DocumentInitParameters object to load binary data.
            loadingTask.promise.then(function (pdf) {
                console.log('PDF loaded');

                // Fetch the first page
                let pageNumber = 1;
                pdf.getPage(pageNumber).then(function (page) {
                    console.log('Page loaded');

                    let scale = 1;
                    let viewport = page.getViewport({scale: scale});

                    while (viewport.height > zoneMarguage.clientHeight || viewport.width > zoneMarguage.clientWidth) {
                        scale -= 0.01
                        viewport = page.getViewport({scale: scale})
                    }

                    pdfViewer.height = viewport.height;
                    pdfViewer.width = viewport.width;

                    // Render PDF page into canvas context
                    let renderContext = {
                        canvasContext: context,
                        viewport: viewport,
                        background: 'rgba(0,0,0,0)'
                    };

                    let renderTask = page.render(renderContext);
                    renderTask.promise.then(function () {
                        console.log('Page rendered');
                    });
                });
            }, function (reason) {
                // PDF loading error
                console.error(reason);
            });
        };
        //affichage
        fileReader.readAsArrayBuffer(file);

        logoPersonnalisation.style.display = "block"
        pdfViewer.style.position = "relative"
        pdfViewer.style.display = "block"
        output.style.display = "none"
        pdfViewer.style.top = logoTop.value + 'px'
        pdfViewer.style.left = logoLeft.value + 'px'
        nomFile.textContent = nameFile.value
    }
}else {

}

