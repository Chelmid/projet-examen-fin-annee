/*document.getElementById('upload').addEventListener('change', showImage);

function showImage(evt) {
    var files = evt.target.files;

    if (files.length === 0) {
        console.log('No files selected');
        return;
    }

    var reader = new FileReader();
    reader.onload = function(event) {
        var img = new Image();
        img.onload = function() {
            document.body.appendChild(img);
        };
        img.src = event.target.result;
        document.getElementById('data-file').value = event.target.result;
    };
    reader.readAsDataURL(files[0]);
}*/

// upload fichier image
let openFile = function(event) {

  let input = event.target;
  console.log(input)

  let reader = new FileReader();
  if(input.type != "application/pdf"){

  }

  reader.onload = function(){
    let dataURL = reader.result;
    console.log(reader.result)
    document.getElementById('data-file').value = reader.result;
    let output = document.getElementById('output');
    output.src = dataURL;
  };
  reader.readAsDataURL(input.files[0]);
};