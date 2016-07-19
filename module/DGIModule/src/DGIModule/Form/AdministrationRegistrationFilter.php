<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2016 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Form;

use Zend\InputFilter\InputFilter;

class AdministrationRegistrationFilter extends InputFilter
{
	public function __construct($sm)
	{
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
						'messages' => array(
						      \Zend\Validator\StringLength::TOO_LONG  => _("The username must be between %min% and %max% characters long"),
						      \Zend\Validator\StringLength::TOO_SHORT  => _("The username must be between %min% and %max% characters long")
				        )
					),
				),
				array(
				    'name' => 'Regex',
				    'options' => array(
				        'pattern' => '/^[a-zA-Z][a-zA-Z0-9_.-]+$/',
				        'messages' => array(
				            \Zend\Validator\Regex::NOT_MATCH => _("Invalid username. Usernames must start with a letter. Allowed characters are alphabets (a-z, A-Z), digits (0-9), underscore (_), hyphen (-) , and period (.)")
				        )
				    ),
				),
				array(
					'name'		=> 'DoctrineModule\Validator\NoObjectExists',
					'options' => array(
						'object_repository' => $sm->get('doctrine.entitymanager.orm_default')->getRepository('DGIModule\Entity\User'),
						'fields'            => 'usrName',
				        'messages' => [
				            'objectFound' => _('This username is already registered to Demodyne')
				        ]
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
						'object_repository' => $sm->get('doctrine.entitymanager.orm_default')->getRepository('DGIModule\Entity\User'),
						'fields'            => 'usrEmail',
					    'messages' => [ 
					        'objectFound' => _('This email is already registered to Demodyne')
					    ]
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
						'messages' => array(
						      \Zend\Validator\StringLength::TOO_LONG  => _("The password must be between %min% and %max% characters long"),
						      \Zend\Validator\StringLength::TOO_SHORT  => _("The password must be between %min% and %max% characters long")
				        )
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
                    'name'    => 'Identical',
                    'options' => array(
                        'token' => 'usrPassword',
                        'messages' => array(
                            \Zend\Validator\Identical::NOT_SAME  => _("The two given passwords do not match"),
                        )
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
