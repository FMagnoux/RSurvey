var desactivateSurvey = function(event) {
    desactivateRequest('desactivate-zone.html', this.id);
}

var activateSurvey = function(event) {
    desactivateRequest('activate-zone.html', this.id);
}

$(document).ready(function() {
    $('.desactivate-survey').click(desactivateSurvey);

    $('.activate-survey').click(activateSurvey);
});