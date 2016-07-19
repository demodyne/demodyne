<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2016 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Form;

use Zend\InputFilter\InputFilter;

class UserLoginFilter extends InputFilter
{
	public function __construct($sm)
	{
		$this->add(array(
			'name'     => 'username', 
			'required' => true,
			'filters'  => array(
				array('name' => 'StripTags'),
				array('name' => 'StringTrim'),
			),
			'validators' => array(
				 array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            'isEmpty' => _('Please enter a username'), 
                        ),
                    ),
                ),
			    array(
					'name'    => 'StringLength',
					'options' => array(
						'encoding' => 'UTF-8',
						'min'      => 1,
						'max'      => 100,
					    'messages' => array(
					        //'isEmpty' => 'Please enter User Name between 4 to 20 character!'
					    ),
					),
				),
				array(
					'name'		=> 'DoctrineModule\Validator\ObjectExists',
					'options' => array(
						'object_repository' => $sm->get('doctrine.entitymanager.orm_default')->getRepository('DGIModule\Entity\User'),
						'fields'            => 'usrName',
					    'messages' => array(
					        'noObjectFound' => _('Username not registered'),
					    ),
					),
					
				),
			), 
		));
		
		$this->add(array(
			'name'     => 'password', 
			'required' => true,
			'filters'  => array(
				array('name' => 'StripTags'),
				array('name' => 'StringTrim'),
			),
		));		
	}
}
