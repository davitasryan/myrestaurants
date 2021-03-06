<?php

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use User\Model\User;
use User\Form\UserForm;
//use Zend\Crypt\Password\Bcrypt;

class IndexController extends AbstractActionController
{
	protected $userTable;
	protected $userFavoriteRestaurantTable;
	protected $userFavoriteRestaurantsMapper;
	protected $restaurant;
	
	
    public function indexAction()
    {
		return new ViewModel(array(
             'users' => $this->getUserTable()->fetchAll(),
         ));
    }

    public function addAction()
    {
		$form = new UserForm();
        $form->get('submit')->setValue('Register');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $user = new User();
            $form->setInputFilter($user->getInputFilter());
			
			$saveData = $request->getPost();
			$saveData->password = self::criptPassword($saveData->password);
            $form->setData($saveData);
			
            if ($form->isValid()) {
                $user->exchangeArray($form->getData());
                $this->getUserTable()->saveUser($user);

                return $this->redirect()->toRoute('user');
            }
        }
        return array('form' => $form);

    }

    public function editAction()
    {
		$id = (int) $this->params()->fromRoute('id', 0);
         if (!$id) {
             return $this->redirect()->toRoute('user', array(
                 'action' => 'add'
             ));
         }

         try {
             $user = $this->getUserTable()->getUser($id);
         }
         catch (\Exception $ex) {
             return $this->redirect()->toRoute('user', array(
                 'action' => 'index'
             ));
         }
		$password = $user->password;
		
        $form  = new UserForm();
		 
        $form->bind($user);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
			
		
            $form->setInputFilter($user->getInputFilter());
			
			$saveData = $request->getPost();
			if(empty($saveData->password)){
				$saveData->password = $password;
			}else{
				$saveData->password = self::criptPassword($saveData->password);
			}
			
			$form->setData($saveData);
			
            if ($form->isValid()) {
                $this->getUserTable()->saveUser($user);
                return $this->redirect()->toRoute('user',array('action' => 'profile', 'id' => $id));
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function deleteAction()
    {
		$id = (int) $this->params()->fromRoute('id', 0);
         if (!$id) {
             return $this->redirect()->toRoute('user');
         }

         $request = $this->getRequest();
         if ($request->isPost()) {
             $del = $request->getPost('del', 'No');

             if ($del == 'Yes') {
                 $id = (int) $request->getPost('id');
                 $this->getUserTable()->deleteUser($id);
             }

             // Redirect to list of albums
             return $this->redirect()->toRoute('auth/default', array('controller' => 'index', 'action' => 'logout'));	
         }

         return array(
             'id'    => $id,
             'user' => $this->getUserTable()->getUser($id)
         );
    }
	
	public function profileAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('user');
        }
		
		try {
            $userDetails = $this->getUserTable()->getUser($id);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('user', array(
                'action' => 'index'
            ));
        }
		
		$restaurants = array();
		$userFavRestaurants = $this->getUserFavoriteRestaurantTable()->getUserFavoriteRestaurants($id);
		foreach($userFavRestaurants as $usrResstaurant){
			$restaurants[] = $this->getRestaurantTable()->getRestaurant($usrResstaurant->restaurant_id);
		}
		
		//$restaurants = $this->getUserFavoriteRestaurantsMapper()->findUserRestaurants($id);
		//var_dump($this->mapper->findAll());die;
		return new ViewModel(array(
            'user' => $userDetails,
			'restaurants' => $restaurants
        ));
	}
	 
	public function getUserTable()
    {
        if (!$this->userTable) {
            $sm = $this->getServiceLocator();
            $this->userTable = $sm->get('User\Model\UserTable');
        }
        return $this->userTable;
    }
	
	public function getUserFavoriteRestaurantTable()
    {
        if (!$this->userFavoriteRestaurantTable) {
            $sm = $this->getServiceLocator();
            $this->userFavoriteRestaurantTable = $sm->get('User\Model\UserFavoriteRestaurantTable');
        }
        return $this->userFavoriteRestaurantTable;
    }
	
	public function getRestaurantTable()
    {
        if (!$this->restaurant) {
            $sm = $this->getServiceLocator();
            $this->restaurant = $sm->get('User\Model\RestaurantTable');
        }
        return $this->restaurant;
    }
	
	//generate password
	public static function criptPassword($password){
		
		return md5($password);
	}
}
