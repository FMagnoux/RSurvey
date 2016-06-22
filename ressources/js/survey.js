
console.log("I'm survey !");

function getMap() {
  $.ajax({
    url: window.location.href+".json",
    type: 'GET',
    dataType: 'json'
  })
  .done(function(e) {
    console.log("success");
    console.info(e);
    test = e;
    var userSession = e[2];
    var userQuestion = e[0].oUsrId.iUsrId;
    if(userSession != userQuestion || e[0].bQuestionClose == "1"){
      $("#cloreSurveyButton").hide();
    }
    var dateSurvey = e[0].dQuestionDate.date;
    $('.navigateButton').click(function() {
      var isTrue = $(this).data().next;
      navigateButtons(isTrue,dateSurvey);
    });
    $("#titleSurvey").text(test[0].sQuestionLibel);

    $('#cloreSurveyButton').click(function(event) {
      console.log(e);
      cloreSurvey(e[0].iQuestionId);
    });
    $('#toggleResponse').click(function(event) {
      $('#centermap').toggleClass('hideMap');
      $(this).text('Masquer les réponses');
      if($('#centermap').hasClass('hideMap')) {
        $(this).text('Voir les réponses');
      }
    });
    $('#updateSurveyButton').click(function(event) {
      updateSurvey(e);
    });
    createMap("centermap",e)
    sharingCreator(e[0].iQuestionId,e[0].sQuestionLibel);
  })
  .fail(function(e) {
    console.log("error");
  })
  .always(function() {
    console.log("complete");
  });

}

function createMap(mapPosition,datas) {
  var iSubCode = datas[0].oSub;
  var zone = "zone"+iSubCode;
  var sQuestionLibel = datas[0].sQuestionLibel;
  var dataAjax = "ressources/data/"+zone+".geojson";
  var containerChoice = $("<div></div>").text(sQuestionLibel);
  var choices = $("<form action='#' class='survey-box'></form>");
  $(containerChoice).append(choices);
  datas[1].forEach(function(e,i){
    console.log(e)
    var button = $("<button data-ichoixid="+e.iChoixId+" class='mdl-button mdl-js-button mdl-button--raised mdl-button--accent mdl-js-ripple-effect answerButton answerButton"+i+"'></button>").text(e.sChoixLibel);
    $(choices).append(button);
  });

  var map = L.map(mapPosition,{
    dragging:true,
    touchZoom:true,
    doubleClickZoom:true,
    scrollWheelZoom:true,
    boxZoom:true,
    keyboard:true,
    minZoom:6.5,
    maxZoom:10
  }).setView([46.5, 2.234], 6.5);
  var mapContent = new L.geoJson(null,{
    onEachFeature: onEachFeature,
    style: customStyle
  });
  function onEachFeature(feature, layer) {
    if (feature.properties && feature.properties.nom) {
    layer.on('click',function(){
        showDialog({
                title: feature.properties.nom,
                text:$(containerChoice).html(),
                negative: false,
                positive:false,
                onLoaded:function(e) {
                  $('.answerButton').click(function(e) {
                    e.preventDefault();
                    hideDialog($('#orrsDiag'));
                    console.log(this);
                    var iChoixIdValue = $(this).data().ichoixid;
                    var iSubCodeValue = feature.properties.code;
                    sendAnswer(iChoixIdValue,iSubCodeValue);
                  });
                }
              });
      })
    }
}

function customStyle(feature) {
  if(feature.properties && feature.properties.nom){
    var rgb = [];
    var tempArray = [];
    var backgroundColors = ['#ff4081','#009688','#2196F3'];
    var associativeBackgroundColors = [];
    $(datas[1]).each(function(i){
      if (typeof this.aReponse[feature.properties.code] !== "undefined") {
        console.log(this.aReponse[feature.properties.code]);
        rgb[this.aReponse[feature.properties.code].iChoixId] = parseInt(this.aReponse[feature.properties.code].iReponseVotes);
        associativeBackgroundColors[this.aReponse[feature.properties.code].iChoixId] = backgroundColors[i];

      }
    });

    for(var i in rgb) {tempArray.push(rgb[i]);}
    var maxValue = Math.max.apply(null,tempArray);
    var color = associativeBackgroundColors[rgb.indexOf(maxValue)];
    console.log(rgb);
    console.log(tempArray);
    console.info(rgb.indexOf(maxValue));
    if (typeof color === "undefined") {
          var color = "#E0E0E0";
    }

  return {fillColor:color,color:"black",opacity:1,weight:2,fillOpacity:1}


  }
}
  mapContent.addTo(map);
  $.ajax({
  dataType: "json",
  url: dataAjax,
  success: function(data) {
      $(data.features).each(function(key, data) {
        mapContent.addData(data);
      });
  }
  }).error(function() {});
}




