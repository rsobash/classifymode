$(document).ready(function(){
/*
  userid = 1;
  username = "sobash";
  $("#text1").hide();
  $("#text2").hide();
  $("#img-container, #text-container").show();
  newTrial(event);
*/
  // button to submit user credentials and return confirmation 
  $("#text1button").click(function(event){
      //event.preventDefault();
      var id = $("#idnumber").val();
      var senddata = { 'id' : id };
      $.post("backend.php", senddata, function(data) {
         if (data['verify'] < 1) {
            $("#intro1text").empty();
            $("#intro1text").append("Invalid username");
         } else {
            userid = data['id'];
            username = data['username'];
            $("#text1").hide();
            $("#text2").show();
         }
      }, dataType='json');
  });

  // button to advance past instructions and show storms
  $("#text2button").click(function(event){
      //event.preventDefault(); 
      $("#img-container, #text-container").show();
      newTrial(event);
      $("#text2").hide();
      //$("#text3").show();
  });

  //$("#reset").submit(restartExp);

  $("button.typebutton").click(function(event) {
      $("button.typebutton").removeClass('selected'); 
      $(this).toggleClass('selected');
      label = $(this).attr("name");
  });
  
  $("button.confbutton").click(function(event) {
      $("button.confbutton").removeClass('selected'); 
      $(this).toggleClass('selected');
      conf = $(this).attr("name");
  });
  
  $("button.submitbutton").click(sendDecision);
    
  $("div#cref").click(function() {
      $("div.rollover.field.selected").removeClass('selected');
      $("div#cref").addClass('selected');
      prefix = "";
      loadImages(); 
  });

  $("div#t2").click(function() {
      $("div.rollover.field.selected").removeClass('selected');
      $("div#t2").addClass('selected');
      prefix = "t2_";
      loadImages();
  });

  $("div#img-container").hover( 
      function() {
          $("div#rollover-container").fadeTo(100, 1.0);
      }, function() {
          $('img#fcstimage').attr("src", window.imagelist[2]);
          $("div#rollover-container").fadeTo(100, 0.25);
          $("div.rollover.selected.hour").removeClass('selected');
          $("div#p0").addClass('selected');
      }
  );
 
});

function sendDecision(event){
  if (label != null && conf != null) {
      //event.preventDefault();
      var senddata = { 'label': label, 'thisid': thisid, 'userid': userid, 'conf':conf }

      // once this label is stored in database, then request new storm
      $.post("backend.php", senddata, function() { 
          newTrial(event)
      }, dataType='json');
  }
}

function restartExp(event) {
  event.preventDefault();
  var senddata = { 'reset' : null }
  $.post("backend.php", senddata, function() {
      location.reload(true);
  });
}

function newTrial(event) {
  //event.preventDefault();
  // GET USER ID AND INITIAL INFO, SEND TO PHP SCRIPT, RETURN FIRST TRIAL DATA 
  $.getJSON("backend.php?trial=1&id="+userid, displayTrial);
}

function displayTrial(data) {
    imgname = data['imgname']; // storm id string
    numclassified = data['numclassified']; // number already classified
    thisid = data['id'];

    label = null;
    conf = null;

    // LOAD NEW IMAGE 
    d = new Date();

    $("#img").empty();
    $("#img").append("<img id=\"fcstimage\" src=\"\" />");
    $("span.username").html("Username: <b>"+username+"</b>");
    $("span.numclassified").html("Total storms classified: <b>"+numclassified+"</b>");
    //$("#fcstimage").attr("src", "./stormimage.png?"+d.getTime()); // tack on time as query string so browser doesnt show cached image (force reload)
  
    // when displaying new storm, reset prefix, idx, and rollover shading
    prefix = "";
    idx = 2;
    $("div.rollover.selected").removeClass('selected');
    $("div#p0").addClass('selected');
    $("div#cref").addClass('selected');

    loadImages();
    $("button").removeClass('selected');
  
    $("#poll").submit(function() { return false; }); // turn off submit buttons

}

function loadImages() {
    // preload images here
    imagelist = [ "./stormimg/"+prefix+imgname+"_-2.png",
                  "./stormimg/"+prefix+imgname+"_-1.png",
                  "./stormimg/"+prefix+imgname+"_+0.png",
                  "./stormimg/"+prefix+imgname+"_+1.png",
                  "./stormimg/"+prefix+imgname+"_+2.png" ]
    
    imagesLoaded = new Array();
    window.images = new Array();
                
    $('img#fcstimage').attr("src", window.imagelist[idx])

    for (var i = 0; i < window.imagelist.length; i++) {
        window.images[i]= new Image();               // initialize array of image objects
        window.images[i].onload = function() {

            // figure out which forecast hour this is
            var indexes = this.src.match(/[-,+]\d/g);
            var fhr = indexes.pop();

            var thing = fhr.replace('+', 'p');
            var thing = thing.replace('-', 'm');

            var thisrollover = $("div#"+thing+".rollover")
            imagesLoaded.push(fhr);

            // change class
            thisrollover.addClass("loaded");

            // attach mouseover 
            thisrollover.mouseover(function() {
                var fcsthr = $( this ).attr('id');

                // change class for rollover
                $("div.rollover.hour").removeClass("selected");
                $( this ).addClass("selected");

                if (fcsthr == "m2") { idx = 0; }
                if (fcsthr == "m1") { idx = 1; }
                if (fcsthr == "p0") { idx = 2; }
                if (fcsthr == "p1") { idx = 3; }
                if (fcsthr == "p2") { idx = 4; }

                $('img#fcstimage').attr("src", window.imagelist[idx])
            });
        };
        window.images[i].src = window.imagelist[i];
    }
}

