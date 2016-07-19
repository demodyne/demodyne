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
use DGIModule\Entity\Vote;

class AddEditVoteForm extends Form implements InputFilterProviderInterface
{
    public function __construct()
    {
        parent::__construct('voteForm');
        $this->setObject(new Vote());
        $this->setAttribute('method', 'post');
        $this->setHydrator(new ClassMethods());
        $this->setAttributes([
            'class' => 'form-horizontal',
            'role'=> 'form'        ]);

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
                    5=>_("Highly Favourable"), 
                    3=>_("Favourable"),  
                    0=>_("Neutral"), 
                    -3=>_("Unfavourable"), 
                    -5=>_("Opposed")
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
