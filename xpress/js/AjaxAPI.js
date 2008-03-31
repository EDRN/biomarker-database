// Javascript

	var siteBaseUrl = 'http://localhost/edrn_bmdb-0.3.0/webapp';

	var XPressJS = {
		Version: '1.0',
		require: function(libraryName) {
    		// inserting via DOM fails in Safari 2.0, so brute force approach
    		document.write('<script type="text/javascript" src="'+libraryName+'"><\/script>');
  		},
		loadExtentions: function() {
			$A(document.getElementsByTagName("script")).findAll( function(s) {
				return (s.src && s.src.match(/AjaxAPI\.js(\?.*)?$/))
			}).each( function(s) {
				var path = s.src.replace(/AjaxAPI\.js(\?.*)?$/,'');
				var includes = s.src.match(/\?.*load=([a-z,]*)/);
				(includes ? includes[1] : 'AjaxAPIExtensions').split(',').each(
					function(include) {XPressJS.require(path+include+'.js') });
    		});
		},
		generateId: function() {
			//get a random integer between 1 and 10000
			randomId = parseInt(Math.random()*10000);
			return ('XPressAjax'+randomId);
		}
	};

	
	
	var AjaxNotify = {
		divId : '',
		messageFormatTag: '',
		Create : function(divId,formatTag){
			this.divId = divId;
			this.messageFormatTag = formatTag;
		}
	};

	var MessageFormat = {
	 	None : {
			Tag: 'None',
			Build: function(strJSON,divId){
				return;
			}
	  	},
		Alert : {
			Tag: 'Alert',
			Build: function(strJSON,divId){
				alert(strJSON);
			}
		},
		Info : {
			Tag: 'Info',
			Build: function(strJSON,divId){
				okLink = '<span class="pseudolink" onclick="$(\''+divId+'\').hide();">OK</span>';
				$(divId).setStyle({background: '#dfd'});
				$(divId).update(strJSON + '&nbsp;' + okLink);
			}
		},
		Deleted : {
			Tag: 'Deleted',
			Build: function (strJSON,divId){
				$(divId).setStyle({background: '#fdd'});
				$(divId).update(strJSON);
				Effect.Fade(divId,{duration:1.2});
			}
		},
		extend : function(destination, source) {
			for (var property in source)
	    		destination[property] = source[property];
			return destination;
		},
		execute : function(divId,content,tag){
			if (this[tag]){
				this[tag].Build(content,divId);
			}
		}
	};

	function ajaxNotify(divId,content,tag){
		//alert('ajaxNotify called: ' + divId+', tag: '+tag);
		MessageFormat.execute(divId,content,tag);
		return;
	}

	var Biomarker = {
		Version: '1.0',
		CreateHandler: siteBaseUrl + 'xpress/js/AjaxHandler.php',
		LinkHandler:   siteBaseUrl + 'xpress/js/AjaxHandler.php',
		UnlinkHandler: siteBaseUrl + 'xpress/js/AjaxHandler.php',
		DeleteHandler: siteBaseUrl + 'xpress/js/AjaxHandler.php',
  
		Create: function(Title,objAjaxNotify){
		  new Ajax.Request(
		    this.CreateHandler,{
		    method:'post',
		    parameters:'action=create&objType=Biomarker&Title='+Title+'',
		    onSuccess: function (transport){
		      ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
		    }});
		},
		
		Delete: function(objId,objAjaxNotify){
			if (confirm('Really delete this object?')){
			  new Ajax.Request(
			    this.DeleteHandler,{
			      method:'post',
			      parameters:'action=delete&objType=Biomarker&objId='+objId,
			      onSuccess: function (transport){
			        ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
			      }});
			}
		},

		linkAlias: function (BiomarkerId,BiomarkerAliasId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=Biomarker&obj1Id='+BiomarkerId+'&obj1Attr=Aliases&obj2Type=BiomarkerAlias&obj2Id='+BiomarkerAliasId+'&obj2Attr=Biomarker',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkStudy: function (BiomarkerId,BiomarkerStudyDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=Biomarker&obj1Id='+BiomarkerId+'&obj1Attr=Studies&obj2Type=BiomarkerStudyData&obj2Id='+BiomarkerStudyDataId+'&obj2Attr=Biomarker',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkOrganData: function (BiomarkerId,BiomarkerOrganDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=Biomarker&obj1Id='+BiomarkerId+'&obj1Attr=OrganDatas&obj2Type=BiomarkerOrganData&obj2Id='+BiomarkerOrganDataId+'&obj2Attr=Biomarker',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkPublication: function (BiomarkerId,PublicationId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=Biomarker&obj1Id='+BiomarkerId+'&obj1Attr=Publications&obj2Type=Publication&obj2Id='+PublicationId+'&obj2Attr=Biomarkers',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkResource: function (BiomarkerId,ResourceId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=Biomarker&obj1Id='+BiomarkerId+'&obj1Attr=Resources&obj2Type=Resource&obj2Id='+ResourceId+'&obj2Attr=Biomarkers',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkAlias: function (BiomarkerId,BiomarkerAliasId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=Biomarker&obj1Id='+BiomarkerId+'&obj1Attr=Aliases&obj2Type=BiomarkerAlias&obj2Id='+BiomarkerAliasId+'&obj2Attr=Biomarker',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkStudy: function (BiomarkerId,BiomarkerStudyDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=Biomarker&obj1Id='+BiomarkerId+'&obj1Attr=Studies&obj2Type=BiomarkerStudyData&obj2Id='+BiomarkerStudyDataId+'&obj2Attr=Biomarker',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkOrganData: function (BiomarkerId,BiomarkerOrganDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=Biomarker&obj1Id='+BiomarkerId+'&obj1Attr=OrganDatas&obj2Type=BiomarkerOrganData&obj2Id='+BiomarkerOrganDataId+'&obj2Attr=Biomarker',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkPublication: function (BiomarkerId,PublicationId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=Biomarker&obj1Id='+BiomarkerId+'&obj1Attr=Publications&obj2Type=Publication&obj2Id='+PublicationId+'&obj2Attr=Biomarkers',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkResource: function (BiomarkerId,ResourceId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=Biomarker&obj1Id='+BiomarkerId+'&obj1Attr=Resources&obj2Type=Resource&obj2Id='+ResourceId+'&obj2Attr=Biomarkers',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkAlias: function (BiomarkerId,BiomarkerAliasId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=Biomarker&obj1Id='+BiomarkerId+'&obj1Attr=Aliases&obj2Type=BiomarkerAlias&obj2Id='+BiomarkerAliasId+'&obj2Attr=Biomarker',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkStudy: function (BiomarkerId,BiomarkerStudyDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=Biomarker&obj1Id='+BiomarkerId+'&obj1Attr=Studies&obj2Type=BiomarkerStudyData&obj2Id='+BiomarkerStudyDataId+'&obj2Attr=Biomarker',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkOrganData: function (BiomarkerId,BiomarkerOrganDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=Biomarker&obj1Id='+BiomarkerId+'&obj1Attr=OrganDatas&obj2Type=BiomarkerOrganData&obj2Id='+BiomarkerOrganDataId+'&obj2Attr=Biomarker',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkPublication: function (BiomarkerId,PublicationId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=Biomarker&obj1Id='+BiomarkerId+'&obj1Attr=Publications&obj2Type=Publication&obj2Id='+PublicationId+'&obj2Attr=Biomarkers',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkResource: function (BiomarkerId,ResourceId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=Biomarker&obj1Id='+BiomarkerId+'&obj1Attr=Resources&obj2Type=Resource&obj2Id='+ResourceId+'&obj2Attr=Biomarkers',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkAlias: function (BiomarkerId,BiomarkerAliasId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=Biomarker&obj1Id='+BiomarkerId+'&obj1Attr=Aliases&obj2Type=BiomarkerAlias&obj2Id='+BiomarkerAliasId+'&obj2Attr=Biomarker',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkStudy: function (BiomarkerId,BiomarkerStudyDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=Biomarker&obj1Id='+BiomarkerId+'&obj1Attr=Studies&obj2Type=BiomarkerStudyData&obj2Id='+BiomarkerStudyDataId+'&obj2Attr=Biomarker',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkOrganData: function (BiomarkerId,BiomarkerOrganDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=Biomarker&obj1Id='+BiomarkerId+'&obj1Attr=OrganDatas&obj2Type=BiomarkerOrganData&obj2Id='+BiomarkerOrganDataId+'&obj2Attr=Biomarker',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkPublication: function (BiomarkerId,PublicationId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=Biomarker&obj1Id='+BiomarkerId+'&obj1Attr=Publications&obj2Type=Publication&obj2Id='+PublicationId+'&obj2Attr=Biomarkers',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkResource: function (BiomarkerId,ResourceId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=Biomarker&obj1Id='+BiomarkerId+'&obj1Attr=Resources&obj2Type=Resource&obj2Id='+ResourceId+'&obj2Attr=Biomarkers',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		}
	};

	var BiomarkerAlias = {
		Version: '1.0',
		CreateHandler: siteBaseUrl + 'xpress/js/AjaxHandler.php',
		LinkHandler:   siteBaseUrl + 'xpress/js/AjaxHandler.php',
		UnlinkHandler: siteBaseUrl + 'xpress/js/AjaxHandler.php',
		DeleteHandler: siteBaseUrl + 'xpress/js/AjaxHandler.php',
  
		Create: function(BiomarkerId,BiomarkerId,objAjaxNotify){
		  new Ajax.Request(
		    this.CreateHandler,{
		    method:'post',
		    parameters:'action=create&objType=BiomarkerAlias&BiomarkerId='+BiomarkerId+'&BiomarkerId='+BiomarkerId+'',
		    onSuccess: function (transport){
		      ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
		    }});
		},
		
		Delete: function(objId,objAjaxNotify){
			if (confirm('Really delete this object?')){
			  new Ajax.Request(
			    this.DeleteHandler,{
			      method:'post',
			      parameters:'action=delete&objType=BiomarkerAlias&objId='+objId,
			      onSuccess: function (transport){
			        ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
			      }});
			}
		},

		linkBiomarker: function (BiomarkerAliasId,BiomarkerId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=BiomarkerAlias&obj1Id='+BiomarkerAliasId+'&obj1Attr=Biomarker&obj2Type=Biomarker&obj2Id='+BiomarkerId+'&obj2Attr=Aliases',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkBiomarker: function (BiomarkerAliasId,BiomarkerId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=BiomarkerAlias&obj1Id='+BiomarkerAliasId+'&obj1Attr=Biomarker&obj2Type=Biomarker&obj2Id='+BiomarkerId+'&obj2Attr=Aliases',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkBiomarker: function (BiomarkerAliasId,BiomarkerId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=BiomarkerAlias&obj1Id='+BiomarkerAliasId+'&obj1Attr=Biomarker&obj2Type=Biomarker&obj2Id='+BiomarkerId+'&obj2Attr=Aliases',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkBiomarker: function (BiomarkerAliasId,BiomarkerId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=BiomarkerAlias&obj1Id='+BiomarkerAliasId+'&obj1Attr=Biomarker&obj2Type=Biomarker&obj2Id='+BiomarkerId+'&obj2Attr=Aliases',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		}
	};

	var Study = {
		Version: '1.0',
		CreateHandler: siteBaseUrl + 'xpress/js/AjaxHandler.php',
		LinkHandler:   siteBaseUrl + 'xpress/js/AjaxHandler.php',
		UnlinkHandler: siteBaseUrl + 'xpress/js/AjaxHandler.php',
		DeleteHandler: siteBaseUrl + 'xpress/js/AjaxHandler.php',
  
		Create: function(Title,objAjaxNotify){
		  new Ajax.Request(
		    this.CreateHandler,{
		    method:'post',
		    parameters:'action=create&objType=Study&Title='+Title+'',
		    onSuccess: function (transport){
		      ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
		    }});
		},
		
		Delete: function(objId,objAjaxNotify){
			if (confirm('Really delete this object?')){
			  new Ajax.Request(
			    this.DeleteHandler,{
			      method:'post',
			      parameters:'action=delete&objType=Study&objId='+objId,
			      onSuccess: function (transport){
			        ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
			      }});
			}
		},

		linkBiomarker: function (StudyId,BiomarkerStudyDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=Study&obj1Id='+StudyId+'&obj1Attr=Biomarkers&obj2Type=BiomarkerStudyData&obj2Id='+BiomarkerStudyDataId+'&obj2Attr=Study',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkBiomarkerOrgan: function (StudyId,BiomarkerOrganDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=Study&obj1Id='+StudyId+'&obj1Attr=BiomarkerOrgans&obj2Type=BiomarkerOrganData&obj2Id='+BiomarkerOrganDataId+'&obj2Attr=',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkBiomarkerOrganData: function (StudyId,BiomarkerOrganStudyDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=Study&obj1Id='+StudyId+'&obj1Attr=BiomarkerOrganDatas&obj2Type=BiomarkerOrganStudyData&obj2Id='+BiomarkerOrganStudyDataId+'&obj2Attr=Study',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkPublication: function (StudyId,PublicationId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=Study&obj1Id='+StudyId+'&obj1Attr=Publications&obj2Type=Publication&obj2Id='+PublicationId+'&obj2Attr=Studies',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkResource: function (StudyId,ResourceId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=Study&obj1Id='+StudyId+'&obj1Attr=Resources&obj2Type=Resource&obj2Id='+ResourceId+'&obj2Attr=Studies',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkBiomarker: function (StudyId,BiomarkerStudyDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=Study&obj1Id='+StudyId+'&obj1Attr=Biomarkers&obj2Type=BiomarkerStudyData&obj2Id='+BiomarkerStudyDataId+'&obj2Attr=Study',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkBiomarkerOrgan: function (StudyId,BiomarkerOrganDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=Study&obj1Id='+StudyId+'&obj1Attr=BiomarkerOrgans&obj2Type=BiomarkerOrganData&obj2Id='+BiomarkerOrganDataId+'&obj2Attr=',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkBiomarkerOrganData: function (StudyId,BiomarkerOrganStudyDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=Study&obj1Id='+StudyId+'&obj1Attr=BiomarkerOrganDatas&obj2Type=BiomarkerOrganStudyData&obj2Id='+BiomarkerOrganStudyDataId+'&obj2Attr=Study',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkPublication: function (StudyId,PublicationId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=Study&obj1Id='+StudyId+'&obj1Attr=Publications&obj2Type=Publication&obj2Id='+PublicationId+'&obj2Attr=Studies',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkResource: function (StudyId,ResourceId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=Study&obj1Id='+StudyId+'&obj1Attr=Resources&obj2Type=Resource&obj2Id='+ResourceId+'&obj2Attr=Studies',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkBiomarker: function (StudyId,BiomarkerStudyDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=Study&obj1Id='+StudyId+'&obj1Attr=Biomarkers&obj2Type=BiomarkerStudyData&obj2Id='+BiomarkerStudyDataId+'&obj2Attr=Study',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkBiomarkerOrgan: function (StudyId,BiomarkerOrganDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=Study&obj1Id='+StudyId+'&obj1Attr=BiomarkerOrgans&obj2Type=BiomarkerOrganData&obj2Id='+BiomarkerOrganDataId+'&obj2Attr=',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkBiomarkerOrganData: function (StudyId,BiomarkerOrganStudyDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=Study&obj1Id='+StudyId+'&obj1Attr=BiomarkerOrganDatas&obj2Type=BiomarkerOrganStudyData&obj2Id='+BiomarkerOrganStudyDataId+'&obj2Attr=Study',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkPublication: function (StudyId,PublicationId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=Study&obj1Id='+StudyId+'&obj1Attr=Publications&obj2Type=Publication&obj2Id='+PublicationId+'&obj2Attr=Studies',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkResource: function (StudyId,ResourceId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=Study&obj1Id='+StudyId+'&obj1Attr=Resources&obj2Type=Resource&obj2Id='+ResourceId+'&obj2Attr=Studies',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkBiomarker: function (StudyId,BiomarkerStudyDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=Study&obj1Id='+StudyId+'&obj1Attr=Biomarkers&obj2Type=BiomarkerStudyData&obj2Id='+BiomarkerStudyDataId+'&obj2Attr=Study',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkBiomarkerOrgan: function (StudyId,BiomarkerOrganDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=Study&obj1Id='+StudyId+'&obj1Attr=BiomarkerOrgans&obj2Type=BiomarkerOrganData&obj2Id='+BiomarkerOrganDataId+'&obj2Attr=',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkBiomarkerOrganData: function (StudyId,BiomarkerOrganStudyDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=Study&obj1Id='+StudyId+'&obj1Attr=BiomarkerOrganDatas&obj2Type=BiomarkerOrganStudyData&obj2Id='+BiomarkerOrganStudyDataId+'&obj2Attr=Study',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkPublication: function (StudyId,PublicationId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=Study&obj1Id='+StudyId+'&obj1Attr=Publications&obj2Type=Publication&obj2Id='+PublicationId+'&obj2Attr=Studies',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkResource: function (StudyId,ResourceId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=Study&obj1Id='+StudyId+'&obj1Attr=Resources&obj2Type=Resource&obj2Id='+ResourceId+'&obj2Attr=Studies',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		}
	};

	var BiomarkerStudyData = {
		Version: '1.0',
		CreateHandler: siteBaseUrl + 'xpress/js/AjaxHandler.php',
		LinkHandler:   siteBaseUrl + 'xpress/js/AjaxHandler.php',
		UnlinkHandler: siteBaseUrl + 'xpress/js/AjaxHandler.php',
		DeleteHandler: siteBaseUrl + 'xpress/js/AjaxHandler.php',
  
		Create: function(StudyId,BiomarkerId,StudyId,BiomarkerId,objAjaxNotify){
		  new Ajax.Request(
		    this.CreateHandler,{
		    method:'post',
		    parameters:'action=create&objType=BiomarkerStudyData&StudyId='+StudyId+'&BiomarkerId='+BiomarkerId+'&StudyId='+StudyId+'&BiomarkerId='+BiomarkerId+'',
		    onSuccess: function (transport){
		      ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
		    }});
		},
		
		Delete: function(objId,objAjaxNotify){
			if (confirm('Really delete this object?')){
			  new Ajax.Request(
			    this.DeleteHandler,{
			      method:'post',
			      parameters:'action=delete&objType=BiomarkerStudyData&objId='+objId,
			      onSuccess: function (transport){
			        ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
			      }});
			}
		},

		linkStudy: function (BiomarkerStudyDataId,StudyId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=BiomarkerStudyData&obj1Id='+BiomarkerStudyDataId+'&obj1Attr=Study&obj2Type=Study&obj2Id='+StudyId+'&obj2Attr=Biomarkers',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkBiomarker: function (BiomarkerStudyDataId,BiomarkerId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=BiomarkerStudyData&obj1Id='+BiomarkerStudyDataId+'&obj1Attr=Biomarker&obj2Type=Biomarker&obj2Id='+BiomarkerId+'&obj2Attr=Studies',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkPublication: function (BiomarkerStudyDataId,PublicationId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=BiomarkerStudyData&obj1Id='+BiomarkerStudyDataId+'&obj1Attr=Publications&obj2Type=Publication&obj2Id='+PublicationId+'&obj2Attr=BiomarkerStudies',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkResource: function (BiomarkerStudyDataId,ResourceId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=BiomarkerStudyData&obj1Id='+BiomarkerStudyDataId+'&obj1Attr=Resources&obj2Type=Resource&obj2Id='+ResourceId+'&obj2Attr=BiomarkerStudies',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkStudy: function (BiomarkerStudyDataId,StudyId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=BiomarkerStudyData&obj1Id='+BiomarkerStudyDataId+'&obj1Attr=Study&obj2Type=Study&obj2Id='+StudyId+'&obj2Attr=Biomarkers',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkBiomarker: function (BiomarkerStudyDataId,BiomarkerId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=BiomarkerStudyData&obj1Id='+BiomarkerStudyDataId+'&obj1Attr=Biomarker&obj2Type=Biomarker&obj2Id='+BiomarkerId+'&obj2Attr=Studies',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkPublication: function (BiomarkerStudyDataId,PublicationId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=BiomarkerStudyData&obj1Id='+BiomarkerStudyDataId+'&obj1Attr=Publications&obj2Type=Publication&obj2Id='+PublicationId+'&obj2Attr=BiomarkerStudies',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkResource: function (BiomarkerStudyDataId,ResourceId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=BiomarkerStudyData&obj1Id='+BiomarkerStudyDataId+'&obj1Attr=Resources&obj2Type=Resource&obj2Id='+ResourceId+'&obj2Attr=BiomarkerStudies',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkStudy: function (BiomarkerStudyDataId,StudyId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=BiomarkerStudyData&obj1Id='+BiomarkerStudyDataId+'&obj1Attr=Study&obj2Type=Study&obj2Id='+StudyId+'&obj2Attr=Biomarkers',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkBiomarker: function (BiomarkerStudyDataId,BiomarkerId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=BiomarkerStudyData&obj1Id='+BiomarkerStudyDataId+'&obj1Attr=Biomarker&obj2Type=Biomarker&obj2Id='+BiomarkerId+'&obj2Attr=Studies',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkPublication: function (BiomarkerStudyDataId,PublicationId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=BiomarkerStudyData&obj1Id='+BiomarkerStudyDataId+'&obj1Attr=Publications&obj2Type=Publication&obj2Id='+PublicationId+'&obj2Attr=BiomarkerStudies',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkResource: function (BiomarkerStudyDataId,ResourceId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=BiomarkerStudyData&obj1Id='+BiomarkerStudyDataId+'&obj1Attr=Resources&obj2Type=Resource&obj2Id='+ResourceId+'&obj2Attr=BiomarkerStudies',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkStudy: function (BiomarkerStudyDataId,StudyId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=BiomarkerStudyData&obj1Id='+BiomarkerStudyDataId+'&obj1Attr=Study&obj2Type=Study&obj2Id='+StudyId+'&obj2Attr=Biomarkers',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkBiomarker: function (BiomarkerStudyDataId,BiomarkerId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=BiomarkerStudyData&obj1Id='+BiomarkerStudyDataId+'&obj1Attr=Biomarker&obj2Type=Biomarker&obj2Id='+BiomarkerId+'&obj2Attr=Studies',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkPublication: function (BiomarkerStudyDataId,PublicationId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=BiomarkerStudyData&obj1Id='+BiomarkerStudyDataId+'&obj1Attr=Publications&obj2Type=Publication&obj2Id='+PublicationId+'&obj2Attr=BiomarkerStudies',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkResource: function (BiomarkerStudyDataId,ResourceId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=BiomarkerStudyData&obj1Id='+BiomarkerStudyDataId+'&obj1Attr=Resources&obj2Type=Resource&obj2Id='+ResourceId+'&obj2Attr=BiomarkerStudies',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		}
	};

	var Organ = {
		Version: '1.0',
		CreateHandler: siteBaseUrl + 'xpress/js/AjaxHandler.php',
		LinkHandler:   siteBaseUrl + 'xpress/js/AjaxHandler.php',
		UnlinkHandler: siteBaseUrl + 'xpress/js/AjaxHandler.php',
		DeleteHandler: siteBaseUrl + 'xpress/js/AjaxHandler.php',
  
		Create: function(Name,objAjaxNotify){
		  new Ajax.Request(
		    this.CreateHandler,{
		    method:'post',
		    parameters:'action=create&objType=Organ&Name='+Name+'',
		    onSuccess: function (transport){
		      ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
		    }});
		},
		
		Delete: function(objId,objAjaxNotify){
			if (confirm('Really delete this object?')){
			  new Ajax.Request(
			    this.DeleteHandler,{
			      method:'post',
			      parameters:'action=delete&objType=Organ&objId='+objId,
			      onSuccess: function (transport){
			        ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
			      }});
			}
		},

		linkOrganData: function (OrganId,BiomarkerOrganDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=Organ&obj1Id='+OrganId+'&obj1Attr=OrganDatas&obj2Type=BiomarkerOrganData&obj2Id='+BiomarkerOrganDataId+'&obj2Attr=Organ',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkOrganData: function (OrganId,BiomarkerOrganDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=Organ&obj1Id='+OrganId+'&obj1Attr=OrganDatas&obj2Type=BiomarkerOrganData&obj2Id='+BiomarkerOrganDataId+'&obj2Attr=Organ',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkOrganData: function (OrganId,BiomarkerOrganDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=Organ&obj1Id='+OrganId+'&obj1Attr=OrganDatas&obj2Type=BiomarkerOrganData&obj2Id='+BiomarkerOrganDataId+'&obj2Attr=Organ',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkOrganData: function (OrganId,BiomarkerOrganDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=Organ&obj1Id='+OrganId+'&obj1Attr=OrganDatas&obj2Type=BiomarkerOrganData&obj2Id='+BiomarkerOrganDataId+'&obj2Attr=Organ',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		}
	};

	var BiomarkerOrganData = {
		Version: '1.0',
		CreateHandler: siteBaseUrl + 'xpress/js/AjaxHandler.php',
		LinkHandler:   siteBaseUrl + 'xpress/js/AjaxHandler.php',
		UnlinkHandler: siteBaseUrl + 'xpress/js/AjaxHandler.php',
		DeleteHandler: siteBaseUrl + 'xpress/js/AjaxHandler.php',
  
		Create: function(OrganId,BiomarkerId,OrganId,BiomarkerId,objAjaxNotify){
		  new Ajax.Request(
		    this.CreateHandler,{
		    method:'post',
		    parameters:'action=create&objType=BiomarkerOrganData&OrganId='+OrganId+'&BiomarkerId='+BiomarkerId+'&OrganId='+OrganId+'&BiomarkerId='+BiomarkerId+'',
		    onSuccess: function (transport){
		      ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
		    }});
		},
		
		Delete: function(objId,objAjaxNotify){
			if (confirm('Really delete this object?')){
			  new Ajax.Request(
			    this.DeleteHandler,{
			      method:'post',
			      parameters:'action=delete&objType=BiomarkerOrganData&objId='+objId,
			      onSuccess: function (transport){
			        ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
			      }});
			}
		},

		linkOrgan: function (BiomarkerOrganDataId,OrganId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=BiomarkerOrganData&obj1Id='+BiomarkerOrganDataId+'&obj1Attr=Organ&obj2Type=Organ&obj2Id='+OrganId+'&obj2Attr=OrganDatas',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkBiomarker: function (BiomarkerOrganDataId,BiomarkerId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=BiomarkerOrganData&obj1Id='+BiomarkerOrganDataId+'&obj1Attr=Biomarker&obj2Type=Biomarker&obj2Id='+BiomarkerId+'&obj2Attr=OrganDatas',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkResource: function (BiomarkerOrganDataId,ResourceId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=BiomarkerOrganData&obj1Id='+BiomarkerOrganDataId+'&obj1Attr=Resources&obj2Type=Resource&obj2Id='+ResourceId+'&obj2Attr=BiomarkerOrgans',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkPublication: function (BiomarkerOrganDataId,PublicationId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=BiomarkerOrganData&obj1Id='+BiomarkerOrganDataId+'&obj1Attr=Publications&obj2Type=Publication&obj2Id='+PublicationId+'&obj2Attr=BiomarkerOrgans',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkStudyData: function (BiomarkerOrganDataId,BiomarkerOrganStudyDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=BiomarkerOrganData&obj1Id='+BiomarkerOrganDataId+'&obj1Attr=StudyDatas&obj2Type=BiomarkerOrganStudyData&obj2Id='+BiomarkerOrganStudyDataId+'&obj2Attr=BiomarkerOrganData',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkOrgan: function (BiomarkerOrganDataId,OrganId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=BiomarkerOrganData&obj1Id='+BiomarkerOrganDataId+'&obj1Attr=Organ&obj2Type=Organ&obj2Id='+OrganId+'&obj2Attr=OrganDatas',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkBiomarker: function (BiomarkerOrganDataId,BiomarkerId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=BiomarkerOrganData&obj1Id='+BiomarkerOrganDataId+'&obj1Attr=Biomarker&obj2Type=Biomarker&obj2Id='+BiomarkerId+'&obj2Attr=OrganDatas',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkResource: function (BiomarkerOrganDataId,ResourceId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=BiomarkerOrganData&obj1Id='+BiomarkerOrganDataId+'&obj1Attr=Resources&obj2Type=Resource&obj2Id='+ResourceId+'&obj2Attr=BiomarkerOrgans',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkPublication: function (BiomarkerOrganDataId,PublicationId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=BiomarkerOrganData&obj1Id='+BiomarkerOrganDataId+'&obj1Attr=Publications&obj2Type=Publication&obj2Id='+PublicationId+'&obj2Attr=BiomarkerOrgans',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkStudyData: function (BiomarkerOrganDataId,BiomarkerOrganStudyDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=BiomarkerOrganData&obj1Id='+BiomarkerOrganDataId+'&obj1Attr=StudyDatas&obj2Type=BiomarkerOrganStudyData&obj2Id='+BiomarkerOrganStudyDataId+'&obj2Attr=BiomarkerOrganData',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkOrgan: function (BiomarkerOrganDataId,OrganId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=BiomarkerOrganData&obj1Id='+BiomarkerOrganDataId+'&obj1Attr=Organ&obj2Type=Organ&obj2Id='+OrganId+'&obj2Attr=OrganDatas',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkBiomarker: function (BiomarkerOrganDataId,BiomarkerId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=BiomarkerOrganData&obj1Id='+BiomarkerOrganDataId+'&obj1Attr=Biomarker&obj2Type=Biomarker&obj2Id='+BiomarkerId+'&obj2Attr=OrganDatas',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkResource: function (BiomarkerOrganDataId,ResourceId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=BiomarkerOrganData&obj1Id='+BiomarkerOrganDataId+'&obj1Attr=Resources&obj2Type=Resource&obj2Id='+ResourceId+'&obj2Attr=BiomarkerOrgans',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkPublication: function (BiomarkerOrganDataId,PublicationId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=BiomarkerOrganData&obj1Id='+BiomarkerOrganDataId+'&obj1Attr=Publications&obj2Type=Publication&obj2Id='+PublicationId+'&obj2Attr=BiomarkerOrgans',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkStudyData: function (BiomarkerOrganDataId,BiomarkerOrganStudyDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=BiomarkerOrganData&obj1Id='+BiomarkerOrganDataId+'&obj1Attr=StudyDatas&obj2Type=BiomarkerOrganStudyData&obj2Id='+BiomarkerOrganStudyDataId+'&obj2Attr=BiomarkerOrganData',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkOrgan: function (BiomarkerOrganDataId,OrganId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=BiomarkerOrganData&obj1Id='+BiomarkerOrganDataId+'&obj1Attr=Organ&obj2Type=Organ&obj2Id='+OrganId+'&obj2Attr=OrganDatas',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkBiomarker: function (BiomarkerOrganDataId,BiomarkerId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=BiomarkerOrganData&obj1Id='+BiomarkerOrganDataId+'&obj1Attr=Biomarker&obj2Type=Biomarker&obj2Id='+BiomarkerId+'&obj2Attr=OrganDatas',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkResource: function (BiomarkerOrganDataId,ResourceId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=BiomarkerOrganData&obj1Id='+BiomarkerOrganDataId+'&obj1Attr=Resources&obj2Type=Resource&obj2Id='+ResourceId+'&obj2Attr=BiomarkerOrgans',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkPublication: function (BiomarkerOrganDataId,PublicationId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=BiomarkerOrganData&obj1Id='+BiomarkerOrganDataId+'&obj1Attr=Publications&obj2Type=Publication&obj2Id='+PublicationId+'&obj2Attr=BiomarkerOrgans',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkStudyData: function (BiomarkerOrganDataId,BiomarkerOrganStudyDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=BiomarkerOrganData&obj1Id='+BiomarkerOrganDataId+'&obj1Attr=StudyDatas&obj2Type=BiomarkerOrganStudyData&obj2Id='+BiomarkerOrganStudyDataId+'&obj2Attr=BiomarkerOrganData',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		}
	};

	var BiomarkerOrganStudyData = {
		Version: '1.0',
		CreateHandler: siteBaseUrl + 'xpress/js/AjaxHandler.php',
		LinkHandler:   siteBaseUrl + 'xpress/js/AjaxHandler.php',
		UnlinkHandler: siteBaseUrl + 'xpress/js/AjaxHandler.php',
		DeleteHandler: siteBaseUrl + 'xpress/js/AjaxHandler.php',
  
		Create: function(StudyId,BiomarkerOrganDataId,StudyId,BiomarkerOrganDataId,objAjaxNotify){
		  new Ajax.Request(
		    this.CreateHandler,{
		    method:'post',
		    parameters:'action=create&objType=BiomarkerOrganStudyData&StudyId='+StudyId+'&BiomarkerOrganDataId='+BiomarkerOrganDataId+'&StudyId='+StudyId+'&BiomarkerOrganDataId='+BiomarkerOrganDataId+'',
		    onSuccess: function (transport){
		      ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
		    }});
		},
		
		Delete: function(objId,objAjaxNotify){
			if (confirm('Really delete this object?')){
			  new Ajax.Request(
			    this.DeleteHandler,{
			      method:'post',
			      parameters:'action=delete&objType=BiomarkerOrganStudyData&objId='+objId,
			      onSuccess: function (transport){
			        ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
			      }});
			}
		},

		linkStudy: function (BiomarkerOrganStudyDataId,StudyId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=BiomarkerOrganStudyData&obj1Id='+BiomarkerOrganStudyDataId+'&obj1Attr=Study&obj2Type=Study&obj2Id='+StudyId+'&obj2Attr=BiomarkerOrganDatas',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkBiomarkerOrganData: function (BiomarkerOrganStudyDataId,BiomarkerOrganDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=BiomarkerOrganStudyData&obj1Id='+BiomarkerOrganStudyDataId+'&obj1Attr=BiomarkerOrganData&obj2Type=BiomarkerOrganData&obj2Id='+BiomarkerOrganDataId+'&obj2Attr=StudyDatas',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkPublication: function (BiomarkerOrganStudyDataId,PublicationId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=BiomarkerOrganStudyData&obj1Id='+BiomarkerOrganStudyDataId+'&obj1Attr=Publications&obj2Type=Publication&obj2Id='+PublicationId+'&obj2Attr=BiomarkerOrganStudies',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkResource: function (BiomarkerOrganStudyDataId,ResourceId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=BiomarkerOrganStudyData&obj1Id='+BiomarkerOrganStudyDataId+'&obj1Attr=Resources&obj2Type=Resource&obj2Id='+ResourceId+'&obj2Attr=BiomarkerOrganStudies',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkStudy: function (BiomarkerOrganStudyDataId,StudyId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=BiomarkerOrganStudyData&obj1Id='+BiomarkerOrganStudyDataId+'&obj1Attr=Study&obj2Type=Study&obj2Id='+StudyId+'&obj2Attr=BiomarkerOrganDatas',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkBiomarkerOrganData: function (BiomarkerOrganStudyDataId,BiomarkerOrganDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=BiomarkerOrganStudyData&obj1Id='+BiomarkerOrganStudyDataId+'&obj1Attr=BiomarkerOrganData&obj2Type=BiomarkerOrganData&obj2Id='+BiomarkerOrganDataId+'&obj2Attr=StudyDatas',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkPublication: function (BiomarkerOrganStudyDataId,PublicationId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=BiomarkerOrganStudyData&obj1Id='+BiomarkerOrganStudyDataId+'&obj1Attr=Publications&obj2Type=Publication&obj2Id='+PublicationId+'&obj2Attr=BiomarkerOrganStudies',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkResource: function (BiomarkerOrganStudyDataId,ResourceId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=BiomarkerOrganStudyData&obj1Id='+BiomarkerOrganStudyDataId+'&obj1Attr=Resources&obj2Type=Resource&obj2Id='+ResourceId+'&obj2Attr=BiomarkerOrganStudies',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkStudy: function (BiomarkerOrganStudyDataId,StudyId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=BiomarkerOrganStudyData&obj1Id='+BiomarkerOrganStudyDataId+'&obj1Attr=Study&obj2Type=Study&obj2Id='+StudyId+'&obj2Attr=BiomarkerOrganDatas',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkBiomarkerOrganData: function (BiomarkerOrganStudyDataId,BiomarkerOrganDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=BiomarkerOrganStudyData&obj1Id='+BiomarkerOrganStudyDataId+'&obj1Attr=BiomarkerOrganData&obj2Type=BiomarkerOrganData&obj2Id='+BiomarkerOrganDataId+'&obj2Attr=StudyDatas',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkPublication: function (BiomarkerOrganStudyDataId,PublicationId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=BiomarkerOrganStudyData&obj1Id='+BiomarkerOrganStudyDataId+'&obj1Attr=Publications&obj2Type=Publication&obj2Id='+PublicationId+'&obj2Attr=BiomarkerOrganStudies',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkResource: function (BiomarkerOrganStudyDataId,ResourceId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=BiomarkerOrganStudyData&obj1Id='+BiomarkerOrganStudyDataId+'&obj1Attr=Resources&obj2Type=Resource&obj2Id='+ResourceId+'&obj2Attr=BiomarkerOrganStudies',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkStudy: function (BiomarkerOrganStudyDataId,StudyId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=BiomarkerOrganStudyData&obj1Id='+BiomarkerOrganStudyDataId+'&obj1Attr=Study&obj2Type=Study&obj2Id='+StudyId+'&obj2Attr=BiomarkerOrganDatas',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkBiomarkerOrganData: function (BiomarkerOrganStudyDataId,BiomarkerOrganDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=BiomarkerOrganStudyData&obj1Id='+BiomarkerOrganStudyDataId+'&obj1Attr=BiomarkerOrganData&obj2Type=BiomarkerOrganData&obj2Id='+BiomarkerOrganDataId+'&obj2Attr=StudyDatas',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkPublication: function (BiomarkerOrganStudyDataId,PublicationId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=BiomarkerOrganStudyData&obj1Id='+BiomarkerOrganStudyDataId+'&obj1Attr=Publications&obj2Type=Publication&obj2Id='+PublicationId+'&obj2Attr=BiomarkerOrganStudies',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkResource: function (BiomarkerOrganStudyDataId,ResourceId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=BiomarkerOrganStudyData&obj1Id='+BiomarkerOrganStudyDataId+'&obj1Attr=Resources&obj2Type=Resource&obj2Id='+ResourceId+'&obj2Attr=BiomarkerOrganStudies',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		}
	};

	var Publication = {
		Version: '1.0',
		CreateHandler: siteBaseUrl + 'xpress/js/AjaxHandler.php',
		LinkHandler:   siteBaseUrl + 'xpress/js/AjaxHandler.php',
		UnlinkHandler: siteBaseUrl + 'xpress/js/AjaxHandler.php',
		DeleteHandler: siteBaseUrl + 'xpress/js/AjaxHandler.php',
  
		Create: function(PubMedID,objAjaxNotify){
		  new Ajax.Request(
		    this.CreateHandler,{
		    method:'post',
		    parameters:'action=create&objType=Publication&PubMedID='+PubMedID+'',
		    onSuccess: function (transport){
		      ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
		    }});
		},
		
		Delete: function(objId,objAjaxNotify){
			if (confirm('Really delete this object?')){
			  new Ajax.Request(
			    this.DeleteHandler,{
			      method:'post',
			      parameters:'action=delete&objType=Publication&objId='+objId,
			      onSuccess: function (transport){
			        ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
			      }});
			}
		},

		linkBiomarker: function (PublicationId,BiomarkerId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=Publication&obj1Id='+PublicationId+'&obj1Attr=Biomarkers&obj2Type=Biomarker&obj2Id='+BiomarkerId+'&obj2Attr=Publications',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkBiomarkerOrgan: function (PublicationId,BiomarkerOrganDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=Publication&obj1Id='+PublicationId+'&obj1Attr=BiomarkerOrgans&obj2Type=BiomarkerOrganData&obj2Id='+BiomarkerOrganDataId+'&obj2Attr=Publications',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkBiomarkerOrganStudy: function (PublicationId,BiomarkerOrganStudyDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=Publication&obj1Id='+PublicationId+'&obj1Attr=BiomarkerOrganStudies&obj2Type=BiomarkerOrganStudyData&obj2Id='+BiomarkerOrganStudyDataId+'&obj2Attr=Publications',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkBiomarkerStudy: function (PublicationId,BiomarkerStudyDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=Publication&obj1Id='+PublicationId+'&obj1Attr=BiomarkerStudies&obj2Type=BiomarkerStudyData&obj2Id='+BiomarkerStudyDataId+'&obj2Attr=Publications',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkStudy: function (PublicationId,StudyId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=Publication&obj1Id='+PublicationId+'&obj1Attr=Studies&obj2Type=Study&obj2Id='+StudyId+'&obj2Attr=Publications',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkBiomarker: function (PublicationId,BiomarkerId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=Publication&obj1Id='+PublicationId+'&obj1Attr=Biomarkers&obj2Type=Biomarker&obj2Id='+BiomarkerId+'&obj2Attr=Publications',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkBiomarkerOrgan: function (PublicationId,BiomarkerOrganDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=Publication&obj1Id='+PublicationId+'&obj1Attr=BiomarkerOrgans&obj2Type=BiomarkerOrganData&obj2Id='+BiomarkerOrganDataId+'&obj2Attr=Publications',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkBiomarkerOrganStudy: function (PublicationId,BiomarkerOrganStudyDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=Publication&obj1Id='+PublicationId+'&obj1Attr=BiomarkerOrganStudies&obj2Type=BiomarkerOrganStudyData&obj2Id='+BiomarkerOrganStudyDataId+'&obj2Attr=Publications',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkBiomarkerStudy: function (PublicationId,BiomarkerStudyDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=Publication&obj1Id='+PublicationId+'&obj1Attr=BiomarkerStudies&obj2Type=BiomarkerStudyData&obj2Id='+BiomarkerStudyDataId+'&obj2Attr=Publications',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkStudy: function (PublicationId,StudyId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=Publication&obj1Id='+PublicationId+'&obj1Attr=Studies&obj2Type=Study&obj2Id='+StudyId+'&obj2Attr=Publications',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkBiomarker: function (PublicationId,BiomarkerId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=Publication&obj1Id='+PublicationId+'&obj1Attr=Biomarkers&obj2Type=Biomarker&obj2Id='+BiomarkerId+'&obj2Attr=Publications',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkBiomarkerOrgan: function (PublicationId,BiomarkerOrganDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=Publication&obj1Id='+PublicationId+'&obj1Attr=BiomarkerOrgans&obj2Type=BiomarkerOrganData&obj2Id='+BiomarkerOrganDataId+'&obj2Attr=Publications',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkBiomarkerOrganStudy: function (PublicationId,BiomarkerOrganStudyDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=Publication&obj1Id='+PublicationId+'&obj1Attr=BiomarkerOrganStudies&obj2Type=BiomarkerOrganStudyData&obj2Id='+BiomarkerOrganStudyDataId+'&obj2Attr=Publications',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkBiomarkerStudy: function (PublicationId,BiomarkerStudyDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=Publication&obj1Id='+PublicationId+'&obj1Attr=BiomarkerStudies&obj2Type=BiomarkerStudyData&obj2Id='+BiomarkerStudyDataId+'&obj2Attr=Publications',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkStudy: function (PublicationId,StudyId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=Publication&obj1Id='+PublicationId+'&obj1Attr=Studies&obj2Type=Study&obj2Id='+StudyId+'&obj2Attr=Publications',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkBiomarker: function (PublicationId,BiomarkerId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=Publication&obj1Id='+PublicationId+'&obj1Attr=Biomarkers&obj2Type=Biomarker&obj2Id='+BiomarkerId+'&obj2Attr=Publications',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkBiomarkerOrgan: function (PublicationId,BiomarkerOrganDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=Publication&obj1Id='+PublicationId+'&obj1Attr=BiomarkerOrgans&obj2Type=BiomarkerOrganData&obj2Id='+BiomarkerOrganDataId+'&obj2Attr=Publications',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkBiomarkerOrganStudy: function (PublicationId,BiomarkerOrganStudyDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=Publication&obj1Id='+PublicationId+'&obj1Attr=BiomarkerOrganStudies&obj2Type=BiomarkerOrganStudyData&obj2Id='+BiomarkerOrganStudyDataId+'&obj2Attr=Publications',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkBiomarkerStudy: function (PublicationId,BiomarkerStudyDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=Publication&obj1Id='+PublicationId+'&obj1Attr=BiomarkerStudies&obj2Type=BiomarkerStudyData&obj2Id='+BiomarkerStudyDataId+'&obj2Attr=Publications',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkStudy: function (PublicationId,StudyId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=Publication&obj1Id='+PublicationId+'&obj1Attr=Studies&obj2Type=Study&obj2Id='+StudyId+'&obj2Attr=Publications',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		}
	};

	var Resource = {
		Version: '1.0',
		CreateHandler: siteBaseUrl + 'xpress/js/AjaxHandler.php',
		LinkHandler:   siteBaseUrl + 'xpress/js/AjaxHandler.php',
		UnlinkHandler: siteBaseUrl + 'xpress/js/AjaxHandler.php',
		DeleteHandler: siteBaseUrl + 'xpress/js/AjaxHandler.php',
  
		Create: function(objAjaxNotify){
		  new Ajax.Request(
		    this.CreateHandler,{
		    method:'post',
		    parameters:'action=create&objType=Resource',
		    onSuccess: function (transport){
		      ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
		    }});
		},
		
		Delete: function(objId,objAjaxNotify){
			if (confirm('Really delete this object?')){
			  new Ajax.Request(
			    this.DeleteHandler,{
			      method:'post',
			      parameters:'action=delete&objType=Resource&objId='+objId,
			      onSuccess: function (transport){
			        ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
			      }});
			}
		},

		linkBiomarker: function (ResourceId,BiomarkerId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=Resource&obj1Id='+ResourceId+'&obj1Attr=Biomarkers&obj2Type=Biomarker&obj2Id='+BiomarkerId+'&obj2Attr=Resources',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkBiomarkerOrgan: function (ResourceId,BiomarkerOrganDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=Resource&obj1Id='+ResourceId+'&obj1Attr=BiomarkerOrgans&obj2Type=BiomarkerOrganData&obj2Id='+BiomarkerOrganDataId+'&obj2Attr=Resources',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkBiomarkerOrganStudy: function (ResourceId,BiomarkerOrganStudyDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=Resource&obj1Id='+ResourceId+'&obj1Attr=BiomarkerOrganStudies&obj2Type=BiomarkerOrganStudyData&obj2Id='+BiomarkerOrganStudyDataId+'&obj2Attr=Resources',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkBiomarkerStudy: function (ResourceId,BiomarkerStudyDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=Resource&obj1Id='+ResourceId+'&obj1Attr=BiomarkerStudies&obj2Type=BiomarkerStudyData&obj2Id='+BiomarkerStudyDataId+'&obj2Attr=Resources',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkStudy: function (ResourceId,StudyId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=Resource&obj1Id='+ResourceId+'&obj1Attr=Studies&obj2Type=Study&obj2Id='+StudyId+'&obj2Attr=Resources',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkBiomarker: function (ResourceId,BiomarkerId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=Resource&obj1Id='+ResourceId+'&obj1Attr=Biomarkers&obj2Type=Biomarker&obj2Id='+BiomarkerId+'&obj2Attr=Resources',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkBiomarkerOrgan: function (ResourceId,BiomarkerOrganDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=Resource&obj1Id='+ResourceId+'&obj1Attr=BiomarkerOrgans&obj2Type=BiomarkerOrganData&obj2Id='+BiomarkerOrganDataId+'&obj2Attr=Resources',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkBiomarkerOrganStudy: function (ResourceId,BiomarkerOrganStudyDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=Resource&obj1Id='+ResourceId+'&obj1Attr=BiomarkerOrganStudies&obj2Type=BiomarkerOrganStudyData&obj2Id='+BiomarkerOrganStudyDataId+'&obj2Attr=Resources',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkBiomarkerStudy: function (ResourceId,BiomarkerStudyDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=Resource&obj1Id='+ResourceId+'&obj1Attr=BiomarkerStudies&obj2Type=BiomarkerStudyData&obj2Id='+BiomarkerStudyDataId+'&obj2Attr=Resources',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkStudy: function (ResourceId,StudyId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=Resource&obj1Id='+ResourceId+'&obj1Attr=Studies&obj2Type=Study&obj2Id='+StudyId+'&obj2Attr=Resources',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkBiomarker: function (ResourceId,BiomarkerId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=Resource&obj1Id='+ResourceId+'&obj1Attr=Biomarkers&obj2Type=Biomarker&obj2Id='+BiomarkerId+'&obj2Attr=Resources',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkBiomarkerOrgan: function (ResourceId,BiomarkerOrganDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=Resource&obj1Id='+ResourceId+'&obj1Attr=BiomarkerOrgans&obj2Type=BiomarkerOrganData&obj2Id='+BiomarkerOrganDataId+'&obj2Attr=Resources',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkBiomarkerOrganStudy: function (ResourceId,BiomarkerOrganStudyDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=Resource&obj1Id='+ResourceId+'&obj1Attr=BiomarkerOrganStudies&obj2Type=BiomarkerOrganStudyData&obj2Id='+BiomarkerOrganStudyDataId+'&obj2Attr=Resources',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkBiomarkerStudy: function (ResourceId,BiomarkerStudyDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=Resource&obj1Id='+ResourceId+'&obj1Attr=BiomarkerStudies&obj2Type=BiomarkerStudyData&obj2Id='+BiomarkerStudyDataId+'&obj2Attr=Resources',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkStudy: function (ResourceId,StudyId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=Resource&obj1Id='+ResourceId+'&obj1Attr=Studies&obj2Type=Study&obj2Id='+StudyId+'&obj2Attr=Resources',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkBiomarker: function (ResourceId,BiomarkerId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=Resource&obj1Id='+ResourceId+'&obj1Attr=Biomarkers&obj2Type=Biomarker&obj2Id='+BiomarkerId+'&obj2Attr=Resources',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkBiomarkerOrgan: function (ResourceId,BiomarkerOrganDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=Resource&obj1Id='+ResourceId+'&obj1Attr=BiomarkerOrgans&obj2Type=BiomarkerOrganData&obj2Id='+BiomarkerOrganDataId+'&obj2Attr=Resources',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkBiomarkerOrganStudy: function (ResourceId,BiomarkerOrganStudyDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=Resource&obj1Id='+ResourceId+'&obj1Attr=BiomarkerOrganStudies&obj2Type=BiomarkerOrganStudyData&obj2Id='+BiomarkerOrganStudyDataId+'&obj2Attr=Resources',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkBiomarkerStudy: function (ResourceId,BiomarkerStudyDataId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=Resource&obj1Id='+ResourceId+'&obj1Attr=BiomarkerStudies&obj2Type=BiomarkerStudyData&obj2Id='+BiomarkerStudyDataId+'&obj2Attr=Resources',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkStudy: function (ResourceId,StudyId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=Resource&obj1Id='+ResourceId+'&obj1Attr=Studies&obj2Type=Study&obj2Id='+StudyId+'&obj2Attr=Resources',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		}
	};

	var Site = {
		Version: '1.0',
		CreateHandler: siteBaseUrl + 'xpress/js/AjaxHandler.php',
		LinkHandler:   siteBaseUrl + 'xpress/js/AjaxHandler.php',
		UnlinkHandler: siteBaseUrl + 'xpress/js/AjaxHandler.php',
		DeleteHandler: siteBaseUrl + 'xpress/js/AjaxHandler.php',
  
		Create: function(objAjaxNotify){
		  new Ajax.Request(
		    this.CreateHandler,{
		    method:'post',
		    parameters:'action=create&objType=Site',
		    onSuccess: function (transport){
		      ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
		    }});
		},
		
		Delete: function(objId,objAjaxNotify){
			if (confirm('Really delete this object?')){
			  new Ajax.Request(
			    this.DeleteHandler,{
			      method:'post',
			      parameters:'action=delete&objType=Site&objId='+objId,
			      onSuccess: function (transport){
			        ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
			      }});
			}
		},

		linkStaff: function (SiteId,PersonId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=Site&obj1Id='+SiteId+'&obj1Attr=Staff&obj2Type=Person&obj2Id='+PersonId+'&obj2Attr=Site',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkStaff: function (SiteId,PersonId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=Site&obj1Id='+SiteId+'&obj1Attr=Staff&obj2Type=Person&obj2Id='+PersonId+'&obj2Attr=Site',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkStaff: function (SiteId,PersonId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=Site&obj1Id='+SiteId+'&obj1Attr=Staff&obj2Type=Person&obj2Id='+PersonId+'&obj2Attr=Site',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkStaff: function (SiteId,PersonId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=Site&obj1Id='+SiteId+'&obj1Attr=Staff&obj2Type=Person&obj2Id='+PersonId+'&obj2Attr=Site',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		}
	};

	var Person = {
		Version: '1.0',
		CreateHandler: siteBaseUrl + 'xpress/js/AjaxHandler.php',
		LinkHandler:   siteBaseUrl + 'xpress/js/AjaxHandler.php',
		UnlinkHandler: siteBaseUrl + 'xpress/js/AjaxHandler.php',
		DeleteHandler: siteBaseUrl + 'xpress/js/AjaxHandler.php',
  
		Create: function(objAjaxNotify){
		  new Ajax.Request(
		    this.CreateHandler,{
		    method:'post',
		    parameters:'action=create&objType=Person',
		    onSuccess: function (transport){
		      ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
		    }});
		},
		
		Delete: function(objId,objAjaxNotify){
			if (confirm('Really delete this object?')){
			  new Ajax.Request(
			    this.DeleteHandler,{
			      method:'post',
			      parameters:'action=delete&objType=Person&objId='+objId,
			      onSuccess: function (transport){
			        ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
			      }});
			}
		},

		linkSite: function (PersonId,SiteId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=Person&obj1Id='+PersonId+'&obj1Attr=Site&obj2Type=Site&obj2Id='+SiteId+'&obj2Attr=Staff',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		linkSite: function (PersonId,SiteId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=associate&obj1Type=Person&obj1Id='+PersonId+'&obj1Attr=Site&obj2Type=Site&obj2Id='+SiteId+'&obj2Attr=Staff',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkSite: function (PersonId,SiteId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=Person&obj1Id='+PersonId+'&obj1Attr=Site&obj2Type=Site&obj2Id='+SiteId+'&obj2Attr=Staff',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		},

		unlinkSite: function (PersonId,SiteId,objAjaxNotify){
			new Ajax.Request(
				this.CreateHandler,{
					method:'post',
					parameters:'action=dissociate&obj1Type=Person&obj1Id='+PersonId+'&obj1Attr=Site&obj2Type=Site&obj2Id='+SiteId+'&obj2Attr=Staff',
					onSuccess: function (transport){
						ajaxNotify(objAjaxNotify.divId,transport.responseText,objAjaxNotify.messageFormatTag);
					}});
		}
	};

