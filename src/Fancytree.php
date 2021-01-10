<?php

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
     * @var string Key of the active node (only if the node isn't lazily loaded).
     */
    public $activeNode;

    /**
     * @var boolean Cache the HTTP GET requests, on the client side.
     */
    public $cache = true;

    /**
     * @var string[] List of HTML class names for the tree container.
     */
    public $classes = [];

    /**
     * @var array The various options of FancyTree.
     *   See https://wwwendt.de/tech/fancytree/doc/jsdoc/global.html#FancytreeOptions
     *   and https://wwwendt.de/tech/fancytree/demo/sample-configurator.html
     */
    public $options = [];

    /**
     * @var string This will overwrite the options['source'] config.
     */
    public $url;

    /**
     * Setup the AJAX lazy loading of the tree with calls to "$url?mode=children&id={{node.key}}".
     *
     * @param string $url
     */
    public function applyAjaxUrl(string $url): void
    {
        $this->options['source'] = [
            'url' => $url,
            'cache' => $this->cache
        ];
        $encodedUrl = json_encode($url);
        $encodedCache = json_encode($this->cache);
        $this->options['lazyLoad'] = new \yii\web\JsExpression(
            <<<EOJS
            function(event, data){
                var node = data.node;
                // Load child nodes via ajax GET url?mode=children&id=1234
                data.result = {
                    url: {$encodedUrl},
                    data: { id: node.key },
                    cache: {$encodedCache}
                };
            }
            EOJS
        );
    }

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if (!empty($this->options['skin'])) {
            FancytreeAssets::$skin = $this->options['skin'];
        }
        if (
            isset($this->options['extensions'])
            && in_array('persist', $this->options['extensions'])
            && isset($this->options['persist']['store'])
            && $this->options['persist']['store'] !== 'local'
            && $this->options['persist']['store'] !== 'session'
        ) {
            FancytreeAssets::$cookies = true;
        }
        FancytreeAssets::register($this->getView());

        if ($this->url) {
            $this->applyAjaxUrl($this->url);
        }
    }

    /**
     * @inheritdoc
     */
    public function run(): string
    {
        $id = empty($this->options['id']) ? 'fancytree-' . $this->getId() : $this->options['id'];
        $selector = json_encode("#{$id}");
        if ($this->activeNode) {
            $activeNode = json_encode($this->activeNode);
            $this->options['init'] = new \yii\web\JsExpression(
                <<<EOJS
                function(event, data) {
                    $({$selector}).fancytree('getTree').activateKey('{$activeNode}');
                }
                EOJS
            );
        }

        // Loads jQuery and the initialisation JS code
        $this->getView()->registerJs(
            "$({$selector}).fancytree(" . Json::encode($this->options) . ");"
        );

        $this->classes[] = 'fancytree';
        return Html::tag('div', '', ['id' => $id, 'class' => join(" ", $this->classes)]);
    }
}
