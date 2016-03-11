<?php
namespace User\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\Factory as InputFactory;



class RestaurantImages
{
	public $restaurant_id;
    public $fileupload;
    protected $inputFilter;
	
	public function exchangeArray($data)
    {
        $this->restaurant_id  = (isset($data['restaurant_id']))  ? $data['restaurant_id']     : null; 
        $this->fileupload  = (isset($data['fileupload']))  ? $data['fileupload']     : null; 
    } 
     
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }
     
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();
              
            $inputFilter->add(
                $factory->createInput(array(
                    'name'     => 'restaurant_id',
                    'required' => true,
                ))
            );
             
            $inputFilter->add(
                $factory->createInput(array(
                    'name'     => 'fileupload',
                    'required' => true,
                ))
            );
             
            $this->inputFilter = $inputFilter;
        }
         
        return $this->inputFilter;
    }
	
/*	
	public $id;
	public $name;
	public $restaurant_id;
	
	protected $inputFilter; 

	public function exchangeArray($data)
	{
		$this->id     = (!empty($data['id'])) ? $data['id'] : null;
		$this->name     = (!empty($data['name'])) ? $data['name'] : null;
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
                'name'     => 'id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            ));

            $inputFilter->add(array(
                'name'     => 'name',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 200,
                        ),
                    ),
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

*/	
	

}