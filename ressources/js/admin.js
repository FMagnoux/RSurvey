var desactivateRequest = function(link, id) {
    $.ajax({
        url: link,
        type: 'POST',
        dataType: 'json',
        data: {id: id}
    })
        .done(function(e) {
            console.log("success");
            console.info(e);
            if(e[0] == "success") {
                var button = $("#" + id);
                if(link == "desactivate-zone.html") {
                    button.unbind();
                    button.click(activateSurvey);
                    button.html("Activer la zone");
                    button.removeClass("desactivate-survey");
                    button.addClass("activate-survey");
                }
                else if(link == "activate-zone.html") {
                    button.unbind();
                    button.click(desactivateSurvey);
                    button.html("Desactiver la zone");
                    button.removeClass("activate-survey");
                    button.addClass("desactivate-survey");
                }
                else {
                    button.remove();
                }
            }
        })
        .fail(function(e) {
            console.log("error");
            console.warn(e.responseText);
        })
        .always(function(e) {
            console.log("complete");
        });
}