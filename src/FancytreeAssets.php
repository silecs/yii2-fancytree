<?php

/**
 * @license http://www.gnu.org/licenses/gpl-3.0.html  GNU GPL v3
 */

namespace silecs\fancytree;

use yii\web\AssetBundle;

/**
 * @author François Gannaz <francois.gannaz@silecs.info>
 */
class FancytreeAssets extends AssetBundle
{
    /**
     * @var string The FancyTree skin.
     */
    static public $skin = 'vista';

    /**
     * @var boolean
     */
    static public $cookies = false;

    /**
     * @var string the directory that contains the source asset files for this asset bundle.
     */
    public $sourcePath = '@bower';

    /**
     * @var array list of bundle class names that this bundle depends on.
     */
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\jui\JuiAsset',
    ];

    /**
     * @inheritdoc
     */
    public function registerAssetFiles($view)
    {
        if (self::$cookies) {
            $this->js[] = 'js-cookie/src/js.cookie.js';
        }
        $this->js[] = 'fancytree/dist/jquery.fancytree-all'
            . (defined('YII_DEBUG') && YII_DEBUG ? '.js' : '.min.js');
        $this->css[] = "fancytree/dist/skin-" . self::$skin . '/ui.fancytree'
            . (defined('YII_DEBUG') && YII_DEBUG ? '.css' : '.min.css');
        parent::registerAssetFiles($view);
    }
}
