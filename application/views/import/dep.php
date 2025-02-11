<header class="header-requirement mb-3">
    <nav class="header-menu">
        <ul class="nav">
            <li class="nav-item me-2">
                <a class="nav-link" href="<?=URI?>/import/branch/<?=$this->data['info']['branch_id']?>" title="Вернуться к департаменту">
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
        <form action="/ulab/import/depUpdate" method="post" class="form-horizontal">
            <input type="hidden" name="dep_id" value="<?=$this->data['info']['id']?>">

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" >Организация</label>
                <div class="col-sm-8">
                    <label class="col-form-label"><?=$this->data['org_info']['name']?></label>
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" >Департамент</label>
                <div class="col-sm-8">
                    <label class="col-form-label"><?=$this->data['branch_info']['name']?></label>
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label" for="form_entity_name">Название *</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="form_entity_name" name="form[name]" value="<?=$this->data['info']['name']?>" required>
                </div>
                <div class="col-sm-2"></div>
            </div>

            <button type="submit" class="btn btn-primary">Сохранить</button>
        </form>
    </div>
</div>

<div class="panel panel-default">
    <header class="panel-heading">
        Лаборатории
        <span class="tools float-end"><a href="#" class="fa fa-chevron-up"></a></span>
    </header>
    <div class="panel-body">
        <a href="#popup_form" class="popup-with-form btn btn-success mb-2">Добавить</a>

        <table id="journal_lab" class="table table-striped journal">
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

<form id="popup_form" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative" action="/ulab/import/labImportUpdate" method="post">
    <div class="title mb-3 h-2">
        Данные лаборатории
    </div>

    <div class="line-dashed-small"></div>

    <input type="hidden" id="form_entity_id" name="lab_id" value="">
    <input type="hidden" name="form[dep_id]" value="<?=$this->data['info']['id']?>">

    <div class="mb-3">
        <label class="form-label" for="form_entity_name">Название *</label>
        <input type="text" class="form-control" id="form_entity_name" name="form[name]" value="" required>
    </div>

    <button type="submit" class="btn btn-primary">Сохранить</button>
</form>