console.log("I'm main !");
var linkToWebsite = "http://romainfrancois.com/RSurvey";

/*VIEW DEFINTION */
var contentNewSurvey = "<form id='formNewSurvey' action='#'> <div class='mdl-textfield mdl-js-textfield mdl-textfield--full-width mdl-textfield--floating-label'> <input class='sQuestionLibel mdl-textfield__input' id='newSurveyQuestion' type='text' name='sQuestionLibel'> <label class='mdl-textfield__label'>Question (100 caractères max)</label> </div> <div class='mdl-textfield mdl-js-textfield mdl-textfield--full-width mdl-textfield--floating-label'> <input class='mdl-textfield__input' id='newSurveychoice1' type='text' name='choice1'> <label class='mdl-textfield__label'>Choix 1 (Obligatoire)</label> </div> <div class='mdl-textfield mdl-js-textfield mdl-textfield--full-width mdl-textfield--floating-label'> <input class='mdl-textfield__input' id='newSurveychoice2' type='text' name='choice2'> <label class='mdl-textfield__label'>Choix 2 (Obligatoire)</label> </div> <div class='mdl-textfield mdl-js-textfield mdl-textfield--full-width mdl-textfield--floating-label'> <input class='mdl-textfield__input' id='newSurveychoice3' type='text' name='choice3'> <label class='mdl-textfield__label'>Choix 3 (Optionnel)</label> </div> <label class='mdl-radio mdl-js-radio mdl-js-ripple-effect' for='option-1'> <input type='radio' id='option-1' class='mdl-radio__button newSurveychoiceZone' name='iIdSub' value='1' checked> <span class='mdl-radio__label'>France-Departements</span> </label> </form>"

var contentLogin = "<form id='formLogin' action='#'> <div class='mdl-textfield mdl-js-textfield mdl-textfield--full-width mdl-textfield--floating-label'> <input class='mdl-textfield__input' type='mail' name='sUsrMail'> <label class='mdl-textfield__label'>Mail</label> </div> <div class='mdl-textfield mdl-js-textfield mdl-textfield--full-width mdl-textfield--floating-label'> <input class='mdl-textfield__input' type='password' name='sUsrPassword'> <label class='mdl-textfield__label'>Mot de passe</label> </div></form>"

var contentSignup = "<form id='formSignup' action='#'> <div class='mdl-textfield mdl-js-textfield mdl-textfield--full-width mdl-textfield--floating-label'> <input class='mdl-textfield__input' type='text' name='sUsrPseudo'> <label class='mdl-textfield__label'>Pseudo</label> </div> <div class='mdl-textfield mdl-js-textfield mdl-textfield--full-width mdl-textfield--floating-label'> <input class='mdl-textfield__input' type='mail' name='sUsrMail'> <label class='mdl-textfield__label'>Mail</label> </div> <div class='mdl-textfield mdl-js-textfield mdl-textfield--full-width mdl-textfield--floating-label'> <input class='mdl-textfield__input' type='password' name='sUsrPassword'> <label class='mdl-textfield__label'>Mot de passe</label> </div> <div class='mdl-textfield mdl-js-textfield mdl-textfield--full-width mdl-textfield--floating-label'> <input class='mdl-textfield__input' type='password' name='sUsrConfirmPassword'> <label class='mdl-textfield__label'>Valider le mot de passe</label> </div> </form>"

var contentUpdateUser = "<form id='formUpdateUser' action='#'> <div class='mdl-textfield mdl-js-textfield mdl-textfield--full-width mdl-textfield--floating-label'> <input class='sUsrMail mdl-textfield__input' type='mail' name='sUsrMail'> <label class='mdl-textfield__label'>Mail</label> </div> <div class='mdl-textfield mdl-js-textfield mdl-textfield--full-width mdl-textfield--floating-label'> <input class='mdl-textfield__input' type='password' name='sUsrPassword'> <label class='mdl-textfield__label'>Mot de passe</label> </div><div class='mdl-textfield mdl-js-textfield mdl-textfield--full-width mdl-textfield--floating-label'> <input class='mdl-textfield__input' type='password' name='sUsrConfirmPassword'> <label class='mdl-textfield__label'>Confirmer le mot de passe</label> </div></form>"

