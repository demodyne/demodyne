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

class AddEditMeasureForm extends Form implements InputFilterProviderInterface
{
    public function __construct($entityManager = null)
    {
        parent::__construct('measureForm');
        $this->setAttribute('method', 'post');
        $this->setHydrator(new ClassMethods());
        $this->setAttribute('class', 'form-horizontal');
        $this->setOption( 'use_as_base_fieldset', true);
        
        $this->add(array(
            'type' => 'DGIModule\Form\MeasureFieldset',
            'name' => 'measure',
        ));

        $this->add(array(
		    'name' => 'propSavedName',
		    'attributes' => array(
		        'type'  => 'text',
		        'required' => 'required',
		        'class'=>'form-control text-change',
		        'id' => 'propName',
		        'maxlength' => 50,
		        'size' => 50,
		    ),
            'options' => array(
                'label' => 'Name:',
            ),
		));
		
		$this->add([
            'type' => 'Zend\Form\Element\Textarea',
            'name' => 'propDescription',
            'attributes' => array(
                'rows' => 4,
                'class'=>'form-control text-change',
                'id' => 'propDescription',
                'style' => 'display:none'
            ),
            'options' => [
                'label' => 'Description: ',
            ]
        ]);
		
    }
    
    /**
     * TODO add validator messages
     * 
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [
            'propSavedName' => [
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
                            'max'      => 100,
                        ),
                    ),
                ),
            ],
            'propDescription' => [
                'required' => true,
                'filters'  => [
                    array('name' => 'StripTags',
                        'options' => [
    				        'allowTags' => ['b','u', 'i', 'br', 'strong', 'blockquote', 'span', 'div', 'p']
    				    ]),
                    array('name' => 'StringTrim'),
                ],
                'validators' => [
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 2000,
                        ),
                    ),
                
                ]
            ]
        ];
        
    }
    
}
