<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2016 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Form;

use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;

class UserEditInfoForm extends Form
{
    public function __construct($name = null, $options = [])
    {
        parent::__construct('edit-info');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->setHydrator(new ClassMethods());
        $this->setInputFilter(new UserEditInfoFilter());

        $this->add(array(
            'name' => 'usrOldPassword',
            'attributes' => array(
                'type'  => 'password',
                'id' => 'old-password',
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
                'id' => 'password',
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
                'id' => 'password2',
                'class'=>'form-control text-change',
                'maxlength' => 12,
                'size' => 50,
            ),
            'options' => array(
                'label' => 'Confirm new password',
            ),
        ));	
        
        $this->add([
            'type' => 'Zend\Form\Element\Textarea',
            'name' => 'usrPresentation',
            'attributes' => array(
                'rows' => 4,
                'class'=>'form-control text-change',
                'id' => 'usrPresentation'
            ),
            'options' => [
                'label' => 'Presentation: ',
            ]
        ]);
        
        $this->add([
            'type' => 'Zend\Form\Element\File',
            'name' => 'usrPicture',
            'attributes' => array(
                'type' => 'file',
                'accept' => 'image/*',
                'id' => 'usrPicture'
            ),
            'options' => [
                'label' => 'Change picture: ',
            ]
        ]);

		
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
