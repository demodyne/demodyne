<?php 
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2016 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Form;

use DGIModule\Entity\Event;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;
use DGIModule\Entity\Hydrator\Strategy\DateTimeStrategy;

class AddEditEventForm extends Form implements InputFilterProviderInterface
 {
     public function __construct()
     {
         parent::__construct('event-form');
         $this
             ->setObject(new Event())
             ->setAttribute('name', 'event')
             ->setOption( 'use_as_base_fieldset', true)
             ->setOption( 'use_input_filter_defaults', false)
         ;
             $this->setAttribute('enctype', 'multipart/form-data');
             $this->setAttribute('method', 'post');
             $hydrator = new ClassMethods();
             $hydrator->addStrategy('eventStartDate', new DateTimeStrategy());
             $hydrator->addStrategy('eventEndDate', new DateTimeStrategy());
             $this->setHydrator($hydrator);

             $this->add(array(
                 'name' => 'eventName',
                 'attributes' => array(
                     'type'  => 'text',
                     'required' => 'required',
                     'class'=>'form-control text-change',
                     'id' => 'eventName',
                     'maxlength' => 200,
                     'placeholder' => 'Event name'
                 ),
             ));

             $this->add([
                 'type' => 'Zend\Form\Element\Textarea',
                 'name' => 'eventDescription',
                 'attributes' => array(
                     'rows' => 6,
                     'class'=>'form-control text-change',
                     'id' => 'eventDescription',
                     'placeholder' => 'Enter event description',
                     'style' => 'display:none'
                 ),
             ]);
             $this->add(array(
                 'name' => 'eventLink',
                 'attributes' => array(
                     'type'  => 'text',
                     'required' => 'required',
                     'class'=>'form-control text-change',
                     'id' => 'eventLink',
                     'maxlength' => 1000,
                     'placeholder' => 'Event url'
                 ),
             ));
             $this->add([
                 'type' => 'Zend\Form\Element\File',
                 'name' => 'eventImage',
                 'attributes' => array(
                     'type' => 'file',
                     'accept' => 'image/*',
                     'id' => 'eventImage'
                 ),
             ]);
             $this->add(array(
                 'name' => 'eventStartDate',
                 'type'  => 'Zend\Form\Element\DateTime',
                 'options' => array(
                     'label' => 'Start date: ',
                     'format' => 'd/m/Y H:i',
                 ),
                 'attributes' => [
                     'type'  => 'text',
                     'id' => 'eventStartDate',
                     'class'=>'form-control text-change',
                     'step' => '1', // days; default step interval is 1 day
                     'placeholder' => 'DD/MM/YYYY HH:mm'
                 ]
             ));
             $this->add(array(
                 'name' => 'eventEndDate',
                 'type'  => 'Zend\Form\Element\DateTime',
                 'options' => array(
                     'label' => 'End date: ',
                     'format' => 'd/m/Y H:i',
                 ),
                 'attributes' => [
                     'type'  => 'text',
                     'id' => 'eventEndDate',
                     'class'=>'form-control text-change',
                     'step' => '1', // days; default step interval is 1 day
                     'placeholder' => 'DD/MM/YYYY HH:mm'
                 ]
             ));
             $this->add(array(
                 'name' => 'eventLocation',
                 'attributes' => array(
                     'type'  => 'text',
                     'required' => 'required',
                     'class'=>'form-control text-change',
                     'id' => 'eventLocation',
                     'maxlength' => 500,
                     'placeholder' => 'Event location'
                 ),
             ));
     }
     /**
      * @return array
      */
     public function getInputFilterSpecification()
     {
         return array(
             'eventName' => array(
                 'required' => true,
                 'filters'  => array(
                     array('name' => 'StripTags'),
                     array('name' => 'StringTrim'),
                 ),
                 'validators' => array(
                     array(
                         'name'    => 'NotEmpty',
                         'options' => array(
                             'messages' => array(\Zend\Validator\NotEmpty::IS_EMPTY  => _("You must provide a name for the event."))
                         ),
                     ),
                     array(
                         'name'    => 'StringLength',
                         'options' => array(
                             'encoding' => 'UTF-8',
                             'max'      => 200,
                             'messages' => array(\Zend\Validator\StringLength::TOO_LONG  => _("The location is more than %max% characters long"))
                         ),
                     ),
                 ),
             ),
             'eventDescription' => array(
                 'required' => true,
                 'filters' => array(
                     array('name' => 'StripTags', 
				    'options' => [
				        'allowTags' => ['b','u', 'i', 'br', 'strong', 'blockquote', 'span', 'div', 'p']
				    ]),
                 ),
                 'validators' => array(
                      array(
                         'name'    => 'NotEmpty',
                         'options' => array(
                             'messages' => array(\Zend\Validator\NotEmpty::IS_EMPTY  => _("You must provide a description for the event."))
                         ),
                     ),
                     array(
                         'name'    => 'StringLength',
                         'options' => array(
                             'encoding' => 'UTF-8',
                             'max'      => 5000,
                             'messages' => array(\Zend\Validator\StringLength::TOO_LONG  => _("The event description is more than %max% characters long"))
                         ),
                     ),
                 ),
             ),
             'eventStartDate' => array(
                 'required' => true,
                 'validators' => array(
                     array(
                         'name'    => 'NotEmpty',
                         'options' => array(
                             'messages' => array(\Zend\Validator\NotEmpty::IS_EMPTY  => _("You must provide the start date."))
                         ),
                     ),
                     array(
                         'name'    => 'Date',
                         'options' => array(
                             'format' => 'd/m/Y H:i',
                             'messages' => array(\Zend\Validator\Date::INVALID_DATE  => _("The start date does not appear to be a valid date"))
                         ),
                     ),
                 ),
             ), 
             'eventEndDate' => array(
                 'required' => true,
                 'validators' => array(
                     array(
                         'name'    => 'NotEmpty',
                         'options' => array(
                             'messages' => array(\Zend\Validator\NotEmpty::IS_EMPTY  => _("You must provide the end date."))
                         ),
                     ),
                     array(
                         'name'    => 'Date',
                         'options' => array(
                             'format' => 'd/m/Y H:i',
                             'messages' => array(\Zend\Validator\Date::INVALID_DATE  => _("The end date does not appear to be a valid date"))
                         ),
                     ),
                 ),
             ), 
             'eventLocation' => array(
                 'required' => true,
                 'filters'  => array(
                     array('name' => 'StripTags'),
                     array('name' => 'StringTrim'),
                 ),
                 'validators' => array(
                     array(
                         'name'    => 'NotEmpty',
                         'options' => array(
                             'messages' => array(\Zend\Validator\NotEmpty::IS_EMPTY  => _("You must provide a location."))
                         ),
                     ),
                     array(
                         'name'    => 'StringLength',
                         'options' => array(
                             'encoding' => 'UTF-8',
                             'max'      => 500,
                             'messages' => array(\Zend\Validator\StringLength::TOO_LONG  => _("The location is more than %max% characters long"))
                         ),
                     ),
                 ),
             ),
             'eventLink' => array(
                 'required' => false,
                 'filters' => array(
                     array('name' => 'StripTags'),
                 ),
                 'validators' => array(
                      array(
                         'name'    => 'NotEmpty',
                         'options' => array(
                             'messages' => array(\Zend\Validator\NotEmpty::IS_EMPTY  => _("You must provide an URL."))
                         ),
                     ),
                      array(
                         'name' => 'Uri',
                         'options' => array(
                             'allowAbsolute' => true,
                             'allowRelative' => false,
                             'messages' => array(\Zend\Validator\Uri::NOT_URI  => _("The address does not appear to be a valid URL (should start with 'http(s)://')"))
                         ),
                     ),
                     array(
                         'name' => 'StringLength',
                         'options' => array(
                             'encoding' => 'UTF-8',
                             'max' => 2000,
                             'messages' => array(\Zend\Validator\StringLength::TOO_LONG  => _("The URL is more than %max% characters long"))
                         ),
                     ),
                 ),
             ),
             'eventImage' => array(
                 'required' => false,
                 'validators' => array(
                     array(
                         'name'    => 'NotEmpty',
                         'options' => array(
                             'messages' => array(\Zend\Validator\NotEmpty::IS_EMPTY  => _("You must provide an image file."))
                         ),
                     ),
                     array(
                         'name'    => 'Zend\Validator\File\UploadFile',
                         'options' => array(
                             'messages' => array(
                                 \Zend\Validator\File\UploadFile::NO_FILE  => _("The banner image was not uploaded"),
                             )
                         ),
                     ),
                     array(
                         'name'    => 'Zend\Validator\File\Size',
                         'options' => array(
                             'max'      => 500000, // 500Ko
                             'messages' => array(
                                 \Zend\Validator\File\Size::TOO_BIG  => _("Maximum allowed size for file is '%max%' but '%size%' detected"),
                                 \Zend\Validator\File\Size::NOT_FOUND  => _("File is not readable or does not exist"),
                             )
                         ),
                     ),
                 ),
             )
         );
     }
 }
 