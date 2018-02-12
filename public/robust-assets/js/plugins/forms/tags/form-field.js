(function(window, document, $) {
    'use strict';
    

    $(window).on('load',function(){
        $('.form-group-style .form-control').focus(function() {
            $(this).parent(".form-group-style").addClass('focus');
            console.log($(this).val());
            if($(this).val() !== ""){
                 $(this).parent(".form-group-style").children("label").addClass("filled");
            }
            else{
                 $(this).parent(".form-group-style").children("label").removeClass("filled");
            }
        });
        $('.form-group-style .form-control').focusout(function() {
            if($(this).parent(".form-group-style").hasClass('focus')){
                $(this).parent(".form-group-style").removeClass('focus');
            }
            if($(this).val() !== ""){
                // $('.form-group-style label').addClass("filled");
                $(this).parent(".form-group-style").children("label").addClass("filled");
            }
            else{
                $(this).parent(".form-group-style").children("label").removeClass("filled");
                // $('.form-group-style label').removeClass("filled");
            }
        });

        // Basic initialization
        // $('.tokenTag').tokenfield();
        /*$('.tagsInput').tagsinput();

        var citynames = new Bloodhound({
          datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
          queryTokenizer: Bloodhound.tokenizers.whitespace,
          prefetch: {
            url: '../../../robust-assets/js/plugins/forms/tags/citynames.json',
            filter: function(list) {
              return $.map(list, function(cityname) {
                return { name: cityname }; });
            }
          }
        });
        citynames.initialize();

        $('.tagsTypehead').tagsinput({
          typeahead: {
            source: ['Amsterdam', 'Washington', 'Sydney', 'Beijing', 'Cairo']
          }
        });*/
    });
})(window, document, jQuery);