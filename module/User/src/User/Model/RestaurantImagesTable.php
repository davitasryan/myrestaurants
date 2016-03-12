<?php

namespace User\Model;

use Zend\Db\TableGateway\TableGateway;

class RestaurantImagesTable
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

    public function getRestaurantImages($restaurant_id)
    {
        $restaurant_id  = (int) $restaurant_id;
        $resultSet = $this->tableGateway->select(array('restaurant_id' => $restaurant_id));
        
        return $resultSet;
    }
	
    public function saveRestaurantImages(RestaurantImages $rest)
    {
        $data = array(
            'fileupload' => $rest->fileupload,
			'restaurant_id' => $rest->restaurant_id,
			
        );
		$this->tableGateway->insert($data);
    }

    public function deleteRestaurantImage($id)
	
    {
        $this->tableGateway->delete(array('id' => (int) $id));
    }
}