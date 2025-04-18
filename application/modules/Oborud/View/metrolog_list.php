<header class="header-requirement mb-3">
    <nav class="header-menu">
        <ul class="nav">
            <li class="nav-item me-2">
                <a class="nav-link" href="/protocol_generator/passport_rc.php" title="Скачать формы 2-5">
                    Скачать формы 2-5
                </a>
            </li>
        </ul>
    </nav>
</header>

<div class="filters mb-4">
    <div class="row">
        <div class="col">
            <select id="selectLab" class="form-control filter filter-lab">
                <option value="">Все лаборатории</option>
                <option value="-1">Вне лабораторий</option>
                <?php if ($this->data['lab']): ?>
                    <?php foreach ($this->data['lab'] as $lab): ?>
                        <option value="<?= $lab['DEPARTMENT'] ?? '' ?>"><?= $lab['NAME'] ?? '' ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div class="col-auto">
            <button type="button" class="btn btn-outline-secondary filter-btn-reset">Сбросить</button>
        </div>
    </div>
</div>

<div class="panel panel-default">
    <header class="panel-heading">
        Оборудование с истёкшим сроком проверки
        <span class="tools float-end"><a href="#" class="fa fa-chevron-up"></a></span>
    </header>
    <div class="panel-body">
        <table id="journal_end" class="table table-striped journal">
            <thead>
            <tr class="table-light">
                <th scope="col"></th>
                <th scope="col" class="text-nowrap">Наименование</th>
                <th scope="col" class="text-nowrap">Тип</th>
                <th scope="col" class="text-nowrap">Заводской номер</th>
                <th scope="col" class="text-nowrap">Инвентарный номер</th>
                <th scope="col" class="text-nowrap">Поверка "До"</th>
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
                <th scope="col">
                </th>
                <th scope="col">
                </th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>


<div class="panel panel-default">
    <header class="panel-heading">
        Оборудование у которого истекает срок проверки
        <span class="tools float-end"><a href="#" class="fa fa-chevron-up"></a></span>
    </header>
    <div class="panel-body">
        <table id="journal_close_end" class="table table-striped journal">
            <thead>
            <tr class="table-light">
                <th scope="col"></th>
                <th scope="col" class="text-nowrap">Наименование</th>
                <th scope="col" class="text-nowrap">Тип</th>
                <th scope="col" class="text-nowrap">Заводской номер</th>
                <th scope="col" class="text-nowrap">Инвентарный номер</th>
                <th scope="col" class="text-nowrap">Поверка "До"</th>
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
                <th scope="col">
                </th>
                <th scope="col">
                </th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>


<div class="panel panel-default">
    <header class="panel-heading">
        Требует проверки отделом метрологии
        <span class="tools float-end"><a href="#" class="fa fa-chevron-up"></a></span>
    </header>
    <div class="panel-body">
        <table id="journal_need_check" class="table table-striped journal">
            <thead>
            <tr class="table-light">
                <th scope="col"></th>
                <th scope="col" class="text-nowrap">Наименование</th>
                <th scope="col" class="text-nowrap">Тип</th>
                <th scope="col" class="text-nowrap">Заводской номер</th>
                <th scope="col" class="text-nowrap">Инвентарный номер</th>
                <th scope="col" class="text-nowrap">Поверка "До"</th>
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
                <th scope="col">
                </th>
                <th scope="col">
                </th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>


<div class="panel panel-default">
    <header class="panel-heading">
        Статистика по оборудованию
        <span class="tools float-end"><a href="#" class="fa fa-chevron-up"></a></span>
    </header>
    <div class="panel-body">
        <table id="journal_statistics" class="table table-striped journal w-50">
            <tr>
                <th style="width: 50px"></th>
                <th>Статус оборудования</th>
                <th class="text-center">Кол-во единиц</th>
            </tr>
            <tr>
                <td>
                    <div class="stage rounded bg-light-blue"></div>
                </td>
                <td>
                    Всего единиц оборудования
                </td>
                <td class="text-center">
                    <?=$this->data['statistics']['all_oborud']?>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="stage rounded bg-red"></div>
                </td>
                <td>
                    Истёк срок проверки
                </td>
                <td class="text-center">
                    <?=$this->data['statistics']['end_verification']?>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="stage rounded" style="background-color: #999AC7; color: #999AC7"></div>
                </td>
                <td>
                    Требует проверки отделом метрологии
                </td>
                <td class="text-center">
                    <?=$this->data['statistics']['need_check']?>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="stage rounded bg-grey"></div>
                </td>
                <td>
                    На консервации
                </td>
                <td class="text-center">
                    <?=$this->data['statistics']['long_storage']?>
                </td>
            </tr>
        </table>
    </div>
</div>