var contentForgotPassword = "<form id='formForgotPassword' action='#'> <div class='mdl-textfield mdl-js-textfield mdl-textfield--full-width mdl-textfield--floating-label'> <input class='mdl-textfield__input' type='mail' name='sMail'> <label class='mdl-textfield__label'>Mail</label> </div></form>"

/*MODELS DEFINTION */


var newSurveyRequest = function() {
    var sQuestionLibelValue = $("#newSurveyQuestion").val();
    var aQuestionChoixValues = [];
    var iIdSubValue = $(".newSurveychoiceZone:checked").val();
    var newSurveychoice1 = $('#newSurveychoice1').val();
    var newSurveychoice2 = $('#newSurveychoice2').val();
    var newSurveychoice3 = $('#newSurveychoice3').val();

    if (newSurveychoice1 != '') {
        aQuestionChoixValues.push(newSurveychoice1);
    }
    if (newSurveychoice2 != '') {
        aQuestionChoixValues.push(newSurveychoice2);
    }
    if (newSurveychoice3 != '') {
        aQuestionChoixValues.push(newSurveychoice3);
    }
    if (aQuestionChoixValues[0] == undefined) {
        aQuestionChoixValues.push(null);
    }


    $.ajax({
            url: 'creer-sondage.html',
            type: 'POST',
            dataType: 'json',
            data: {
                sQuestionLibel: sQuestionLibelValue,
                aQuestionChoix: aQuestionChoixValues,
                iIdSub: iIdSubValue
            }
        })
        .done(function(e) {
            console.log("success");
            console.log(e)

            if (e[0] == "success") {
                hideDialog($('#orrsDiag'));
                showDialog({
                    title: "<span class='mdl-color-text--green-800'>" + e[1] + "</span>",
                    text: "<div class=mdl-typography--text-center> Cliquez ici pour acceder à votre sondage <a target=_blank href='" + linkToWebsite + "/" + e[2] + "'>" + linkToWebsite + "/" + e[2] + "</a>"+sharingGenerator(e[2],sQuestionLibelValue)+"</div>",
                    negative: false,
                    positive: false
                });

            } else if (e[0] == "error") {
                $('#formNewSurvey').prepend("<span id='errorForm' class='mdl-color-text--red-800'>" + e[1] + "</span>");
            }
        })
        .fail(function(e) {
            console.log("error");
            console.log(e.responseText);
        })
        .always(function(e) {
            console.log("complete");
        });


}


