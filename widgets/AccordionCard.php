<?php

namespace app\widgets;

use Yii;

/**
 * Card veiw for forms
 *
 * ```php
 * AccordionCard::widget(['title'=>'Title', 'buttons'=>[], 'content'=>null]);
 * ```
 *
 * @author Selvedin Haskic <selvedinh@gmail.com>
 */
class AccordionCard extends \yii\bootstrap5\Widget
{
    /**
     * @var title the title configuration for the Card.
     * @var id the html id configuration for the Card.
     * @var content the html id configuration for the Card.
     * @var expanded the boolean configuration for the Card to show content on load.
     * 
     * @author Selvedin Haskic <selvedinh@gmail.com>
     */
    public $title = "";
    public $id = "accordion-id";
    public $content = null;
    public $show = false;
    public $marginless = "body";

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $collapse = $this->show ? '' : 'collapsed';
        $show = $this->show ? 'show' : '';
        echo "<div class='accordion mt-4' id='collapsible$this->id'>";
        echo '<div class="card accordion-item">';
        echo "<h2 class='accordion-header' id='heading$this->id'>";

        echo "<button class='accordion-button $collapse' type='button' data-bs-toggle='collapse' 
        data-bs-target='#collapse$this->id' aria-expanded='$this->show' 
        aria-controls='collapse$this->id'>$this->title</button>";

        echo "</h2>";
        echo "<div id='collapse$this->id' class='accordion-collapse collapse $show' 
        aria-labelledby='heading$this->id' data-bs-parent='#collapsible$this->id'>";

        echo "<div class='accordion-$this->marginless'>$this->content</div></div></div></div>";
    }

    public static function begin($config = ['id' => 'accordion-card', 'title' => 'Card', 'show' => 'show'])
    {
        $collapse = $config['show'] ? '' : 'collapsed';
        $show = $config['show'] ? 'show' : '';
        $id = $config['id'];
        $title = $config['title'];
        echo "<div class='accordion mt-4' id='collapsible$id'>";
        echo '<div class="card accordion-item">';
        echo "<h2 class='accordion-header' id='heading$id'>";

        echo "<button class='accordion-button $collapse' type='button' data-bs-toggle='collapse' 
        data-bs-target='#collapse$id' aria-expanded='$show' 
        aria-controls='collapse$id'>$title</button>";

        echo "</h2>";
        echo "<div id='collapse$id' class='accordion-collapse collapse $show' 
        aria-labelledby='heading$id' data-bs-parent='#collapsible$id'>";

        echo "<div class='accordion-body'>";
    }

    public static function end()
    {
        echo "</div></div></div></div>";
    }
}
