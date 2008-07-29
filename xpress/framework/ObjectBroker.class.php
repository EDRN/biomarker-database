<?php


class ObjectBroker {
	public static function get($className,$objId) {
		switch ($className) {
			case "Biomarker":
				return BiomarkerFactory::retrieve($objId);
				break;
			case "BiomarkerAlias":
				return BiomarkerAliasFactory::retrieve($objId);
				break;
			case "Study":
				return StudyFactory::retrieve($objId);
				break;
			case "BiomarkerStudyData":
				return BiomarkerStudyDataFactory::retrieve($objId);
				break;
			case "Organ":
				return OrganFactory::retrieve($objId);
				break;
			case "BiomarkerOrganData":
				return BiomarkerOrganDataFactory::retrieve($objId);
				break;
			case "BiomarkerOrganStudyData":
				return BiomarkerOrganStudyDataFactory::retrieve($objId);
				break;
			case "Publication":
				return PublicationFactory::retrieve($objId);
				break;
			case "Resource":
				return ResourceFactory::retrieve($objId);
				break;
			case "Site":
				return SiteFactory::retrieve($objId);
				break;
			case "Person":
				return PersonFactory::retrieve($objId);
				break;
			default:
				return false;
				break;
		}
	}
}

?>