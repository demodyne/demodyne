<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Form;

use DGIModule\Entity\Proposal;
use Zend\Form\Form;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\InputFilter\InputFilterProviderInterface;

class AddEditMeasureForm extends Form implements InputFilterProviderInterface
{
    public function __construct($entityManager, Proposal $proposal = null)
    {
        parent::__construct('proposal-form');
        $this->setAttribute('method', 'post');
        $this->setHydrator(new DoctrineHydrator($entityManager,'DGIModule\Entity\Proposal'));
        $this->setAttribute('class', 'form-horizontal');
        $this->setOption( 'use_as_base_fieldset', true);
        $this->setAttribute('id', 'proposal-form');
        
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
		        'id' => 'propSavedName',
		        'maxlength' => 100,
                'value' => $proposal?$proposal->getPropSavedName():'',
		    ),
		));
		
		$this->add([
            'type' => 'Zend\Form\Element\Textarea',
            'name' => 'propDescription',
            'attributes' => array(
                'rows' => 4,
                'class'=>'form-control text-change',
                'id' => 'propDescription',
                'style' => 'display:none',
                'value' => $proposal?$proposal->getPropDescription():'',
            ),
        ]);

        $this->add([
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'propHiddenImage1',
            'attributes' => array(
                'id' => 'propImage1'
            ),
        ]);

        $this->add([
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'propHiddenImage2',
            'attributes' => array(
                'id' => 'propImage2'
            ),
        ]);

        $this->add([
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'propHiddenImage3',
            'attributes' => array(
                'id' => 'propImage3'
            ),
        ]);
    }
    
    /**
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
                            'max'      => 10000,
                        ),
                    ),
                
                ]
            ]
        ];
        
    }
    
    
  
}