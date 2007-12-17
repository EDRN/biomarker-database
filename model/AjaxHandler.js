/**
 *  create
 *    - create an object by specifying all 'unique' variables and IDs of all child elements
 *  link{Var}
 *    - link an object to a variable (by ID)
 *  unlink{Var}
 *    - unlink an object from a variable (by ID)
 *  delete
 *    - delete an object from the database (by ID,type)
 *
 *
**/



function createBiomarkerOrganData(BiomarkerId,OrganId){
  alert("creating biomarkerorgandata object...");
  new Ajax.Request('model/AjaxHandler.php',{
                    method:'post',
                    parameters:'action=create&objType=BiomarkerOrganData&BiomarkerId='+BiomarkerId+'&OrganId='+OrganId,
                    onSuccess: function (transport){
                      ajaxNotice('overviewContainer',transport.responseText,'#dfd','biomarkerOrganSummary');
                    }});
}
function linkBiomarkerOrganStudyPublication(BiomarkerOrganStudyDataId,PublicationId){
  alert("linking biomarkerorganstudy ("+BiomarkerOrganStudyDataId+") and publication ("+PublicationId+")");
  new Ajax.Request('model/AjaxHandler.php',{
                    method:'post',
                    parameters:'action=associate&obj1Type=BiomarkerOrganStudyData&obj1Id='+BiomarkerOrganStudyDataId+'&obj1Attr=Publications&obj2Type=Publication&obj2Id='+PublicationId+'&obj2Attr=BiomarkerOrganStudies',
                    onSuccess: function (transport){
                      ajaxNotice('notice',transport.responseText,'#dfd','');
                    }});
}
function linkBiomarkerPublication(BiomarkerId,PublicationId){
  alert("linking biomarker and publication...");
  new Ajax.Request('model/AjaxHandler.php',{
                    method:'post',
                    parameters:'action=associate&obj1Type=Biomarker&obj1Id='+BiomarkerId+'&obj1Attr=Publications&obj2Type=Publication&obj2Id='+PublicationId+'&obj2Attr=Biomarkers',
                    onSuccess: function (transport){
                      ajaxNotice('notice',transport.responseText,'#dfd','');
                    }});
}
function unlinkBiomarkerPublication(BiomarkerId,PublicationId,divId){
  alert("unlinking biomarker and publication...");
  new Ajax.Request('model/AjaxHandler.php',{
                    method:'post',
                    parameters:'action=associate&obj1Type=Biomarker&obj1Id='+BiomarkerId+'&obj1Attr=Publications&obj2Type=Publication&obj2Id='+PublicationId+'&obj2Attr=Biomarkers',
                    onSuccess: function (transport){
                      ajaxNotice('notice',transport.responseText,'#dfd','');
                    }});
}
function linkStudyPublication(StudyId,PublicationId){
  alert("linking study and publication...");
  new Ajax.Request('model/AjaxHandler.php',{
                    method:'post',
                    parameters:'action=associate&obj1Type=Study&obj1Id='+StudyId+'&obj1Attr=Publications&obj2Type=Publication&obj2Id='+PublicationId+'&obj2Attr=Studies',
                    onSuccess: function (transport){
                      ajaxNotice('notice',transport.responseText,'#dfd','');
                    }});
}


function linkBiomarkerOrganPublication(BiomarkerOrganDataId,PublicationId){
  alert("linking biomarker/organ and publication...");
  new Ajax.Request('model/AjaxHandler.php',{
                    method:'post',
                    parameters:'action=associate&obj1Type=BiomarkerOrganData&obj1Id='+BiomarkerOrganDataId+'&obj1Attr=Publications&obj2Type=Publication&obj2Id='+PublicationId+'&obj2Attr=BiomarkerOrgans',
                    onSuccess: function (transport){
                      ajaxNotice('notice',transport.responseText,'#dfd','');
                    }});
}

function deletePublication(PublicationId,divId){
  alert("deleting publication with objId: " + PublicationId);
  new Ajax.Request('model/AjaxHandler.php',{
                    method:'post',
                    parameters:'action=delete&objType=Publication&objId='+PublicationId,
                    onSuccess: function (transport){
                      ajaxNotice(divId,transport.responseText,'#dfd','deleted');
                    }});
}
function deleteBiomarkerOrganData(BiomarkerOrganDataId,divId){
  if (confirm('Really Delete this Biomarker/Organ Data?')){
    new Ajax.Request('model/AjaxHandler.php',{
                    method:'post',
                    parameters:'action=delete&objType=BiomarkerOrganData&objId='+BiomarkerOrganDataId,
                    onSuccess: function (transport){
                      ajaxNotice(divId,transport.responseText,'#dfd','deleted');
                    }});
  }
}

