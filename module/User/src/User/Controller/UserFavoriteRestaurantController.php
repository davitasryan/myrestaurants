<?php

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use User\Model\UserFavoriteRestaurant;
use User\Model\RestaurantTable;

class UserFavoriteRestaurantController extends AbstractActionController
{
	
	protected $userFavoriteRestaurant;
	
    public function indexAction()
    {
		return new ViewModel();
    }

    public function favoriteAction()
    {
		$id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('restaurant', array(
                'action' => 'add'
            ));
        }
		
/*        try {
            $rest = $this->getRestaurantTable()->getRestaurant($id);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('restaurant', array(
                'action' => 'index'
            ));
        } */
		
        $request = $this->getRequest();
        if ($logUser = $this->identity()) {
			
			$valid = new \Zend\Validator\Callback(function($rows){
			
				if(isset($rows) && !empty($rows) && is_array($rows) && count($rows) == 2
					&& isset($rows['user_id']) && isset($rows['restaurant_id']) 
					&& !empty($rows['user_id']) && !empty($rows['restaurant_id']))
				{
						return true;
				}
				else
				{
						return false;
				}
			});
			
			$input = [
				'user_id' => $logUser->id,
				'restaurant_id' => $id
			];
			
			if($valid->isValid($input)){
				$rest = new UserFavoriteRestaurant();
				$rest->exchangeArray($input);
					$this->getUserFavoriteRestaurantTable()->saveUserFavoriteRestaurant($rest);
			}
			
        }

        return $this->redirect()->toRoute('restaurant', array(
                'action' => 'index'
            ));
    }
	
	public function unlikeAction(){
		$restaurant_id = (int) $this->params()->fromRoute('id', 0);
        if (!$restaurant_id) {
            return $this->redirect()->toRoute('restaurant', array(
                'action' => 'add'
            ));
        }
		
		$user_id = (int) $this->params()->fromRoute('id2', 0);
        if (!$user_id) {
            return $this->redirect()->toRoute('restaurant', array(
                'action' => 'add'
            ));
        }
		
		$this->getUserFavoriteRestaurantTable()->deleteRestaurant($user_id, $restaurant_id);
		
		return $this->redirect()->toRoute('user', array(
                'action' => 'profile',
				'id' => $user_id
            ));
		
	}

	public function getUserFavoriteRestaurantTable()
    {
        if (!$this->userFavoriteRestaurant) {
            $sm = $this->getServiceLocator();
            $this->userFavoriteRestaurant = $sm->get('User\Model\UserFavoriteRestaurantTable');
        }
        return $this->userFavoriteRestaurant;
    }
	
	/*public function getRestaurants($restaurantId)
	{
		$sm = $this->getServiceLocator();
		$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
				
		$authAdapter = new AuthAdapter($dbAdapter,
				'restaurant', // there is a method setTableName to do the same
				'id', // there is a method setIdentityColumn to do the same
				);
		$authAdapter
			->setIdentity($restaurantId)
		;
	}*/
}
