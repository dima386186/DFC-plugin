<div class="modal fade" id="ModalDCF-for-create" tabindex="-1" role="dialog"
     aria-labelledby="ModalDCF-for-createLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close"
                        data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="ModalDCF-for-createLabel">
                    Создание нового поля
                </h4>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">

                <form role="form" id="DCF-add-new-field">
                    <div class="form-group">
                        <label for="type">Тип поля</label>
                        <select name="type" id="type" class="form-control">
                            <option value="text">text</option>
                            <option value="email">email</option>
                            <option value="number">number</option>
                            <option value="file">file</option>
                            <option value="checkbox">checkbox</option>
                            <option value="textarea">textarea</option>
                        </select>
                    </div>

                    <div class="form-group FCD-name-field">
                        <label for="name">Название поля (должно быть уникальным!)</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Название поля" required>
                    </div>

                    <div class="form-group FCD-value-field">
                        <label for="value">Значение поля по умолчанию</label>
                        <input type="text" class="form-control" name="value" id="value" placeholder="Значение поля по умолчанию">
                    </div>

                    <div class="form-group FCD-placeholder-field">
                        <label for="placeholder">Placeholder для поля</label>
                        <input type="text" class="form-control" name="placeholder" id="placeholder" placeholder="Placeholder для поля">
                    </div>

                    <div class="checkbox">
                        <label>
                            <input class="required" type="checkbox" name="required" id="required"> <b class="for-checkbox">Required для поля</b>
                        </label>
                    </div>

                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="label" id="label"> <b class="for-checkbox">Label для поля</b>
                        </label>
                    </div>

                    <div class="form-group none">
                        <label for="label_text">Текст для Label</label>
                        <input type="text" class="form-control" name="label_text" id="label_text" placeholder="Текст для Label">
                    </div>

                    <div class="form-group">
                        <label for="wrap_classes">Классы для Wrapper (через пробел!)</label>
                        <input type="text" class="form-control" name="wrap_classes" id="wrap_classes" placeholder="Классы для Wrapper">
                    </div>

                    <input type="hidden" name="place" id="place">

                    <button type="submit" class="btn btn-primary">Создать</button>
                </form>

            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal">
                    Закрыть
                </button>
            </div>
        </div>
    </div>
</div>