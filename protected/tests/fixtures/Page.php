<?php
return  array(
    's1'=>array(
        'id'=>1,
        'title'=>'animal',
        'content'=>'Животные',
        'parent'=>null,
        'children'=>'cat,dog',
        'url'=>'animal'
    ),
    's2'=>array(
        'id'=>2,
        'title'=>'cat',
        'content'=>'Кот',
        'parent'=>1,
        'children'=>null,
        'url'=>'cat'
    ),
    's3'=>array(
        'id'=>3,
        'title'=>'dog',
        'content'=>'Сабаки',
        'parent'=>1,
        'children'=>'richi,rex',
        'url'=>'dog'
    ),
    's4'=>array(
        'id'=>4,
        'title'=>'richi',
        'content'=>'Ричи',
        'parent'=>3,
        'children'=>null,
        'url'=>'richi'
    ),
    's5'=>array(
        'id'=>5,
        'title'=>'rex',
        'content'=>'Рэкс',
        'parent'=>3,
        'children'=>null,
        'url'=>'rex'
    )
);