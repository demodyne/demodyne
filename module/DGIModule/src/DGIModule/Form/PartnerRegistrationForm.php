<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2016 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Form;

use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Captcha\Image as CaptchaImage;
use DGIModule\Entity\User;

class PartnerRegistrationForm extends Form
{
    public function __construct($entityManager = null)
    {
        parent::__construct('registration');
        $this->setAttribute('method', 'post');
        $this->setHydrator(new ClassMethods(false));
        $this->setObject(new User());
        $this->setAttribute('class', 'form-horizontal');
        
        $this->add(array(
            'type' => 'DGIModule\Form\PartnerFieldset',
            'name' => 'partner',
        ));

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
                'maxlength' => 20,
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
		
		$this->add(array(
		    'name' => 'usrPostalcode',
		    'attributes' => array(
		        'type'  => 'text',
		        'required' => 'required',
		        'class'=>'form-control text-change',
		        'id' => 'usrPostalcode',
		        'maxlength' => 5,
		        'size' => 5,
		    ),
		    'options' => array(
		        'label' => 'Postalcode:',
		    ),
		));
		
		$this->add(array(
		    'name' => 'city',
		    'type'  => 'Zend\Form\Element\Select',
		    'options' => array(
		        'label' => 'City: ',
		        'empty_option' => '--- please write your postalcode first ---',
		        'disable_inarray_validator' => true,
		    ),
		    'attributes' => [
		        'id' => 'city',
		        'required' => 'required',
		        'class'=>'form-control text-change',
		        'required' => 'required',
		    ]
		));
		
		$this->add(array(
		    'name' => 'usrCity',
		    'attributes' => array(
		        'type'  => 'text',
		        'required' => 'required',
		        'class'=>'form-control text-change',
		        'id' => 'usrCity',
		        'maxlength' => 50,
		        'size' => 50,
		        'readonly' => 'readonly'
		    ),
		));
		
		$this->add(array(
		    'name' => 'usrPhone',
		    'type'  => 'DGIModule\Form\Element\Phone',
		    'options' => array(
		        'label' => 'Phone:',
		    ),
		    'attributes' => [
		        'id' => 'phone',
		        'maxlength' => 12,
		        'size' => 12,
		        'class'=>'form-control text-change',
		    ]
		));
		
		$dirdata = './public/img';
		
		$captchaImage = new CaptchaImage(  array(
		    'font' => '/data/fonts/arialnb.ttf',
		    'width' => 250,
		    'height' => 100,
		    'dotNoiseLevel' => 40,
		    'lineNoiseLevel' => 3)
		);
		$captchaImage->setImgDir($dirdata.'/captcha');
		$captchaImage->setImgUrl('/captcha');
		
		$this->add(array(
		    'type' => 'Zend\Form\Element\Captcha',
		    'name' => 'captcha',
		    'options' => array(
		        'label' => 'Please verify you are human:',
		        'captcha' => $captchaImage
		    ),
		    'attributes' => [
		        'id' => 'captcha',
		        'class'=>'form-control text-change',
		    ]
		));
		
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        )); 
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'userCSRF',
            'options' => array(
                'csrf_options' => array(
                    'timeout' => 1200
                )
            )
        ));
    }
    
}