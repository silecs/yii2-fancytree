silecs/yii2-fancytree
=====================

Yet another JavaScript tree widget for Yii2.

Wraps [FancyTree](https://github.com/mar10/fancytree) into a Yii2 widget.

Differences from wbraganca/yii2-fancytree-widget:

* Easy AJAX lazy-loading,
* Theming.

Installation
------------

Either use the command line:
```
composer require "silecs/yii2-fancytree"
```

Or edit the file `composer.json`, and add under `require` a line:
```
	"silecs/yii2-fancytree": "*"
```
Beware of the trailing commas!

Usage
-----

In a Yii2 view, insert this minimal sample:
```
<?= \silecs\fancytree\Fancytree::widget([
    'url' => Url::to(['tree/children'],
]); ?>
```

A richer code sample that shows the full syntax of this widget:
```
<?php
echo \silecs\fancytree\Fancytree::widget([
	'url' => Url::to(['tree/children'], // default is "" (read from options["source"])
    'cache' => false,                   // default is true
    'skin' => 'awesome',                // default is "vista"
    'options' => [],                    // genuine FancyTree configuration
]);
?>
```

In this example, the action 'tree/children' will receive GET requests with a parameter `id`,
and must return JSON texts like:
```
[
	{
		"key": "node-00",
		"title": "a new <b>node</b>",
		"folder": true,
		"lazy": true
	}
]
```

See [FancyTree's documentation](https://github.com/mar10/fancytree/wiki) for information
on filling the `options` parameter.

Other skins are listed at <https://github.com/mar10/fancytree/tree/master/dist>.
