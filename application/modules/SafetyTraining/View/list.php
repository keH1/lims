<div class="safety-training-wrapper">
    <header class="mb-3">
        <nav class="header-menu">
            <ul class="nav">
                <li class="nav-item me-2">
                    <button type="button" class="btn bg-white btn-square mt-0 add-training"
                            title="Добавить инструктаж">
                        <i class="fa-solid fa-plus icon-fix"></i>
                    </button>
                </li>
            </ul>
        </nav>
    </header>

    <div class="filters mb-4">
        <div class="row">
            <div class="col">
                <input type="date" id="inputDateStart" class="form-control filter filter-date-start bg-transparent"
                       value="" placeholder="Введите дату начала:">
            </div>
            <div class="col">
                <input type="date" id="inputDateEnd" class="form-control filter filter-date-end bg-transparent"
                       value="" placeholder="Введите дату окончания:">
            </div>
            <div class="col-auto">
                <button type="button" class="btn btn-outline-secondary filter-btn-reset">Сбросить</button>
            </div>
        </div>
    </div>

    <table id="safetyTrainingLog" class="table table-striped journal text-center">
        <thead>
        <tr class="table-light align-middle">
            <th scope="col">№ п/п</th>
            <th scope="col">ФИО</th>
            <th scope="col">Вид инструктажа</th>
            <th scope="col">Дата инструктажа</th>
        </tr>
        <tr class="header-search">
            <th scope="col"></th>
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


    <form id="safetyTrainingModalForm" class="bg-light mfp-hide col-md-5 m-auto p-3 position-relative"
          action="/ulab/safetyTraining/insert/" method="post">

        <div class="modal-header mb-3 h-2">
            Данные
        </div>

        <div class="line-dashed-small"></div>

        <div class="modal-body">
            <div class="row g-3">
                <div class="col-12">
                    <label for="lastName" class="form-label">Фамилия <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="lastName" name="form[last_name]" required>
                </div>
                <div class="col-12">
                    <label for="name" class="form-label">Имя <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="form[name]" required>
                </div>
                <div class="col-12">
                    <label for="secondName" class="form-label">Отчество <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="secondName" name="form[second_name]" required>
                </div>

                <div class="col-12">
                    <label for="trainingType" class="form-label">Вид инструктажа <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="trainingType" name="form[training_type]" required>
                </div>

                <div class="col-12">
                    <label for="trainingDate" class="form-label">Дата инструктажа <span class="text-danger">*</span></label>
                    <input type="date" class="form-control bg-white" id="trainingDate" name="form[training_date]" required>
                </div>
            </div>
        </div>

        <div class="line-dashed-small"></div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </div>
    </form>
</div>
