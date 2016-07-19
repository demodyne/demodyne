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
use DGIModule\Entity\Proposal;

class AddEditProposalForm extends Form implements InputFilterProviderInterface
{
    public function __construct($entityManager = null)
    {
        parent::__construct('proposalForm');
        $this->setAttribute('method', 'post');
        $this->setHydrator(new ClassMethods());
        $this->setObject(new Proposal());
        $this->setAttribute('class', 'form-horizontal');

        $this->add(array(
		    'name' => 'propSavedName',
		    'attributes' => array(
		        'type'  => 'text',
		        'class'=>'form-control text-change',
		        'id' => 'propSavedName',
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
        return array(
            'propSavedName' => array(
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
                        'name'    => 'NotEmpty',
                        'options' => array(
                            'messages' => array(\Zend\Validator\NotEmpty::IS_EMPTY  => _("You must provide a proposal name."))
                        ),
                    ),
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'max'      => 50,
                            'messages' => array(\Zend\Validator\StringLength::TOO_LONG  => _("The proposal name is more than %max% characters long"))
                        ),
                    ),
                ),
            ),
            'propDescription' => array(
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
                            'max'      => 5000,
                        ),
                    ),
                ),
            ),
            
        );
    }
    
}
