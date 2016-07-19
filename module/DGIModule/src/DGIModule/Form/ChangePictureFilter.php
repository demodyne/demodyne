<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2016 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Form;

use Zend\InputFilter\InputFilter;

class ChangePictureFilter extends InputFilter
{
	public function __construct()
	{
		$this->add(array(
		    'name'     => 'picture-file',
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
