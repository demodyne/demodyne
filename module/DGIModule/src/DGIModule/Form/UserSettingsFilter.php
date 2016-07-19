<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2016 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Form;

use Zend\InputFilter\InputFilter;

class UserSettingsFilter extends InputFilter
{
	public function __construct()
	{
		
		$this->add(array(
			'name'     => 'usrOldPassword',
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
						'min'      => 6,
						'max'      => 12,
					),
				),
			),
		));	
		
		$this->add(array(
		    'name'     => 'usrNewPassword',
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
		                'min'      => 6,
		                'max'      => 12,
		            ),
		        ),
		    ),
		));

		$this->add(array(
			'name'     => 'usrNewPasswordConfirm',
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
						'min'      => 6,
						'max'      => 12,
					),
				),
                array(
                    'name'    => 'Identical',
                    'options' => array(
                        'token' => 'usrNewPassword',
                    ),
                ),
			),
		));		
		
	}
}
