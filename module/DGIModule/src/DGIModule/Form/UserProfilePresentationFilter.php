<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2016 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Form;

use Zend\InputFilter\InputFilter;

class UserProfilePresentationFilter extends InputFilter
{
	public function __construct($serviceLocator)
	{
				
	    $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
	    
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
		
		$this->add(array(
		    'name' => 'usrBirthday',
		    'required' => false
		));
		
		$this->add(array(
		    'name' => 'usrGendre',
		    'required' => false
		));
		
		$this->add(array(
		    'name' => 'usrPresentation',
		    'required' => false
		));
				
	}
}
