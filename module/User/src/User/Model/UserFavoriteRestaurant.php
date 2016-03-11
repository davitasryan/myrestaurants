<?php
namespace User\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class UserFavoriteRestaurant
{
	public $user_id;
	public $restaurant_id;
	
	protected $inputFilter; 

	public function exchangeArray($data)
	{
		$this->user_id     = (!empty($data['user_id'])) ? $data['user_id'] : null;
		$this->restaurant_id     = (!empty($data['restaurant_id'])) ? $data['restaurant_id'] : null;
	}
	
	public function getArrayCopy()
    {
        return get_object_vars($this);
    }

	
	public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }
	
	
	public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();

            $inputFilter->add(array(
                'name'     => 'user_id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            ));
			$inputFilter->add(array(
                'name'     => 'restaurant_id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            ));           

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

	
	

}