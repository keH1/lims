<div class="wrapper-adhesion">
    <em class="info d-block mb-4">
        <strong>Адгезия к минеральному материалу ГОСТ P 58952.10</strong>
    </em>

    <input type="hidden" id="ugtp_id" value="<?= $this->data['ugtp_id'] ?>">

    <div class="mb-3">
        <label>Оценка адгезии, %</label>
        <input type="number" step="any"
               name="form_data[<?= $this->data['ugtp_id'] ?>][form][adhesion]"
               value="<?= $this->data['measuring']['form']['adhesion'] ?>"
        >
    </div>

    <div>
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </div>
</div>