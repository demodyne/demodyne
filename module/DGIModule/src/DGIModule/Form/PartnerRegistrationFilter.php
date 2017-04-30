<?php
namespace DGIModule\Form;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;

class PartnerRegistrationFilter extends InputFilter
{
	public function __construct($entityManager)
	{
		// self::__construct(); // parnt::__construct(); - trows and error
		$this->add(array(
			'name'     => 'usrName',
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
						'min'      => 5,
						'max'      => 20,
					),
				),
				array(
					'name'		=> 'DoctrineModule\Validator\NoObjectExists',
					'options' => array(
						'object_repository' => $entityManager->getRepository('DGIModule\Entity\User'),
						'fields'            => 'usrName'
					),
				),
			),
		));

        $this->add(array(
            'name'       => 'usrEmail',
            'required'   => true,
            'validators' => array(
                array(
                    'name' => 'EmailAddress'
                ),
				array(
					'name'		=> 'DoctrineModule\Validator\NoObjectExists',
					'options' => array(
						'object_repository' => $entityManager->getRepository('DGIModule\Entity\User'),
						'fields'            => 'usrEmail'
					),
				),
            ),
        ));
		
		$this->add(array(
			'name'     => 'usrPassword',
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
						'min'      => 6,
						'max'      => 12,
					),
				),
			),
		));	

		$this->add(array(
			'name'     => 'usrPasswordConfirm',
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
						'min'      => 6,
						'max'      => 12,
					),
				),
                array(
                    'name'    => 'Identical',
                    'options' => array(
                        'token' => 'usrPassword',
                    ),
                ),
			),
		));		
		
		$this->add(array(
		    'name' => 'usrFirstname',
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
		                'max' => 100
		            ),
		        ),
		    ),
		));
		
		$this->add(array(
		    'name' => 'usrLastname',
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
		                'max' => 100
		            ),
		        ),
		    ),
		));
		
		
		$this->add(array(
		    'name' => 'country',
		    'required' => true,
		));
		
		
		$this->add(array(
		    'name' => 'usrPostalcode',
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
		
		$this->add(array(
		    'name' => 'usrCity',
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
		                'max' => 50
		            ),
		        ),
		    ),
		));
		
		$this->add(array(
		    'name' => 'usrPhone',
		    'required' => false,
		    'filters' => array(
		        array('name' => 'StripTags'),
		        array('name' => 'StringTrim'),
		    ),
		    'validators' => array(
		        array(
		            'name' => 'StringLength',
		            'options' => array(
		                'encoding' => 'UTF-8',
		                'min' => 10,
		                'max' => 12
		            ),
		        ),
		    ),
		));
		
		
	}
}