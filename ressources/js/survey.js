
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
    if(userSession != userQuestion){
      $("#cloreSurveyButton").hide();
    }
    var dateSurvey = e[0].dQuestionDate.date;
    $('.navigateButton').click(function() {
      var isTrue = $(this).data().next;
      navigateButtons(isTrue,dateSurvey);
    });
    $("#titleSurvey").text(test[0].sQuestionLibel);
    $('#cloreSurveyButton').click(function(e) {
      cloreSurvey('yo');
    });
    createMap("centermap",e)

  })
  .fail(function() {
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
    dragging:false,
    touchZoom:true,
    doubleClickZoom:false,
    scrollWheelZoom:false,
    boxZoom:false,
    keyboard:false
  }).setView([46.5, 2.234], 6.5);
  var mapContent = new L.geoJson(null,{onEachFeature: onEachFeature});
  function onEachFeature(feature, layer) {
    if (feature.properties && feature.properties.nom) {
      layer.on('click',function(){
        showDialog({
                title: feature.properties.nom,
                text:$(containerChoice).html(),
                negative: false,
                positive:false,
                onLoaded:function(e) {
                  console.log('yo');
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
    window.location = "http://localhost/RSurvey/"+e[0].iQuestionId;
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
    data: {param1: 'value1'}
  })
  .done(function() {
    console.log("success");
  })
  .fail(function(e) {
    console.log("error");
    console.log(e.responseText)
  })
  .always(function() {
    console.log("complete");
  });

}

$(document).ready(function() {
  console.log(window.location.href)
  getMap();

});
