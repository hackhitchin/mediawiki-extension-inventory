<?php
namespace MediaWiki\Extension\HackHitchinInventory\Services;

use Wikimedia\Rdbms\LBFactory;
use MediaWiki\Extension\HackHitchinInventory\Models\Location;
use Wikimedia\Rdbms\DBQueryError;

class LocationService {
	/** @var LBFactory */
	private $lbFactory;

	/**
	 * @param LBFactory $lbFactory
	 */
	public function __construct(LBFactory $lbFactory) {
		$this->lbFactory = $lbFactory;
    }

    public function store(Location $location) : void {
        $db = $this->lbFactory->getPrimaryDatabase();
        if ($location->getId() === null) {
            try {
                $db->insert('hhi_location', [
                    'hhi_location_key' => $location->getKey(),
                    'hhi_location_description' => $location->getDescription()
                ]);
            } catch (DBQueryError $e) {
                // TODO: Better error handling
                if (str_contains(strtolower($e->error), 'unique') === true) {
                    throw new \Exception('Key must be unique');
                } else {
                    throw $e;
                }
            }
        } else {
            $db->update('hhi_location', [
                'hhi_location_key' => $location->getKey(),
                'hhi_location_description' => $location->getDescription()
            ], [
                'hhi_location_id' => $location->getId()
            ]);
        }
    }

    public function getById(int $id) : Location {
        $db = $this->lbFactory->getReplicaDatabase();
        $row = $this->findOne(["hhi_location_id" => $id]);
        return Location::recreate(
            $row->location_id,
            $row->location_key,
            $row->location_description
        );
    }

    public function getByKey(string $key) : Location {
        $row = $this->findOne(["hhi_location_key" => $key]);
        return Location::recreate(
            $row->location_id,
            $row->location_key,
            $row->location_description
        );
    }

    public function getAll() : array {
        $db = $this->lbFactory->getReplicaDatabase();
        $result = $db->newSelectQueryBuilder()
           ->select('*')
           ->from('hhi_location')
           ->fetchResultSet();
        
        $locations = [];
        foreach ($result as $row) {
            $locations[] = Location::recreate(
                $row->location_id,
                $row->location_key,
                $row->location_description
            );
        }
        return $locations;
    }

    private function findOne($criteria) {
        $db = $this->lbFactory->getReplicaDatabase();
        return $db->newSelectQueryBuilder()
           ->select('*')
           ->from('hhi_location')
           ->where($criteria)
           ->fetchRow();
    }
}