
console.log("I'm survey !");

function getMap() {
  $.ajax({
    url: window.location.href+".json",
    type: 'GET',
    dataType: 'json'
  })
  .done(function(e) {
    console.log("success");
    console.log();
    test = e;
    var dateSurvey = e[0].dQuestionDate.date;
    var titleSurvey = e[0].sQuestionLibel;
    var iIdQuestionValue = e[0].iQuestionId;
    createMap("centermap",e)
    $('.navigateButton').click(function() {
      var isTrue = $(this).data().next;
      navigateButtons(isTrue,dateSurvey);
    });

    $("#titleSurvey").text(titleSurvey);
    $('#cloreSurveyButton').click(function() {
      cloreSurvey(iIdQuestionValue);
    });

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
  datas[1].forEach(function(e){
    console.log(e.sChoixLibel)
    var button = $("<button data-ichoixid="+e.iChoixId+" class='mdl-button mdl-js-button mdl-button--raised mdl-button--accent mdl-js-ripple-effect answerButton'></button>").text(e.sChoixLibel);
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
                    var iChoixIdValue = $(this).data().ichoixid;
                    var idCode = feature.properties.code;
                    sendAnswer(iChoixIdValue,idCode);
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
  })
  .fail(function(e) {
    console.log("error");
    console.log(e.responseText);

  })
  .always(function() {
    console.log("complete");
  });

}

var cloreSurvey = function(iIdQuestionValue) {
  console.log(iIdQuestionValue);
  $.ajax({
    url: 'close-question.html',
    type: 'POST',
    dataType: 'json',
    data: {iIdQuestion: iIdQuestionValue}
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

$(document).ready(function() {
  console.log(window.location.href)
  getMap();

});
