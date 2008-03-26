MessageFormat.extend(MessageFormat,{
  BiomarkerOrganSummary: {
    Tag: 'BiomarkerOrganSummary',
    Build: function(strJSON,divId){
      jss = String((strJSON));
      try {
        obj = jss.evalJSON(true);
        str = '<div class=\"overview\" style="margin-top:9px;" id="overview'+obj.objId+'">'
            + '<h3><a href="biomarkerorgan.php?view=basics&objId='+obj.objId+'">'+obj.Organ.Name+'</a>&nbsp;&nbsp;<span class="titleAction pseudolink grey" onclick="BiomarkerOrganData.Delete('+obj.objId+',new AjaxNotify.Create(\'overview'+obj.objId+'\',\'Info\'));">(delete)</span></h3>'
            + '<table>'
            + '<tr><td>Sensitivity (Min/Max): </td><td>'+((obj.SensitivityMin == '')? '0' : obj.SensitivityMin)+' / '+((obj.SensitivityMax == '')? '0' : obj.SensitivityMax)+'</td></tr>'
            + '<tr><td>Specificity (Min/Max): </td><td>'+((obj.SpecificityMin == '')? '0' : obj.SpecificityMin)+' / '+((obj.SpecificityMax == '')? '0' : obj.SpecificityMax)+'</td></tr>'
            + '<tr><td>Negative Predictive Value (Min/Max): </td><td>'+((obj.NPVMin == '')? '0' : obj.NPVMin)+' / '+((obj.NPVMax == '')? '0' : obj.NPVMax)+'</td></tr>'
            + '<tr><td>Positive Predictive Value (Min/Max): </td><td>'+((obj.PPVMin == '')? '0' : obj.PPVMin)+' / '+((obj.PPVMax == '')? '0' : obj.PPVMax)+'</td></tr>'
            + '</table>'
            + '</div>';
      } catch (e){
        str = 'An error was encountered while drawing the content. Please refresh the page.';
      }  
      Element.insert(divId,str);
    }
  }}
);

MessageFormat.extend(MessageFormat,{
  BiomarkerStudySummary: {
    Tag: 'BiomarkerStudySummary',
    Build: function(strJSON,divId){
      jss = String(strJSON);
      try {
        obj = jss.evalJSON(true);
        new Ajax.Request(
            siteBaseUrl + '/util/ifcomponents.php',{
              method:'get',
              parameters:'action=display&type=BiomarkerStudyDisplay&objId='+obj.objId,
              onSuccess: function (transport){
                insertContent(divId,transport.responseText);
              }});
      } catch (e){
        str = 'An error was encountered while drawing the content. Please refresh the page.';
        Element.insert(divId,str);
      }
    }
  }}
);

MessageFormat.extend(MessageFormat,{
  BiomarkerOrganStudySummary: {
    Tag: 'BiomarkerOrganStudySummary',
    Build: function(strJSON,divId){
      jss = String(strJSON);
      try {
        obj = jss.evalJSON(true);
        new Ajax.Request(
            siteBaseUrl + '/util/ifcomponents.php',{
              method:'get',
              parameters:'action=display&type=BiomarkerOrganStudyDisplay&objId='+obj.objId,
              onSuccess: function (transport){
                insertContent(divId,transport.responseText);
              }});
      } catch (e){
        str = 'An error was encountered while drawing the content. Please refresh the page.';
        Element.insert(divId,str);
      }
    }
  }}
);

