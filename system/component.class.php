<?php
class Component
{
    public static function createTitle($title) {
        echo '<div class="title">';
        echo '<h3>'.$title.'</h3>';
        echo '</div>';
        echo '<br>';
    }
    public static function createTable() {
        echo 'ciao';
    }
}