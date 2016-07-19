<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2016 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Form;

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
