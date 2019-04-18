/* Add this block to the global.js file of your theme */
if($(".owl-cat").length > 0 && $.fn.owlCarousel !== undefined) {
    $(".owl-cat").owlCarousel(Object.assign({},owlOptions,{
        responsive:{
            0:{
                items:1,
                margin: 0
            },
            480:{
                items:2,
                margin: 0
            },
            768:{
                items:2,
                margin: 30
            },
            992:{
                items:3,
                margin: 30
            }
        }
    }));
}