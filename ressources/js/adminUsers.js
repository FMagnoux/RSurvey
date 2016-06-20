$(document).ready(function() {
    $('.desactivate-survey').click(function(event) {
        desactivateRequest('desactivate-user.html', this.id);
    });
});