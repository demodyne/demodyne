<?php
namespace DGIModule\Form;

use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Captcha\Image as CaptchaImage;

class UserRegistrationForm extends Form
{
    public function __construct($entityManager = null)
    {
        parent::__construct('registration');
        $this->setAttribute('method', 'post');
        $this->setHydrator(new ClassMethods());
        $this->setAttribute('class', 'form-horizontal');
        

        $this->add(array(
		    'name' => 'usrFirstname',
		    'attributes' => array(
		        'type'  => 'text',
		        'required' => 'required',
		        'class'=>'form-control text-change',
		        'id' => 'firstname',
		        'maxlength' => 50,
		        'size' => 50,
		    ),
            'options' => array(
                'label' => 'Firstname:',
                'placeholder' => 'Firstname'
            ),
		));
		
		$this->add(array(
		    'name' => 'usrLastname',
		    'attributes' => array(
		        'type'  => 'text',
		        'required' => 'required',
		        'class'=>'form-control text-change',
		        'id' => 'lastname',
		        'maxlength' => 50,
		        'size' => 50,
		    ),
		    'options' => array(
		        'label' => 'Lastname:',
		    ),
		));
		
		 $this->add(array(
            'name' => 'usrName',
            'attributes' => array(
                'type'  => 'text',
                'class'=>'form-control text-change',
                'id' => 'username',
                'maxlength' => 50,
                'size' => 50,
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'Username',
            ),
        ));
		
        $this->add(array(
            'name' => 'usrEmail',
            'attributes' => array(
                'type'  => 'email',
                'id' => 'email',
                'class'=>'form-control text-change',
                'maxlength' => 50,
                'size' => 50,
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'E-mail',
            ),
        ));	
		
        $this->add(array(
            'name' => 'usrPassword',
            'attributes' => array(
                'type'  => 'password',
                'id' => 'password',
                'class'=>'form-control text-change',
                'maxlength' => 12,
                'size' => 50,
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'Password',
            ),
        ));
		
        $this->add(array(
            'name' => 'usrPasswordConfirm',
            'attributes' => array(
                'type'  => 'password',
                'id' => 'password2',
                'class'=>'form-control text-change',
                'maxlength' => 12,
                'size' => 50,
                'required' => 'required',
            ),
            'options' => array(
                'label' => 'Confirm Password',
            ),
        ));	

        $this->add([
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'name' => 'country',
            'value' => 73,
            'attributes' => array(
                'required' => true,
                'class'=>'form-control text-change',
                'id' => 'country'
            ),
            'options' => [
                'label' => 'Country: ',
                'object_manager' => $entityManager,
                'target_class' => '\DGIModule\Entity\Country',
                'label_generator' => function ($country) {
                    return $country->getCountryName();
                },
                //'empty_option' => '--- please choose ---',
                'is_method' => true,
                'required' => false,
                'find_method' => array(
                    'name' => 'getAllCountries',
                    'params' => array(
                        'criteria' => array(),
                    ),
                ),
            ]
        ]);
		
		
    }
  
    

}