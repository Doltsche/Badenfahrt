function showImage(src, target) {
    var fr = new FileReader();
    // when image is loaded, set the src of the image where you want to display it
    fr.onload = function (e) {
        alert('Furz');
        target.src = this.result;
    };
    src.addEventListener("change", function () {
        alert('Furz');
        // fill fr with image data    
        fr.readAsDataURL(src.files[0]);
    });
}


