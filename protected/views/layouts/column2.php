<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
<div class="span-19">
	<div id="content">
		<?php echo $content; ?>
	</div><!-- content -->
</div>
<div class="span-5 last">
	<div id="sidebar">
	<?php
		$this->beginWidget('zii.widgets.CPortlet', array(
			'title'=>'Operations',
		));
		$this->widget('zii.widgets.CMenu', array(
			'items'=>$this->menu,
			'htmlOptions'=>array('class'=>'operations'),
		));
		$this->endWidget();
	?>
	</div><!-- sidebar -->

    <div id="sidebar-child">
        <?php
        $this->beginWidget('zii.widgets.CPortlet', array(
            'title'=>'child page(s)',
        ));
        $this->widget('zii.widgets.CMenu', array(
            'items'=>$this->menu_child,
            'htmlOptions'=>array('class'=>'link'),
        ));
        $this->endWidget();
        ?>
    </div>
</div>
<?php $this->endContent(); ?>