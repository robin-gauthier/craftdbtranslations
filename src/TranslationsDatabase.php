<?php
/**
 * Translations Database plugin for Craft CMS 3.x
 *
 * Translate string from database key.
 *
 * @link      https://github.com/robin-gauthier
 * @copyright Copyright (c) 2019 Robin Gauthier
 */

namespace rgauthier\translationsdatabase;

use rgauthier\translationsdatabase\services\TranslationsService;
use rgauthier\translationsdatabase\twigextensions\TranslationsDatabaseTwigExtension;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\web\UrlManager;
use craft\events\RegisterUrlRulesEvent;

use yii\base\Event;

/**
 * Class TranslationsDatabase
 *
 * @author    Robin Gauthier
 * @package   TranslationsDatabase
 * @since     1.0.0
 *
 * @property  TranslationsService $translations
 */
class TranslationsDatabase extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var TranslationsDatabase
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $schemaVersion = '1.0.0';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        Craft::$app->view->registerTwigExtension(new TranslationsDatabaseTwigExtension());

        // Register components
        $this->setComponents([
            'translations'  => TranslationsService::class,
        ]);

        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                //index page (list)
                $event->rules['translations-database'] = 'translations-database/admin/index';
                $event->rules['translations-database/save'] = 'translations-database/admin/save';
                $event->rules['translations-database/generate'] = 'translations-database/admin/generate';
                $event->rules['translations-database/clean'] = 'translations-database/admin/clean';
            }
        );
        
    }

    // Protected Methods
    // =========================================================================

}
