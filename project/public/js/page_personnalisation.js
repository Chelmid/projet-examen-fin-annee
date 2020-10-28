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
    }
    //les party pour les images en png ou jpg
    if (file.type == "image/jpeg" || file.type == "image/png") {
        convertToBase64()
        //affichage
        logoPersonnalisation.style.display = "block"
        pdfViewer.style.position = "relative"
        pdfViewer.style.display = "block"
        output.style.display = "none"
        pdfViewer.style.top = '0'
        pdfViewer.style.left = '0'
    }
});

// convertir Base64
function convertToBase64() {
    //Read File
    let selectedFile = upload.files;

    //Check File is not Empty
    if (selectedFile.length > 0) {
        // Select the very first file from list
        let fileToLoad = selectedFile[0];
        // FileReader function for read the file.
        let fileReader = new FileReader();
        let base64;
        // Onload of file read the file content
        fileReader.onload = function (fileLoadedEvent) {
            // image en base64
            base64 = fileLoadedEvent.target.result;
            //Print data in console
            console.log(base64);
            dataFile.value = base64
            //dataFile.value = fileReader.result;
            //output.src = base64*/

            //pour canvas
            let myImage = new Image(); // Creates image object
            myImage.src = base64//fileLoadedEvent.target.result; // Assigns converted image to image object
            //dataFile.value = myImage.src

            //cahrgement de l'image
            myImage.onload = function (ev) {
                pdfViewer.width = myImage.width; // Assigns image's width to canvas
                pdfViewer.height = myImage.height; // Assigns image's height to canvas

                // Prepare canvas using image page dimensions
                let scale = '';
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

                    if (scaleY > scaleX) {
                        scale = scaleX
                    } else {
                        scale = scaleX
                    }

                    //la taille original * le scale < 1
                    pdfViewer.width = scale * myImage.width
                    pdfViewer.height = scale * myImage.height
                }
                //draw image
                context.drawImage(myImage, 0, 0, pdfViewer.width, pdfViewer.height); // Draws the image on canvas
                pdfViewer.toDataURL("image/jpeg"); // Assigns image base64 string in jpeg format to a variable

                //sauvegarder les données
                size['width'] = pdfViewer.width
                size['height'] = pdfViewer.height
                size['top'] = pdfViewer.style.top
                size['left'] = pdfViewer.style.left
                console.log(logoWidth)
                logoWidth.value = size['width']
                logoHeight.value = size['height']
                logoLeft.value = size['left'].replace('px', '')
                logoTop.value = size['top'].replace('px', '')
            }

        };
        // Convert data to base64
        fileReader.readAsDataURL(fileToLoad);
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

            // Fetch the first page
            let pageNumber = 1;
            pdf.getPage(pageNumber).then(function (page) {
                console.log('Page loaded');

                //resize du pdf
                let scale = 1;
                let viewport = page.getViewport({scale: scale});

                // Prepare canvas using PDF page dimensions
                while (viewport.height > zoneMarguage.clientHeight || viewport.width > zoneMarguage.clientWidth) {
                    scale -= 0.01
                    viewport = page.getViewport({scale: scale})
                }
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

                // Render PDF page into canvas context
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
    //affichage
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

        // pdfViewer.style.left = (mousePosition.x + offset[0]) + 'px';
        //pdfViewer.style.top = (mousePosition.y + offset[1]) + 'px';

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

//verification si il n'a pas de nom du file charger
if (nameFile.value != null || nameFile.value != '') {

    console.log(nameFile.value.search('.pdf'))
    // recherche de l'extension
    if (nameFile.value.search('.png') == -1 || nameFile.value.search('.jpg') == -1) {

        //objet image
        let img = new Image;
        img.onload = function () {
            context.drawImage(img, 0, 0, logoWidth.value, logoHeight.value); // Or at whatever offset you like
        };
        img.src = dataFile.value

        logoPersonnalisation.style.display = "block"
        pdfViewer.style.position = "relative"
        pdfViewer.style.display = "block"
        output.style.display = "none"
        pdfViewer.style.top = logoTop.value + 'px'
        pdfViewer.style.left = logoLeft.value + 'px'
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
    }
}

