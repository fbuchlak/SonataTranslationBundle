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

namespace Sonata\TranslationBundle\Admin\Extension;

use Sonata\AdminBundle\Admin\AbstractAdminExtension;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\TranslationBundle\Checker\TranslatableChecker;
use Sonata\TranslationBundle\Model\TranslatableInterface;
use Sonata\TranslationBundle\Provider\LocaleProviderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author Nicolas Bastien <nbastien.pro@gmail.com>
 *
 * @phpstan-extends AbstractAdminExtension<TranslatableInterface>
 */
abstract class AbstractTranslatableAdminExtension extends AbstractAdminExtension
{
    /**
     * Request parameter.
     */
    public const TRANSLATABLE_LOCALE_PARAMETER = 'tl';

    /**
     * @var string|null
     */
    protected $translatableLocale;

    /**
     * @var TranslatableChecker
     */
    protected $translatableChecker;

    /**
     * @var string|LocaleProviderInterface|null
     */
    private $defaultTranslationLocaleOrLocaleProvider;

    /**
     * NEXT_MAJOR: Change $defaultTranslationLocaleOrLocaleProvider to LocaleProviderInterface $localeOrLocaleProvider.
     *
     * @param string|LocaleProviderInterface|null $defaultTranslationLocaleOrLocaleProvider
     */
    public function __construct(TranslatableChecker $translatableChecker, $defaultTranslationLocaleOrLocaleProvider = null)
    {
        $this->translatableChecker = $translatableChecker;

        // NEXT_MAJOR: Remove this block.
        if (!$defaultTranslationLocaleOrLocaleProvider instanceof LocaleProviderInterface) {
            @trigger_error(sprintf(
                'Omitting the argument 2 or passing other type than "%s" to "%s()" is deprecated'
                .' since sonata-project/translation-bundle 2.9 and will be not possible in version 3.0.',
                LocaleProviderInterface::class,
                __METHOD__
            ), \E_USER_DEPRECATED);
        }

        $this->defaultTranslationLocaleOrLocaleProvider = $defaultTranslationLocaleOrLocaleProvider;
    }

    /**
     * @param TranslatableChecker $translatableChecker
     *
     * @return void
     */
    public function setTranslatableChecker($translatableChecker)
    {
        $this->translatableChecker = $translatableChecker;
    }

    /**
     * @return TranslatableChecker
     */
    public function getTranslatableChecker()
    {
        return $this->translatableChecker;
    }

    /**
     * Return current translatable locale
     * ie: the locale used to load object translations != current request locale.
     *
     * @phpstan-param AdminInterface<TranslatableInterface> $admin
     *
     * @return string
     */
    public function getTranslatableLocale(AdminInterface $admin)
    {
        if (null === $this->translatableLocale) {
            // NEXT_MAJOR: Remove the "else" part and remove the "if" check.
            if ($this->defaultTranslationLocaleOrLocaleProvider instanceof LocaleProviderInterface) {
                $this->translatableLocale = $this->defaultTranslationLocaleOrLocaleProvider->get();
            } else {
                if ($admin->hasRequest()) {
                    $translatableLocale = $admin->getRequest()->query->get(self::TRANSLATABLE_LOCALE_PARAMETER);
                }

                if (isset($translatableLocale)) {
                    $this->translatableLocale = (string) $translatableLocale;
                } else {
                    $this->translatableLocale = $this->defaultTranslationLocaleOrLocaleProvider ?? $this->getDefaultTranslationLocale($admin);
                }
            }
        }

        return $this->translatableLocale;
    }

    public function getPersistentParameters(AdminInterface $admin)
    {
        return [self::TRANSLATABLE_LOCALE_PARAMETER => $this->getTranslatableLocale($admin)];
    }

    public function alterNewInstance(AdminInterface $admin, $object)
    {
        if (null === $object->getLocale()) {
            $object->setLocale($this->getTranslatableLocale($admin));
        }
    }

    /**
     * NEXT_MAJOR: Remove this method.
     *
     * @deprecated since version 2.7, to be removed in 3.0. Use dependency injection instead.
     *
     * @phpstan-param AdminInterface<TranslatableInterface> $admin
     *
     * @return ContainerInterface
     */
    protected function getContainer(AdminInterface $admin)
    {
        @trigger_error(sprintf(
            'The "%s()" method is deprecated since sonata-project/translation-bundle 2.7 and will be removed in 3.0.'
            .' Use dependency injection instead.',
            __METHOD__
        ), \E_USER_DEPRECATED);

        \assert(\is_callable([$admin, 'getConfigurationPool']));

        return $admin->getConfigurationPool()->getContainer();
    }

    /**
     * NEXT_MAJOR: Remove this method.
     *
     * @deprecated since version 2.7, to be removed in 3.0.
     *
     * @phpstan-param AdminInterface<TranslatableInterface> $admin
     *
     * Return the list of possible locales for your models.
     *
     * @return string[]
     */
    protected function getTranslationLocales(AdminInterface $admin)
    {
        @trigger_error(sprintf(
            'The "%s()" method is deprecated since sonata-project/translation-bundle 2.7 and will be removed in 3.0.',
            __METHOD__
        ), \E_USER_DEPRECATED);

        /** @var string[] $locales */
        $locales = $this->getContainer($admin)->getParameter('sonata_translation.locales');

        return $locales;
    }

    /**
     * NEXT_MAJOR: Remove this method.
     *
     * @deprecated since version 2.7, to be removed in 3.0.
     *
     * Return the default locale if url parameter is not present.
     *
     * @phpstan-param AdminInterface<TranslatableInterface> $admin
     *
     * @return string
     */
    protected function getDefaultTranslationLocale(AdminInterface $admin)
    {
        $locale = $this->getContainer($admin)->getParameter('sonata_translation.default_locale');
        \assert(\is_string($locale));

        return $locale;
    }
}
