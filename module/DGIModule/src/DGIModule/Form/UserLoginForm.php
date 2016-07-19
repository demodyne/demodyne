<?php
namespace DGIModule\Form;
use Zend\Form\Form;
class UserLoginForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('login');
        $this->setAttribute('method', 'post');
        $this->setAttribute('role', 'form');

        $this->add(array(
            'name' => 'username', 
            'attributes' => array(
                'type'  => 'text',
                'required' => 'required',
                'placeholder' => _('username')
            ),
            'options' => array(
                'label' => 'Username',
            ),
        ));
        $this->add(array(
            'name' => 'password', 
            'attributes' => array(
                'type'  => 'password',
                'required' => 'required',
                'placeholder' => _('password')
            ),
            'options' => array(
                'label' => 'Password',
            ),
        ));
        $this->add(array(
            'name' => 'rememberme',
			'type' => 'checkbox', 	
            'options' => array(
                'label' => 'Remember Me? ',
            ),
        ));	
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Login',
                'id' => 'submitbutton',
		        'class'=>'btn btn-warning',
            ),
        )); 
    }
}