var sendAnswer = function(c,s) {

  $.ajax({
    url: 'answer-question.html',
    type: 'POST',
    dataType: 'json',
    data: {iIdChoix:c,iSubCode:s}
  })
  .done(function(e) {
    console.log("success");
    console.log(e);
  })
  .fail(function(e) {
    console.log("error");
    console.log(e);

  })
  .always(function() {
    console.log("complete");
  });

}


var navigateButtons = function(data,date) {
  $.ajax({
    url: 'change-question.html',
    type: 'POST',
    dataType: 'json',
    data: {next: data,dDate:date}
  })
  .done(function(e) {
    console.log("success");
    console.log(e);
    window.location = "./"+e[0].iQuestionId;
  })
  .fail(function(e) {
    console.log("error");
    console.log(e.responseText);

  })
  .always(function() {
    console.log("complete");
  });

}

var cloreSurvey = function(e) {
  console.log(e);
  $.ajax({
    url: 'close-question.html',
    type: 'POST',
    dataType: 'json',
    data: {iIdQuestion: e}
  })
  .done(function() {
    console.log("success");
    location.reload(true);
  })
  .fail(function(e) {
    console.log("error");
    console.log(e.responseText)
  })
  .always(function() {
    console.log("complete");
  });

}

var updateSurvey = function(datas) {
  event.preventDefault();
  showDialog({
      onLoaded: function(e) {

          $('.sQuestionLibel').val([datas[0].sQuestionLibel]).parent().addClass('is-dirty');
          $('#newSurveychoice1').val([datas[1][0].sChoixLibel]).parent().addClass('is-dirty');
          $('#newSurveychoice2').val([datas[1][1].sChoixLibel]).parent().addClass('is-dirty');
          $('#newSurveychoice3').val([datas[1][2].sChoixLibel]).parent().addClass('is-dirty');

          $('#positive').off('click');
          $('#positive').click(function() {
              $('#errorForm').remove()
              updateSurveyRequest(datas);
          });
      },
      title: "<span class='mdl-color-text--blue-800'> Modifer un sondage</span>",
      text: "<p class='mdl-color-text--red-800'> Attention ! Toutes les réponses seront effacées</p>"+contentNewSurvey,
      negative: false,
      positive: {
          title: 'Modifier un sondage'
      }
  });
}

var updateSurveyRequest = function(datas) {
  var iIdQuestionValue = datas[0].iQuestionId;
  var sQuestionLibelValue = $("#newSurveyQuestion").val();
  var oQuestionChoixValues = {"aQuestionChoixValues":[]};
  var iIdSubValue = $(".newSurveychoiceZone:checked").val();
  var newSurveychoice1 = $('#newSurveychoice1').val();
  var newSurveychoice2 = $('#newSurveychoice2').val();
  var newSurveychoice3 = $('#newSurveychoice3').val();
console.log(oQuestionChoixValues);

  if (newSurveychoice1 != '') {
    var iIdChoix = {};
    iIdChoix["iIdChoix"] = datas[1][0].iChoixId;
    iIdChoix["sChoixLibel"] = newSurveychoice1;
    oQuestionChoixValues.aQuestionChoixValues.push(iIdChoix);
  }
  if (newSurveychoice2 != '') {
    var iIdChoix = {};
    iIdChoix["iIdChoix"] = datas[1][1].iChoixId;
    iIdChoix["sChoixLibel"] = newSurveychoice2;
    oQuestionChoixValues.aQuestionChoixValues.push(iIdChoix);
  }
  if (newSurveychoice3 != '') {
    var iIdChoix = {};
    iIdChoix["iIdChoix"] = datas[1][2].iChoixId;
    iIdChoix["sChoixLibel"] = newSurveychoice3;
    oQuestionChoixValues.aQuestionChoixValues.push(iIdChoix);
  }
  if (oQuestionChoixValues.aQuestionChoixValues[0] == undefined) {
      oQuestionChoixValues.aQuestionChoixValues.push(null);
  }

console.log(JSON.stringify(oQuestionChoixValues));

$.ajax({
  url: 'update-question.html',
  type: 'POST',
  dataType: 'json',
  data: {iIdQuestion: iIdQuestionValue,sQuestionLibel:sQuestionLibelValue,aChoix:JSON.stringify(oQuestionChoixValues)
  }
})
.done(function(e) {
  console.log("success");
  console.log(e[0]);
})
.fail(function(e) {
  console.log("error");
  console.log(e.responseText);
})
.always(function() {
  console.log("complete");
});


}
var sharingCreator = function(url,sQuestionLibelValue) {
      $('#fab').click(function(event) {
        showDialog({
            title: "<span class='mdl-color-text--green-800'>Partager ce sondage !</span>",
            text: sharingGenerator(url,sQuestionLibelValue),
            negative: false,
            positive: {
                title: 'Retour'
            }
        });
      });
}

$(document).ready(function() {
  console.log(window.location.href)
  getMap();

});
