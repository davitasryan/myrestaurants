<?php
namespace User\Form;

use Zend\InputFilter;
use Zend\Form\Element;
use Zend\Form\Form;

class RestaurantImagesForm extends Form
{
	public function __construct($name = null)
    {
        parent::__construct('RestaurantImages');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype','multipart/form-data');
         
        $this->add(array(
            'name' => 'restaurant_id',
			'type' => 'Hidden',
            'attributes' => array(
                'type'  => 'int',
            ),
        ));
 
         
        $this->add(array(
            'name' => 'fileupload',
            'attributes' => array(
                'type'  => 'file',
            ),
            'options' => array(
                'label' => 'File Upload',
            ),
        )); 
         
         
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Upload Now'
            ),
        )); 
    }
	
/*
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        $this->addElements();
		$this->addInputFilter();
    }

    public function addElements()
    {
        /*
		$this->add(array(
            'name' => 'name',
            'attributes' => array(
                'type'  => 'file',
				'multiple' => true
            ),
            'options' => array(
                'label' => 'Restaurant Image Upload',
            ),
        )); 
		*/
/*		
		$file = new Element\File('name');
        $file->setLabel('Avatar Image Upload')
             ->setAttribute('id', 'name');
        $this->add($file);
		
		$this->add(array(
            'name' => 'restaurant_id',
            'type' => 'Hidden',
			
        ));
		
		$this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));
		
		$this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Upload Now'
            ),
        )); 
    }
	
	public function addInputFilter()
    {
        $inputFilter = new InputFilter\InputFilter();

        // File Input
        $fileInput = new InputFilter\FileInput('name');
        $fileInput->setRequired(true);

        // You only need to define validators and filters
        // as if only one file was being uploaded. All files
        // will be run through the same validators and filters
        // automatically.
        $fileInput->getValidatorChain()
            ->attachByName('filesize',      array('max' => 204800))
            ->attachByName('filemimetype',  array('mimeType' => 'image/png,image/x-png,image/jpeg'));
            //->attachByName('fileimagesize', array('maxWidth' => 100, 'maxHeight' => 100));

        // All files will be renamed, i.e.:
        //   ./data/tmpuploads/avatar_4b3403665fea6.png,
        //   ./data/tmpuploads/avatar_5c45147660fb7.png
        /*$fileInput->getFilterChain()->attachByName(
            'filerenameupload',
            array(
                'target'    => './data/tmpuploads/avatar.png',
                'randomize' => true,
            )
        );*/
/*
        $inputFilter->add($fileInput);

        $this->setInputFilter($inputFilter);
    }
*/

}