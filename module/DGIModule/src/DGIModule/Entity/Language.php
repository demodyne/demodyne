<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Language
 *
 * @ORM\Table(name="dgi_languages")
 * @ORM\Entity(repositoryClass="DGIModule\Entity\Repository\LanguageRepository")
 */
class Language
{
    /**
     * @var string
     *
     * @ORM\Column(name="lang_id", type="string", length=2, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $langId;

    /**
     * @var string
     *
     * @ORM\Column(name="lang_name", type="string", length=45, nullable=false)
     */
    private $langName;

    /**
     * @var string
     *
     * @ORM\Column(name="lang_flag", type="string", length=45, nullable=false)
     */
    private $langFlag;



    /**
     * Get langId
     *
     * @return string
     */
    public function getLangId()
    {
        return $this->langId;
    }

    /**
     * Set langName
     *
     * @param string $langName
     *
     * @return Language
     */
    public function setLangName($langName)
    {
        $this->langName = $langName;

        return $this;
    }

    /**
     * Get langName
     *
     * @return string
     */
    public function getLangName()
    {
        return $this->langName;
    }

    /**
     * Set langFlag
     *
     * @param string $langFlag
     *
     * @return Language
     */
    public function setLangFlag($langFlag)
    {
        $this->langFlag = $langFlag;

        return $this;
    }

    /**
     * Get langFlag
     *
     * @return string
     */
    public function getLangFlag()
    {
        return $this->langFlag;
    }
}
