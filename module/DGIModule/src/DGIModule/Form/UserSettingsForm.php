<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Form;

use DGIModule\Entity\User;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Mvc\I18n\Translator;
use Zend\Validator\Identical;
use Zend\Validator\StringLength;

class UserSettingsForm extends Form implements InputFilterProviderInterface
{
    /** @var Translator $translator */
    protected $translator;
    protected $config;

    public function __construct(User $user, array $config, Translator $translator)
    {
        parent::__construct('user-settings');

        $this->config = $config;
        $this->translator = $translator;

        $this->setAttribute('method', 'post');

        $this->add([
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'language',
            'attributes' => array(
                'id' => 'language',
                'value' => '0',

            ),
        ]);

        $freq = [];
        foreach ($config['demodyne']['email']['digest'] as $item => $value) {
            $freq[$value] = $translator->translate(ucfirst($item), 'DGIModule');
        }

        $style = 'margin-right:2px!important;margin-left:4px!important;';

        $this->add(array(
            'name' => 'freq',
            'type'  => 'Zend\Form\Element\Radio',
            'options' => array(
                'value_options' => $freq,
            ),
            'attributes' => [
                'id' => 'freq',
                'value' => $user->getDigest()->getDigestFrequency(),
                'class'=>'right10 left10',
                'style' => $style
            ]
        ));

        $this->add(array(
            'name' => 'highlight',
            'type'  => 'Zend\Form\Element\Checkbox',
            'options' => array(
                'checked_value' => 1,
            ),
            'attributes' => [
                'id' => 'highlight',
                'value' => $user->getDigest()->getDigestHighligts(),
                'class'=>'right10 left10',
                'style' => 'padding-right:10px!important;',
                'disabled' => true
            ]
        ));

        $this->add(array(
            'name' => 'proposals',
            'type'  => 'Zend\Form\Element\Checkbox',
            'options' => array(
                'checked_value' => 1,
            ),
            'attributes' => [
                'id' => 'proposals',
                'value' => $user->getDigest()->getDigestPropProg(),
                'class'=>'right10 left10',
                'style' => 'padding-right:10px!important;'
            ]
        ));

        $this->add(array(
            'name' => 'events',
            'type'  => 'Zend\Form\Element\Checkbox',
            'options' => array(
                'checked_value' => 1,
            ),
            'attributes' => [
                'id' => 'events',
                'value' => $user->getDigest()->getDigestEvent(),
                'class'=>'right10 left10',
                'style' => 'padding-right:10px!important;'
            ]
        ));

        $this->add(array(
            'name' => 'academy',
            'type'  => 'Zend\Form\Element\Checkbox',
            'options' => array(
                'checked_value' => 1,
            ),
            'attributes' => [
                'id' => 'academy',
                'value' => $user->getDigest()->getDigestAcademy(),
                'class'=>'right10 left10',
                'style' => 'padding-right:10px!important;'
            ]
        ));

        $alertFreq = [];
        foreach ($config['demodyne']['email']['alert'] as $item => $value) {
            $alertFreq[$value] = $translator->translate(ucfirst($item), 'DGIModule');
        }

        $this->add(array(
            'name' => 'privateMessagesAlert',
            'type'  => 'Zend\Form\Element\Radio',
            'options' => array(
                'value_options' => $alertFreq,
            ),
            'attributes' => [
                'id' => 'freq',
                'value' => $user->getDigest()->getDigestAlertPrivate(),
                'class'=>'right10 left10',
                'style' => $style
            ]
        ));

        $this->add(array(
            'name' => 'commentsAlert',
            'type'  => 'Zend\Form\Element\Radio',
            'options' => array(
                'value_options' => $alertFreq,
            ),
            'attributes' => [
                'id' => 'freq',
                'value' => $user->getDigest()->getDigestAlertComments(),
                'class'=>'right10 left10',
                'style' => $style
            ]
        ));

        $this->add(array(
            'name' => 'updatesAlert',
            'type'  => 'Zend\Form\Element\Radio',
            'options' => array(
                'value_options' => $alertFreq,
            ),
            'attributes' => [
                'id' => 'freq',
                'value' => $user->getDigest()->getDigestAlertUpdates(),
                'class'=>'right10 left10',
                'style' => $style
            ]
        ));


        $remainder = [];
        foreach ($config['demodyne']['email']['remainder'] as $item => $value) {
            $remainder[$value] = $translator->translate(ucfirst($item), 'DGIModule');
        }

        $alertEvent = $user->getDigest()->getDigestAlertEvent();
        $alertEventValue=[];$n=0;
        while ($alertEvent>0) {
            if ($alertEvent & 1) {
                $alertEventValue[] = $n;
            }
            $n++;
            $alertEvent>>=1;
        }

        $this->add(array(
            'name' => 'eventsAlert',
            'type'  => 'Zend\Form\Element\MultiCheckbox',
            'options' => array(
                'value_options' => $remainder,
            ),
            'attributes' => [
                'id' => 'freq',
                'value' => $alertEventValue,
                'class'=>'right10 left10',
                'style' => $style
            ]
        ));

        $statusAlert = $user->getDigest()->getDigestAlertStatus();
        $statusAlertValue=[];$n=0;
        while ($statusAlert>0) {
            if ($statusAlert & 1) {
                $statusAlertValue[] = $n;
            }
            $n++;
            $statusAlert>>=1;
        }

        $this->add(array(
            'name' => 'statusAlert',
            'type'  => 'Zend\Form\Element\MultiCheckbox',
            'options' => array(
                'value_options' => $remainder,
            ),
            'attributes' => [
                'id' => 'freq',
                'value' => $statusAlertValue,
                'class'=>'right10 left10',
                'style' => $style
            ]
        ));

        $this->add(array(
            'name' => 'usrOldPassword',
            'attributes' => array(
                'type'  => 'password',
                'id' => 'usrOldPassword',
                'class'=>'form-control text-change',
                'maxlength' => 12,
                'size' => 50,
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
        ));
    }

    public function getInputFilterSpecification()
    {
        return array(
            'usrOldPassword' => [
                'required' => false,
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 6,
                            'max'      => 12,
                        ),
                    ),
                ),
            ],
            'usrNewPassword'=> [
                'required' => false,
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 6,
                            'max'      => 12,
                            'messages' => array(
                                StringLength::TOO_LONG  => $this->translator->translate("The password must be between %min% and %max% characters long", 'DGIModule'),
                                StringLength::TOO_SHORT  => $this->translator->translate("The password must be between %min% and %max% characters long", 'DGIModule')
                            )
                        ),
                    ),
                ),
            ],
            'highlight' => [
                'required' => false,
            ],
            'freq' => [
                'required' => false,
            ],
            'proposals' => [
                'required' => false,
            ],
            'events' => [
                'required' => false,
            ],
            'eventsAlert' => [
                'required' => false,
            ],
            'statusAlert' => [
                'required' => false,
            ],
            'updatesAlert' => [
                'required' => false,
            ],
            'commentsAlert' => [
                'required' => false,
            ],
            'eventsStatus' => [
                'required' => false,
            ],
            'privateMessagesAlert' => [
                'required' => false,
            ],
            'academy' => [
                'required' => false,
            ],
            'usrNewPasswordConfirm' => [
                'required' => false,
                'validators' => array(
                    array(
                        'name'    => 'Identical',
                        'options' => array(
                            'token' => 'usrPassword',
                            'messages' => array(
                                Identical::NOT_SAME  => $this->translator->translate("The two given passwords do not match", 'DGIModule'),
                            )
                        ),
                    ),
                ),
            ]

        );

    }

}