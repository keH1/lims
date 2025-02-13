<?php if ( $this->data['is_show_btn'] ): ?>
    <header class="header-requirement mb-3">
        <nav class="header-menu">
            <ul class="nav">
                <li class="nav-item me-2">
                    <a class="nav-link" href="<?=URI?>/import/organizationList/" title="Вернуться к журналу организаций">
                        <i class="fa-solid fa-arrow-left-long"></i>
                    </a>
                </li>
            </ul>
        </nav>
    </header>
<?php endif; ?>

<div class="panel panel-default">
    <header class="panel-heading">
        Общая информация
        <span class="tools float-end"><a href="#" class="fa fa-chevron-up"></a></span>
    </header>
    <div class="panel-body">
        <form action="/ulab/import/orgUpdate" method="post" class="form-horizontal">
            <input type="hidden" name="org_id" value="<?=$this->data['info']['id']?>">

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Наименование *</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="form[name]" value="<?=$this->data['info']['name']?>" required>
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Руководитель</label>
                <div class="col-sm-8">
                    <select class="form-control select2" name="form[head_user_id]">
                        <option value="">Не выбран</option>
                        <?php foreach ($this->data['users'] as $user): ?>
                            <option value="<?=$user['ID']?>" <?=$this->data['info']['head_user_id'] == $user['ID'] ? 'selected' : ''?>><?=$user['NAME']?> <?=$user['LAST_NAME']?></option>
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
        Департаменты
        <span class="tools float-end"><a href="#" class="fa fa-chevron-up"></a></span>
    </header>
    <div class="panel-body">
        <a href="#popup_form" class="popup-with-form btn btn-success mb-2">Добавить</a>

        <table id="journal_branch" class="table table-striped journal">
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

<form id="popup_form" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative" action="/ulab/import/branchInsertUpdate" method="post">
    <div class="title mb-3 h-2">
        Данные департамента
    </div>

    <div class="line-dashed-small"></div>

    <input type="hidden" id="form_entity_id" name="branch_id" value="">
    <input type="hidden" name="form[organization_id]" value="<?=$this->data['info']['id']?>">

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