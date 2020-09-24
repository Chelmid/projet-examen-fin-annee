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