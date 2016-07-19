<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2016 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Form;

use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;

class AddEditNewsletterForm extends Form
{
    public function __construct($name = null, $options = [])
    {
        parent::__construct('newsletter-add-edit-newsletter-form');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->setHydrator(new ClassMethods());
        $this->setInputFilter(new AddEditNewsletterFilter());

        
        $this->add(array(
            'name' => 'nlName',
            'attributes' => array(
                'required' => 'required',
                'class'=>'form-control',
                'id' => 'nlName'
            ),
        ));
        
        $this->add([
            'type' => 'Zend\Form\Element\Textarea',
            'name' => 'nlDescription',
            'attributes' => array(
                'rows' => 3,
                'class'=>'form-control',
                'id' => 'nlDescription'
            ),
        ]);
        
        $this->add([
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'nlSendTo',
            'attributes' => array(
                'id' => 'nlSendTo',
                'value' => '0',
                
            ),
        ]);
        
        $this->add(array(
            'name' => 'nlSubject',
            'attributes' => array(
                'required' => 'required',
                'class'=>'form-control',
                'id' => 'nlSubject',
            ),
        ));
        
        $this->add(array(
            'name' => 'nlContact',
            'attributes' => array(
                'class'=>'form-control',
                'id' => 'nlContact',
            ),
        ));
        
        $this->add([
            'type' => 'Zend\Form\Element\Textarea',
            'name' => 'nlMessage',
            'attributes' => array(
                'rows' => 6,
                'class'=>'form-control',
                'id' => 'nlMessage',
                'maxlength' => 3000,
                'style' => 'display:none'
            ),
        ]);
        
        $this->add([
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'nlReply',
            'value' => '1',
            'attributes' => array(
            ),
        ]);
        
        
        $this->add([
            'type' => 'Zend\Form\Element\File',
            'name' => 'picture-file',
            'attributes' => array(
                'type' => 'file',
                'accept' => 'image/*',
                'class'=>'form-control',
                'id' => 'picture-file'
            ),
        ]);

		
        $this->add(array(
            'name' => 'nlUrl',
            'type' => 'Zend\Form\Element\Url',
            'attributes' => array(
                'class'=>'form-control',
                'id' => 'nlUrl'
            ),
        ));
       
    }
    
}
