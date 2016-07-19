<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2016 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Form;

use Zend\InputFilter\InputFilter;

class NewMessageFilter extends InputFilter
{
	public function __construct($sm = null)
	{
		$this->add(array(
			'name'     => 'msgTo',
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
						'min'      => 1,
						'max'      => 100,
					),
				),
			),
		));
		$this->add(array(
		    'name'     => 'msgTitle',
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
		                'min'      => 1,
		                'max'      => 50,
		            ),
		        ),
		    ),
		));
		$this->add(array(
		    'name'     => 'msgText',
		    'required' => true,
		    'filters'  => array(
		        array('name' => 'StripTags', 
				    'options' => [
				        'allowTags' => ['b', 'i', 'br', 'strong', 'blockquote', 'span', 'div', 'p', 'u']
				    ]),
		        array('name' => 'StringTrim'),
		    ),
		    'validators' => array(
		        array(
		            'name'    => 'StringLength',
		            'options' => array(
		                'encoding' => 'UTF-8',
		                'max'      => 10000,
		            ),
		        ),
		    ),
		));
	}
}
