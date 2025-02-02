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

use Sonata\TranslationBundle\Model\Gedmo\AbstractTranslatable;
use Sonata\TranslationBundle\Model\Gedmo\TranslatableInterface;

class DeprecatedModelTranslatable extends AbstractTranslatable implements TranslatableInterface
{
}
