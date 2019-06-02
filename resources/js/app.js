import $ from "jquery";

$(document).ready(function(e)
{
    $("#btn-submit").click(function(e)
    {
        e.preventDefault();

        var spinner = $('#spinner');
        var result = $('#result');
        spinner.css({"display":"block"});
        result.html('');

        var webUrl = $('#url').val();
        var token = $('#token').val();

        var webpSupport = Modernizr.webp;

        $.ajax
        ({
            type: 'post',
            url: '',
            data:
                {
                    webUrl: webUrl,
                    webpSupport: webpSupport,
                    _token: token
                },
            success: function(response)
            {
                spinner.css({"display":"none"});
                result.html(response);
            },
            error: function(xhr, status, error)
            {
                spinner.css({"display":"none"});
                result.html('<span>Někde nastala chyba, pravděpodobně URL adresa neexistuje.</span>');
            }
        });
    });
});
