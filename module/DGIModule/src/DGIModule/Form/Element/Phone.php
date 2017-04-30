<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Form\Element;

use Zend\Form\Element;
use Zend\InputFilter\InputProviderInterface;
use Zend\Validator\Regex as RegexValidator;
use Zend\Validator\ValidatorInterface;

class Phone extends Element implements InputProviderInterface
{
	/**
	 * @var ValidatorInterface
	 */
	protected $validator;

	/**
	 * Get a validator if none has been set.
	 *
	 * @return ValidatorInterface
	 */
	public function getValidator()
	{
		if (null === $this->validator) {
			$validator = new RegexValidator('/^\+?\d{10,11}$/');
			$validator->setMessage(_('Please enter 10 or +11 digits only!'),
					RegexValidator::NOT_MATCH);

			$this->validator = $validator;
		}

		return $this->validator;
	}

	/**
	 * Sets the validator to use for this element
	 *
	 * @param  ValidatorInterface $validator
	 * @return Phone
	 */
	public function setValidator(ValidatorInterface $validator)
	{
		$this->validator = $validator;
		return $this;
	}

	/**
	 * Provide default input rules for this element
	 *
	 * Attaches a phone number validator.
	 *
	 * @return array
	 */
	public function getInputSpecification()
	{
		return array(
				'name' => $this->getName(),
				'required' => true,
				'filters' => array(
						array('name' => 'Zend\Filter\StringTrim'),
				),
				'validators' => array(
						$this->getValidator(),
				),
		);
	}
}