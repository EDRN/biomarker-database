<?php
class AclsController extends AppController {
	
	
	var $name = "Acls";
	var $helpers = array('Html','Js','Pagination');
	var $components = array('Pagination');
	var $uses = 
		array(
			'Acl',
			'Biomarker',
			'BiomarkerName',
			'Auditor',
			'LdapUser',
			'Organ',
			'OrganData',
			'Study',
			'StudyData',
			'BiomarkerStudyData',
			'Publication',
			'StudyDataResource',
			'BiomarkerStudyDataResource',
			'OrganDataResource',
			'BiomarkerResource',
			'Sensitivity'
		);
		
	public static function utilAlphabetizeGroupNames($groups) {
		$result = array();
		foreach ($groups as $groupId => $groupData) {
			$result[$groupId] = $groupData['name'];
		}
		sort($result);
		return $result;
	}
			
	function edit($objectType,$objectId,$parentId='') {
		$list     = false;
		$returnto = false;
		switch (strtolower($objectType)) {
			case "biomarker":
				$list     = $this->Biomarker->readACL($objectId);
				$returnto = "biomarkers/view/{$objectId}"; 
				break;
			case "biomarkerorgan":
				$list     = $this->OrganData->readACL($objectId);
				$returnto = "biomarkers/organs/{$parentId}/{$objectId}";
				break;
			default: die("unsupported object type {$objectType}");
		}
		
		// Ensure a valid user
		$this->checkSession("/{$returnto}");
		
		// Determine selected and available groups
		$allgroups    = $this->Acl->getLDAPGroups();
		$sortedGroups = self::utilAlphabetizeGroupNames($allgroups);
		
		$availableGroups = array();
		$selectedGroups  = array();
		
		foreach ($sortedGroups as $groupId => $groupName) {
			$groupIsSelected = false;
			foreach ($list as $result) {
				if ($result['acl']['ldapGroup'] == $groupName) {
					$selectedGroups[$groupId] = $groupName;
					$groupIsSelected = true;
					break;		
				}
			}
			if (!$groupIsSelected) {
				$availableGroups[$groupId] = $groupName;
			}
		}
		
		
		$this->set('objectType',strtolower($objectType));
		$this->set('objectId',$objectId);
		$this->set('returnTo',$returnto);
		$this->set('availableGroups',$availableGroups);
		$this->set('selectedGroups',$selectedGroups);
	}
	
	function store() {
		$this->checkSession("/");
		$data =& $this->request->data;
		if ($data) {
			$ot  = $data['object_type'];
			$oid = $data['object_id'];
			$groups = array_slice(explode(",",$data['values']),1); 
			
			// Delete all existing for this object (purges obsolete ldap groups)
			$q = "DELETE FROM `acl` WHERE `objectType`='{$ot}' AND `objectId`='{$oid}' ";
			$this->Biomarker->query($q);
			
			// Insert a row for all newly selected elements
			$q = "INSERT INTO `acl` (`ldapGroup`,`objectType`,`objectId`,`readOnly`) VALUES ";
			$values = array();
			foreach ($groups as $g) {
				$values[] = "('".mysql_escape_mimic($g)."','{$ot}','{$oid}','0')";	// grant r/w access
			}
			$q .= implode(",",$values);
			if (count($values) > 0) {
				$this->Biomarker->query($q);
			}
			
			$this->redirect("/{$data['return_to']}");
		} else {
			die("GET operation not supported. Please try POSTing your request");
		}
	}
	
	function manage() {
		$this->checkSession("/");
		
		$allgroups    = $this->Acl->getLDAPGroups();
		$sortedGroups = self::utilAlphabetizeGroupNames($allgroups);
		
		$message = isset($_GET['message']) ? $_GET['message'] : '';
		$this->set('message',$message);
		$this->set('allgroups',$sortedGroups);
	}
	
	function bulkGrant() {
		$this->checkSession("/");
		$data =& $this->request->data;
		if ($data) {
			$ot    = $data['objectType'];
			$group = $data['group'];

			// Delete all existing matches
			$q = "DELETE FROM `acl` WHERE `objectType`='{$ot}' AND `ldapGroup`='{$group}' ";
			$this->Acl->query($q);
			
			// Insert new entries 
			$ids = array();
			switch (strtolower($ot)) {
				case "biomarker":
					$q = "SELECT `id` FROM `biomarkers` ";
					$objects = $this->Acl->query($q);
					foreach ($objects as $object) {
						$ids[] = $object['biomarkers']['id'];
					}
					break;
				case "biomarkerorgan":
					$q = "SELECT `id` FROM `organ_data` ";
					$objects = $this->Acl->query($q);
					foreach ($objects as $object) {
						$ids[] = $object['organ_data']['id'];
					}
					break;
					break;
				default:
					die("Unsupported object type {$ot}");
			}
			
			$q = "INSERT INTO `acl` (`ldapGroup`,`objectType`,`objectId`,`readOnly`) VALUES ";
			$values = array();
			foreach ($ids as $id) {
				$values[] = "('".mysql_escape_mimic($group)."','{$ot}','{$id}','0') "; // grant r/w access
			}
			$q .= implode(",",$values);;
			
			$this->Acl->query($q);
			
			$this->redirect("/acls/manage?message=Granted+access+to+all+{$ot}+objects+to+all+members+of+{$group}.");
		} else {
			die("GET operation not supported. Please try POSTing your request");
		}
	}
	
	function bulkRevoke() {
		$this->checkSession("/");
		
		$data =& $this->request->data;
		if ($data) {
			$ot    = $data['objectType'];
			$group = $data['group'];
			
			// Delete all existing matches
			$q = "DELETE FROM `acl` WHERE `objectType`='{$ot}' AND `ldapGroup`='{$group}' ";
			$this->Acl->query($q);
			
			$this->redirect("/acls/manage?message=Revoked+access+to+all+{$ot}+objects+by+all+members+of+{$group}.");
		} else {
			die("GET operation not supported. Please try POSTing your request");
		}
	}
}
?>