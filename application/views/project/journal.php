<div class="filters mb-3">
    <div class="row">
        <div class="col-auto">
            <button open-row-modal type="button"
                    class="btn btn-primary w-100 mw-100 mt-0">
                Добавить запись в журнал
            </button>
        </div>
    </div>
</div>

<div>
    <table id="table" class="table table-striped text-center journal" style="width=100%; min-width: 100%">
        <thead>
            <tr class="table-light align-middle">
                <th class="col-1">#</th>
                <th class="col-5">Проект</th>
                <th class="col-5" >Плановые расходы</th>
                <th class="col-1" ></th>
            </tr>
        </thead>
    </table>
</div>

<form id="row-modal" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative">
    <div class="title mb-3 h-2">
        Проект
    </div>

    <input hidden type="number" name="id">

    <div class="mb-3">
        <label for="name">Название проекта:</label>
        <input class="form-control" type="text" name="name">
    </div>

    <div class="mb-3">
        <label for="plan_expenses">Плановая сумма:</label>
        <input class="form-control bg-white" type="number" name="plan_expenses">
    </div>

    <div class="mb-3">
        <label for="bg">Выберете цвет:</label>
        <input type="color" name="bg">
    </div>

    <div class="line-dashed-small"></div>

    <div class="d-flex">
        <button type="button" id="update-row" class="btn btn-primary">Сохранить</button>
        <button type="button" data-js-close-modal class="btn btn-secondary" style="margin-left: 5px">Закрыть</button>
    </div>
</form>