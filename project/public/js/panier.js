console.log('hello')

let colors = document.querySelectorAll('.multiColor')

colors.forEach(color => {
    console.log(color.dataset.color)
    let n_match = ntc.name(color.dataset.color);
    n_name = n_match[1];
    console.log(n_name )
    color.innerHTML = n_name
})

//let n_match = ntc.name("#6195ED");
//n_rgb = n_match[0]; // RGB value of closest match
//n_name = n_match[1]; // Text string: Color name
//n_exactmatch = n_match[2]; // True if exact color match

