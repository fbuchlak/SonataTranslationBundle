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

namespace Sonata\TranslationBundle\Tests\Fixtures\Model;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

/** @ORM\Entity() */
class ModelTranslatable implements Translatable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var int|null
     */
    public $id;

    /**
     * @Gedmo\Locale()
     *
     * @var string|null
     */
    public $locale = null;

    /**
     * @ORM\Column(type="string", length=10)
     *
     * @var string
     */
    public $refreshableField = '';
}