function linkBiomarkerOrganStudyDataPublication(BiomarkerOrganStudyDataId,PublicationId){
  alert("linking biomarkerorganstudy and publication...");
  new Ajax.Request('model/AjaxHandler.php',{
                    method:'post',
                    parameters:'action=associate&obj1Type=BiomarkerOrganStudyData&obj1Id='+BiomarkerOrganStudyDataId+'&obj1Attr=Publications&obj2Type=Publication&obj2Id='+PublicationId+'&obj2Attr=BiomarkerOrganStudies',
                    onSuccess: function (transport){
                      ajaxNotice('notice',transport.responseText,'#dfd','');
                    }});
}

function createBiomarkerStudyData(BiomarkerId,StudyId){
  alert("creating biomarkerstudy object...");
  new Ajax.Request('model/AjaxHandler.php',{
                    method:'post',
                    parameters:'action=create&objType=BiomarkerStudyData&BiomarkerId='+BiomarkerId+'&StudyId='+StudyId,
                    onSuccess: function (transport){
                      ajaxNotice('notice',transport.responseText,'#dfd','');
                    }});
}

function createBiomarkerOrganStudyData(BiomarkerOrganDataId,StudyId){
  alert("creating biomarkerorganstudydata object...");
  new Ajax.Request('model/AjaxHandler.php',{
                    method:'post',
                    parameters:'action=create&objType=BiomarkerOrganStudyData&BiomarkerOrganDataId='+BiomarkerOrganDataId+'&StudyId='+StudyId,
                    onSuccess: function (transport){
                      ajaxNotice('notice',transport.responseText,'#dfd','');
                    }});
  
}

function create(objType){
  new Ajax.Request('model/AjaxHandler.php',{
                    method:'post',
                    parameters:'action=create&objType='+objType,
                    onSuccess: function (transport){
                      ajaxNotice('notice',transport.responseText,'#dfd','');
                    }});
}

function link(obj1Id,obj1Type,obj1Attr,
              obj2Id,obj2Type,obj2Attr,
              successDiv,failureDiv){
  new Ajax.Updater({success:successDiv,failure:failureDiv},
                  'model/AjaxHandler.php',{
                    parameters: {
                     obj1Id:obj1Id,
                     obj1Type:obj1Type,
                     obj1Attr:obj1Attr,
                     obj2Id:obj2Id,
                     obj2Type:obj2Type,
                     obj2Attr:obj2Attr,
                     action: 'associate'
                    },
                    insertion: Insertion.Top
                  }
  );

}

function ajaxNotice(divId,content,bgColor,style){
  switch (style){
    case 'biomarkerOrganSummary':
        c = String(content);
        new Insertion.After(divId,drawBiomarkerOrganSummary(c)); 
        break;
    case 'deleted':
        $(divId).update(content).setStyle({background: bgColor});
        break;
    default:
      break;
  }
}
  
  var teststr = '{"objId": "11", "SensitivityMin": "", "SensitivityMax": "", "SensitivityComment": "", "SpecificityMin": "", "SpecificityMax": "", "SpecificityComment": "", "PPVMin": "", "PPVMax": "", "PPVComment": "", "NPVMin": "", "NPVMax": "", "NPVComment": "", "_objectType": "BiomarkerOrganData"}';
  
function drawBiomarkerOrganSummary(objJSON){
    jss = String(objJSON);
    obj = jss.evalJSON(true);
    str = '<div class=\"overview\" style="margin-left:9px;margin-top:9px;" id="overview'+obj.objId+'">'
          + '<h3><a href="biomarkerorgan.php?view=basics&objId='+obj.objId+'">'+obj.Organ.Name+'</a>&nbsp;<span class="titleAction pseudolink grey" onclick="deleteBiomarkerOrganData('+obj.objId+',\'overview'+obj.objId+'\');">(delete)</span></h3>'
          + '<table>'
          + '<tr><td>Sensitivity (Min/Max): </td><td>'+((obj.SensitivityMin == '')? '0' : obj.SensitivityMin)+' / '+((obj.SensitivityMax == '')? '0' : obj.SensitivityMax)+'</td></tr>'
          + '<tr><td>Specificity (Min/Max): </td><td>'+((obj.SpecificityMin == '')? '0' : obj.SpecificityMin)+' / '+((obj.SpecificityMax == '')? '0' : obj.SpecificityMax)+'</td></tr>'
          + '<tr><td>Negative Predictive Value (Min/Max): </td><td>'+((obj.NPVMin == '')? '0' : obj.NPVMin)+' / '+((obj.NPVMax == '')? '0' : obj.NPVMax)+'</td></tr>'
          + '<tr><td>Positive Predictive Value (Min/Max): </td><td>'+((obj.PPVMin == '')? '0' : obj.PPVMin)+' / '+((obj.PPVMax == '')? '0' : obj.PPVMax)+'</td></tr>'
          + '</table>'
          + '</div>';
    return str;
}
