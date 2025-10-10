var photo = document.getElementById("photo");
photo.classList.remove("photo-large");

var orig_width = document.getElementById("photo_width").innerHTML;
var orig_height = document.getElementById("photo_height").innerHTML;

function screenWidth(){
    // from https://stackoverflow.com/a/26191207

    return window.innerWidth && document.documentElement.clientWidth ?
            Math.min(window.innerWidth, document.documentElement.clientWidth) :
            window.innerWidth ||
            document.documentElement.clientWidth ||
            document.getElementsByTagName('body')[0].clientWidth;
}

var screen_width = screenWidth();

var computed_width = 1000;

if(screen_width < 1200){
    // we interpolate between known useful values for the two extremes

    // screen_width -> computed_width
    // 320          -> 240
    // 1200         -> 1000

    // now do some maths
    var m = (1000-240) / (1200-320);
    var c = 240 - 320 * m;
    computed_width = m * screen_width + c;
}

var computed_height = computed_width * orig_height / orig_width;

photo.width = computed_width;
photo.height = computed_height;
