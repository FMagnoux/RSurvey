console.log("I'm main !");

/*VIEW DEFINTION */
var contentNewSurvey = "<form id='formNewSurvey' action='#'> <div class='mdl-textfield mdl-js-textfield mdl-textfield--full-width mdl-textfield--floating-label'> <input class='mdl-textfield__input' id='newSurveyQuestion' type='text' name='sQuestionLibel'> <label class='mdl-textfield__label'>Question (100 caractères max)</label> </div> <div class='mdl-textfield mdl-js-textfield mdl-textfield--full-width mdl-textfield--floating-label'> <input class='mdl-textfield__input' id='newSurveychoice1' type='text' name='choice1'> <label class='mdl-textfield__label'>Choix 1 (Obligatoire)</label> </div> <div class='mdl-textfield mdl-js-textfield mdl-textfield--full-width mdl-textfield--floating-label'> <input class='mdl-textfield__input' id='newSurveychoice2' type='text' name='choice2'> <label class='mdl-textfield__label'>Choix 2 (Obligatoire)</label> </div> <div class='mdl-textfield mdl-js-textfield mdl-textfield--full-width mdl-textfield--floating-label'> <input class='mdl-textfield__input' id='newSurveychoice3' type='text' name='choice3'> <label class='mdl-textfield__label'>Choix 3 (Optionnel)</label> </div> <label class='mdl-radio mdl-js-radio mdl-js-ripple-effect' for='option-1'> <input type='radio' id='option-1' class='mdl-radio__button newSurveychoiceZone' name='iIdSub' value='1' checked> <span class='mdl-radio__label'>France-Departements</span> </label> <label class='mdl-radio mdl-js-radio mdl-js-ripple-effect' for='option-2'> <input type='radio' id='option-2' class='mdl-radio__button newSurveychoiceZone' name='iIdSub' value='2'> <span class='mdl-radio__label'>USA-Etats</span> </label> </form>"
var contentLogin = "<form id='formLogin' action='#'> <div class='mdl-textfield mdl-js-textfield mdl-textfield--full-width mdl-textfield--floating-label'> <input class='mdl-textfield__input' type='mail' name='sUsrMail'> <label class='mdl-textfield__label'>Mail</label> </div> <div class='mdl-textfield mdl-js-textfield mdl-textfield--full-width mdl-textfield--floating-label'> <input class='mdl-textfield__input' type='password' name='sUsrPassword'> <label class='mdl-textfield__label'>Mot de passe</label> </div></form>"

var contentSignup = "<form id='formSignup' action='#'> <div class='mdl-textfield mdl-js-textfield mdl-textfield--full-width mdl-textfield--floating-label'> <input class='mdl-textfield__input' type='text' name='sUsrPseudo'> <label class='mdl-textfield__label'>Pseudo</label> </div> <div class='mdl-textfield mdl-js-textfield mdl-textfield--full-width mdl-textfield--floating-label'> <input class='mdl-textfield__input' type='mail' name='sUsrMail'> <label class='mdl-textfield__label'>Mail</label> </div> <div class='mdl-textfield mdl-js-textfield mdl-textfield--full-width mdl-textfield--floating-label'> <input class='mdl-textfield__input' type='password' name='sUsrPassword'> <label class='mdl-textfield__label'>Mot de passe</label> </div> <div class='mdl-textfield mdl-js-textfield mdl-textfield--full-width mdl-textfield--floating-label'> <input class='mdl-textfield__input' type='password' name='sUsrConfirmPassword'> <label class='mdl-textfield__label'>Valider le mot de passe</label> </div> </form>"

var contentForgotPassword = "<form id='formForgotPassword' action='#'> <div class='mdl-textfield mdl-js-textfield mdl-textfield--full-width mdl-textfield--floating-label'> <input class='mdl-textfield__input' type='mail' name='sMail'> <label class='mdl-textfield__label'>Mail</label> </div></form>"

/*MODELS DEFINTION */


