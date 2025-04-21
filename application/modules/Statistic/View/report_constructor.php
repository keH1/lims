<div class="mb-4">
    <div class="row">
        <div class="col">
            <select id="select_entities" class="form-control">
                <?php foreach ($this->data['entities'] as $key => $entity): ?>
                    <option value="<?=$key?>"><?=$entity['title']?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-auto">
            <button id="generate_journal" type="button" class="btn btn-outline-secondary">Сгенерировать</button>
        </div>

    </div>
</div>

<div class="d-none">
    <div class="row">
        <div class="col-6">
            <div class="panel mb-0">
                <div id="chart-donut-1" class="chart-donut"></div>
            </div>
        </div>
        <div class="col-6">
            <div class="panel mb-0">
                <div id="chart-donut-2" class="chart-donut"></div>
            </div>
        </div>
    </div>
</div>

<div class="filters mb-4">
    <div class="row">
        <div class="col">
            <input type="date" id="inputDateStart" class="form-control filter filter-date-start" value="2023-01-01">
        </div>

        <div class="col">
            <input type="date" id="inputDateEnd" class="form-control filter filter-date-end" value="<?=date('Y-m-d')?>">
        </div>

        <div class="col-auto">
            <button type="button" class="btn btn-outline-secondary filter-btn-reset">Сбросить</button>
        </div>
    </div>
</div>

<table id="journal_all" class="table table-striped journal">
    <thead>
    <tr class="table-light header-title">
    </tr>
    <tr class="header-search">
    </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<div id="chart-bar-block" class="mb-4 mt-4 d-none">
    <div class="row">
        <div class="col">
            <div class="panel mb-0">
                <canvas id="chart-bar"></canvas>
            </div>
        </div>
    </div>
</div>
