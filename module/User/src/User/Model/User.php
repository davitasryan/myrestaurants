<?php
namespace User\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;


class User
{
	public $id;
	public $name;
	public $age;
	public $gender;
	public $occupancy;
	public $avatar;
	public $phone;
	public $email;
	public $password;
	protected $inputFilter; 

	public function exchangeArray($data)
	{
		$this->id     = (!empty($data['id'])) ? $data['id'] : null;
		$this->name     = (!empty($data['name'])) ? $data['name'] : null;
		$this->age     = (!empty($data['age'])) ? $data['age'] : null;
		$this->gender     = (!empty($data['gender'])) ? $data['gender'] : null;
		$this->occupancy     = (!empty($data['occupancy'])) ? $data['occupancy'] : null;
		$this->avatar     = (!empty($data['avatar'])) ? $data['avatar'] : null;
		$this->phone     = (!empty($data['phone'])) ? $data['phone'] : null;
		$this->email     = (!empty($data['email'])) ? $data['email'] : null;
		$this->password     = (!empty($data['password'])) ? $data['password'] : null;
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
                            'max'      => 40,
                        ),
                    ),
                ),
            ));

            $inputFilter->add(array(
                'name'     => 'email',
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
                            'max'      => 80,
                        ),
                    ),
                ),
            ));
			
			$inputFilter->add(array(
                'name'     => 'password',
                'required' => true,
                'validators' => array(
					array(
						'name' => 'not_empty',
					),
					array(
						'name' => 'string_length',
						'options' => array(
							'min' => 6
						),
					),
				),
            ));
			
			$inputFilter->add(array(
                'name'     => 'age',
                'required' => false,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            ));
			
			$inputFilter->add(array(
                'name'     => 'occupancy',
                'required' => false,
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
                            'max'      => 255,
                        ),
                    ),
                ),
            ));
			
			$inputFilter->add(array(
                'name'     => 'gender',
                'required' => false,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            ));
			
			$inputFilter->add(array(
                'name'     => 'avatar',
                'required' => false,
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
                            'max'      => 255,
                        ),
                    ),
                ),
            ));
			
			$inputFilter->add(array(
                'name'     => 'phone',
                'required' => false,
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
                            'max'      => 20,
                        ),
                    ),
                ),
            ));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

	
	

}