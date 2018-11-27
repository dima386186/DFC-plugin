<button class="btn btn-success btn-lg pull-right dcf-create">Создать</button>
<div class="fields-settings-dcf">
	<? include 'modal-for-create.php'?>
	<? include 'modal-for-update.php'?>
	<? include 'modal-for-delete.php'?>

	<? foreach ($data['model'] as $data) : ?>
		<? include 'field-block.php'?>
	<? endforeach ?>
</div>

