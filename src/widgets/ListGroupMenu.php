<?php ///[yongtiger/yii2-listgroup-menu]

/**
 * Yii2 Listgroup Menu
 *
 * @link        http://www.brainbook.cc
 * @see         https://github.com/yongtiger/yii2-listgroup-menu
 * @author      Tiger Yong <tigeryang.brainbook@outlook.com>
 * @copyright   Copyright (c) 2017 BrainBook.CC
 * @license     http://opensource.org/licenses/MIT
 */

namespace yongtiger\listgroupmenu\widgets;

use Yii;
use yii\widgets\Menu;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * Displays a multi-level list group menu.
 *
 * Example html code of the list group menu is in `http://www.yiiframework.com/doc-2.0/guide-index.html`, e.g.:
 *
 * ```
 * <div id="navigation" class="list-group">
 *     <a class="list-group-item active" href="#navigation-11146" data-toggle="collapse" data-parent="#navigation" aria-expanded="true">yii\bootstrap <b class="caret"></b></a>
 *     <div id="navigation-11146" class="submenu panel-collapse collapse in" aria-expanded="true">
 *         <a class="list-group-item" href="./yii-bootstrap-activefield.html">ActiveField</a>
 *         <a class="list-group-item active" href="./yii-bootstrap-activeform.html">ActiveForm</a>
 * 
 *         <a class="list-group-item" href="#navigation-10986" data-toggle="collapse" data-parent="#navigation">yii\behaviors <b class="caret"></b></a>
 *         <div id="navigation-10986" class="submenu panel-collapse collapse">
 *         <a class="list-group-item" href="./yii-behaviors-attributebehavior.html">AttributeBehavior</a>
 *         </div>
 * 
 *         <a class="list-group-item" href="./yii-bootstrap-alert.html">Alert</a>
 *     </div>
 *     <a class="list-group-item" href="#navigation-10985" data-toggle="collapse" data-parent="#navigation">yii\behaviors <b class="caret"></b></a>
 *     <div id="navigation-10985" class="submenu panel-collapse collapse">
 *     <a class="list-group-item" href="./yii-behaviors-attributebehavior.html">AttributeBehavior</a>
 *     </div>
 * </div>
 * ```
 *
 * The following example shows how to use ListGroupMenu:
 *
 * ```php
 * echo ListGroupMenu::widget([
 *     'items' => $menuItems,   // same as menu
 *     'options' => ['id' => 'yii2doc'],   // optional
 *     'activateParents' => true,   ///optional
 * ]);
 * ```
 *
 */
class ListGroupMenu extends Menu
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->options = ArrayHelper::merge(['tag' => 'div', 'id' => 'navigation', 'class' => 'list-group'], $this->options);

        $this->registerClientScript();
    }

    /**
     * Registers the needed JavaScript.
     */
    public function registerClientScript()
    {
        $view = $this->getView();
        $view->registerCss(<<<CSS
.submenu a:hover, .submenu a:active, .submenu a.active, .submenu a.active:hover, .submenu a.active:active {
    background: #44b5f6;
    border-color: #44b5f6;
    border-radius: 0;
    color: #fff;
}
CSS
        );
    }

    /**
     * @inheritdoc
     */
    protected function renderItems($items)
    {
        $lines = [];
        foreach ($items as $i => $item) {
            if (!empty($item['items'])) {
                $menu = Html::a(
                    $item['label'] . '<b class="caret"></b>',
                    '#' . $this->options['id'] . '-' . $item['id'],
                    [
                        'class' => 'list-group-item' . ($item['active'] ? ' active' : ''),
                        'data-toggle' => 'collapse',
                        'data-parent' => '#' . $this->options['id'],
                    ]
                );
                $menu .= Html::tag(
                    'div',
                    $this->renderItems($item['items']),
                    [
                        'id' => $this->options['id'] . '-' . $item['id'],
                        'class' => 'submenu panel-collapse collapse'. ($item['active'] ? ' in' : ''),
                    ]
                );

            } else {
                $menu = $this->renderItem($item);
            }
            $lines[] = $menu;
        }
        return implode("\n", $lines);
    }

    /**
     * @inheritdoc
     */
    protected function renderItem($item)
    {
        if (isset($item['url'])) {
            return Html::a(
                $item['label'],
                $item['url'],
                ['class' => 'list-group-item' . ($item['active'] ? ' active' : '')]
            );

        }
    }
}