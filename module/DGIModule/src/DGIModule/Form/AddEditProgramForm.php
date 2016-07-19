<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2016 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Form;

use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\InputFilter\InputFilterProviderInterface;
use DGIModule\Entity\Program;

class AddEditProgramForm extends Form implements InputFilterProviderInterface
{
    public function __construct()
    {
        parent::__construct('programForm');
        $this->setObject(new Program());
        $this->setAttribute('method', 'post');
        $this->setHydrator(new ClassMethods());
        $this->setAttributes([
            'class' => 'form-horizontal',
            'role'=> 'form'        ]);

        $this->add(array(
		    'name' => 'progName',
		    'attributes' => array(
		        'type'  => 'text',
		        'required' => 'required',
		        'class'=>'form-control text-change',
		        'id' => 'progName',
		        'maxlength' => 50,
		        'size' => 50,
		    ),
		));
		
		$this->add([
            'type' => 'Zend\Form\Element\Textarea',
            'name' => 'progDescription',
            'attributes' => array(
                'rows' => 4,
                'class'=>'form-control text-change',
                'id' => 'progDescription',
                'style' => 'display:none'
            ),
        ]);
		
    }
    
    public function getInputFilterSpecification()
    {
        return array(
            'progName' => [
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                    array('name' => 'PregReplace', 
                        'options' => [
                            'pattern' => "/\"/",
                            'replacement' => '&rdquo;'
                        ]
                    ),
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
            ],
            'progDescription' => [
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags', 
				    'options' => [
				        'allowTags' => ['b','u', 'i', 'br', 'strong', 'blockquote', 'span', 'div', 'p']
				    ]),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 2000,
                        ),
                    ),
            
                ),
            ]
        );
    }
  
}
