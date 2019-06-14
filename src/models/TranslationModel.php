<?php
/**
 * Translations Database plugin for Craft CMS 3.x
 *
 * Translate string from database key.
 *
 * @link      https://github.com/robin-gauthier
 * @copyright Copyright (c) 2019 Robin Gauthier
 */

namespace rgauthier\translationsdatabase\models;

use rgauthier\translationsdatabase\TranslationsDatabase;

use Craft;
use craft\base\Model;

/**
 * @author    Robin Gauthier
 * @package   TranslationsDatabase
 * @since     1.0.0
 */
class TranslationModel extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * @var string $translation_key
     */
    public $translation_key;

    /**
     * @var string $value
     */
    public $value;

    /**
     * @var string $lang
     */
    public $lang;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                ['translation_key', 'value', 'lang'], 
                'string'
            ],
        ];
    }
}
