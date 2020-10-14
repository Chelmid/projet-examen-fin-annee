// upload fichier image
// Loaded via <script> tag, create shortcut to access PDF.js exports.
let pdfjsLib = window['pdfjs-dist/build/pdf'];
// The workerSrc property shall be specified.
pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://mozilla.github.io/pdf.js/build/pdf.worker.js';

let upload = document.getElementById('upload')
let dataFile = document.getElementById('dataFile')
let output = document.getElementById('output');
let logoPersonnalisation = document.getElementById('logoPersonnalisation');
let pdfViewer = document.getElementById('pdfViewer');
let zoneMarguage = document.querySelector('.zone-marquage');
let test = document.getElementById('test')
let canvas = document.querySelector("canvas");
let context = canvas.getContext('2d');

let dataUri = ''


upload.addEventListener("change", (e) => {
    let file = e.target.files[0]
    // la partie pour le pdf
    if (file.type == "application/pdf") {
        convertToBase64PDF(file)
        logoPersonnalisation.style.display = "block"
        pdfViewer.style.position = "relative"
        pdfViewer.style.display = "block"
        output.style.display = "none"
    }
    //les party pour les images
    if (file.type == "image/jpeg" || file.type == "image/png") {
        convertToBase64()
        logoPersonnalisation.style.display = "block"
        //output.style.position = "absolute"
        pdfViewer.style.display = "none"
        output.style.display = "block"
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
            base64 = fileLoadedEvent.target.result;
            // Print data in console
            dataUri = base64
            console.log(base64);
            dataFile.value = fileReader.result;
            output.src = base64;
        };
        // Convert data to base64
        fileReader.readAsDataURL(fileToLoad);
    }
}

function convertToBase64PDF(event_target_files) {

    let fileReader = new FileReader();
    fileReader.onload = function () {

        // Print data in console
        let pdfData = new Uint8Array(this.result);
        // Using DocumentInitParameters object to load binary data.
        let loadingTask = pdfjsLib.getDocument({data: pdfData});

        loadingTask.promise.then(function (pdf) {
            console.log('PDF loaded');

            // Fetch the first page
            let pageNumber = 1;
            pdf.getPage(pageNumber).then(function (page) {
                console.log('Page loaded');

                let scale = 1;
                let viewport = page.getViewport({scale: scale});

                // Prepare canvas using PDF page dimensions
                while (viewport.height > zoneMarguage.clientHeight || viewport.width > zoneMarguage.clientWidth){
                    scale -= 0.1
                    viewport = page.getViewport({scale: scale})
                }
                pdfViewer.height = viewport.height;
                pdfViewer.width = viewport.width;
                //context.scale(0.7,0.7)
                // information de la taille de l'image

                // Render PDF page into canvas context
                let renderContext = {
                    canvasContext: context,
                    viewport: viewport,
                    background: 'rgba(0,0,0,0)'
                };

                //context.scale(0.7, 0.7)
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
    fileReader.readAsArrayBuffer(event_target_files);
}

let pressing = false;

test.addEventListener("mousedown", (e) => {
    pressing = true;
    test.style.height = pdfViewer.offsetHeight + 'px'
    test.style.width = pdfViewer.offsetWidth + 'px'
    console.log(pdfViewer.offsetWidth)
})


test.addEventListener("mousemove", (e) => {
    if (pressing === true) {
        // pdfViewer.style.top = `${e.pageY}px`
        //pdfViewer.style.left =`${e.pageX}px`
        //console.log(e.pageY)

        var cWidth = pdfViewer.offsetWidth
        var cHeight = pdfViewer.offsetHeight;
        moveXAmount = (e.offsetX / zoneMarguage.clientHeight) * 200;
        //moveXAmount = moveXAmount - (cHeight/2);
        moveYAmount = (e.offsetY / zoneMarguage.clientWidth) * 200;
        //moveYAmount = moveYAmount + (cWidth/2);
        console.log(moveYAmount)
        pdfViewer.style.top = moveYAmount + 'px'
        pdfViewer.style.left = moveXAmount + 'px'
    }
})


window.addEventListener("mouseup", (e) => {
    pressing = false;
})

