<div class="mb-3">
    <div class="row">
        <div class="col-auto ">
            <div class="two-button">
                <button type="button" name="add_entry"
                        class="btn btn-primary popup-first btn-add-entry w-100 mw-100 mt-0 btn-reactive">
                    Добавить запись
                </button>
            </div>
        </div>
    </div>
</div>


<table id="fridge_journal" class="table table-striped text-center">
    <thead>
    <tr class="table-light">
        <th scope="col">Дата проведения работ</th>
        <th scope="col">№ помещения</th>
        <th scope="col">Номер и марка кондиционера</th>
        <th scope="col">Используемое дезинфицирующее средство, концентрация</th>
        <th scope="col">Дата приготовления раствора</th>
        <th scope="col">Ответственный</th>
    </tr>
    <tr class="header-search">
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
        <th scope="col">
            <input type="text" class="form-control search">
        </th>
    </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<form id="add-entry-modal-form-first" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative"
      action="/ulab/disinfectionConditioners/addRecord/" method="post">
    <div class="title mb-3 h-2">
        Добавьте запись
    </div>

    <div class="row mb-3">
        <label class="form-label">Дата проведения работ</label>
        <div class="col">
            <input type="date" name="toSQL[disinfection_conditioners][date]" value="<?= date('Y-m-d') ?>" required>
        </div>
    </div>

    <div class="row mb-3">
        <label class="form-label">Расположение оборудования, № помещения <span class="redStars">*</span></label>
        <div class="col">
            <select name="toSQL[disinfection_conditioners][room_id]" class="form-control select-room" required>
                <option value=""></option>
                <?php foreach ($this->data['rooms'] as $room): ?>
                    <option value="<?= $room['ID'] ?>"><?= $room['NAME'] . " " .  $room['NUMBER'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <label class="form-label">Номер и марка кондиционера <span class="redStars">*</span></label>
        <div class="col">
            <input type="text" name="toSQL[disinfection_conditioners][conditioner]"
                   class="form-control bg-white conditioner" maxlength="256" required>
        </div>
    </div>

    <div class="row mb-3">
        <label class="form-label">Дезинфицирующее средство, концентрация <span class="redStars">*</span></label>
        <div class="col">
            <input type="text" name="toSQL[disinfection_conditioners][disinfectant]" class="form-control bg-white"
                   maxlength="256" required>
        </div>
    </div>
    <div class="row mb-3">
        <label class="form-label">Дата приготовления раствора</label>
        <div class="col">
            <input type="date" name="toSQL[disinfection_conditioners][date_sol]" value="<?= date('Y-m-d') ?>" required>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Отправить</button>

</form>

