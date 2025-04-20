
<div class="filters mb-4">
    <div class="row">
        <div class="col">
            <select id="selectStage" class="form-control filter filter-stage">
                <option value="2">В ОА</option>
                <option value="3">РОА</option>
                <option value="5">Вне ОА</option>
                <option value="1">Актуальные</option>
                <option value="7">Не актуальные</option>
            </select>
        </div>

        <div class="col">
            <select id="selectLab" class="form-control filter filter-lab">
                <option value="0">Все лаборатории</option>
                <?php foreach ($this->data['lab_list'] as $item): ?>
                    <option value="<?=$item['ID']?>"><?=$item['NAME']?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-auto">
            <button type="button" class="btn btn-outline-secondary filter-btn-reset">Сбросить</button>
        </div>
    </div>
</div>


<table id="journal_matrix" class="table table-striped journal table-bordered">
    <thead>
    <tr class="table-light header-title">
        <th scope="col" class="text-nowrap">№ ОА</th>
        <th scope="col" class="text-nowrap">Номер документа</th>
        <th scope="col">Определяемая характеристика / показатель</th>
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
    </tr>
    </thead>
    <tbody>
    </tbody>
</table>