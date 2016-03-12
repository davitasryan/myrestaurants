<?php

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use User\Model\Restaurant;
use User\Form\RestaurantForm;
use User\Form\RestaurantImagesForm;
use User\Model\RestaurantImages;
use Zend\Validator\File\Size;

class RestaurantController extends AbstractActionController
{
	protected $restaurantTable;
	protected $userFavoriteRestaurant;
	protected $restaurantImagesTable;
	
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

                return $this->redirect()->toRoute('restaurant');
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
		
		$form  = new RestaurantForm();
		 
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
			'rest' => $rest
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
             'restaurant' => $this->getRestaurantTable()->getRestaurant($id)
         );
    }
	
	public function viewAction()
	{
				
		$id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('restaurant');
        }
		
		try {
            $rest = $this->getRestaurantTable()->getRestaurant($id);
        }
        catch (\Exception $ex) {
            return $this->redirect()->toRoute('restaurant', array(
                'action' => 'index'
            ));
        }
		
		$images = $this->getRestaurantImagesTable()->getRestaurantImages($id);
		
		return array(
            'id'    => $id,
            'restaurant' => $rest,
			'images' => $images
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
	
	public function getRestaurantImagesTable()
    {
        if (!$this->restaurantImagesTable) {
            $sm = $this->getServiceLocator();
            $this->restaurantImagesTable = $sm->get('User\Model\RestaurantImagesTable');
        }
        return $this->restaurantImagesTable;
    }
	
	public function restaurantImagesAction()
	{
		$form = new RestaurantImagesForm();
        $request = $this->getRequest();  
        if ($request->isPost()) {
             
            $profile = new RestaurantImages();
            $form->setInputFilter($profile->getInputFilter());
           
			$File    = $this->params()->fromFiles('fileupload');
			$fileName = end(explode('\\',$File['tmp_name'])).'_'.$File['name'];
			
            $data    = array_merge_recursive(
				$this->getRequest()->getPost()->toArray(),           
                $this->getRequest()->getFiles()->toArray()
            );
             
 
            //set data post and file ...    
            $form->setData($data);
              
            if ($form->isValid()) {
                $size = new Size(array('min'=>2000000));
     
				$adapter = new \Zend\File\Transfer\Adapter\Http(); 
				
				$adapter->setValidators(array($size), $File['name']);
				 
				if (!$adapter->isValid()){
					$dataError = $adapter->getMessages();
					$error = array();
					foreach($dataError as $key=>$row)
					{
						$error[] = $row;
					} //set formElementErrors
					$form->setMessages(array('fileupload'=>$error ));
				} else {
					
					if(!file_exists('public/uploads/')){
						mkdir('public/uploads/');
					}
					$adapter->setDestination('public/uploads/');
					if ($adapter->receive()) {
						
						$profile->exchangeArray($form->getData());
						$profile->fileupload = $fileName;
						$this->getRestaurantImagesTable()->saveRestaurantImages($profile);
						
						return $this->redirect()->toRoute('restaurant', array('action' => 'view','id' =>$profile->restaurant_id));
						/*
						return $this->forward()->dispatch('User\Controller\RestaurantImages', array(
						  'action' => 'save',
						  'profile'   => $profile
						));
						*/
						
					}
				}  
            } 
        }else{
			$id = (int) $this->params()->fromRoute('id', 0);
			if (!$id) {
				 return $this->redirect()->toRoute('restaurant');
			}
			$form->get('restaurant_id')->setValue($id);
		}
          
        return array('form' => $form);  

	}
	

}
