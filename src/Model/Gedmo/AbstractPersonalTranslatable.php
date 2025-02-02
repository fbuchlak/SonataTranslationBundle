<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\TranslationBundle\Model\Gedmo;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * NEXT_MAJOR: Remove this file.
 *
 * This is your base class if you want to use gedmo personal translation
 * ie: if you want to have a dedicated translation table by model table.
 *
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
 *
 * @deprecated since version 2.9, to be removed in 3.0. Create your own instead.
 * @see https://github.com/l3pp4rd/DoctrineExtensions/blob/master/doc/translatable.md : Personal translations
 */
abstract class AbstractPersonalTranslatable extends AbstractTranslatable
{
    /**
     * @var ArrayCollection|AbstractPersonalTranslation[]
     *
     * @phpstan-var ArrayCollection<int, AbstractPersonalTranslation>
     */
    protected $translations;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

    /**
     * @return ArrayCollection|AbstractPersonalTranslation[]
     *
     * @phpstan-return ArrayCollection<int, AbstractPersonalTranslation>
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * @param string $field
     * @param string $locale
     *
     * @return string|null
     */
    public function getTranslation($field, $locale)
    {
        foreach ($this->getTranslations() as $translation) {
            if (0 === strcmp($translation->getField(), $field) && 0 === strcmp($translation->getLocale(), $locale)) {
                return $translation->getContent();
            }
        }

        return null;
    }

    /**
     * @return $this
     */
    public function addTranslation(AbstractPersonalTranslation $translation)
    {
        if (!$this->translations->contains($translation)) {
            $translation->setObject($this);
            $this->translations->add($translation);
        }

        return $this;
    }
}
