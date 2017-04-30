<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Form;

use Zend\Form\Form;
use Zend\Hydrator\ClassMethods;
use Zend\InputFilter\InputFilterProviderInterface;
use DGIModule\Entity\Vote;
use Zend\Mvc\I18n\Translator;

class AddEditVoteForm extends Form implements InputFilterProviderInterface
{
    public function __construct(Translator $translator)
    {
        parent::__construct('voteForm');
        $this->setObject(new Vote());
        $this->setAttribute('method', 'post');
        $this->setHydrator(new ClassMethods());
        $this->setAttributes([
            'class' => 'form-horizontal',
            'role'=> 'form'
        ]);

        $this->add(array(
		    'name' => 'voteVote',
            'type' => 'Zend\Form\Element\Select',
		    'attributes' => array(
		        'type'  => 'select',
		        'required' => 'required',
		        'class'=>'form-control',
		        'id' => 'vote'
		    ),
            'options' => [
                'value_options' => [
                    5=>$translator->translate("Highly Favourable", 'DGIModule'),
                    3=>$translator->translate("Favourable", 'DGIModule'),
                    0=>$translator->translate("Neutral", 'DGIModule'),
                    -3=>$translator->translate("Unfavourable", 'DGIModule'),
                    -5=>$translator->translate("Opposed", 'DGIModule')
                ]
            ]
		));
		
		
		
    }
    
    public function getInputFilterSpecification()
    {
        return array(
            'voteVote' => [
                'required' => true
            ]
        );
    }
  
}