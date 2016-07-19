<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2016 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Form;

use Zend\InputFilter\InputFilter;

class UserEditInfoFilter extends InputFilter
{
	public function __construct()
	{
		
		$this->add(array(
			'name'     => 'usrOldPassword',
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
					),
				),
			),
		));	
		
		$this->add(array(
		    'name'     => 'usrNewPassword',
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
		                'max'      => 12,
		                'messages' => [
		                    'stringLengthTooShort' => 'The password must be between 6 and 12 chars long.',
		                    'stringLengthTooLong' => 'The password must be between 6 and 12 chars long.'
		                ],
		            ),
		        ),
		    ),
		));

		$this->add(array(
			'name'     => 'usrNewPasswordConfirm',
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
						'max'      => 12,
					    'messages' => [
					        'stringLengthTooShort' => 'The password must be between 6 and 12 chars long.',
					        'stringLengthTooLong' => 'The password must be between 6 and 12 chars long.'
					    ],
					),
				),
                array(
                    'name'    => 'Identical',
                    'options' => array(
                        'token' => 'usrNewPassword',
                        'messages' => [
                            'notSame' => 'The password is not the same.',
                        ],
                    ),
                ),
			),
		));		
		
		$this->add(array(
		    'name'     => 'usrPresentation',
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
		                'max'      => 1000,
		            ),
		        ),
		    ),
		));
		
		$filename="avatar".time();
		
		$this->add(array(
		    'name'     => 'usrPicture',
		    'required' => true,
		    'validators' => array(
		        array(
		            'name'    => 'Zend\Validator\File\Size',
		            'options' => array(
		                'max'      => 65536, // 64Ko
		            ),
		        ),
		    ),
		));
		
	}
}
