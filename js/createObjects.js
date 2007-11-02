  
  
  
  function doCreateMarker(){
    //Element.show('createMarker');
    $('createNewMarkerLabel').innerHTML = 'Marker Title (Long Name):';
    new Effect.BlindDown('createNewMarker',{duration:0.3});
  }
  function cancelCreateMarker(){
    //Element.hide('createNewMarker');
    new Effect.BlindUp('createNewMarker',{duration:0.3});
    $('createNewMarkerLabel').innerHTML = '<a href="javascript:doCreateMarker();">Create a new Biomarker</a>';
  }
  function doCreatePublication(){
    //Element.show('createPublication');
    $('createNewPublicationLabel').innerHTML = 'PublMed ID:';
    new Effect.BlindDown('createNewPublication',{duration:0.3});
  }
  function cancelCreatePublication(){
    //Element.hide('createNewPublication');
    new Effect.BlindUp('createNewPublication',{duration:0.3});
    $('createNewPublicationLabel').innerHTML = '<a href="javascript:doCreatePublication();">Create a new Publication</a>';
  }
  function doCreateStudy(){
    $('createNewStudyLabel').innerHTML = 'Study Title (Long Name):';
    new Effect.BlindDown('createNewStudy',{duration:0.3});
  }
  function cancelCreateStudy(){
    new Effect.BlindUp('createNewStudy',{duration:0.3});
    $('createNewStudyLabel').innerHTML = '<a href="javascript:doCreateStudy();">Create a new Study</a>';
  }
  function doCreateOrgan(){
    $('createNewOrganLabel').innerHTML = 'Organ Site:';
    new Effect.BlindDown('createNewOrgan',{duration:0.3});
  }
  function cancelCreateOrgan(){
    new Effect.BlindUp('createNewOrgan',{duration:0.3});
    $('createNewOrganLabel').innerHTML = '<a href="javascript:doCreateOrgan();">Create a new Organ Site</a>';
  }