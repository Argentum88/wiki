<?php
/* @var $this PageController */
/* @var $model Page */

$this->menu=array(
	array('label'=>'Create Page', 'url'=>array('create', 'id'=>$model->id)),
	array('label'=>'Update Page', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Page', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
);

$this->menu_child = $model->generateChildrenMenuItems();
?>

<div>Родительская страница:
    <?php
        if($model->getTitleParentPage()=='нет')
            echo 'нет';
        else
            echo CHtml::link($model->getTitleParentPage(), array('view', 'id'=>$model->parent));
    ?>
</div>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'title',
		'content',
		'url',
	),
)); ?>
