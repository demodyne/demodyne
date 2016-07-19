<?php 
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2016 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Form;

 use Zend\Form\Form;
 use DGIModule\Entity\Partner;
 use Zend\InputFilter\InputFilterProviderInterface;
 use Zend\Stdlib\Hydrator\ClassMethods as ClassMethodsHydrator;

 class AdministrationPresentationForm extends Form implements InputFilterProviderInterface
 {
     public function __construct($serviceLocator = null)
     {
         parent::__construct('user-profile-partner-presentation-form');

         $this->setAttribute('method', 'post');
         $this->setAttribute('class', 'form-horizontal');
         $this
             ->setHydrator(new ClassMethodsHydrator(false))
             ->setObject(new Partner())
         ;
             $entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');

         $this->add(array(
             'name' => 'partName',
             'options' => array(
                 'label' => 'Name:',
             ),
             'attributes' => array(
                 'required' => 'required',
                 'class'=>'form-control partner-presentation-text-change',
             ),
         ));
         
         $this->add(array(
             'name' => 'partSiret',
             'options' => array(
                 'label' => 'SIRET:',
             ),
             'attributes' => array(
                 'required' => 'required',
                 'class'=>'form-control partner-presentation-text-change',
             ),
         ));
         
         $this->add(array(
             'name' => 'partAddress',
             'options' => array(
                 'label' => 'Address:',
             ),
             'attributes' => array(
                 'required' => 'required',
                 'class'=>'form-control partner-presentation-text-change',
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
                 'class'=>'form-control partner-presentation-text-change',
             ]
         ));
         
         $this->add([
             'type' => 'Zend\Form\Element\Textarea',
             'name' => 'partPresentation',
             'attributes' => array(
                 'required' => 'required',
                 'rows' => 3,
                 'class'=>'form-control partner-presentation-text-change',
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
                     1 => "<200k€",
                     2 => "200k€-2M€",
                     3 => "2M€-10M€",
                     4 => ">10M€",
                 ],
             ),
             'attributes' => [
                 'id' => 'partEmployees',
                 'class'=>'form-control partner-presentation-text-change',
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
                 'required' => 'required',
                 'class'=>'form-control partner-presentation-text-change',
             ),
         ));
         
         $this->add([
             'type' => 'DoctrineModule\Form\Element\ObjectSelect',
             'name' => 'country',
             'value' => null,
             'attributes' => array(
                 'required' => true,
                 'class'=>'form-control partner-presentation-text-change',
                 'id' => 'country',
                 'disabled' => 'disabled'
             ),
             'options' => [
                 'label' => 'Country: ',
                 'object_manager' => $entityManager,
                 'target_class' => '\DGIModule\Entity\Country',
                 'label_generator' => function ($country) {
                 return $country->getCountryName();
                 },
                 //  'empty_option' => '--- please choose ---',
                 'is_method' => true,
                 'required' => true,
                 'find_method' => array(
                     'name' => 'getAllCountries',
                     'params' => array(
                         'criteria' => array(),
                     ),
                 ),
                 ]
                 ]);
         
         
         $this->add(array(
             'name' => 'usrPostalcode',
             'attributes' => array(
                 'type'  => 'text',
                 'required' => 'required',
                 'class'=>'form-control partner-presentation-text-change',
                 'id' => 'usrPostalcode',
                 'maxlength' => 5,
                 'size' => 5,
             ),
             'options' => array(
                 'label' => 'Postalcode:',
             ),
         ));
         
         $authService = $serviceLocator->get('Zend\Authentication\AuthenticationService');
         
         $user = $authService->getIdentity();
         
         $this->add([
             'type' => 'DoctrineModule\Form\Element\ObjectSelect',
             'name' => 'city',
             'value' => $user->getCity(),
             'attributes' => array(
                 'required' => true,
                 'class'=>'form-control partner-presentation-text-change',
                 'id' => 'city'
             ),
             'options' => [
                 'label' => 'City: ',
                 'disable_inarray_validator' => true,
                 'object_manager' => $entityManager,
                 'target_class' => '\DGIModule\Entity\City',
                 'label_generator' => function ($city) {
                 return $city->getCityName();
                 },
                 'is_method' => true,
                 'required' => true,
                 'find_method' => array(
                     'name' => 'getCities',
                     'params' => array(
                         'postalcode'=>$user->getCity()->getCityPostalcode(),
                         'country' => $user->getCountry()
                     ),
                 ),
             ]
         ]);
         
     }

     /**
      * @return array
      */
     public function getInputFilterSpecification()
     {
         return array(
             
             'usrPostalcode' => array(
                 'required' => true,
                 'filters' => array(
                     array('name' => 'StripTags'),
                     array('name' => 'StringTrim'),
                 ),
                 'validators' => array(
                     array(
                         'name' => 'StringLength',
                         'options' => array(
                             'encoding' => 'UTF-8',
                             'min' => 2,
                             'max' => 5
                         ),
                     ),
                     array(
                         'name' => 'PostCode',
                         'options' => array(
                             'locale' => 'fr_FR',
                         ),
                     )
                 ),
             ),
             
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
                             //'min'      => 0,
                             'max'      => 500,
                         ),
                     ),
                 
                 ),
             ),

         );
     }
 }
 