var ajaxRequestSerialize = function(idForm, urlDest) {
    var datas = $('#' + idForm).serialize();
    $.ajax({
            url: urlDest,
            type: 'POST',
            dataType: 'json',
            data: datas
        })
        .done(function(e) {
            console.log("success");
            if (e[0] == "success") {
                hideDialog($('#orrsDiag'));
                showDialog({
                    title: "<span class='mdl-color-text--green-800'>" + e[1] + "</span>",
                    text: "",
                    negative: false,
                    positive: false
                });
                setTimeout(function() {
                    location.reload(true);
                }, 4000);

            } else if (e[0] == "error") {
                $('#' + idForm).prepend("<span id='errorForm' class='mdl-color-text--red-800'>" + e[1] + "</span>");

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


var updateUserRequest = function() {
        $.ajax({
          url: 'get-user.html',
          type: 'POST',
          dataType: 'json',
          data: {},
        })
        .done(function(e) {
          console.log("success");
          console.log(e);
          var datasUser = e;
          event.preventDefault();
          showDialog({
              onLoaded: function(e) {
                  $('#positive').off('click');
                  $('.sUsrMail').val(datasUser.sUsrMail).parent().addClass('is-dirty');

                  $('#positive').click(function() {
                      $('#errorForm').remove()
                      ajaxRequestSerialize('formUpdateUser', 'update-user.html');
                  });
              },
              title: "<span class='mdl-color-text--blue-800'>Modifier son compte</span>",
              text:contentUpdateUser,
              positive: {
                  title: 'Modifier son compte'
              }
          });

        })
        .fail(function(e) {
          console.log("error");
          console.log(e.responseText);

        })
        .always(function() {
          console.log("complete");
        });


    }

var sharingGenerator = function(url,sQuestionLibelValue) {
  return "<p class=shareButtonsContainer ><a class=shareButton target=_blank href=https://www.facebook.com/sharer/sharer.php?app_id=113869198637480&sdk=joey&u=" + encodeURI(linkToWebsite + "/" + url) + "><button class='mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent mdl-color--blue-800'>Partager sur Facebook !</button></a><a class=shareButton target=_blank href=https://twitter.com/intent/tweet?text=" + encodeURI("Je viens de créer un sondage ! " + sQuestionLibelValue + " " + linkToWebsite + "/" + url) + "><button class='mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent mdl-color--blue-500'>Partager sur Twitter !</button></a></p>";
}

var newSurveyModal = function(event) {
    event.preventDefault();
    showDialog({
        onLoaded: function(e) {
            $('#positive').off('click');
            $('#positive').click(function() {
                $('#errorForm').remove()
                newSurveyRequest();
            });
        },
        title: "<span class='mdl-color-text--blue-800'>Ajouter un sondage</span>",
        text: contentNewSurvey,
        negative: false,
        positive: {
            title: 'Ajouter un sondage'
        }
    });
}

var closeSurveyRequest = function(question) {
    $.ajax({
        url: 'close-question.html',
        type: 'POST',
        dataType: 'json',
        data: {iIdQuestion: question.id}
    })
        .done(function(e) {
            console.log("success");
            if(e[0] == "success") {
                question.remove();
            }
        })
        .fail(function(e) {
            console.log("error");
            console.log(e.responseText)
        })
        .always(function() {
            console.log("complete");
        });
}


var contactFormRequest = function(e) {
    $.ajax({
        url: 'contact.html',
        type: 'POST',
        dataType: 'json',
        data: {sMessage: e.target.sMessage.value, sEmail: e.target.sEmail.value}
    })
        .done(function(e) {
            console.log("success");
            alert(e[1]);
        })
        .fail(function(e) {
            console.log("error");
            console.log(e.responseText)
        })
        .always(function() {
            console.log("complete");
        });
    return false;
}

/*DOCUMENT READY*/

$(document).ready(function() {

    $('#newSurvey').click(newSurveyModal);
    $('#newSurvey2').click(newSurveyModal);

    $('#login').click(function(event) {
        event.preventDefault();
        showDialog({
            onLoaded: function(e) {
                $('#positive').off('click');
                $('#positive').click(function() {
                    $('#errorForm').remove()
                    ajaxRequestSerialize('formLogin', 'login.html');
                });
            },
            title: "<span class='mdl-color-text--blue-800'>Se connecter</span>",
            text: contentLogin,
            negative: {
                title: 'Mot de passe oublié ?',
                onClick: function() {
                    showDialog({
                        onLoaded: function(e) {
                            $('#positive').off('click');
                            $('#positive').click(function() {
                                $('#errorForm').remove()
                                ajaxRequestSerialize('formForgotPassword', 'mot-de-passe-oublie.html');
                            });
                        },
                        title: "<span class='mdl-color-text--blue-800'>Mot de passe oublié ?</span>",
                        text: contentForgotPassword,
                        negative: false,
                        positive: {
                            title: 'Envoyer'
                        }
                    });
                }
            },
            positive: {
                title: 'Se connecter'
            }
        });
    });

    $('#signup').click(function(event) {
        event.preventDefault();
        showDialog({
            onLoaded: function(e) {
                $('#positive').off('click');
                $('#positive').click(function() {
                    $('#errorForm').remove()
                    ajaxRequestSerialize('formSignup', 'inscription.html');
                });
            },
            title: "<span class='mdl-color-text--blue-800'>Créer un compte</span>",
            text: contentSignup,
            negative: false,
            positive: {
                title: 'Créer un compte'
            }
        });
    });


    $('#updateUser').click(function(event) {
        event.preventDefault();
        updateUserRequest();
    });

    $('.close-survey').click(function(event) {
        closeSurveyRequest(this);
    });

    $("#contactForm").submit(contactFormRequest);
});
