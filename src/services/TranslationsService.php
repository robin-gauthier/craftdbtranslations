<?php
/**
 * Translations Database plugin for Craft CMS 3.x
 *
 * Translate string from database key.
 *
 * @link      https://github.com/robin-gauthier
 * @copyright Copyright (c) 2019 Robin Gauthier
 */

namespace rgauthier\translationsdatabase\services;

use Craft;
use craft\base\Component;

use rgauthier\translationsdatabase\TranslationsDatabase;
use rgauthier\translationsdatabase\records\TranslationsRecord;
use rgauthier\translationsdatabase\models\TranslationModel;

/**
 * @author    Robin Gauthier
 * @package   TranslationsDatabase
 * @since     1.0.0
 */
class TranslationsService extends Component
{
    // Public Methods
    // =========================================================================

    /*
    * @return true
    */
    public function saveRecords($form, $lang)
    {
        foreach($form as $id=>$value) {

            $result = TranslationsRecord::find()->where(['=', 'id', $id])->one();
            $result->value = $value;
            $result->save();
        }
    }

    /*
     * @return mixed
     */
    public function getTranslation($key, $lang = null)
    {
        $lang = is_null($lang)?Craft::$app->language:$lang;
        $result = TranslationsRecord::find()
                        ->where(['=', 'translation_key', $key])
                        ->andWhere(['=', 'lang', $lang])
                        ->andWhere(['!=', 'value', ''])
                        ->one();

        if($result) {
            return $result->value;
        }

        return $key;
    }

    /*
     * @return mixed
     */
    public function getList($lang, $search = null)
    {
        if(!is_null($search)) {

            return TranslationsRecord::find()
                    ->where(['=', 'lang', $lang])
                    ->andWhere(['or',
                        ['like', 'translation_key', $search],
                        ['like', 'value', $search],
                    ]);
        }

        return TranslationsRecord::find()->where(['=', 'lang', $lang]);
    }

    /*
    * @return true/false
    */
    public function generateDbEntries($lang) 
    {
        
        foreach($this->getListFromFiles() as $val) {
            
            $exist = TranslationsRecord::find()
                            ->where(['=', 'lang', $lang])
                            ->andWhere(['=', 'translation_key', $val])
                            ->exists();
            
            if(!$exist) {
                // Create a new device for this user
                $translationModel = new TranslationModel();
                $translationModel->setAttributes([
                    'translation_key' => $val,
                    'value'           => '',
                    'lang'            => $lang,
                ], false);

                if ($translationModel->validate())
                {
                    $translationsRecord = new translationsRecord($translationModel);
                    $translationsRecord->save();
                }
            }
        }

        return;
    }

    public function cleanDbEntries($lang)
    {   
        $toDelete = [];
        $keys = $this->getListFromFiles();    
        $list = TranslationsRecord::find()->where(['=', 'lang', $lang])->all();    

        foreach($list as $row) {
            if(!in_array($row->translation_key, $keys)) {
                array_push($toDelete, $row->id);
            }
        }

        if(count($toDelete)) {
            $ids = implode( ',', $toDelete );
            TranslationsRecord::deleteAll('id IN ('. $ids.')');
        }

        return;        
    }

    /*
    * Iterate over all files from current theme
    * Return an array of keys used in templates
    */
    private function getListFromFiles()
    {   
        $values = array();

        $accepted_files = ['php', 'blade', 'twig'];
        $dir = Craft::$app->getPath()->getSiteTemplatesPath();
    
        $di = new \RecursiveDirectoryIterator($dir);
        foreach (new \RecursiveIteratorIterator($di) as $filename => $file) {
            if(in_array($file->getExtension(), $accepted_files)) {
                $contents = file_get_contents($file);
                
                $pattern = "/[\'\"][\w\d\.]+[\'\"]\|dbt/";
                
                if(preg_match_all($pattern, $contents, $matches)){            
                    foreach($matches[0] as $term) { 
                        
                        $term = substr($term, 0, -4);
                        $term = preg_replace("/[\"\']/", "", $term);
                        
                        array_push($values, $term);
                    }
                }
            }
        }

        return $values;
    }
    
}
