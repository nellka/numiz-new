<ul>
	<li><?php echo CHtml::link('Создать статью',array('post/create')); ?></li>
	<li><?php //echo CHtml::link('Manage Posts',array('post/admin')); ?></li>
	<li><?php //echo CHtml::link('Approve Comments',array('comment/index')) . ' (' . Comment::model()->pendingCommentCount . ')'; ?></li>
</ul>