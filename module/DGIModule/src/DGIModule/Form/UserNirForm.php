<?php/** * @link      https://github.com/demodyne/demodyne * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org) * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License */namespace DGIModule\Form;use DGIModule\Entity\User;use Doctrine\ORM\EntityManager;use Zend\Form\Form;use Zend\Mvc\I18n\Translator;use Zend\InputFilter\InputFilterProviderInterface;use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;class UserNirForm extends Form implements InputFilterProviderInterface{    /** @var EntityManager $entityManager */    private $entityManager;    /** @var Translator $translator */    private $translator;    public function __construct(EntityManager $entityManager, Translator $translator, User $user)    {        $this->entityManager = $entityManager;        $this->translator = $translator;        parent::__construct('user-nir-form');        $this->setAttribute('method', 'post');        $this->setHydrator(new DoctrineHydrator($entityManager,'DGIModule\Entity\User'));        $this->setAttribute('class', 'form-horizontal');        $this->setAttribute('data-fv-framework', 'bootstrap');        $this->setAttribute('data-fv-icon-valid', 'glyphicon glyphicon-ok');        $this->setAttribute('data-fv-icon-invalid', 'glyphicon glyphicon-remove');        $this->setAttribute('data-fv-icon-validating', 'glyphicon glyphicon-refresh');        $this->add(array(            'name' => 'usrNIR',            'attributes' => array(                'type'  => 'text',                'required' => 'required',                'class'=>'form-control input-md',                'id' => 'usrNIR',                'maxlength' => 15,                'data-fv-digits' => 'true',                'data-fv-stringlength'=>"true",                'data-fv-stringlength-max'=>"15",                'data-fv-stringlength-min'=>"15",                'data-fv-stringlength-message'=>"Le numéro de Sécurité Sociale doit avoir 15 chiffres",                'placeholder' => 'votre numéro de Sécurité Sociale'            ),        ));        $this->add([            'name' => 'usrName',            'attributes' => [                'type'  => 'text',                'class'=>'form-control input-md',                'readonly'=> 'readonly',                'id' => 'username',                'value' => $user->getUsrName()            ],        ]);    }    public function getInputFilterSpecification()    {        return [            'usrName'=> [                'required' => true,            ],            'usrNIR'=> [                'required' => true,                'filters' => [                    ['name' => 'StripTags'],                    ['name' => 'StringTrim'],                ],                'validators' => [                    [                        'name' => 'StringLength',                        'options' => [                            'encoding' => 'UTF-8',                            'min' => 15,                            'max' => 15                        ],                    ],                ],            ],        ];    }}