<?php
	/* @var $this ParametersFormController */
	/* @var $model ParametersForm */
	/* @var $form CActiveForm */

	Yii::app()->getClientScript()->registerScriptFile(CHtml::asset(
		'scripts/parameters_form.js'), CClientScript::POS_HEAD);

	$this->pageTitle = Yii::app()->name . ' - Параметры';
?>

<header class = "page-header visible-xs">
	<h4>Параметры</h4>
</header>

<?php
	$form = $this->beginWidget('CActiveForm', array(
		'id' => 'parameters-form',
		'enableAjaxValidation' => true,
		'enableClientValidation' => true,
		'errorMessageCssClass' => 'alert alert-danger'
	));
?>
	<?php echo $form->errorSummary($model, NULL, NULL, array('class' =>
		'alert alert-danger')); ?>

	<div class = "panel panel-default">
		<fieldset>
			<legend>Пароль:</legend>

			<div class = "form-group">
				<?php echo $form->labelEx($model, 'password'); ?>
				<?php echo $form->passwordField($model, 'password', array(
					'class' => 'form-control',
					'autocomplete' => 'off'
				)); ?>
				<?php echo $form->error($model, 'password'); ?>
			</div>

			<div class = "form-group">
				<?php echo $form->labelEx($model, 'password_copy'); ?>
				<?php
					echo $form->passwordField($model, 'password_copy',
						array(
							'class' => 'form-control',
							'autocomplete' => 'off'
						));
				?>
				<?php echo $form->error($model, 'password_copy'); ?>
			</div>
		</fieldset>
	</div>

	<div class = "form-group">
		<?php echo $form->labelEx($model, 'posts_on_page'); ?>
		<?php
			echo $form->textField($model, 'posts_on_page', array(
				'class' => 'form-control',
				'min' => Constants::MINIMUM_POSTS_ON_PAGE,
				'max' => Constants::MAXIMUM_POSTS_ON_PAGE
			));
		?>
		<?php echo $form->error($model, 'posts_on_page'); ?>
	</div>

	<div class = "form-group">
		<?= $form->labelEx($model, 'maximal_width_of_images') ?>
		<?= $form->textField(
			$model,
			'maximal_width_of_images',
			array(
				'class' => 'form-control',
				'min' => Constants::MINIMUM_MAXIMAL_WIDTH_OF_IMAGES,
				'max' => Constants::MAXIMUM_MAXIMAL_WIDTH_OF_IMAGES
			)
		) ?>
		<?php echo $form->error($model, 'maximal_width_of_images') ?>
	</div>

	<div class = "form-group">
		<?= $form->labelEx($model, 'dropbox_access_token') ?>
		<?= $form->textField(
			$model,
			'dropbox_access_token',
			array(
				'class' => 'form-control',
				'maxlength' =>
					Constants::DROPBOX_ACCESS_TOKEN_LENGTH_MAXIMUM
			)
		) ?>
		<?= $form->error($model, 'dropbox_access_token') ?>
	</div>

	<?php echo CHtml::submitButton('Сохранить', array('class' =>
		'btn btn-primary')); ?>
<?php $this->endWidget(); ?>
