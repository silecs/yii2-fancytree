<?php

/*
 * @license http://www.gnu.org/licenses/gpl-3.0.html  GNU GPL v3
 */

namespace silecs\fancytree;

use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * Sample:
 *
 * <div class="my-ajax-tree">
 * <?php
 * echo \silecs\fancytree\Fancytree::widget([
 *     'url' => Url::to(['tree/node'],
 * ]);
 * ?>
 * </div>
 *
 * @author François Gannaz <francois.gannaz@silecs.info>
 */
class Fancytree extends Widget
{
    /**
     * @var string
     */
    public $skin;

    /**
     * @var string
     */
    public $url;

    /**
     * @var boolean
     */
    public $cache = true;

    /**
     * @var array
     */
    public $options = [];

    /**
     * Helps to setup a fully AJAX tree to $url?id={key}.
     *
     * @param string $url
     */
    public function applyAjaxUrl($url)
    {
        $this->options['source'] = [
            'url' => $url,
            'cache' => $this->cache
        ];
        $this->options['lazyLoad'] = new \yii\web\JsExpression('
function(event, data){
    var node = data.node;
    // Load child nodes via ajax GET url?mode=children&parent=1234
    data.result = {
      url: "' . addslashes($url) . '",
      data: { id: node.key },
      cache: ' . ($this->cache ? "true" : "false") . '
    };
}'
        );
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->skin) {
            FancytreeAssets::$skin = $this->skin;
        }
        FancytreeAssets::register($this->getView());
        if ($this->url) {
            $this->applyAjaxUrl($this->url);
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $id = (empty($this->options['id']) ? $this->getId() : $this->options['id']);
        echo Html::tag('div', '', ['id' => 'fancytree-' . $id]);
        // Loads jQuery and the initialisation JS code
        $this->getView()->registerJs(
            "$('#fancytree-{$id}').fancytree("
            . Json::encode($this->options)
            . ");"
        );
    }
}