var newSurveyRequest = function() {
  var sQuestionLibelValue = $("#newSurveyQuestion").val();
  var aQuestionChoixValues = []
  var iIdSubValue = $(".newSurveychoiceZone:checked").val();
  var newSurveychoice1 = $('#newSurveychoice1').val();
  var newSurveychoice2 = $('#newSurveychoice2').val();
  var newSurveychoice3 = $('#newSurveychoice3').val();

  if(newSurveychoice1 != '') {aQuestionChoixValues.push(newSurveychoice1);}
  if(newSurveychoice2 != '') {aQuestionChoixValues.push(newSurveychoice2);}
  if(newSurveychoice3 != '') {aQuestionChoixValues.push(newSurveychoice3);}

console.log(sQuestionLibelValue);
console.log(aQuestionChoixValues);
console.log(iIdSubValue);

$.ajax({
  url: 'creer-sondage.html',
  type: 'POST',
  dataType: 'json',
  data: {sQuestionLibel: sQuestionLibelValue,aQuestionChoix:aQuestionChoixValues,iIdSub:iIdSubValue}
})
.done(function(e) {
  console.log("success");
  console.log(e[1]);
})
.fail(function(e) {
  console.log("error");
  console.log(e[1]);
})
.always(function(e) {
  console.log("complete");
});


}


var signupRequest = function() {
  var datas = $('#formSignup').serialize();
  $.ajax({
    url: 'inscription.html',
    type: 'POST',
    dataType: 'json',
    data: datas
  })
  .done(function(e) {
    console.log("success");
    console.info(e[1]);

  })
  .fail(function(e) {
    console.log("error");
    console.warn(e.responseText);
  })
  .always(function(e) {
    console.log("complete");
  });
}


var loginRequest = function() {
    var datas = $('#formLogin').serialize();
  $.ajax({
    url: 'login.html',
    type: 'POST',
    dataType: 'json',
    data: datas
  })
  .done(function(e) {
    console.log("success");
    console.info(e[1]);

  })
  .fail(function(e) {
    console.log("error");
    console.warn(e.responseText);
  })
  .always(function(e) {
    console.log("complete");
  });
}


var forgotPassowrdRequest = function() {
  var datas = $('#formForgotPassword').serialize();
  $.ajax({
    url: 'mot-de-passe-oublie.html',
    type: 'POST',
    dataType: 'json',
    data: datas
  })
  .done(function(e) {
    console.log("success");
    console.info(e[1]);

  })
  .fail(function(e) {
    console.log("error");
    console.warn(e);

  })
  .always(function(e) {
    console.log("complete");
  });

}

/*DOCUMENT READY*/

$(document).ready(function() {

  $('#newSurvey').click(function(event) {
      event.preventDefault();
      showDialog({
          title: "<span class='mdl-color-text--blue-800'>Ajouter un sondage</span>",
          text: contentNewSurvey,
          negative: false,
          positive: {
              title: 'Ajouter un sondage',
              onClick: function(e) {
                  newSurveyRequest();
              }
          }
      });
  });

  $('#login').click(function(event) {
      event.preventDefault();
      showDialog({
          title: "<span class='mdl-color-text--blue-800'>Se connecter</span>",
          text: contentLogin,
          negative: {
              title: 'Mot de passe oublié ?',
              onClick: function() {
                  showDialog({
                      title: "<span class='mdl-color-text--blue-800'>Mot de passe oublié ?</span>",
                      text: contentForgotPassword,
                      negative: false,
                      positive: {
                          title: 'Envoyer',
                          onClick: function() {
                              forgotPassowrdRequest();
                          }
                      }
                  });
              }
          },
          positive: {
              title: 'Se connecter',
              onClick: function() {
                  loginRequest();
              }
          }
      });
  });

  $('#signup').click(function(event) {
      event.preventDefault();
      showDialog({
          title: "<span class='mdl-color-text--blue-800'>Créer un compte</span>",
          text: contentSignup,
          negative: false,
          positive: {
              title: 'Créer un compte',
              onClick: function() {
                  signupRequest();
              }
          }
      });
  });
});
