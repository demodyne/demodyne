<?php
namespace DGIModule\Form;

use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;

class PartnerCityForm extends Form
{
    public function __construct($entityManager = null, $name = null, $options = [])
    {
        parent::__construct('partnerCity');
        $this->setAttribute('method', 'post');
        $this->setHydrator(new ClassMethods());
        $this->setInputFilter(new PartnerCityFilter());

       
		$this->add(array(
		    'name' => 'partnerPostalcode',
		    'attributes' => array(
		        'type'  => 'text',
		        'required' => 'required',
		        'class'=>'form-control text-change',
		        'id' => 'partnerPostalcode',
		        'maxlength' => 5,
		        'size' => 5,
		    ),
		    'options' => array(
		        'label' => 'Postalcode:',
		    ),
		));
		
		$this->add(array(
		    'name' => 'country',
		    'attributes' => array(
		        'type'  => 'text',
		        'required' => 'required',
		        'class'=>'form-control text-change',
		        'id' => 'country'
		    ),
		));
		
		$this->add(array(
		    'name' => 'city',
		    'type'  => 'Zend\Form\Element\Select',
		    'options' => array(
		        'label' => 'City: ',
		        'empty_option' => '--- please write your postalcode first ---',
		        'disable_inarray_validator' => true,
		    ),
		    'attributes' => [
		        'id' => 'city',
		        'required' => 'required',
		        'class'=>'form-control text-change',
		        'required' => 'required',
		        'disabled' => 'disabled',
		    ]
		));
		
		
		
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Save',
                'id' => 'submitbutton',
            ),
        )); 
        
    }
    
    
}