<?php

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use User\Model\Restaurant;
use User\Form\RestaurantForm;

class RestaurantController extends AbstractActionController
{
	protected $restaurantTable;
	
    public function indexAction()
    {
		return new ViewModel(array(
             'restaurants' => $this->getRestaurantTable()->fetchAll(),
         ));
    }

    public function addAction()
    {
		$form = new RestaurantForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $rest = new Restaurant();
            $form->setInputFilter($rest->getInputFilter());
			$saveData = $request->getPost();
			$form->setData($saveData);
			
            if ($form->isValid()) {
                $rest->exchangeArray($form->getData());
                $this->getRestaurantTable()->saveRestaurant($rest);

                return $this->redirect()->toRoute('rest');
            }
        }
        return array('form' => $form);

    }

    public function editAction()
    {
		$id = (int) $this->params()->fromRoute('id', 0);
         if (!$id) {
             return $this->redirect()->toRoute('restaurant', array(
                 'action' => 'add'
             ));
         }

        try {
            $rest = $this->getRestaurantTable()->getRestaurant($id);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('restaurant', array(
                'action' => 'index'
            ));
        }
		
		
        $form  = new UserForm();
		 
        $form->bind($rest);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
			
		
            $form->setInputFilter($rest->getInputFilter());
			
			$saveData = $request->getPost();
			if(empty($saveData->password)){
				$saveData->password = $password;
			}else{
				$saveData->password = self::criptPassword($saveData->password);
			}
			
			$form->setData($saveData);
			
            if ($form->isValid()) {
                $this->getRestaurantTable()->saveRestaurant($rest);
                return $this->redirect()->toRoute('restaurant');
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
             return $this->redirect()->toRoute('restaurant');
         }

         $request = $this->getRequest();
         if ($request->isPost()) {
             $del = $request->getPost('del', 'No');

             if ($del == 'Yes') {
                 $id = (int) $request->getPost('id');
                 $this->getRestaurantTable()->deleteRestaurant($id);
             }

             // Redirect to list of albums
             return $this->redirect()->toRoute('restaurant');
         }

         return array(
             'id'    => $id,
             'user' => $this->getRestaurantTable()->getRestaurant($id)
         );
    }
	 
	public function getRestaurantTable()
    {
        if (!$this->restaurantTable) {
            $sm = $this->getServiceLocator();
            $this->restaurantTable = $sm->get('User\Model\RestaurantTable');
        }
        return $this->restaurantTable;
    }
}
