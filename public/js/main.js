$('.navbar-collapse ul li a').click(function() {
    $('.navbar-toggler:visible').click();
});

$('.carousel').carousel({
    interval: 2600
})

$('#redes').tooltip(true);

$('.owl-carousel').owlCarousel({
    loop:true,
    margin:10,
    nav:false,
    dots:false,
    autoHeight:true,
    autoplay:true,
    autoplayHoverPause:false,
    responsive:{
        0:{
            items:1
        },
        600:{
            items:2
        },
        1000:{
            items:5
        }
    }
})

window.onscroll = function() {scrollFunction()};

function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        document.getElementById("btnScroll").style.display = "block";
    } else {
        document.getElementById("btnScroll").style.display = "none";
    }
}

function topFunction() {
    document.body.scrollTop = 0; 
    document.documentElement.scrollTop = 0; 
} 

var newSlider = new KiwwwiSlider(document.querySelectorAll('#slider')[0], 3000, 1);

function may(obj, id){
    obj = obj.toUpperCase();
    document.getElementById(id).value = obj;
}

// $('#buscar').keyUp(function (event) {
//     var contenido = new RegExp($(this).val(), 'i');
//     $('#cursor').hide();
//     $('#cursor').filter(function () {
//         return contenido.test($(this).text());
//     }).show();
// });