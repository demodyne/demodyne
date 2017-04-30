<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Form;

use Doctrine\ORM\EntityManager;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Mvc\I18n\Translator;

class UserLoginForm extends Form implements InputFilterProviderInterface
{
    /** @var EntityManager $entityManager */
    private $entityManager;
    /** @var Translator $translator */
    private $translator;

    public function __construct(EntityManager $entityManager, Translator $translator)
    {
        parent::__construct('login');

        $this->entityManager = $entityManager;
        $this->translator = $translator;

        $this->setAttribute('method', 'post');
        $this->setAttribute('role', 'form');
        $this->add(array(
            'name' => 'username',
            'attributes' => array(
                'type'  => 'text',
                'required' => 'required',
                'placeholder' => $this->translator->translate('username', 'DGIModule')
            ),
        ));
        $this->add(array(
            'name' => 'password',
            'attributes' => array(
                'type'  => 'password',
                'required' => 'required',
                'placeholder' => $this->translator->translate('password', 'DGIModule')
            ),
        ));
       /* $this->add(array(
            'name' => 'rememberme',
			'type' => 'checkbox', // 'Zend\Form\Element\Checkbox',			
            'options' => array(
                'label' => 'Remember Me? ',
//				'checked_value' => 'true', without value here will be 1
//				'unchecked_value' => 'false', // witll be 1
            ),
        ));	*/
        
       /* $this->add(array(
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'userCSRF',
            'options' => array(
                'csrf_options' => array(
                    'timeout' => 300
                )
            )
        ));*/
    }

    public function getInputFilterSpecification()
    {
        return [
            'username' => [
                'required' => true,
                'filters'  => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    [
                        'name' => 'NotEmpty',
                        'options' => [
                            'messages' => [
                                'isEmpty' => $this->translator->translate('Please enter a username', 'DGIModule'),
                            ],
                        ],
                    ],
                    [
                        'name'		=> 'DoctrineModule\Validator\ObjectExists',
                        'options' => [
                            'object_repository' => $this->entityManager->getRepository('DGIModule\Entity\User'),
                            'fields'            => 'usrName',
                            'messages' => [
                                'noObjectFound' => $this->translator->translate('Username not registered', 'DGIModule'),
                            ],
                        ],

                    ],
                ],
            ],

		    'password' => [
                'required' => true,
                'validators' => [
                    [
                        'name' => 'NotEmpty',
                        'options' => [
                            'messages' => [
                                'isEmpty' => $this->translator->translate('Please enter a password', 'DGIModule'),
                            ],
                        ],
                    ],
                ],
            ]
        ];
    }
}