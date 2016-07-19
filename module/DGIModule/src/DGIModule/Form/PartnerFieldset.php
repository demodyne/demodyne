<?php 
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2016 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Form;

use DGIModule\Entity\Partner;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

 class PartnerFieldset extends Fieldset implements InputFilterProviderInterface
 {
     public function __construct()
     {
         parent::__construct('partner');

         $this
             ->setHydrator(new ClassMethodsHydrator(false))
             ->setObject(new Partner())
         ;

         $this->add(array(
             'name' => 'partName',
             'options' => array(
                 'label' => 'Name:',
             ),
             'attributes' => array(
                 'required' => 'required',
                 'class'=>'form-control text-change',
             ),
         ));
         
         $this->add(array(
             'name' => 'partSiret',
             'options' => array(
                 'label' => 'SIRET:',
             ),
             'attributes' => array(
                 'required' => 'required',
                 'class'=>'form-control text-change',
             ),
         ));
         
         $this->add(array(
             'name' => 'partAddress',
             'options' => array(
                 'label' => 'Address:',
             ),
             'attributes' => array(
                 'required' => 'required',
                 'class'=>'form-control text-change',
             ),
         ));
         
         $this->add(array(
             'name' => 'partEmployees',
             'type'  => 'Zend\Form\Element\Select',
             'options' => array(
                 'label' => 'Gendre: ',
                 'value_options' => [
                     1 => "1-10", 
                     2 => "11-50",
                     3 => "51-500",
                     4 => ">500",
                 ],
             ),
             'attributes' => [
                 'id' => 'partEmployees',
                 'class'=>'form-control text-change',
             ]
         ));
         
         $this->add([
             'type' => 'Zend\Form\Element\Textarea',
             'name' => 'partActivity',
             'attributes' => array(
                 'required' => 'required',
                 'rows' => 2,
                 'class'=>'form-control text-change',
                 'id' => 'partActivity'
             ),
             'options' => [
                 'label' => 'Activity: ',
             ]
         ]);
         
         $this->add([
             'type' => 'Zend\Form\Element\Textarea',
             'name' => 'partPresentation',
             'attributes' => array(
                 'required' => 'required',
                 'rows' => 8,
                 'class'=>'form-control text-change',
                 'id' => 'partPresentation'
             ),
             'options' => [
                 'label' => 'Presentation: ',
             ]
         ]);
         
         $this->add(array(
             'name' => 'partGain',
             'type'  => 'Zend\Form\Element\Select',
             'options' => array(
                 'label' => 'Gain: ',
                 'value_options' => [
                     1 => "<200k�",
                     2 => "200k�-2M�",
                     3 => "2M�-10M�",
                     4 => ">10M�",
                 ],
             ),
             'attributes' => [
                 'id' => 'partEmployees',
                 'class'=>'form-control text-change',
                 'required' => 'required',
             ]
         ));

         $this->add(array(
             'name' => 'partWebsite',
             'type' => 'Zend\Form\Element\Url',
             'options' => array(
                 'label' => 'Website:',
             ),
             'attributes' => array(
                 'class'=>'form-control text-change',
             ),
         ));
         
         $this->add(array(
             'name' => 'partFax',
             'type'  => 'DGIModule\Form\Element\Phone',
             'options' => array(
                 'label' => 'Fax:',
             ),
             'attributes' => [
                 'id' => 'fax',
                 'maxlength' => 12,
                 'size' => 12,
                 'class'=>'form-control text-change',
             ]
         ));
     }

     /**
      * @return array
      */
     public function getInputFilterSpecification()
     {
         return array(
             'partName' => array(
                 'required' => true,
                 'filters'  => array(
                     array('name' => 'StripTags'),
                     array('name' => 'StringTrim'),
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
             ),
             'partSiret' => array(
                 'required' => true,
                 'filters'  => array(
                     array('name' => 'StripTags'),
                     array('name' => 'StringTrim'),
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
             ),
             'partAddress' => array(
                 'required' => true,
                 'filters'  => array(
                     array('name' => 'StripTags'),
                     array('name' => 'StringTrim'),
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
             ),
             'partEmployees' => array(
                 'required' => false,
             ),
             'partActivity' => array(
                 'required' => true,
                 'filters'  => array(
                     array('name' => 'StripTags'),
                     array('name' => 'StringTrim'),
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
             ),
             'partPresentation' => array(
                 'required' => true,
                 'filters'  => array(
                     array('name' => 'StripTags'),
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
             ),
             'partGain' => array(
                 'required' => true,
             ),
             'partWebsite' => array(
                 'required' => false,
                 'filters'  => array(
                     array('name' => 'StripTags'),
                     array('name' => 'StringTrim'),
                 ),
                 'validators' => array(
                     array(
                         'name'    => 'StringLength',
                         'options' => array(
                             'encoding' => 'UTF-8',
                             'max'      => 500,
                         ),
                     ),
                 
                 ),
             ),
             'partFax' => array(
                 'required' => false,
                 'filters'  => array(
                     array('name' => 'StripTags'),
                     array('name' => 'StringTrim'),
                 ),
                 'validators' => array(
                     array(
                         'name'    => 'StringLength',
                         'options' => array(
                             'encoding' => 'UTF-8',
                             'min'      => 10,
                             'max'      => 12,
                         ),
                     ),
                 
                 ),
             ),
         );
     }
 }
 