console.log("I'm main !");

/*VIEW DEFINTION */
var contentNewSurvey = "<form action='#'> <div class='mdl-textfield mdl-js-textfield mdl-textfield--full-width mdl-textfield--floating-label'> <input class='mdl-textfield__input' type='text' name='nameNewSurvey'> <label class='mdl-textfield__label'>Nom du Sondage</label> </div> <div class='mdl-textfield mdl-js-textfield mdl-textfield--full-width mdl-textfield--floating-label'> <input class='mdl-textfield__input' type='text' name='content'> <label class='mdl-textfield__label'>Contenu (100 caractères max)</label> </div> <div class='mdl-textfield mdl-js-textfield mdl-textfield--full-width mdl-textfield--floating-label'> <input class='mdl-textfield__input' type='text' name='choice1'> <label class='mdl-textfield__label'>Choix 1 (Obligatoire)</label> </div> <div class='mdl-textfield mdl-js-textfield mdl-textfield--full-width mdl-textfield--floating-label'> <input class='mdl-textfield__input' type='text' name='choice2'> <label class='mdl-textfield__label'>Choix 2 (Obligatoire)</label> </div> <div class='mdl-textfield mdl-js-textfield mdl-textfield--full-width mdl-textfield--floating-label'> <input class='mdl-textfield__input' type='text' name='choice3'> <label class='mdl-textfield__label'>Choix 3 (Optionnel)</label> </div> <div class='mdl-textfield mdl-js-textfield mdl-textfield--full-width mdl-textfield--floating-label'> <input class='mdl-textfield__input' type='text' name='choice4'> <label class='mdl-textfield__label'>Choix 4 (Optionnel)</label> </div></form>"

var contentLogin = "<form action='#'> <div class='mdl-textfield mdl-js-textfield mdl-textfield--full-width mdl-textfield--floating-label'> <input class='mdl-textfield__input' type='mail' name='login'> <label class='mdl-textfield__label'>Mail</label> </div> <div class='mdl-textfield mdl-js-textfield mdl-textfield--full-width mdl-textfield--floating-label'> <input class='mdl-textfield__input' type='password' name='password'> <label class='mdl-textfield__label'>Mot de passe</label> </div></form>"

var contentSignup = "<form id='formSignup' action='#'> <div class='mdl-textfield mdl-js-textfield mdl-textfield--full-width mdl-textfield--floating-label'> <input class='mdl-textfield__input' type='text' name='sUsrPseudo'> <label class='mdl-textfield__label'>Pseudo</label> </div> <div class='mdl-textfield mdl-js-textfield mdl-textfield--full-width mdl-textfield--floating-label'> <input class='mdl-textfield__input' type='mail' name='sUsrMail'> <label class='mdl-textfield__label'>Mail</label> </div> <div class='mdl-textfield mdl-js-textfield mdl-textfield--full-width mdl-textfield--floating-label'> <input class='mdl-textfield__input' type='password' name='sUsrPassword'> <label class='mdl-textfield__label'>Mot de passe</label> </div> <div class='mdl-textfield mdl-js-textfield mdl-textfield--full-width mdl-textfield--floating-label'> <input class='mdl-textfield__input' type='password' name='sUsrConfirmPassword'> <label class='mdl-textfield__label'>Valider le mot de passe</label> </div> </form>"


/*MODELS DEFINTION */

var signupRequest = function(e) {
  var datas = $('#formSignup').serialize();

  $.ajax({
    url: 'inscription.html',
    type: 'POST',
    dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
    data: datas
  })
  .done(function() {
    console.log("success");
  })
  .fail(function(e) {
    console.log("error");
    console.warn(e);
  })
  .always(function() {
    console.log("complete");
  });


}

$(document).ready(function() {

  $('#newSurvey').click(function(event) {event.preventDefault();showDialog({ title: "<span class='mdl-color-text--blue-800'>Ajouter un sondage</span>", text:contentNewSurvey, negative:false, positive: { title: 'Ajouter un sondage', onClick: function (e) { alert('Action performed!'); } } });});

  $('#login').click(function(event) {event.preventDefault();showDialog({ title: "<span class='mdl-color-text--blue-800'>Se connecter</span>", text:contentLogin, negative:false, positive: { title: 'Se connecter', onClick: function (e) { alert('Action performed!'); } } });});

  $('#signup').click(function(event) {event.preventDefault();showDialog({ title: "<span class='mdl-color-text--blue-800'>Créer un compte</span>", text:contentSignup, negative:false, positive: { title: 'Créer un compte', onClick: function (e) { signupRequest(e); } } });});



});
