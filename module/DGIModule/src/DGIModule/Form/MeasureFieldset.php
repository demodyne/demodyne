<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Form;

use DGIModule\Entity\Measure;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Hydrator\ClassMethods;

use DGIModule\Entity\Hydrator\Strategy\DateStrategy;


class MeasureFieldset extends Fieldset implements InputFilterProviderInterface
{


    public function __construct()
    {
        parent::__construct('measure-fieldset');

        $this
            ->setObject(new Measure())
            ->setAttribute('name', 'measure')
            ->setOption( 'use_as_base_fieldset', true)
        ;

        $this->setAttribute('method', 'post');
        $hydrator = new ClassMethods();
        $hydrator->addStrategy('mesStartDate', new DateStrategy());
        $hydrator->addStrategy('mesEndDate', new DateStrategy());
        $this->setHydrator($hydrator);

        $this->add(array(
            'name' => 'mesStartDate',
            'type'  => 'Zend\Form\Element\Date',
            'options' => array(
                'format' => 'd/m/Y',
            ),
            'attributes' => [
                'type'  => 'text',
                'id' => 'mesStartDate',
                'class'=>'form-control text-change',
                'step' => '1', // days; default step interval is 1 day
                'placeholder' => 'DD/MM/YYYY'
            ]
        ));

        $this->add(array(
            'name' => 'mesEndDate',
            'type'  => 'Zend\Form\Element\Date',
            'options' => array(
                'format' => 'd/m/Y',
            ),
            'attributes' => [
                'type'  => 'text',
                'id' => 'mesEndDate',
                'class'=>'form-control text-change',
                'step' => '1', // days; default step interval is 1 day
                'placeholder' => 'DD/MM/YYYY'
            ]
        ));

        $this->add(array(
            'name' => 'mesCost',
            'attributes' => array(
                'class'=>'form-control text-change',
            ),
        ));

    }

    /**
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return array(

            'mesStartDate' => array(
                'required' => false,
            ),
            'mesEndDate' => array(
                'required' => false,
            ),
            'mesCost' => array(
                'required' => false,
                'validators' => array(
                    array(
                        'name'  => 'Digits',
                        'break_chain_on_failure' => true,
                    ),
                    array(
                        'name' => 'Between',
                        'options' => array(
                            'min' => 0,
                            'max' => PHP_INT_MAX,
                        ),
                    ),
                ),
            ),
        );
    }
}