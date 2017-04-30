<?php
namespace DGIModule\DBAL\Types;
 
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeType;
 
class UTCDateTimeType extends DateTimeType
{
 
     static private $utc = null;
 
     /**
      * @param \DateTime $value
      * @param \Doctrine\DBAL\Platforms\AbstractPlatform $platform
      * @return string
      */
     public function convertToDatabaseValue($value, AbstractPlatform $platform)
     {
        if ($value === null) {
            return null;
        }
        $formatString = $platform->getDateTimeFormatString();
 
        $value->setTimezone((self::$utc) ? self::$utc : (self::$utc = new \DateTimeZone('UTC')));
 
        $formatted = $value->format($formatString);
 
        return $formatted;
    }
 
    /**
     * @param string $value
     * @param \Doctrine\DBAL\Platforms\AbstractPlatform $platform
     * @return \DateTime|mixed|null
     * @throws \Doctrine\DBAL\Types\ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }
 
        $val = \DateTime::createFromFormat(
            $platform->getDateTimeFormatString(),
            $value,
            (self::$utc) ? self::$utc : (self::$utc = new \DateTimeZone('UTC'))
        );
        if (!$val) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }
        return $val;
    }
}