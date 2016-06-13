
console.log("I'm survey !");

function createMap(mapPosition,mapContent) {
  var dataAjax = "data/"+mapContent+".geojson";
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
                text: "<span>Chocolatine ou Pain au chocolat ?</span><form action='#' class='survey-box'><button type='submit' class='mdl-button mdl-js-button mdl-button--raised mdl-button--accent mdl-js-ripple-effect'>Chocolatine</button> <button type='submit' class='mdl-button mdl-js-button mdl-button--raised mdl-button--accent mdl-js-ripple-effect mdl-color--blue-500'>Pain au chocolat</button> </form>",
                negative: false,
                positive:false
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


$(document).ready(function() {
createMap("centermap","frenchdepartments")
});
