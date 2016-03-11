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
             /*
            $nonFile = $request->getPost()->toArray();
            $File    = $this->params()->fromFiles('fileupload');
            $data = array_merge(
                 $nonFile, //POST 
                 array('fileupload'=> $File['name']) //FILE...
             );*/
             
            /** if you're using ZF >= 2.1.1  
              *  you should update to the latest ZF2 version
              *  and assign $data like the following 
			  */
			$File    = $this->params()->fromFiles('fileupload');
			$fileName = end(explode('\\',$File['tmp_name'])).$File['name'];
			
            $data    = array_merge_recursive(
				$this->getRequest()->getPost()->toArray(),           
                $this->getRequest()->getFiles()->toArray()
            );
             
 
            //set data post and file ...    
            $form->setData($data);
              
            if ($form->isValid()) {
                $size = new Size(array('min'=>2000000)); //minimum bytes filesize
     
				$adapter = new \Zend\File\Transfer\Adapter\Http(); 
				//validator can be more than one...
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
					$adapter->setDestination('data/uploads');
					if ($adapter->receive()) {
						
						$profile->exchangeArray($form->getData());
						$profile->fileupload = $fileName;
						$this->getRestaurantImagesTable()->saveRestaurantImages($profile);
						/*
						return $this->forward()->dispatch('User\Controller\RestaurantImages', array(
						  'action' => 'save',
						  'profile'   => $profile
						));
						*/
						echo 'Profile Name '.$fileName;die;
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
    
/*
		$form = new RestaurantImagesForm();
		$request = $this->getRequest();
	
		if ($request->isPost()) {
			
			$rImages = new RestaurantImages();
            $form->setInputFilter($rImages->getInputFilter());
			
			$nonFile = $request->getPost()->toArray();
            $File    = $this->params()->fromFiles('name');
			
            $data = array_merge(
                 $nonFile,
                 array('name'=> $File['name'])
             );
			
	/*			
			$data = array_merge_recursive(
				$request->getPost()->toArray(),
				$request->getFiles()->toArray()
			);
	*/
/*
			$form->setData($data);
			
			var_dump($form->isValid(), $data);die;
			if ($form->isValid()) {
				$data = $form->getData();
				var_dump($data);die;
				$this->getRestaurantTable()->saveRestaurant($rest);
                return $this->redirect()->toRoute('restaurant');
				
			}
		}else{
			$id = (int) $this->params()->fromRoute('id', 0);
			if (!$id) {
				 return $this->redirect()->toRoute('restaurant');
			}
			$form->get('restaurant_id')->setValue($id);
		}

		return array('form' => $form);
*/
	}
	

}
