<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Form;

use Zend\Form\Form;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use Zend\InputFilter\InputFilterProviderInterface;
use DGIModule\Entity\Proposal;
use Zend\Mvc\I18n\Translator;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;

class AddEditProposalForm extends Form implements InputFilterProviderInterface
{
    /** @var Translator $translator */
    private $translator;

    public function __construct($entityManager, Translator $translator, Proposal $proposal = null)
    {
        parent::__construct('proposal-form');

        $this->translator = $translator;

        $this->setAttribute('method', 'post');
        $this->setHydrator(new DoctrineHydrator($entityManager,'DGIModule\Entity\Proposal'));
        $this->setObject(new Proposal());
        $this->setAttribute('class', 'form-horizontal');
        $this->setAttribute('id', 'proposal-form');


        $this->add(array(
		    'name' => 'propSavedName',
            'attributes' => array(
		        'type'  => 'text',
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
                            'messages' => array(NotEmpty::IS_EMPTY  => $this->translator->translate("You must provide a proposal name.", 'DGIModule'))
                        ),
                    ),
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'max'      => 100,
                            'messages' => array(StringLength::TOO_LONG  => $this->translator->translate("The proposal name is more than %max% characters long", 'DGIModule'))
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
                        'name'    => 'NotEmpty',
                        'options' => array(
                            'messages' => array(NotEmpty::IS_EMPTY  => $this->translator->translate("You must provide a description for this proposal.", 'DGIModule'))
                        ),
                    ),
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'max'      => 10000,
                            'messages' => array(StringLength::TOO_LONG  => $this->translator->translate("The proposal description is more than %max% characters long", 'DGIModule'))
                        ),
                    ),
                ),
            ),
            
        );
    }

}