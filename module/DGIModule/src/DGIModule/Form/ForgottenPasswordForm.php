<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2016 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Form;

use Zend\Form\Form;

class ForgottenPasswordForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('registration');
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'form-horizontal');
        
        $this->add(array(
            'name' => 'usrEmail',
            'attributes' => array(
                'type'  => 'email',
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'E-mail',
            ),
        ));	
		
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        )); 
    }
}
