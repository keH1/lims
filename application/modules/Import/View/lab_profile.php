<header class="header-requirement mb-3">
    <nav class="header-menu">
        <ul class="nav">
            <li class="nav-item me-2">
                <a class="nav-link" href="<?=URI?>/import/dep/<?=$this->data['info']['dep_id']?>" title="Вернуться к отделу">
                    <i class="fa-solid fa-arrow-left-long"></i>
                </a>
            </li>
        </ul>
    </nav>
</header>

<div class="panel panel-default">
    <header class="panel-heading">
        Общая информация
        <span class="tools float-end"><a href="#" class="fa fa-chevron-up"></a></span>
    </header>
    <div class="panel-body">
        <form action="/ulab/import/labUpdate" method="post" class="form-horizontal">
            <input type="hidden" name="lab_id" id="lab_id" value="<?=$this->data['info']['ID']?>">
            <input type="hidden" name="form[bitrix_dep_id]" id="bitrix_dep_id" value="<?=$this->data['info']['id_dep']?>">

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Организация</label>
                <div class="col-sm-8">
                    <label class="col-form-label"><?=$this->data['org_info']['name']?></label>
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Департамент</label>
                <div class="col-sm-8">
                    <label class="col-form-label"><?=$this->data['branch_info']['name']?></label>
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Отдел</label>
                <div class="col-sm-8">
                    <label class="col-form-label"><?=$this->data['dep_info']['name']?></label>
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Наименование *</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="form[NAME]" value="<?=$this->data['info']['NAME']?>" required>
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Руководитель</label>
                <div class="col-sm-8">
                    <select class="form-control select2" name="form[HEAD_ID]">
                        <option value="-1">Не выбран</option>
                        <?php foreach ($this->data['users'] as $user): ?>
                            <option value="<?=$user['ID']?>" <?=$this->data['info']['HEAD_ID'] == $user['ID'] ? 'selected' : ''?>><?=$user['NAME']?> <?=$user['LAST_NAME']?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-sm-2"></div>
            </div>

            <button type="submit" class="btn btn-primary">Сохранить</button>
        </form>
    </div>
</div>

<div class="panel panel-default">
    <header class="panel-heading">
        Помещения лаборатории
        <span class="tools float-end"><a href="#" class="fa fa-chevron-up"></a></span>
    </header>
    <div class="panel-body">
        <a href="#popup_form_rooms" class="popup-with-form btn btn-success mb-2">Добавить</a>

        <a href="/ulab/import/rooms/<?=$this->data['info']['ID']?>" class="ms-2">Управление помещениями</a>

        <table id="journal_rooms" class="table table-striped journal">
            <thead>
            <tr class="table-light">
                <th scope="col" class="text-nowrap">Название</th>
                <th scope="col" class="text-nowrap"></th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<div class="panel panel-default">
    <header class="panel-heading">
        Сотрудники
        <span class="tools float-end"><a href="#" class="fa fa-chevron-up"></a></span>
    </header>
    <div class="panel-body">
        <a href="#popup_form_users" class="popup-with-form btn btn-success mb-2">Добавить</a>

        <a href="/ulab/user/list/" class="ms-2">Управление кадрами</a>

        <a href="/ulab/permission/users/" class="ms-2">Настройка ролей</a>

        <table id="journal_users" class="table table-striped journal">
            <thead>
            <tr class="table-light">
                <th scope="col" class="text-nowrap">ФИО</th>
                <th scope="col" class="text-nowrap">Должность</th>
                <th scope="col" class="text-nowrap">Статус</th>
                <th scope="col" class="text-nowrap">Заменяет</th>
                <th scope="col" class="text-nowrap"></th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<div class="panel panel-default">
    <header class="panel-heading">
        Справочники
        <span class="tools float-end"><a href="#" class="fa fa-chevron-up"></a></span>
    </header>
    <div class="panel-body">

        <li class="list-group-item ">
            <div class="form-group row mb-0 align-items-center">
                <label class="col-sm-4 col-form-label">Единицы измерений</label>
                <div class="col">
                    <a href="/ulab/reference/unitList/"  title="Журнал единиц измерений" data-bs-placement="left" data-bs-toggle="tooltip">
                        Редактировать
                    </a>
                </div>
            </div>
        </li>
        <li class="list-group-item  ">
            <div class="form-group row mb-0 align-items-center">
                <label class="col-sm-4 col-form-label">Определяемые характеристики</label>
                <div class="col">
                    <a href="/ulab/reference/measuredPropertiesList/"  title="Журнал определяемых характеристик" data-bs-placement="left" data-bs-toggle="tooltip">
                        Редактировать
                    </a>
                </div>
            </div>
        </li>

        <li class="list-group-item  ">
            <div class="form-group row mb-0 align-items-center">
                <label class="col-sm-4 col-form-label">Оборудование</label>
                <div class="col">
                    <a href="/ulab/oborud/list/"  title="Журнал оборудования, Импорт и экспорт" data-bs-placement="left" data-bs-toggle="tooltip">
                        Редактировать
                    </a>
                </div>
            </div>
        </li>

        <li class="list-group-item  ">
            <div class="form-group row mb-0 align-items-center">
                <label class="col-sm-4 col-form-label">Область аккредитации</label>
                <div class="col">
                    <a href="/ulab/gost/list/"  title="Журнал областей аккредитации, Импорт и экспорт" data-bs-placement="left" data-bs-toggle="tooltip">
                        Редактировать
                    </a>
                </div>
            </div>
        </li>

        <li class="list-group-item  ">
            <div class="form-group row mb-0 align-items-center">
                <label class="col-sm-4 col-form-label">Нормативная документация</label>
                <div class="col">
                    <a href="/ulab/normDocGost/list/"  title="Журнал НД" data-bs-placement="left" data-bs-toggle="tooltip">
                        Редактировать
                    </a>
                </div>
            </div>
        </li>

        <li class="list-group-item  ">
            <div class="form-group row mb-0 align-items-center">
                <label class="col-sm-4 col-form-label">Объекты испытаний</label>
                <div class="col">
                    <a href="/ulab/material/list/"  title="Журнал объектов испытаний" data-bs-placement="left" data-bs-toggle="tooltip">
                        Редактировать
                    </a>
                </div>
            </div>
        </li>

