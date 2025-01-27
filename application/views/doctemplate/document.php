<header class="header-requirement mb-3">
    <nav class="header-menu">
        <ul class="nav">
            <li class="nav-item me-2">
                <a class="nav-link" href="<?=URI?>/docTemplate/listTemplate/" title="Журнал шаблонов">
                    <i class="fa-solid fa-list"></i>
                </a>
            </li>
            
            <li class="nav-item me-2">
                <a class="nav-link" href="<?=URI?>/docTemplate/listMacros/" title="Журнал макросов">
                    <i class="fa-solid fa-list"></i>
                </a>
            </li>
        </ul>
    </nav>
</header>

<form class="form-horizontal" method="post" action="<?=URI?>/generator/createDocument/">
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Шаблон <span class="redStars">*</span></label>
        <div class="col-sm-8">
            <select class="form-control" name="template_id">
                <option value="">Выбрать шаблон</option>
                <?php foreach ($this->data['template_list'] as $template): ?>
                    <option value="<?=$template['id']?>"><?=$template['name']?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-sm-2"></div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label">ИД заявки <span class="redStars">*</span></label>
        <div class="col-sm-8">
            <input type="number" name="deal_id" class="form-control" value="" required>
        </div>
        <div class="col-sm-2"></div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label">ИД протокола</label>
        <div class="col-sm-8">
            <input type="number" name="protocol_id" class="form-control" value="">
        </div>
        <div class="col-sm-2"></div>
    </div>

    <input type="hidden" name="go_to" value="/docTemplate/document/">

    <button class="btn btn-primary" type="submit">Создать</button>
</form>

<!--<br>-->
<!--<br>-->
<!---->
<!---->
<!--<a class="btn btn-primary" href="--><?//=URI?><!--/generator/createTestTable/">Тест кастомных таблиц</a>-->