// PUBLICATION SUMMARY
MessageFormat.extend(MessageFormat,{
  PublicationSummary: {
    Tag: 'PublicationSummary',
    Build: function(strJSON,divId){
      jss = String(strJSON);
      try {
        response = jss.evalJSON(true);
        message     = response.AjaxMessage;
        description = message.Description;
        content     = message.Content;
        new Ajax.Request(
          siteBaseUrl + '/util/ifcomponents.php',{
            method:'get',
            parameters:'action=display&type=PublicationSummary&objId='+content.obj1Id+'&containerObjectType='+content.obj2Type+'&containerObjectId='+content.obj2Id,
            onSuccess: function (transport){
              insertContent(divId,transport.responseText);
            }});
      } catch (e){
        str = 'An error was encountered while drawing the content. Please refresh the page.';
        Element.insert(divId,str);
      }
    }
  }}
);
// PUBLICATION SHORT SUMMARY
MessageFormat.extend(MessageFormat,{
  PublicationShortSummary: {
    Tag: 'PublicationShortSummary',
    Build: function(strJSON,divId){
      jss = String(strJSON);
      try {
        response = jss.evalJSON(true);
        message     = response.AjaxMessage;
        description = message.Description;
        content     = message.Content;
        new Ajax.Request(
          siteBaseUrl + '/util/ifcomponents.php',{
            method:'get',
            parameters:'action=display&type=PublicationShortSummary&objId='+content.obj1Id+'&containerObjectType='+content.obj2Type+'&containerObjectId='+content.obj2Id,
            onSuccess: function (transport){
              insertContent(divId,transport.responseText);
            }});
      } catch (e){
        str = 'An error was encountered while drawing the content. Please refresh the page.';
        Element.insert(divId,str);
      }
    }
  }}
);

// RESOURCE SUMMARY
MessageFormat.extend(MessageFormat,{
  ResourceSummary: {
    Tag: 'ResourceSummary',
    Build: function(strJSON,divId){
      jss = String(strJSON);
      try {
        response = jss.evalJSON(true);
        description = response.AjaxMessage.Description;
        content  = response.AjaxMessage.Content;
        new Ajax.Request(
          siteBaseUrl + '/util/ifcomponents.php',{
            method:'get',
            parameters:'action=display&type=ResourceSummary&objId='+content.obj1Id+'&containerObjectType='+content.obj2Type+'&containerObjectId='+content.obj2Id,
            onSuccess: function (transport){
              insertContent(divId,transport.responseText);
            }});
      } catch (e){
        str = 'An error was encountered while drawing the content. Please refresh the page.';
        Element.insert(divId,str);
      }
    }
  }}
);
// RESOURCE SHORT SUMMARY 
MessageFormat.extend(MessageFormat,{
  ResourceShortSummary: {
    Tag: 'ResourceShortSummary',
    Build: function(strJSON,divId){
      jss = String(strJSON);
      try {
        response = jss.evalJSON(true);
        message     = response.AjaxMessage;
        description = message.Description;
        content     = message.Content;
        new Ajax.Request(
          siteBaseUrl + '/util/ifcomponents.php',{
            method:'get',
            parameters:'action=display&type=ResourceShortSummary&objId='+content.obj1Id+'&containerObjectType='+content.obj2Type+'&containerObjectId='+content.obj2Id,
            onSuccess: function (transport){
              insertContent(divId,transport.responseText);
            }});
      } catch (e){
        str = 'An error was encountered while drawing the content. Please refresh the page.';
        str += '<br/>'+e.name+': '+e.message;
        Element.insert(divId,str);
      }
    }
  }}
);

// Customizations to support insertion of components via a second AJAX call
// (its a little slower, but it saves having to tediously rewrite all of the specific
// components in Javascript)
function insertContent(divId,content){
  Element.insert(divId,content);
}

// Handle creation and association of a resource with an object
function createAndAssociateResource(resUrl,resDesc,objType,objId,objAjaxNotify){
  new Ajax.Request(
        siteBaseUrl + '/model/AjaxHandler.php',{
        method:'post',
        parameters:'action=createAndAssociateResource&resUrl='+encodeURIComponent(resUrl)+'&resDesc='+encodeURIComponent(resDesc)+'&objType='+objType+'&objId='+objId,
        onSuccess: function (transport){
          ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
        }});
}
// Handle resource removal
function removeResource(objId,containerObjectType,containerObjectId,objAjaxNotify){
    new Ajax.Request(
        siteBaseUrl + '/model/AjaxHandler.php',{
        method:'post',
        parameters:'action=removeResource&objId='+objId+'&containerObjectType='+containerObjectType+'&containerObjectId='+containerObjectId,
        onSuccess: function (transport){
          ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
        }});

}
