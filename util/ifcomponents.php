<?php
	// Interface Components + Ajax API for dynamic insertion

	if (isset($_GET['action']) && $_GET['action'] == 'display'){
		require_once("../model/ModelProperties.inc.php");
		require_once("utilities/common/AjaxEditors.class.php");
		switch ($_GET['type']){
			case 'BiomarkerOrganSummary':
				$objId = $_GET['objId'];
				$od = new objBiomarkerOrganData($XPress);
				$od->initialize($objId);
				echo BiomarkerInterface::drawOrganDataSummary($od);
				break;
			case 'BiomarkerStudyDisplay':
				$objId = $_GET['objId'];
				$sd = new objBiomarkerStudyData($XPress);
				$sd->initialize($objId);
				echo Studies::displaySummary($sd,"Biomarker");
				//echo BiomarkerOrganInterface::drawStudySummary($sd);
				break;
			case 'BiomarkerOrganStudyDisplay':
				$objId = $_GET['objId'];
				$sd = new objBiomarkerOrganStudyData($XPress);
				$sd->initialize($objId);
				echo Studies::displaySummary($sd,"BiomarkerOrgan");
				//echo BiomarkerOrganInterface::drawStudySummary($sd);
				break;
			case 'PublicationSummary':
				$objId = $_GET['objId'];
				$containerType = $_GET['containerObjectType'];
				$containerId = $_GET['containerObjectId'];
				switch ($containerType){
					case 'Biomarker':
						$co = new objBiomarker($XPress,$containerId);
						break;
					case 'BiomarkerOrganData':
						$co = new objBiomarkerOrganData($XPress,$containerId);
						break;
					case 'BiomarkerStudyData':
						$co = new objBiomarkerStudyData($XPress,$containerId);
						break;
					case 'BiomarkerOrganStudyData':
						$co = new objBiomarkerOrganStudyData($XPress,$containerId);
						break;
					case 'Study':
						$co = new objStudy($XPress,$containerId);
						break;
					default:
						break;
				}
				$p = new objPublication($XPress);
				$p->initialize($objId);
				echo Publications::displaySummary($p,$co);
				break;
			case 'PublicationShortSummary':
				$objId = $_GET['objId'];
				$containerType = $_GET['containerObjectType'];
				$containerId = $_GET['containerObjectId'];
				switch ($containerType){
					case 'BiomarkerStudyData':
						$co = new objBiomarkerStudyData($XPress,$containerId);
						break;
					case 'BiomarkerOrganStudyData':
						$co = new objBiomarkerOrganStudyData($XPress,$containerId);
						break;
					default:
						break;
				}
				$p = new objPublication($XPress);
				$p->initialize($objId);
				echo Publications::displayShortSummary($p,$co);
				break;
			case 'ResourceSummary':
				$objId = $_GET['objId'];
				$containerType = $_GET['containerObjectType'];
				$containerId = $_GET['containerObjectId'];
				switch ($containerType){
					case 'Biomarker':
						$co = new objBiomarker($XPress,$containerId);
						break;
					case 'Study':
						$co = new objStudy($XPress,$containerId);
						break;
					case 'BiomarkerOrganData':
						$co = new objBiomarkerOrganData($XPress,$containerId);
						break;
					default:
						break;
				}
				$r = new objResource($XPress);
				$r->initialize($objId);
				echo Resources::displaySummary($r,$co);
				break;
			case 'ResourceShortSummary':
				$objId = $_GET['objId'];
				$containerType = $_GET['containerObjectType'];
				$containerId = $_GET['containerObjectId'];
				switch ($containerType){
					case 'BiomarkerStudyData':
						$co = new objBiomarkerStudyData($XPress,$containerId);
						break;
					case 'BiomarkerOrganStudyData':
						$co = new objBiomarkerOrganStudyData($XPress,$containerId);
						break;
					default:
						break;
				}
				$r = new objResource($XPress);
				$r->initialize($objId);
				echo Resources::displayShortSummary($r,$co);
				break;
			case 'StudyDataDisplay':
				break;
			default:
				break;
		}
		
	} else {
		require_once("model/ModelProperties.inc.php");
		require_once("utilities/common/AjaxEditors.class.php");
	}
	
	class IFUtils {
		public static function drawAutocompleter($elmtId,$elmtLabel,$cssWidth,$handlerUrl,$objectType,$fieldName,$waitimgURL,$resultName,$fieldList){
			return <<<END
			<input type="text" class="cwspTI" style="width:{$cssWidth}px;" id="{$elmtId}_autocomplete" name="autocomplete_parameter" value="" onfocus="$('{$elmtId}_autocomplete').addClassName('focused');" onblur="$('{$elmtId}_autocomplete').removeClassName('focused');" />
			<!-- Autocomplete stuff -->
			<div id="{$elmtId}_autocomplete_choices" class="autocomplete">&nbsp;</div>
	    	<input type="hidden" id="{$elmtId}AutocompleteID" name="" value=""/> 
	        <span id="{$elmtId}indicator" style="display:none;">
	        	<img src="{$waitimgURL}" alt="Working..." />
	        </span>
	    	<script type="text/javascript">
	        	function {$elmtId}afterAutocomplete(field,element){
	          		$('{$elmtId}AutocompleteID').value = element.id;
	        	}
	    	new Ajax.Autocompleter("{$elmtId}_autocomplete", "{$elmtId}_autocomplete_choices", "{$handlerUrl}", {indicator: '{$elmtId}indicator',parameters: 'objectType={$objectType}&field={$fieldName}&fieldList={$fieldList}',afterUpdateElement: {$elmtId}afterAutocomplete});
	        </script>
END;
		}
		public static function drawTextInput($id,$name,$label,$cssWidth=180){
	    	return <<<END
			<input type="text" class="cwspTI" style="width:{$cssWidth}px;" id="{$id}" name="{$name}" value="{$label}" onfocus="$('{$id}').addClassName('focused');" onblur="$('{$id}').removeClassName('focused');"/>  	
END;
		}
	}

	class Publications {
		public static function displayShortSummary($pubObject,$containerObject){
			switch ($containerObject->_getType()){
				case 'BiomarkerStudyData':
					$onclick = "Publication.unlinkBiomarkerStudy({$pubObject->getObjId()},{$containerObject->getObjId()},new AjaxNotify.Create('studypub{$pubObject->getObjId()}_{$containerObject->getObjId()}','Deleted'));";
					break;
				case 'BiomarkerOrganStudyData':
					$onclick = "Publication.unlinkBiomarkerOrganStudy({$pubObject->getObjId()},{$containerObject->getObjId()},new AjaxNotify.Create('studypub{$pubObject->getObjId()}_{$containerObject->getObjId()}','Deleted'));";
					break;
				default:
					$onclick = '';
					break;		
			}
			return <<<END
					<li id="studypub{$pubObject->getObjId()}_{$containerObject->getObjId()}"><a href="publication.php?view=basics&objId={$pubObject->getObjId()}">{$pubObject->getTitle()}</a>&nbsp;<span class="titleAction pseudolink grey" onclick="{$onclick}">(unlink)</span></li>
END;
		}
		public static function displaySummary($pubObject,$containerObject){
			switch ($containerObject->_getType()){
				case 'Biomarker':
					$onclick = "Publication.unlinkBiomarker({$pubObject->getObjId()},{$containerObject->getObjId()},new AjaxNotify.Create('pub{$pubObject->getObjId()}_{$containerObject->getObjId()}','Deleted'));";
					break; 
				case 'BiomarkerOrganData':
					$onclick = "Publication.unlinkBiomarkerOrgan({$pubObject->getObjId()},{$containerObject->getObjId()},new AjaxNotify.Create('pub{$pubObject->getObjId()}_{$containerObject->getObjId()}','Deleted'));";
					break; 
				case 'BiomarkerStudyData':
					$onclick = "Publication.unlinkBiomarkerStudy({$pubObject->getObjId()},{$containerObject->getObjId()},new AjaxNotify.Create('pub{$pubObject->getObjId()}_{$containerObject->getObjId()}','Deleted'));";
					break;
				case 'BiomarkerOrganStudyData':
					$onclick = "Publication.unlinkBiomarkerOrganStudy({$pubObject->getObjId()},{$containerObject->getObjId()},new AjaxNotify.Create('pub{$pubObject->getObjId()}_{$containerObject->getObjId()}','Deleted'));";
					break;
				case 'Study':
					$onclick = "Publication.unlinkStudy({$pubObject->getObjId()},{$containerObject->getObjId()},new AjaxNotify.Create('pub{$pubObject->getObjId()}_{$containerObject->getObjId()}','Deleted'));";
					break;
				default:
					$onclick = '';
					break;
			}
			return <<<END
				<div class="overview" id="pub{$pubObject->getObjId()}_{$containerObject->getObjId()}">
					<h3><a href="publication.php?view=basics&objId={$pubObject->getObjId()}">{$pubObject->getTitle()}</a>&nbsp;<span class="titleAction pseudolink grey" onclick="{$onclick}">(delete)</span></h3>
					<table>
						<tr><td class="label">PubMed ID:</td><td>{$pubObject->getPubMedID()}</td></tr>
						<tr><td class="label">Author:</td><td>{$pubObject->getAuthor()}</td></tr>
						<tr><td class="label">Journal:</td><td>{$pubObject->getJournal()}</td>
						<tr><td>&nbsp;</td><td>Vol: {$pubObject->getVolume()}, Issue: {$pubObject->GetIssue()}, Year: {$pubObject->getYear()}</td></tr>
					</table>
				</div>
END;
		}	
		public static function displayAssociate($objType,$objId,$outputDivId){
			$autocompPubMedID = IFUtils::drawAutocompleter("assocPub",'',455,'model/AjaxAutocomplete.php','Publication','PubMedID','assets/images/working.gif','assocPub','PubMedID|Title');
			$content = <<<END
				<div class="associationContainer" id="pubAssociationContainer{$objId}" style="display:none;">
				<h3>Add Publication Data:</h3>
				<span class="hint">Type slowly into the box. Available matches will appear in a drop-down list below.</span><br/><br/>
				Publication PubMed ID: &nbsp;
END;
			$content .= $autocompPubMedID;
			switch ($objType){
				case 'Biomarker':
					$content .= "<input type=\"button\" value=\"Associate\" onclick=\"Publication.linkBiomarker($('assocPubAutocompleteID').getValue(),{$objId},new AjaxNotify.Create('{$outputDivId}','PublicationSummary'));$('assocPub_autocomplete').value='';Element.hide('pubAssociationContainer{$objId}');\"/>\r\n";
					break;
				case 'BiomarkerOrganData':
					$content .= "<input type=\"button\" value=\"Associate\" onclick=\"Publication.linkBiomarkerOrgan($('assocPubAutocompleteID').getValue(),{$objId},new AjaxNotify.Create('{$outputDivId}','PublicationSummary'));$('assocPub_autocomplete').value='';Element.hide('pubAssociationContainer{$objId}');\"/>\r\n";
					break;
				case 'BiomarkerStudyData':
					$content .= "<input type=\"button\" value=\"Associate\" onclick=\"Publication.linkBiomarkerStudy($('assocPubAutocompleteID').getValue(),{$objId},new AjaxNotify.Create('{$outputDivId}','PublicationShortSummary'));$('assocPub_autocomplete').value='';Element.hide('pubAssociationContainer{$objId}');\"/>\r\n";
					break;
				case 'BiomarkerOrganStudyData':
					$content .= "<input type=\"button\" value=\"Associate\" onclick=\"Publication.linkBiomarkerOrganStudy($('assocPubAutocompleteID').getValue(),{$objId},new AjaxNotify.Create('{$outputDivId}','PublicationShortSummary'));$('assocPub_autocomplete').value='';Element.hide('pubAssociationContainer{$objId}');\"/>\r\n";
					break;
				case 'Study':
					$content .= "<input type=\"button\" value=\"Associate\" onclick=\"Publication.linkStudy($('assocPubAutocompleteID').getValue(),{$objId},new AjaxNotify.Create('{$outputDivId}','PublicationSummary'));$('assocPub_autocomplete').value='';Element.hide('pubAssociationContainer{$objId}');\"/>\r\n";
					break;
				default: 
					break;
			}
			$content .= <<<END
					<input type="button" value="Cancel" onclick="$('assocPub_autocomplete').value='';Effect.SwitchOff('pubAssociationContainer{$objId}');"/><br/><br/>
			No Matches? <a href="util/importpubmed.php">Import a new publication from PubMed</a>
				</div>
END;
			return $content;
		}
	}

	class Studies {
			public static function displaySummary($studyObject,$containerObjectType){
			$sensEditor = AjaxEditors::create('model/AjaxHandler.php',$studyObject->getSensitivity(),"sens{$studyObject->getObjId()}","sens{$studyObject->getObjId()}",$studyObject->_getType(),$studyObject->getObjId(),BiomarkerOrganStudyDataVars::BIO_SENSITIVITY);
			$specEditor = AjaxEditors::create('model/AjaxHandler.php',$studyObject->getSpecificity(),"spec{$studyObject->getObjId()}","spec{$studyObject->getObjId()}",$studyObject->_getType(),$studyObject->getObjId(),BiomarkerOrganStudyDataVars::BIO_SPECIFICITY);
			$npvEditor  = AjaxEditors::create('model/AjaxHandler.php',$studyObject->getNPV(),"npv{$studyObject->getObjId()}","npv{$studyObject->getObjId()}",$studyObject->_getType(),$studyObject->getObjId(),BiomarkerOrganStudyDataVars::BIO_NPV);
			$ppvEditor  = AjaxEditors::create('model/AjaxHandler.php',$studyObject->getPPV(),"ppv{$studyObject->getObjId()}","npv{$studyObject->getObjId()}",$studyObject->_getType(),$studyObject->getObjId(),BiomarkerOrganStudyDataVars::BIO_PPV);

			if ($containerObjectType == "Biomarker"){
				$jsObjType = "BiomarkerStudyData";
			} else if ($containerObjectType == "BiomarkerOrgan"){
				$jsObjType = "BiomarkerOrganStudyData";
			} else {
				die("{$containerObjectType} not supported");
				return;	// study summary not defined for objects other than those above
			}
			
			$study_abstract = (($studyObject->getStudy()->getAbstract() == "")
				? '<em>no abstract available</em>'
				: $studyObject->getStudy()->getAbstract());
			
			$component = <<<END
				<div class="overview" id="overview{$studyObject->getObjId()}">
					<h3>
						<a href="study.php?view=basics&objId={$studyObject->getStudy()->getObjId()}">{$studyObject->getStudy()->getTitle()}</a>&nbsp;<span class="titleAction pseudolink grey" onclick="{$jsObjType}.Delete({$studyObject->getObjId()},new AjaxNotify.Create('overview{$studyObject->getObjId()}','Deleted'));">(unlink)</span>
					</h3>
					<h4>Abstract:</h4>
					<div style="padding-left:10px;padding-right:10px;padding-bottom:15px;font-size:95%;line-height:1.3em;text-align:justify;">{$study_abstract}</div>
					<table class="ajaxEdits greenborder" >
						<tr><td class="label">Sensitivity (%):</td><td>{$sensEditor}</td></tr>
						<tr class="even"><td class="label">Specificity (%):</td><td>{$specEditor}</td></tr>
						<tr><td class="label">Negative Predictive Value (%):</td><td>{$npvEditor}</td></tr>
						<tr class="even"><td class="label">Positive Predictive Value (%):</td><td>{$ppvEditor}</td></tr>
					</table>
					<h5>Publications (<span style="font-weight:normal;"><span class="pseudolink" onclick="Effect.Appear('pubAssociationContainer{$studyObject->getObjId()}');">associate publication</span></span>)</h5>
END;
			$component .= Publications::displayAssociate($jsObjType,$studyObject->getObjId(),"study{$studyObject->getObjId()}Publications");
			$component .= "<ul id=\"study{$studyObject->getObjId()}Publications\">";
			foreach ($studyObject->getPublications() as $pub){
				$component .= Publications::displayShortSummary($pub,$studyObject);
			}
			$component .= "</ul>";
			//$component .= BiomarkerOrganInterface::showStudyAssociatedPublications($studyObject);
			$component .= "\t\t\t<h5>Resources (<span style=\"font-weight:normal;\"><span class=\"pseudolink\" onclick=\"Effect.Appear('resAssociationContainer{$studyObject->getObjId()}');\">associate resource</a></span>)</h5>\r\n";
			$component .= Resources::displayAssociate($jsObjType,$studyObject->getObjId(),"study{$studyObject->getObjId()}Resources");
			$component .= "<ul id=\"study{$studyObject->getObjId()}Resources\">";
			foreach ($studyObject->getResources() as $res){
				$component .= Resources::displayShortSummary($res,$studyObject);
			}
			$component .= "</ul>";
			//$component .= BiomarkerOrganInterface::showAssociateStudyResource($studyObject);
			//$component .= BiomarkerOrganInterface::showStudyAssociatedResources($studyObject);
			$component .= "\t\t\t</div>\r\n";
			return $component;		
		}
		
	}

	class Resources {
		public static function displayAssociate($objType,$objId,$outputDivId){
			if ($objType == 'BiomarkerStudyData' || $objType == 'BiomarkerOrganStudyData'){
				$displayType = 'ResourceShortSummary';
			} else {
				$displayType = 'ResourceSummary';
			}
			$onclick .= "createAndAssociateResource($('resURL{$objId}').getValue(),$('resDesc{$objId}').getValue(),'{$objType}',{$objId},new AjaxNotify.Create('{$outputDivId}','{$displayType}'));$('resURL{$objId}').value='';$('resDesc{$objId}').value='';Element.hide('resAssociationContainer{$objId}');";
			$urlField  = IFUtils::drawTextInput("resURL{$objId}","resURL","",240);
			$descField = IFUtils::drawTextInput("resDesc{$objId}","resDesc","",240);
			$associateDisplay = <<<END
			<div class="associationContainer" id="resAssociationContainer{$objId}" style="display:none;">
			<h3>Add a Link to an External Resource:</h3>
			<table>
				<tr><td>Resource URL: &nbsp;<span style="color:#333;">http://</span></td>
					<td>{$urlField}</td></tr>
				<tr><td>Description:</td>
					<td>{$descField}</td></tr>
				<tr><td><input type="button" value="Associate" onclick="{$onclick}"/></td>
					<td><input type="button" value="Cancel" onclick="Effect.SwitchOff('resAssociationContainer{$objId}');"/></td></tr>
			</table>
			</div>
END;

			return $associateDisplay;
		}
		public static function displaySummary($resObject,$containerObject){
			$onclick = "Resource.Delete({$resObject->getObjId()},new AjaxNotify.Create('res{$resObject->getObjId()}_{$containerObject->getObjId()}','Deleted'));";
			return <<<END
				<div class="overview" id="res{$resObject->getObjId()}_{$containerObject->getObjId()}">
					<h3><a href="{$resObject->getUrl()}">{$resObject->getUrl()}</a>&nbsp;<span class="titleAction pseudolink grey" onclick="{$onclick}">(delete)</span></h3>
					&nbsp;{$resObject->getName()}
				</div>
END;
		}
		public static function displayShortSummary($resObject,$containerObject){
			$onclick = "Resource.Delete({$resObject->getObjId()},new AjaxNotify.Create('res{$resObject->getObjId()}_{$containerObject->getObjId()}','Deleted'));";
			return <<<END
				<li id="res{$resObject->getObjId()}_{$containerObject->getObjId()}"><a href="{$resObject->getUrl()}">{$resObject->getUrl()}</a>&nbsp;<span class="titleAction pseudolink grey" onclick="{$onclick}">(delete)</span></li>
END;
		}
		
	}
	
	class BiomarkerInterface {
		
		public static function drawOrganDataSummary($od){
			return <<<END
			<div class="overview" id="overview{$od->getObjId()}">
				<h3>
					<a href="biomarkerorgan.php?view=basics&objId={$od->getObjId()}">{$od->getOrgan()->getName()}</a>&nbsp;
					<span class="titleAction pseudolink grey" onclick="BiomarkerOrganData.Delete({$od->getObjId()},new AjaxNotify.Create('overview{$od->getObjId()}','Deleted'));">
						(delete)
					</span>
				</h3>
				<table>
					<tr><td>Sensitivity (Min/Max): </td><td>{$od->getSensitivityMin()} / {$od->getSensitivityMax()}</td></tr>
					<tr><td>Specificity (Min/Max): </td><td>{$od->getSpecificityMin()} / {$od->getSpecificityMax()}</td></tr>
					<tr><td>Negative Predictive Value (Min/Max): </td><td>{$od->getNPVMin()} / {$od->getNPVMax()}</td></tr>
					<tr><td>Positive Predictive Value (Min/Max): </td><td>{$od->getPPVMin()} / {$od->getPPVMax()}</td></tr>
				</table>
			</div>
END;
			
		}
		public static function drawAssociateOrgan($biomarker){
			return <<<END
			<h3>Add organ data to {$biomarker->getTitle()}</h3>
			Organ:&nbsp;
				<select id="organChoice">
					<option id="1" value="1">Bladder</option>
                    <option id="2" value="2">Bone</option>
                    <option id="3" value="3">Brain</option>
                    <option id="4" value="4">Breast</option>
                    <option id="5" value="5">Cervix</option>
                    <option id="6" value="6">Colon</option>
                    <option id="7" value="7">Esophagus</option>
                    <option id="8" value="8">Head and Neck</option>
                    <option id="9" value="9">Kidney</option>
                    <option id="10" value="10">Liver</option>
                    <option id="11" value="11">Lung</option>
                    <option id="12" value="12">Ovary</option>
                    <option id="13" value="13">Pancreas</option>
                    <option id="14" value="14">Prostate</option>
                    <option id="15" value="15">Rectum</option>
                    <option id="16" value="16">Stomach</option>
                    <option id="17" value="17">Thyroid</option>
					<option id="18" value="18">Uterus</option>
                    <option id="19" value="19">Skin</option>
                    <option id="20" value="20">Testis</option>
                    <option id="21" value="21">Lymph Node</option>
                    <option id="22" value="22">Vagina</option>
				</select>&nbsp;
				<input type="button" value="Associate" onclick="BiomarkerOrganData.Create($('organChoice').getValue(),{$biomarker->getObjId()},new AjaxNotify.Create('overviewContainer','BiomarkerOrganSummary'));Element.hide('associationContainer');"/>
				<input type="button" value="Cancel" onclick="Effect.SwitchOff('associationContainer');"/>
END;
		}
	}

	
?>