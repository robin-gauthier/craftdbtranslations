<?php
/**
 * Translations Database plugin for Craft CMS 3.x
 *
 * Translate string from database key.
 *
 * @link      https://github.com/robin-gauthier
 * @copyright Copyright (c) 2019 Robin Gauthier
 */

namespace rgauthier\translationsdatabase\migrations;

use rgauthier\translationsdatabase\TranslationsDatabase;

use Craft;
use craft\config\DbConfig;
use craft\db\Migration;

/**
 * @author    Robin Gauthier
 * @package   TranslationsDatabase
 * @since     1.0.0
 */
class Install extends Migration
{
    // Public Properties
    // =========================================================================

    /**
     * @var string The database driver to use
     */
    public $driver;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        // Place migration code here...
        $this->createTable('{{%translations_database}}', [
            'id' => $this->primaryKey(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            
            'translation_key' => $this->string(255)->notNull()->defaultValue(''),
            'value' => $this->text()->notNull()->defaultValue(''),
            'lang' => $this->string(10)->notNull()->defaultValue(''),
            'uid' => $this->uid(),
            
        ]);

        $this->createIndex(null, '{{%translations_database}}', ['translation_key'], false);
        $this->createIndex(null, '{{%translations_database}}', ['lang'], false);
    }

   /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "Install translations_database cannot be reverted.\n";
        return false;
    }

}
