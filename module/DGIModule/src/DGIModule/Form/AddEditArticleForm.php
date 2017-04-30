<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Form;

use DGIModule\Entity\Article;
use Zend\Form\Form;
use Zend\Hydrator\ClassMethods;
use Zend\InputFilter\InputFilterProviderInterface;


class AddEditArticleForm extends Form implements InputFilterProviderInterface
{
    public function __construct($config, $translator, Article $article = null)
    {
        parent::__construct('blog-add-edit-article-form');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype', 'multipart/form-data');
        $this->setHydrator(new ClassMethods());
        
        $this->add([
            'name' => 'articleTitle',
            'attributes' => [
                'required' => 'required',
                'class'=>'form-control',
                'id' => 'articleTitle',
                'value' => $article?$article->getArticleTitle():'',
                'readonly' => $article&&$article->getArticlePublishedDate()?true:false,
            ],
        ]);

        $this->add([
            'type' => 'Zend\Form\Element\Textarea',
            'name' => 'articleDescription',
            'attributes' => [
                'rows' => 6,
                'class'=>'form-control',
                'id' => 'articleDescription',
                'style' => 'display:none',
                'value' => $article?$article->getArticleDescription():''
            ],
        ]);

        $this->add([
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'articleFeatured',
            'attributes' => [
                'class'=>'form-control',
                'id' => 'articleFeatured',
            ],
        ]);

        $cat = [];
        foreach ($config['demodyne']['blog']['tag'] as $item => $value) {
            if ($value) {
                $cat[$value] = ucfirst($item);
            }
        }

        $this->add([
            'name' => 'articleCategory',
            'type'  => 'Zend\Form\Element\Select',
            'options' => [
                'value_options' => $cat,
            ],
            'attributes' => [
                'id' => 'articleCategory',
                'class'=>'form-control text-change',
                'value' => $article?$article->getArticleCategory():''
            ]
        ]);
        
        $this->add([
            'type' => 'Zend\Form\Element\File',
            'name' => 'picture-file',
            'attributes' => [
                'type' => 'file',
                'accept' => 'image/*',
                'class'=>'form-control',
                'id' => 'picture-file'
            ],
        ]);

    }

    public function getInputFilterSpecification()
    {
        return [
                'articleTitle' => [
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
                                'max'      => 1000,
                            ],
                        ],

                    ],
                ],
          'articleDescription' => [
                'required' => true,
                'filters'  => [
                    ['name' => 'StringTrim'],
                ],
            ],
            'picture-file' => [
                'required' => false,
            ]
        ];

    }
  
}