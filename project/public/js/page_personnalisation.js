// upload fichier image
// Loaded via <script> tag, create shortcut to access PDF.js exports.
let pdfjsLib = window['pdfjs-dist/build/pdf'];
// The workerSrc property shall be specified.
pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://mozilla.github.io/pdf.js/build/pdf.worker.js';

// les selecteur
let upload = document.getElementById('upload')
let dataFile = document.getElementById('dataFile')
let output = document.getElementById('output');
let logoPersonnalisation = document.getElementById('logoPersonnalisation');
let pdfViewer = document.getElementById('pdfViewer');
let zoneMarguage = document.querySelector('.zone-marquage');
let test = document.getElementById('test')
let canvas = document.querySelector("canvas");
let context = canvas.getContext('2d');

let logoWidth = document.querySelector('#logoWidth');
let logoHeight = document.querySelector('#logoHeight');
let logoTop = document.querySelector('#logoTop');
let logoLeft = document.querySelector('#logoLeft');

pdfViewer.style.cursor = 'all-scroll'
let size = []
let dataUri = ''

//upload du pdf ou image
upload.addEventListener("change", (e) => {
    let file = e.target.files[0]
    // la partie pour le pdf
    if (file.type == "application/pdf") {
        convertToBase64PDF(file)
        logoPersonnalisation.style.display = "block"
        pdfViewer.style.position = "relative"
        pdfViewer.style.display = "block"
        output.style.display = "none"
        pdfViewer.style.top = '0'
        pdfViewer.style.left = '0'
    }
    //les party pour les images
    if (file.type == "image/jpeg" || file.type == "image/png") {
        convertToBase64(file)
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
            // pour image
            base64 = fileLoadedEvent.target.result;
            //Print data in consoe
            //dataUri = base64
            console.log(base64);
            //dataFile.value = fileReader.result;
            //output.src = base64*/

            //pour canvas
            var myImage = new Image(); // Creates image object
            myImage.src = fileLoadedEvent.target.result; // Assigns converted image to image object
            dataFile.value = myImage.src
            //console.log(myImage.src)
            myImage.onload = function(ev) {
                pdfViewer.width = myImage.width; // Assigns image's width to canvas
                pdfViewer.height = myImage.height; // Assigns image's height to canvas

                // Prepare canvas using image page dimensions
                let scale = '';
                if (myImage.height > zoneMarguage.clientHeight || myImage.width > zoneMarguage.clientWidth) {
                    // calcul de scale
                    scaleY = zoneMarguage.clientWidth/myImage.width
                    scaleX = zoneMarguage.clientHeight/myImage.height

                    if(scaleY < 1){
                        scale = scaleY
                    }else if (scaleX < 1){
                        scale = scaleX
                    }
                    //la taille original * le scale < 1
                    pdfViewer.width = scale * myImage.width
                    pdfViewer.height = scale * myImage.height
                }
                context.drawImage(myImage, 0, 0, pdfViewer.width, pdfViewer.height); // Draws the image on canvas
                pdfViewer.toDataURL("image/jpeg"); // Assigns image base64 string in jpeg format to a variable

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
                while (viewport.height > zoneMarguage.clientHeight || viewport.width > zoneMarguage.clientWidth) {
                    scale -= 0.01
                    viewport = page.getViewport({scale: scale})
                }
                pdfViewer.height = viewport.height;
                pdfViewer.width = viewport.width;
                //context.scale(0.7,0.7)
                // information de la taille de l'image
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
    convertToBase64()
    fileReader.readAsArrayBuffer(event_target_files);
}

// position du pdf dans la zone de marquage
let pressing = false;
var offset = [0, 0];

pdfViewer.addEventListener("mousedown", (e) => {
    console.log(size)
    pressing = true;
    offset = [
        pdfViewer.offsetLeft - e.clientX,
        pdfViewer.offsetTop - e.clientY
    ];
    console.log(pdfViewer.offsetWidth)
    zoneMarguage.addEventListener('mouseout', (e) =>{
        console.log('la')
    })
})

// movement de l'image
pdfViewer.addEventListener("mousemove", (e) => {
    if (pressing === true) {

        mousePosition = {
            x: e.clientX,
            y: e.clientY
        };

       // pdfViewer.style.left = (mousePosition.x + offset[0]) + 'px';
        //pdfViewer.style.top = (mousePosition.y + offset[1]) + 'px';

        if(mousePosition.x + offset[0] > 0 && mousePosition.x + offset[0] < zoneMarguage.clientWidth - pdfViewer.width ){
            pdfViewer.style.left = mousePosition.x + offset[0] + 'px'
            logoLeft.value = pdfViewer.style.left.replace('px', '')

        }
        if(mousePosition.y + offset[1] > 0 && mousePosition.y + offset[1] < zoneMarguage.clientHeight - pdfViewer.height ){
            pdfViewer.style.top = mousePosition.y + offset[1] + 'px'
            logoTop.value = pdfViewer.style.top.replace('px', '')

        }
        console.log(pdfViewer.style.left)
        console.log(pdfViewer.style.top)
    }
})


window.addEventListener("mouseup", (e) => {
    pressing = false;
})

