$(document).ready(function() {
    $('.desactivate-survey').click(function(event) {
        console.log(desactivateRequest('desactivate-question.html', this.id));
    });
});