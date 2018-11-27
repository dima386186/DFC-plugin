<form role="form" id="DCF-edit-field">
	<? if($data->type!='file') : ?>
		<div class="form-group">
			<label for="type">Тип поля</label>
			<select name="type" id="type" class="form-control">
				<option value="text" <? if($data->type=='text') echo 'selected' ?>>text</option>
				<option value="email" <? if($data->type=='email') echo 'selected' ?>>email</option>
				<option value="number" <? if($data->type=='number') echo 'selected' ?>>number</option>
				<option value="checkbox" <? if($data->type=='checkbox') echo 'selected' ?>>checkbox</option>
				<option value="textarea" <? if($data->type=='textarea') echo 'selected' ?>>textarea</option>
			</select>
		</div>
	<? endif ?>

	<div class="form-group FCD-value-field <? if($data->type=='file') echo 'none' ?>">
		<label for="value">Значение поля по умолчанию</label>
		<input type="text" class="form-control" name="value" id="value" placeholder="Значение поля по умолчанию" value="<? echo $data->value ?>">
	</div>

	<div class="form-group FCD-placeholder-field <? if($data->type=='file') echo 'none' ?>">
		<label for="placeholder">Placeholder для поля</label>
		<input type="text" class="form-control" name="placeholder" id="placeholder" placeholder="Placeholder для поля" value="<? echo $data->placeholder ?>">
	</div>

	<div class="checkbox">
		<label>
			<input class="required" type="checkbox" name="required" id="required" <? if($data->required) echo 'checked' ?>> <b class="for-checkbox">Required для поля</b>
		</label>
	</div>

	<div class="checkbox">
		<label>
			<input type="checkbox" name="label" id="label" <? if($data->label) echo 'checked' ?>> <b class="for-checkbox">Label для поля</b>
		</label>
	</div>

	<div class="form-group <? if(!$data->label) echo 'none' ?>">
		<label for="label_text">Текст для Label</label>
		<input type="text" class="form-control" name="label_text" id="label_text" placeholder="Текст для Label" value="<? echo $data->label_text ?>">
	</div>

	<div class="form-group">
		<label for="wrap_classes">Классы для Wrapper (через пробел!)</label>
		<input type="text" class="form-control" name="wrap_classes" id="wrap_classes" placeholder="Классы для Wrapper" value="<? echo $data->wrap_classes ?>">
	</div>

	<input type="hidden" name="id" id="id" value="<? echo $data->id ?>">
	<input type="hidden" name="place" id="place" value="<? echo $data->place ?>">

	<button type="submit" class="btn btn-primary">Редактировать</button>
</form>