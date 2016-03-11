<?php

namespace User\Model;

use Zend\Db\TableGateway\TableGateway;

class RestaurantTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
         $resultSet = $this->tableGateway->select();
         return $resultSet;
    }

    public function getRestaurant($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
	
    public function saveRestaurant(Restaurant $rest)
    {
        $data = array(
            'name' => $rest->name,
			'address' => $rest->address,
			'lat' => $rest->lat,
			'lng' => $rest->lng,
			'contact' => $rest->contact,
			'site' => $rest->site,
        );
		
        $id = (int) $rest->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getRestaurant($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Restaurant id does not exist');
            }
        }
    }

    public function deleteRestaurant($id)
	
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }
}