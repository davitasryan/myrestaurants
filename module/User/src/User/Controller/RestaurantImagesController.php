<?php

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use User\Form\RestaurantImagesForm;
use User\Model\RestaurantImages;

class RestaurantImagesController extends AbstractActionController
{
	protected $restaurantImagesTable;
	
    public function indexAction()
    {
		return new ViewModel(array(
             'restaurants' => $this->getRestaurantImagesTable()->fetchAll(),
         ));
    }
	
	public function saveAction(){
		
		$profile = $this->params()->fromRoute('profile');
		$this->getRestaurantImagesTable()->saveRestaurantImages($profile);
	}
	
	public function getRestaurantImagesTable()
    {
        if (!$this->restaurantImagesTable) {
            $sm = $this->getServiceLocator();
            $this->restaurantImagesTable = $sm->get('User\Model\RestaurantImagesTable');
        }
        return $this->restaurantImagesTable;
    }
	
}