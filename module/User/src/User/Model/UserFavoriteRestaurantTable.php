<?php

namespace User\Model;

use Zend\Db\TableGateway\TableGateway;

class UserFavoriteRestaurantTable
{
    protected $tableGateway;
	protected $user_id;
	protected $restaurant_id;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
         $resultSet = $this->tableGateway->select();
         return $resultSet;
    }

    public function getUserFavoriteRestaurants($userId)
    {
        $resultSet = $this->tableGateway->select(array('user_id' => $userId));
        
        return $resultSet;
    }
	
	public function issetRow(){
		$rowset = $this->tableGateway->select(array('user_id' => $this->user_id, 'restaurant_id' =>$this->restaurant_id));
		$row = $rowset->current();
        if (!$row) {
            return false;
        }
        return true;
	}

    public function saveUserFavoriteRestaurant(UserFavoriteRestaurant $rest)
    {
        $data = array(
            'user_id' => $rest->user_id,
			'restaurant_id' => $rest->restaurant_id,
        );
		$this->user_id = $rest->user_id;
		$this->restaurant_id = $rest->restaurant_id;
		
        $haveRow = $this->issetRow();
        if (!$haveRow) {
            $this->tableGateway->insert($data);
        } 
    }

    public function deleteRestaurant($userId, $restaurantId)
	
    {
        $this->tableGateway->delete(array('user_id' => $userId, 'restaurant_id' => $restaurantId));
    }
}