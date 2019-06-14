<?php
/**
 * Translations Database plugin for Craft CMS 3.x
 *
 * Translate string from database key.
 *
 * @link      https://github.com/robin-gauthier
 * @copyright Copyright (c) 2019 Robin Gauthier
 */

namespace rgauthier\translationsdatabase\twigextensions;

use rgauthier\translationsdatabase\TranslationsDatabase;

use Craft;

/**
 * @author    Robin Gauthier
 * @package   TranslationsDatabase
 * @since     1.0.0
 */
class TranslationsDatabaseTwigExtension extends \Twig_Extension
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'TranslationsDatabase';
    }

    /**
     * @inheritdoc
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('dbt', [$this, 'getTranslation']),
        ];
    }

    /**
     * @inheritdoc
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('getTranslationsList', [$this, 'getList']),
        ];
    }

    public function getTranslation($key)
    {   
        return TranslationsDatabase::$plugin->translations->getTranslation($key);
    }

    public function getList($lang)
    {   
        return TranslationsDatabase::$plugin->translations->getList($lang);
    }

}
