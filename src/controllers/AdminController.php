<?php
/**
 * Translations Database plugin for Craft CMS 3.x
 *
 * Translate string from database key.
 *
 * @link      https://github.com/robin-gauthier
 * @copyright Copyright (c) 2019 Robin Gauthier
 */

namespace rgauthier\translationsdatabase\controllers;

use rgauthier\translationsdatabase\TranslationsDatabase;

use Craft;
use craft\web\Controller;

/**
 * @author    Robin Gauthier
 * @package   TranslationsDatabase
 * @since     1.0.0
 */
class AdminController extends Controller
{

    // Protected Properties
    // =========================================================================

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    protected $allowAnonymous = [];

    // Public Methods
    // =========================================================================

    /**
     * @return mixed
     */
    public function actionIndex()
    {

        $request = Craft::$app->getRequest();
        $action = $request->getParam('custom_action');
        $lang = Craft::$app->request->getQueryParam('lang') ? Craft::$app->request->getQueryParam('lang') : 'en';

        if($action == 'search_db') {
            $list = TranslationsDatabase::$plugin->translations->getList($lang, $request->getParam('search'));
        } else {
            $list = TranslationsDatabase::$plugin->translations->getList($lang);
        }

        return $this->renderTemplate('translations-database/admin/list', ['currentPluginLanguage' => $lang, 'list' => $list, 'action' => $action ]);
    }

    /*
    * Save current form page
    */
    public function actionSave()
    {
        $request = Craft::$app->getRequest();
        $lang = $request->getParam('lang');
        $form = $request->getParam('form');
        $page = ($request->getParam('page') > 1)?'/p'.$request->getParam('page'):'';

        if(!$lang || !$form) { return $this->redirect('translations-database'); }
        
        TranslationsDatabase::$plugin->translations->saveRecords($form, $lang);

        return $this->redirect('translations-database' . $page . '?lang='.$lang);
    }


    /*
    * Crawl templates to extract strings
    */
    public function actionGenerate()
    {
        $request = Craft::$app->getRequest();
        $lang = $request->getParam('lang');

        if(!$lang) { return $this->redirect('translations-database'); }

        TranslationsDatabase::$plugin->translations->generateDbEntries($lang);
        
        return $this->redirect('translations-database?lang='.$lang);
    }

    /*
    * Delete unuse database string
    */
    public function actionClean()
    {
        $request = Craft::$app->getRequest();
        $lang = $request->getParam('lang');

        if(!$lang) { return $this->redirect('translations-database'); }

        TranslationsDatabase::$plugin->translations->cleanDbEntries($lang);

        return $this->redirect('translations-database?lang='.$lang);
    }
    
}
