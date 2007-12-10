function createBiomarkerOrganData(BiomarkerId,OrganId){
  alert("creating biomarkerorgandata object...");
  new Ajax.Request('model/AjaxHandler.php',{
                    method:'post',
                    parameters:'action=create&objType=BiomarkerOrganData&BiomarkerId='+BiomarkerId+'&OrganId='+OrganId,
                    onSuccess: function (transport){
                      ajaxNotice('notice',transport.responseText,'#dfd');
                    }});
}
function linkBiomarkerOrganStudyPublication(BiomarkerOrganStudyDataId,PublicationId){
  alert("linking biomarkerorganstudy ("+BiomarkerOrganStudyDataId+") and publication ("+PublicationId+")");
  new Ajax.Request('model/AjaxHandler.php',{
                    method:'post',
                    parameters:'action=associate&obj1Type=BiomarkerOrganStudyData&obj1Id='+BiomarkerOrganStudyDataId+'&obj1Attr=Publications&obj2Type=Publication&obj2Id='+PublicationId+'&obj2Attr=BiomarkerOrganStudies',
                    onSuccess: function (transport){
                      ajaxNotice('notice',transport.responseText,'#dfd');
                    }});
}
function linkBiomarkerPublication(BiomarkerId,PublicationId){
  alert("linking biomarker and publication...");
  new Ajax.Request('model/AjaxHandler.php',{
                    method:'post',
                    parameters:'action=associate&obj1Type=Biomarker&obj1Id='+BiomarkerId+'&obj1Attr=Publications&obj2Type=Publication&obj2Id='+PublicationId+'&obj2Attr=Biomarkers',
                    onSuccess: function (transport){
                      ajaxNotice('notice',transport.responseText,'#dfd');
                    }});
}
function linkStudyPublication(StudyId,PublicationId){
  alert("linking study and publication...");
  new Ajax.Request('model/AjaxHandler.php',{
                    method:'post',
                    parameters:'action=associate&obj1Type=Study&obj1Id='+StudyId+'&obj1Attr=Publications&obj2Type=Publication&obj2Id='+PublicationId+'&obj2Attr=Studies',
                    onSuccess: function (transport){
                      ajaxNotice('notice',transport.responseText,'#dfd');
                    }});
}


function linkBiomarkerOrganPublication(BiomarkerOrganDataId,PublicationId){
  alert("linking biomarker/organ and publication...");
  new Ajax.Request('model/AjaxHandler.php',{
                    method:'post',
                    parameters:'action=associate&obj1Type=BiomarkerOrganData&obj1Id='+BiomarkerOrganDataId+'&obj1Attr=Publications&obj2Type=Publication&obj2Id='+PublicationId+'&obj2Attr=BiomarkerOrgans',
                    onSuccess: function (transport){
                      ajaxNotice('notice',transport.responseText,'#dfd');
                    }});
}

function deletePublication(PublicationId,divId){
  alert("deleting publication with objId: " + PublicationId);
  new Ajax.Request('model/AjaxHandler.php',{
                    method:'post',
                    parameters:'action=delete&objType=Publication&objId='+PublicationId,
                    onSuccess: function (transport){
                      ajaxNotice(divId,transport.responseText,'#dfd');
                    }});
}

function linkBiomarkerOrganStudyDataPublication(BiomarkerOrganStudyDataId,PublicationId){
  alert("linking biomarkerorganstudy and publication...");
  new Ajax.Request('model/AjaxHandler.php',{
                    method:'post',
                    parameters:'action=associate&obj1Type=BiomarkerOrganStudyData&obj1Id='+BiomarkerOrganStudyDataId+'&obj1Attr=Publications&obj2Type=Publication&obj2Id='+PublicationId+'&obj2Attr=BiomarkerOrganStudies',
                    onSuccess: function (transport){
                      ajaxNotice('notice',transport.responseText,'#dfd');
                    }});
}

function createBiomarkerStudyData(BiomarkerId,StudyId){
  alert("creating biomarkerstudy object...");
  new Ajax.Request('model/AjaxHandler.php',{
                    method:'post',
                    parameters:'action=create&objType=BiomarkerStudyData&BiomarkerId='+BiomarkerId+'&StudyId='+StudyId,
                    onSuccess: function (transport){
                      ajaxNotice('notice',transport.responseText,'#dfd');
                    }});
}

function createBiomarkerOrganStudyData(BiomarkerOrganDataId,StudyId){
  alert("creating biomarkerorganstudydata object...");
  new Ajax.Request('model/AjaxHandler.php',{
                    method:'post',
                    parameters:'action=create&objType=BiomarkerOrganStudyData&BiomarkerOrganDataId='+BiomarkerOrganDataId+'&StudyId='+StudyId,
                    onSuccess: function (transport){
                      ajaxNotice('notice',transport.responseText,'#dfd');
                    }});
  
}

function create(objType){
  new Ajax.Request('model/AjaxHandler.php',{
                    method:'post',
                    parameters:'action=create&objType='+objType,
                    onSuccess: function (transport){
                      ajaxNotice('notice',transport.responseText,'#dfd');
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

function ajaxNotice(divId,content,bgColor){
  $(divId).update(content).setStyle({ background: bgColor });
}