<!--        <li class="list-group-item  ">-->
<!--            <div class="form-group row mb-0 align-items-center">-->
<!--                <label class="col-sm-4 col-form-label">Прайс</label>-->
<!--                <div class="col">-->
<!--                    <a href="/ulab/gost/listPrice/" >-->
<!--                        Редактировать-->
<!--                    </a>-->
<!--                </div>-->
<!--            </div>-->
<!--        </li>-->
    </div>
</div>

<form id="popup_form_rooms" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative" action="/ulab/import/roomInsertUpdate" method="post">
    <div class="title mb-3 h-2">
        Данные помещения
    </div>

    <div class="line-dashed-small"></div>

    <input type="hidden" id="form_entity_id" name="room_id" value="">
    <input type="hidden" name="form[LAB_ID]" value="<?=$this->data['info']['ID']?>">

    <div class="mb-3">
        <label class="form-label" for="form_entity_name">Наименование *</label>
        <input type="text" class="form-control" id="form_entity_name" name="form[NAME]" value="" required>
    </div>

    <div class="mb-3">
        <label class="form-label" for="form_entity_number">Номер комнаты</label>
        <input type="text" class="form-control" id="form_entity_number" name="form[NUMBER]" value="">
    </div>

    <button type="submit" class="btn btn-primary">Сохранить</button>
</form>

<form id="popup_form_users" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative" action="/ulab/import/addAffiliationUser" method="post">
    <div class="title mb-3 h-2">
        Добавить сотрудника
    </div>

    <div class="line-dashed-small"></div>

    <input type="hidden" name="lab_id" value="<?=$this->data['info']['ID']?>">

    <div class="mb-3">
        <label class="form-label" for="form_entity_user_id">Сотрудник <span class="redStars">*</span></label>
        <select id="form_entity_user_id" class="form-control select2" name="user_id" required>
            <option value="">Не выбран</option>
            <?php foreach ($this->data['not_affiliation_users'] as $user): ?>
                <option value="<?=$user['ID']?>" data-position="<?=$user['WORK_POSITION']?>"><?=$user['NAME']?> <?=$user['LAST_NAME']?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label" for="form_entity_position">Должность <span class="redStars">*</span></label>
        <select id="form_entity_position" class="form-control select2" name="position" required>
            <option value="">Не выбрана</option>
            <?php foreach ($this->data['position_list'] as $position): ?>
                <option value="<?= $position ?>"><?= $position ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label" for="form_entity_status">Статус <span class="redStars">*</span></label>
        <select id="form_entity_status" class="form-control select2" name="status" required>
            <option value="">Не выбран</option>
            <?php foreach ($this->data['status_list'] as $key => $status): ?>
                <option value="<?= $key ?>"><?= $status ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label" for="form_entity_replace">Заменяет</label>
        <select id="form_entity_replace" class="form-control select2" name="replace">
            <option value="">Не выбран</option>
            <?php foreach ($this->data['users'] as $user): ?>
                <option value="<?=$user['ID']?>"><?=$user['NAME']?> <?=$user['LAST_NAME']?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Сохранить</button>
</form>

<form id="popup_form_users_edit" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative" action="/ulab/import/addAffiliationUser" method="post">
    <div class="title mb-3 h-2">
        Данные сотрудника
    </div>

    <div class="line-dashed-small"></div>

    <input type="hidden" name="lab_id" value="<?=$this->data['info']['ID']?>">

    <div class="mb-3">
        <label class="form-label" for="form_entity_user_id">Сотрудник</label>
        <select id="form_entity_user_id" class="form-control select2" name="user_id" readonly>
            <?php foreach ($this->data['users'] as $user): ?>
                <option value="<?=$user['ID']?>" data-position="<?=$user['WORK_POSITION']?>"><?=$user['NAME']?> <?=$user['LAST_NAME']?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label" for="form_entity_position">Должность</label>
        <select id="form_entity_position" class="form-control select2" name="position" readonly>
            <option value="">Не выбрана</option>
            <?php foreach ($this->data['position_list'] as $position): ?>
                <option value="<?= $position ?>"><?= $position ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label" for="form_entity_status">Статус <span class="redStars">*</span></label>
        <select id="form_entity_status" class="form-control select2" name="status" required>
            <?php foreach ($this->data['status_list'] as $key => $status): ?>
                <option value="<?= $key ?>"><?= $status ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label" for="form_entity_replace">Заменяет</label>
        <select id="form_entity_replace" class="form-control select2" name="replace">
            <option value="" selected>Не выбран</option>
            <?php foreach ($this->data['users'] as $user): ?>
                <option value="<?=$user['ID']?>"><?=$user['NAME']?> <?=$user['LAST_NAME']?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Сохранить</button>
</form>