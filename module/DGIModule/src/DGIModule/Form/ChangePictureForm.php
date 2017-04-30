<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Hydrator\ClassMethods;

class ChangePictureForm extends Form implements InputFilterProviderInterface
{
    public function __construct($name = null, $options = [])
    {
        parent::__construct('user-profile-change-picture-form');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->setHydrator(new ClassMethods());

        $this->add([
            'type' => 'Zend\Form\Element\File',
            'name' => 'picture-file',
            'attributes' => array(
                'type' => 'file',
                'accept' => 'image/*',
                'id' => 'user-profile-change-picture-file'
            ),
        ]);
    }


    public function getInputFilterSpecification()
    {
        return [
            'picture-file' => [
                'required' => true,
                'validators' => [
                    [
                        'name'    => 'Zend\Validator\File\Size',
                        'options' => [
                            'max'      => 131072, // 128Ko
                        ],
                    ],
                ]
            ]
        ];

    }
}