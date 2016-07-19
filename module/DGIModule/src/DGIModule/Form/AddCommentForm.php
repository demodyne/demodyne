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

class AddCommentForm extends Form  implements InputFilterProviderInterface
{
    public function __construct($entityManager = null)
    {
        parent::__construct('comment-form');
        $this->setAttribute('method', 'post');
        $this->setHydrator(new ClassMethods());

        $this->add([
            'type' => 'Zend\Form\Element\Textarea',
            'name' => 'comText',
            'attributes' => array(
                'id' => 'comText',
                'class'=>'form-control text-change',
                'style' => 'display:none'
            ),
            'options' => [
                'label' => 'Comment: ',
            ]
        ]);
       
    }
    
    public function getInputFilterSpecification()
    {
        return array(
            'comText'=> [
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags',
                        'options' => [
                            'allowTags' => ['b', 'i','u', 'br', 'strong', 'blockquote', 'span', 'div', 'p']
                        ]),
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
            ]
        );
        
    }
  
}
