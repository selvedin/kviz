<?php

namespace app\widgets;

use Yii;
use yii\bootstrap5\Html;

/**
 * Card veiw for forms
 *
 * ```php
 * Tabs::widget(['items'=> array]);
 * ```
 *
 * @author Selvedin Haskic <selvedinh@gmail.com>
 */
class Tabs extends \yii\bootstrap5\Widget
{

    public $items = [];

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $lis = $content = "";
        $num = 0;
        foreach ($this->items as $item) {
            $lis .= Html::tag(
                'li',
                Html::button($item['label'], [
                    'class' => 'nav-link ' . (!$num ? 'active' : ''),
                    'role' => 'tab', 'data-bs-toggle' => 'tab',
                    'data-bs-target' => '#navs-top-' . $item['id'],
                    'aria-controls' => 'navs-top-' . $item['id'], 'aria-selected' => 'true'
                ]),
                ['class' => 'nav-item', 'role' => 'presentation']
            );

            $content .= Html::tag(
                'div',
                $item['content'],
                [
                    'id' => 'navs-top-' . $item['id'],
                    'class' => 'tab-pane fade show ' . (!$num ? 'active' : ''), 'role' => 'tabpanel'
                ]
            );
            $num++;
        }
        $ul = Html::tag('ul', $lis, ['class' => 'nav nav-tabs m-0', 'role' => 'tablist']);
        $content = Html::tag('div', $content, ['class' => 'tab-content']);

        echo Html::tag('div', $ul . $content, ['class' => 'nav-align-top nav-tabs-shadow mt-4']);
    }
}
