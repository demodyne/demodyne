<?php
namespace DGIModule\Form;

//use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class PartnerCityFilter extends InputFilter
{
	public function __construct()
	{
				
		
		
		
		$this->add(array(
		    'name' => 'partnerPostalcode',
		    'required' => true,
		    'filters' => array(
		        array('name' => 'StripTags'),
		        array('name' => 'StringTrim'),
		    ),
		    'validators' => array(
		        array(
		            'name' => 'StringLength',
		            'options' => array(
		                'encoding' => 'UTF-8',
		                'min' => 2,
		                'max' => 5
		            ),
		        ),
		        array(
		            'name' => 'PostCode',
		            'options' => array(
		                'locale' => 'fr_FR',
		            ),
		        )
		    ),
		));
		
		
				
	}
}