<?php
class SitesController extends AppController {

	var $helpers = array('Html','Ajax','Javascript','Pagination');
	var $components = array('Pagination');
	var $uses = array('Site');

	function index() {
		$this->checkSession("/sites");

		$criteria = null;
		$this->Pagination->resultsPerPage = array();
		$this->Pagination->show = 15;
		list($order, $limit, $page) = $this->Pagination->init($criteria);

		$this->set('sites', $this->Site->find('all', compact('criteria', 'order', 'limit', 'page')));

		$sites = $this->Site->find("all", array('name', 'id'));
		$sitesArray = array();
		$count = 1;
		foreach ($sites as $site) {
			$sitesArray[] = "{$site['Site']['name']}|{$site['Site']['id']}";
		}
		$this->set('sitesString', '"'.implode("\",\"", $sitesArray).'"');
	}

        function view($id = null) {
                $this->checkSession("/sites/view/{$id}");
                $site = $this->Site->find('first', array(
                        'conditions' => array('Site.id' => $id),
                        'recursive' => 1
                        )
                );
		
		$this->set('site', $site);
        }

	function redirection() {
		$data = &$this->params['form'];
		if ($data['id']) {
			$this->redirect("/sites/view/{$data['id']}");
		} else {
			$this->redirect("/sites/");
		}
	}

	function create() {
		$this->checkSession("/sites/create");
	}

	function createSite() {
		$this->checkSession("sites/create");

		if ($this->params['form']) {
			$data = &$this->params['form'];
			if ($data['name'] != '') {
				$newId = $this->Site->getMaxId();
				$newId = $newId[0][0]["MAX(`site_id`)"] + 1;

				$this->Site->create(array(
					'site_id'=>$newId,
					'name'=>$data['name']
				));

				$this->Site->save();
				$id = $this->Site->getLastInsertId();

				$this->redirect("/sites/view/{$id}");
			} else {
				$this->set('error', true);
			}
		}
	}
}
?>
