<?php
 namespace User\Form;
 
 use Zend\Form\Form;
 class RestaurantForm extends Form
 {
    public function __construct($name = null)
    {
        
        parent::__construct('restaurant');
		
        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
        $this->add(array(
            'name' => 'name',
			'attributes' => array(
				'id'  => 'name',
			),
            'type' => 'Text',
            'options' => array(
                'label' => 'Name',
            ),
        ));
		$this->add(array(
            'name' => 'address',
            'type' => 'Text',
            'options' => array(
                'label' => 'Address',
            ),
			'attributes' => array(
				'id'  => 'address',
			),
        ));
		$this->add(array(
            'name' => 'lat',
            'type' => 'Hidden',
			'attributes' => array(
				'id'  => 'latitude',
			),
        ));
		$this->add(array(
            'name' => 'lng',
            'type' => 'Hidden',
			'attributes' => array(
				'id'  => 'longitude',
			),
        ));
		$this->add(array(
            'name' => 'site',
            'type' => 'Text',
            'options' => array(
                'label' => 'Url',
            ),
			'attributes' => array(
				'id'  => 'site',
			),
        ));
		$this->add(array(
            'name' => 'contact',
            'type' => 'Text',
            'options' => array(
                'label' => 'Contact',
            ),
			'attributes' => array(
				'id'  => 'contact',
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