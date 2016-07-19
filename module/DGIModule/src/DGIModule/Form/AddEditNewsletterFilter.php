<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2016 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Form;

use Zend\InputFilter\InputFilter;

class AddEditNewsletterFilter extends InputFilter
{
	public function __construct()
	{
		
		$this->add(array(
            'name'=>'nlName',
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
		    'name'=>'nlSendTo',
		    'required' => true,
		    'validators' => array(
		        array(
		            'name'    => 'Between',
		            'options' => array(
		                'min'      => 1,
		                'messages' => array('notBetween' => _('Please select at least one user group.'))
		            ),
		        ),
		         
		    ),
		));
		
		$this->add(array(
		    'name'=>'nlDescription',
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
		                'min'      => 1,
		                'max'      => 1000,
		            ),
		        ),
		         
		    ),
		));
		
		$this->add(array(
		    'name'=>'nlSubject',
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
		                'max'      => 200,
		            ),
		        ),
		         
		    ),
		));
		
		$this->add(array(
		    'name'=>'nlContact',
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
		                'min'      => 1,
		                'max'      => 200,
		            ),
		        ),
		         
		    ),
		));
		
		$this->add(array(
		    'name'=>'nlUrl',
		    'required' => false
		));
		
		$this->add(array(
		    'name'=>'nlMessage',
		    'required' => true,
		    'filters'  => array(
		        array('name' => 'StripTags', 
				    'options' => [
				        'allowTags' => ['b', 'i', 'br', 'strong', 'blockquote', 'span', 'div']
				    ]),
		        array('name' => 'StringTrim'),
		    ),
		    'validators' => array(
		        array(
		            'name'    => 'StringLength',
		            'options' => array(
		                'encoding' => 'UTF-8',
		                'min'      => 1,
		                'max'      => 3000,
		            ),
		        ),
		         
		    ),
		));
		
		
		$this->add(array(
		    'name'     => 'picture-file',
		    'required' => false,
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
