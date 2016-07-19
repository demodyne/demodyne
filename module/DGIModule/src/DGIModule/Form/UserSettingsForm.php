<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2016 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Form;

use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;

class UserSettingsForm extends Form
{
    public function __construct($entityManager = null)
    {
        parent::__construct('user-settings');
        $this->setAttribute('method', 'post');
        $this->setHydrator(new ClassMethods());
        $this->setInputFilter(new UserSettingsFilter());
        
        $this->add([
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'language',
            'attributes' => array(
                'id' => 'language',
                'value' => '0',
                
            ),
        ]);
        
        $this->add(array(
            'name' => 'usrOldPassword',
            'attributes' => array(
                'type'  => 'password',
                'id' => 'usrOldPassword',
                'class'=>'form-control text-change',
                'maxlength' => 12,
                'size' => 50,
            ),
            'options' => array(
                'label' => 'Current password',
            ),
        ));
		
        $this->add(array(
            'name' => 'usrNewPassword',
            'attributes' => array(
                'type'  => 'password',
                'id' => 'usrNewPassword',
                'class'=>'form-control text-change',
                'maxlength' => 12,
                'size' => 50,
            ),
            'options' => array(
                'label' => 'New password',
            ),
        ));
		
        $this->add(array(
            'name' => 'usrNewPasswordConfirm',
            'attributes' => array(
                'type'  => 'password',
                'id' => 'usrNewPasswordConfirm',
                'class'=>'form-control text-change',
                'maxlength' => 12,
                'size' => 50,
            ),
            'options' => array(
                'label' => 'Confirm new password',
            ),
        ));	

		
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Change password',
                'id' => 'submitbutton',
            ),
        )); 
        
    }
  
}
