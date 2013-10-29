<?php
	/* @var $this CController */

	Yii::app()->getClientScript()->registerCoreScript('jquery');

	$with_aside = ($this->action->id == 'list' or $this->action->id == 'view');

	$copyright_years = Constants::COPYRIGHT_START_YEAR;
	$current_year = date('Y');
	if ($current_year > Constants::COPYRIGHT_START_YEAR) {
		$copyright_years .= '-' . $current_year;
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset = "utf-8" />
		<meta name = "MobileOptimized" content = "320" />
		<meta name = "HandheldFriendly" content = "true" />
		<meta name = "viewport" content = "width=device-width" />
		<link rel = "icon" type = "image/png" href = "<?php echo Yii::app()->
			request->baseUrl; ?>/images/logo.png" />
		<link rel = "shortcut icon" type = "image/vnd.microsoft.icon" href =
			"<?php echo Yii::app()->request->baseUrl;
			?>/images/favicon_for_ie.ico" />
		<link rel = "apple-touch-icon" href = "<?php echo Yii::app()->request->
			baseUrl; ?>/images/favicon_for_ios.png" />
		<link rel = "stylesheet" href = "<?php echo Yii::app()->request->
			baseUrl; ?>/bootstrap/css/bootstrap.min.css" />
		<link rel = "stylesheet" href = "<?php echo Yii::app()->request->
			baseUrl; ?>/jquery-ui/css/theme/jquery-ui.min.css" />
		<title><?php echo CHtml::encode($this->pageTitle); ?></title>
		<link rel = "stylesheet" href = "<?php echo Yii::app()->request->
			baseUrl; ?>/css/blog.css" />
		<script src = "<?php echo Yii::app()->request->baseUrl;
			?>/bootstrap/js/bootstrap.min.js"></script>
		<script src = "<?php echo Yii::app()->request->baseUrl;
			?>/jquery-ui/js/jquery-ui.min.js"></script>
		<script src = "<?php echo Yii::app()->request->baseUrl;
			?>/js/ace/ace.js"></script>
	</head>
	<body>
		<section class = "container panel panel-default">
			<header class = "page-header">
				<h1>
					<img src = "<?php echo Yii::app()->request->baseUrl;
					?>/images/logo.png" alt = "logo" /> <?php echo CHtml::link(
						Yii::app()->name, $this->createUrl('post/list')); ?>
				</h1>
			</header>

			<?php if (!Yii::app()->user->isGuest) { ?>
			<nav>
				<?php
					$this->widget('zii.widgets.CMenu',array(
						'items' => array(
							array('label' => 'Главная', 'url' => array(
								'post/list')),
							array('label' => 'Посты', 'url' => array(
								'post/control')),
							array('label' => 'Файлы', 'url' => array(
								'site/files')),
							array('label' => 'Параметры', 'url' => array(
								'parameters/update')),
							array('label' => 'Выход', 'url' => array(
								'site/logout'))
						),
						'htmlOptions' => array('class' => 'nav nav-pills')
					));
				?>
			</nav>
			<?php } ?>

			<?php if ($with_aside) { ?>
			<div class = "row">
				<div class = "col-md-9">
			<?php } ?>
			<?php echo $content; ?>
			<?php if ($with_aside) { ?>
				</div>
				<div class = "col-md-3">
					<?php
						$this->widget('TagCloud', array(
							'title' => 'Теги:',
							'titleCssClass' => 'label ' .
								'label-primary',
							'htmlOptions' => array('class' => 'panel ' .
								'panel-default')
						));
					?>
				</div>
			</div>
			<?php } ?>

			<footer>
				<hr />
				<!-- Format of copyright symbol: http://www.copyright.ru/documents/zashita_prav_internet/copyright_in_site/ -->
				Copyright &copy; thewizardplusplus <?php echo $copyright_years;
					?> Все права защищены<br />
				<?php echo Yii::powered(); ?>
			</footer>
		</section>
	</body>
</html>