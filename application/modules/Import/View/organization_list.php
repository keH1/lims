<!--<header class="header-requirement mb-3">-->
<!--    <nav class="header-menu">-->
<!--        <ul class="nav">-->
<!--            <li class="nav-item me-2">-->
<!--                <a class="nav-link" href="" title="Добавить">-->
<!--                    Добавить-->
<!--                </a>-->
<!--            </li>-->
<!--        </ul>-->
<!--    </nav>-->
<!--</header>-->

<div class="panel panel-default">
    <header class="panel-heading">
        Организации
        <span class="tools float-end"><a href="#" class="fa fa-chevron-up"></a></span>
    </header>
    <div class="panel-body">

        <a href="#popup_form" class="popup-with-form btn btn-success mb-2">Добавить</a>

        <table id="journal_org" class="table table-striped journal">
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

<form id="popup_form" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative" action="/ulab/import/orgInsertUpdate" method="post">
    <div class="title mb-3 h-2">
        Данные организации
    </div>

    <div class="line-dashed-small"></div>

    <input type="hidden" id="form_entity_id" name="org_id" value="">

    <div class="mb-3">
        <label class="form-label" for="form_entity_name">Наименование *</label>
        <input type="text" class="form-control" id="form_entity_name" name="form[name]" value="" required>
    </div>

    <div class="mb-3">
        <label class="form-label" for="form_entity_head">Руководитель</label>
        <select id="form_entity_head" class="form-control select2" name="form[head_user_id]">
            <option value="">Не выбран</option>
            <?php foreach ($this->data['users'] as $user): ?>
                <option value="<?=$user['ID']?>"><?=$user['NAME']?> <?=$user['LAST_NAME']?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Сохранить</button>
</form>