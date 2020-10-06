
var BASE64_MARKER = ';base64,';

function convertDataURIToBinary(dataURI) {
    var base64Index = dataURI.indexOf(BASE64_MARKER) + BASE64_MARKER.length;
    var base64 = dataURI.substring(base64Index);
    var raw = window.atob(base64);
    var rawLength = raw.length;
    var array = new Uint8Array(new ArrayBuffer(rawLength));

    for(var i = 0; i < rawLength; i++) {
        array[i] = raw.charCodeAt(i);
    }
    return array;
}

// upload fichier image
// Loaded via <script> tag, create shortcut to access PDF.js exports.
let pdfjsLib = window['pdfjs-dist/build/pdf'];
// The workerSrc property shall be specified.
pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://mozilla.github.io/pdf.js/build/pdf.worker.js';

let upload = document.getElementById('upload')

upload.addEventListener("change", (e) => {
    let file = e.target.files[0]
    // la partie pour le pdf
    if (file.type == "application/pdf") {

        let fileReader = new FileReader();
        fileReader.onload = function () {

            // Print data in console
            let pdfData = new Uint8Array(this.result);
            // Using DocumentInitParameters object to load binary data.
            let loadingTask = pdfjsLib.getDocument({data: pdfData});
            console.log(btoa(pdfData))
            document.getElementById('data-file').value = '';
            loadingTask.promise.then(function (pdf) {
                console.log('PDF loaded');

                // Fetch the first page
                let pageNumber = 1;
                pdf.getPage(pageNumber).then(function (page) {
                    console.log('Page loaded');

                    let scale = 0.5;
                    let viewport = page.getViewport({scale: scale});

                    // Prepare canvas using PDF page dimensions
                    let canvas = document.querySelector("canvas");
                    let context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    // Render PDF page into canvas context
                    let renderContext = {
                        canvasContext: context,
                        viewport: viewport
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

        fileReader.readAsArrayBuffer(file);
    }
    //les party pour les images
    if (file.type == "image/jpeg" || file.type == "image/png") {

        let fileReader = new FileReader();

        fileReader.onload = function () {
            let dataURL = fileReader.result;
            console.log(fileReader.result)
            document.getElementById('data-file').value = fileReader.result;
            let output = document.getElementById('output');
            output.src = dataURL;
        };
        fileReader.readAsDataURL(file);
    }
});

let imgOriginal = document.querySelector('.imgOriginal');
let output = document.getElementById('output');
console.log(output);

if (imgOriginal.clientHeight != null) {
    console.log(imgOriginal.clientHeight);
    console.log(imgOriginal.clientWidth);
}

