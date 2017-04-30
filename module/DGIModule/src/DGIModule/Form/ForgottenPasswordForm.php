<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Form;

use Doctrine\ORM\EntityManager;
use DoctrineModule\Validator\ObjectExists;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Mvc\I18n\Translator;

class ForgottenPasswordForm extends Form  implements InputFilterProviderInterface
{

    private $entityManager;
    private $translator;

    public function __construct(EntityManager $entityManager, Translator $translator)
    {
        parent::__construct('registration');

        $this->entityManager = $entityManager;
        $this->translator = $translator;

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

    }

    public function getInputFilterSpecification()
    {
        return [
            'usrEmail'=> [
                'required'   => true,
                'validators' => array(
                    array(
                        'name' => 'EmailAddress'
                    ),
                    array(
                        'name'		=> 'DoctrineModule\Validator\ObjectExists',
                        'options' => array(
                            'object_repository' => $this->entityManager->getRepository('DGIModule\Entity\User'),
                            'fields'            => 'usrEmail',
                            'messages' => [
                                ObjectExists::ERROR_NO_OBJECT_FOUND  => $this->translator->translate("This email address is not registered on Demodyne", 'DGIModule')
                            ]
                        ),
                    ),
                ),
            ]
        ];
    }
}