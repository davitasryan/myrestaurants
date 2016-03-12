<?php

 namespace User\Form;

 use Zend\Form\Form;

 class UserForm extends Form
 {
    public function __construct($name = null)
    {
        
        parent::__construct('user');
		
        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'name',
            'type' => 'Text',
            'options' => array(
                'label' => '',
            ),
        ));
        $this->add(array(
            'name' => 'email',
            'type' => 'Email',
            'options' => array(
                'label' => '',
            ),
        ));
		$this->add(array(
            'name' => 'age',
            'type' => 'Text',
            'options' => array(
                'label' => '',
            ),
        ));
		$this->add(array(
            'name' => 'gender',
            'type' => 'Radio',
            'options' => array(
                'label' => 'Gender',
				'value_options' => array(
					'1' => 'Male',
					'2' => 'Female',
				),
            ),
        ));
		$this->add(array(
            'name' => 'occupancy',
            'type' => 'Text',
            'options' => array(
                'label' => '',
            ),
        ));
		$this->add(array(
            'name' => 'avatar',
            'type' => 'Text',
            'options' => array(
                'label' => 'Avatar',
            ),
        ));
		$this->add(array(
            'name' => 'phone',
            'type' => 'Text',
            'options' => array(
                'label' => '',
            ),
        ));
        $this->add(array(
            'name' => 'password',
            'type' => 'Password',
            'options' => array(
                'label' => '',
            ),
        ));		

        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));
    }
 }