<div class="DCF-wrapper">
    <form id="DCF">
        <? foreach ( $data['model'] as $field ) : ?>
            <div class="<? echo $field->wrap_classes ?>">
                <? if ( $field->label ) : ?>
                    <label for="<? echo $field->name ?>"><? echo $field->label_text ?></label>
                <? endif ?>
                <? if ( $field->type != 'textarea' ) : ?>
                    <input type="<? echo $field->type ?>" <? if($field->placeholder) echo 'placeholder='.$field->placeholder ?> name="<? echo $field->name ?>" <? if($field->value) echo "value={$field->value}" ?> <? if($field->type == 'file') echo 'accept="image/*"' ?> <? if($field->required) echo 'required' ?>>
                <? else : ?>
                    <textarea name="<? echo $field->name ?>" placeholder="<? echo $field->placeholder ?>" <? if($field->type == 'file') echo 'required' ?>><?  echo $field->value ?></textarea>
                <? endif ?>
            </div>
        <? endforeach ?>
        <input type="submit" value="Отправить">
    </form>
</div>
