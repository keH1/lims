<div class="fire-safety-wrapper">
    <header class="mb-3">
        <nav class="header-menu">
            <ul class="nav">
                <li class="nav-item me-2">
                    <button type="button" class="btn bg-white btn-square mt-0 add-instruction"
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

    <table id="fireSafetyLog" class="table table-striped journal text-center">
        <thead>
        <tr class="table-light align-middle">
            <th scope="col" rowspan="2">Дата</th>
            <th scope="col" rowspan="2">Вид проводимого инструктажа</th>
            <th scope="col" colspan="2">Инструктируемый</th>
            <th scope="col">Теоретическая часть</th>
            <th scope="col" rowspan="2">Дата</th>
            <th scope="col">Практическая часть</th>
        </tr>
        <tr class="table-light align-middle">
            <th scope="col">Фамилия, имя, отчество</th>
            <th scope="col">Профессия, должность</th>
            <th scope="col">Фамилия, имя, отчество  инструктирующего, номер документа об образовании и (или) квалификации, документа об обучении</th>
            <th scope="col">Фамилия, имя, отчество инструктирующего, номер документа об образовании и (или) квалификации, документа об обучении</th>
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
            <th scope="col">
                <input type="text" class="form-control search">
            </th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    <form id="fireSafetyModalForm" class="bg-light mfp-hide col-md-6 m-auto p-3 position-relative"
          action="/ulab/fireSafety/insert/" method="post">

        <div class="modal-header mb-3 h-2">
            Данные
        </div>

        <div class="line-dashed-small"></div>

        <div class="modal-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="theoryDate" class="form-label">Дата теоретического инструктажа <span class="text-danger">*</span></label>
                    <input type="date" class="form-control bg-white" id="theoryDate" name="form[theory_date]" required>
                </div>

                <div class="col-md-6">
                    <label for="instructionType" class="form-label">Вид инструктажа <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="instructionType" name="form[instruction_type]" required>
                </div>

                <div class="col-12">
                    <h6 class="mt-4 mb-3 border-bottom">Данные инструктируемого</h6>
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="instructedName" class="form-label">ФИО <span class="text-danger">*</span></label>
                            <select class="form-control" id="instructedName" name="form[instructed_id]" required>
                                <option value='' selected>Выберите инструктируемого</option>
                                <?php if ($this->data['users']): ?>
                                    <?php foreach ($this->data['users'] as $user): ?>
                                        <option value="<?= $user['ID'] ?? '' ?>"><?= $user['NAME'] ?> <?= $user['SECOND_NAME'] ?> <?= $user['LAST_NAME'] ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <h6 class="mt-4 mb-3 border-bottom">Теоретическая часть</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="theoryInstructorLastname" class="form-label">Фамилия инструктора <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="theoryInstructorLastname" name="form[theory_instructor_lastname]" required>
                        </div>
                        <div class="col-md-6">
                            <label for="theoryInstructorName" class="form-label">Имя инструктора <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="theoryInstructorName" name="form[theory_instructor_name]" required>
                        </div>
                        <div class="col-md-6">
                            <label for="theoryInstructorSecondname" class="form-label">Отчество инструктора <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="theoryInstructorSecondname" name="form[theory_instructor_secondname]" required>
                        </div>
                        <div class="col-md-6">
                            <label for="theoryInstructorDoc" class="form-label">Документ инструктора <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="theoryInstructorDoc" name="form[theory_instructor_doc]"
                                   placeholder="Номер документа" required>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <h6 class="mt-4 mb-3 border-bottom">Практическая часть</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="practiceDate" class="form-label">Дата практического инструктажа</label>
                            <input type="date" class="form-control bg-white" id="practiceDate" name="form[practice_date]">
                        </div>
                        <div class="col-md-6">
                            <label for="practiceInstructorLastname" class="form-label">Фамилия инструктора</label>
                            <input type="text" class="form-control" id="practiceInstructorLastname" name="form[practice_instructor_lastname]">
                        </div>
                        <div class="col-md-6">
                            <label for="practiceInstructorName" class="form-label">Имя инструктора</label>
                            <input type="text" class="form-control" id="practiceInstructorName" name="form[practice_instructor_name]">
                        </div>
                        <div class="col-md-6">
                            <label for="practiceInstructorSecondname" class="form-label">Отчество инструктора</label>
                            <input type="text" class="form-control" id="practiceInstructorSecondname" name="form[practice_instructor_secondname]">
                        </div>
                        <div class="col-12">
                            <label for="practiceInstructorDoc" class="form-label">Документ инструктора</label>
                            <input type="text" class="form-control" id="practiceInstructorDoc" name="form[practice_instructor_doc]"
                                   placeholder="Номер документа">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="line-dashed-small"></div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </div>
    </form>
</div>
