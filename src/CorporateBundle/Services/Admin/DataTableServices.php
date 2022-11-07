<?php

namespace CorporateBundle\Services\Admin;

use CorporateBundle\Repository\Admin\CorpoDatatableRepository;

class DataTableServices
{
	protected $dtRepo;

	public function __construct(CorpoDatatableRepository $dt){
		$this->dtRepo = $dt;
	}

	public function buildQuery($params){
		return $this->dtRepo->buildQuery($params);
	}

	public function runQuery($queryArr){
		return $this->dtRepo->runQuery($queryArr);
	}
}

