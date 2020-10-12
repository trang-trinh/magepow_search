define(['jquery','mage/url'],function ($, url){
    "use strict";
        return function myJquery() {
            $(document).on('change', '#attribute_option1', function ()
            {
                var attribute_code1 = $('#attribute_code1').val();
                // console.log(attribute_code1);
                var attribute_option1 = $('#attribute_option1').val();
                // console.log(attribute_option1);
                var attribute_code2 = $('#attribute_code2').val();
                // console.log(attribute_code2);
                var getUrl = url.build('magepow_search/index/attribute');
                $.ajax({
                    showLoader: true,
                    url: getUrl,
                    data: {
                        attribute_code1: attribute_code1,
                        attribute_option1: attribute_option1,
                        attribute_code2: attribute_code2
                    },
                    type: "GET",
                    dataType: 'json'
                }).done(function (data) {
                    $('#attribute_option2').empty();
                    $('#attribute_option2').append(data.value);
                });
            });
        }
});
