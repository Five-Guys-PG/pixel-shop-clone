jQuery(function($){
    $(window).load(function() {
        setTimeout(() => {
            $("#st_preloader_wrap").fadeOut("slow");
        }, 1000); // Preloader: adjust time of disappearing
    });
    var st_preloader_mater = function(xy){
        if(xy)
            $('#st_preloader_wrap').removeClass('st_hide_preloader');
        else
            $('#st_preloader_wrap').addClass('st_hide_preloader');
    }
    if(typeof(prestashop)!='undefined'){
        prestashop.on('updateFacets', function(){
            if(typeof(stpreloader)!=='undefined' && stpreloader.enable)
            st_preloader_mater(1);
        });
        prestashop.on('updateProductList', function(){
            if(typeof(stpreloader)!=='undefined' && stpreloader.enable)
            st_preloader_mater(0);
        });
    }
    $('#st_preloader_wrap.st_hide_on_click').on('click', function(){
        st_preloader_mater(0);
    });
});