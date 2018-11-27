<div class="main-settings-dcf" data-id="<? echo $data['model']->id ?>">
    <div class="form-group" data-validate="email">
        <label for="set_where">Кому</label>
        <input type="email" class="form-control" id="set_where" value="<? echo $data['model']->set_where ?>">
        <button class="btn btn-success create-main-setting-dcf" data-handler="set_where">Сохранить</button>
    </div>
    <div class="form-group" data-validate="email">
        <label for="set_from">От кого</label>
        <input type="email" class="form-control" id="set_from" value="<? echo $data['model']->set_from ?>">
        <button class="btn btn-success create-main-setting-dcf" data-handler="set_from">Сохранить</button>
    </div>
    <div class="form-group" data-validate="minlength" data-minlength="2">
        <label for="subject">Тема</label>
        <input type="text" class="form-control" id="subject" value="<? echo $data['model']->subject ?>">
        <button class="btn btn-success create-main-setting-dcf" data-handler="subject">Сохранить</button>
    </div>
    <div class="form-group" data-validate="minlength" data-minlength="2">
        <label for="message">Сообщение</label>
        <textarea id="message" class="form-control" cols="30" rows="10"><? echo $data['model']->message ?></textarea>
        <button class="btn btn-success create-main-setting-dcf" data-handler="message">Сохранить</button>
    </div>
</div>

