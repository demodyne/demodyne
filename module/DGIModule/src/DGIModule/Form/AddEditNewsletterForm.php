<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Form;

use DGIModule\Entity\Newsletter;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Mvc\I18n\Translator;
use Zend\Hydrator\ClassMethods;
use Zend\Validator\File\Size;

class AddEditNewsletterForm extends Form implements InputFilterProviderInterface
{
    private $translator;

    /**
     * AddEditNewsletterForm constructor.
     * @param Translator $translator
     * @param Newsletter $newsletter
     */
    public function __construct(Translator $translator, Newsletter $newsletter = null)
    {
        parent::__construct('newsletter-add-edit-newsletter-form');

        $this->translator = $translator;

        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->setHydrator(new ClassMethods());

        $this->add(array(
            'name' => 'nlName',
            'attributes' => array(
                'required' => 'required',
                'class'=>'form-control',
                'id' => 'nlName',
                'value' => $newsletter?$newsletter->getNlName():''
            ),
        ));

        $this->add([
            'type' => 'Zend\Form\Element\Textarea',
            'name' => 'nlDescription',
            'attributes' => array(
                'rows' => 3,
                'class'=>'form-control',
                'id' => 'nlDescription',
                'value' => $newsletter?$newsletter->getNlDescription():''
            ),
        ]);

        $this->add([
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'nlSendTo',
            'attributes' => [
                'id' => 'nlSendTo',
                'value' => $newsletter?$newsletter->getNlSendTo():'0'
            ],
        ]);

        $this->add(array(
            'name' => 'nlSubject',
            'attributes' => array(
                'required' => 'required',
                'class'=>'form-control',
                'id' => 'nlSubject',
                'value' => $newsletter?$newsletter->getNlSubject():''
            ),
        ));

        $this->add(array(
            'name' => 'nlContact',
            'attributes' => array(
                'class'=>'form-control',
                'id' => 'nlContact',
                'value' => $newsletter?$newsletter->getNlContact():''
            ),
        ));

        $this->add([
            'type' => 'Zend\Form\Element\Textarea',
            'name' => 'nlMessage',
            'attributes' => array(
                'rows' => 6,
                'class'=>'form-control',
                'id' => 'nlMessage',
                'maxlength' => 3000,
                'style' => 'display:none',
                'value' => $newsletter?$newsletter->getNlMessage():'0'
            ),
        ]);

        $this->add([
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'nlReply',
            'attributes' => array(
                'value' => $newsletter?$newsletter->getNlReply():'1'
            ),
        ]);


        $this->add([
            'type' => 'Zend\Form\Element\File',
            'name' => 'picture-file',
            'attributes' => array(
                'type' => 'file',
                'accept' => 'image/*',
                'class'=>'form-control',
                'id' => 'picture-file'
            ),
        ]);


        $this->add(array(
            'name' => 'nlUrl',
            'type' => 'Zend\Form\Element\Url',
            'attributes' => array(
                'class'=>'form-control',
                'id' => 'nlUrl',
                'value' => $newsletter?$newsletter->getNlUrl():''
            ),
        ));

    }

    public function getInputFilterSpecification()
    {
        return [
            'nlName' => [
                'required' => true,
                'filters'  => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ],
                    ],

                ],
            ],
            'nlSendTo' => [
                'required' => true,
                'validators' => [
                    [
                        'name'    => 'Between',
                        'options' => [
                            'min'      => 1,
                            'messages' => ['notBetween' => $this->translator->translate('Please select at least one user group.', 'DGIModule')]
                        ],
                    ],

                ],
            ],
            'nlDescription' => [
                'required' => false,
                'filters'  => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 1000,
                        ],
                    ],

                ],
            ],
            'nlSubject' => [
                'required' => true,
                'filters'  => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 200,
                        ],
                    ],

                ],
            ],
            'nlContact' => [
                'required' => false,
                'filters'  => [
                    ['name' => 'StripTags'],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 200,
                        ],
                    ],

                ],
            ],
            'nlUrl' => [
                'required' => false
            ],
            'nlMessage' => [
                'required' => true,
                'filters'  => [
                    ['name' => 'StripTags',
                        'options' => [
                            'allowTags' => ['b', 'i', 'br', 'strong', 'blockquote', 'span', 'div']
                        ]],
                    ['name' => 'StringTrim'],
                ],
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 3000,
                        ],
                    ],

                ],
            ],
            'picture-file' => [
                'required' => false,
                'validators' => [
                    [
                        'name'    => 'Zend\Validator\File\Size',
                        'options' => [
                            'max'      => 524288, // 512Ko
                            'messages' => [
                                Size::TOO_BIG  => $this->translator->translate("The maximum file size is 512Kb", 'DGIModule'),
                                Size::NOT_FOUND  => $this->translator->translate("File is not readable or does not exist", 'DGIModule'),
                            ]
                        ],
                    ],
                ],
            ]
        ];

    }




}