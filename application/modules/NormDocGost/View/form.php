<header class="header-requirement mb-3">
    <nav class="header-menu">
        <ul class="nav">
            <li class="nav-item me-2">
                <a class="nav-link" href="<?=URI?>/normDocGost/list/" title="Вернуться к списку">
                    <i class="fa-solid fa-list"></i>
                </a>
            </li>
            <li class="nav-item me-2">
                <a class="nav-link" href="<?=URI?>/normDocGost/new/" title="Новый ГОСТ">
                    <i class="fa-solid fa-plus"></i>
                </a>
            </li>
            <?php if ( !empty($this->data['id']) ): ?>
                <li class="nav-item me-2">
                    <a class="nav-link disable-after-click" href="<?=URI?>/normDocGost/copyGost/<?=$this->data['id']?>" title="Скопировать ГОСТ">
                        <i class="fa-regular fa-copy icon-fix"></i>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</header>


<div class="panel panel-default">
    <header class="panel-heading">
        ГОСТ
        <span class="tools float-end">
            <a href="#" class="fa fa-chevron-up"></a>
        </span>
    </header>
    <div class="panel-body">
        <form class="form-horizontal" method="post" action="<?=URI?>/normDocGost/insertUpdate/">
            <?php if ( isset($this->data['id']) && !empty($this->data['id']) ): ?>
                <input type="hidden" value="<?=$this->data['id']?>" name="id" id="gost-id">
            <?php endif; ?>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Номер документа <span class="redStars">*</span></label>
                <div class="col-sm-8">
                    <input type="text" name="form[reg_doc]" class="form-control" value="<?=$this->data['form']['reg_doc'] ?? ''?>" maxlength="64" required>
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Год</label>
                <div class="col-sm-8">
                    <input type="number" name="form[year]" class="form-control appearance-none" value="<?=$this->data['form']['year'] ?? ''?>" maxlength="4" max="3000">
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Наименование документа</label>
                <div class="col-sm-8">
                    <textarea name="form[description]" class="form-control" style="height: 80px;"><?=$this->data['form']['description'] ?? ''?></textarea>
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Наименование объекта <span class="redStars">*</span></label>
                <div class="col-sm-8">
                    <textarea name="form[materials]" class="form-control" style="height: 80px;"><?=$this->data['form']['materials'] ?? ''?></textarea>
                </div>
                <div class="col-sm-2"></div>
            </div>

<!--            <div class="form-group row">-->
<!--                <label class="col-sm-2 col-form-label">Код ТН ВЭД ЕАЭС</label>-->
<!--                <div class="col-sm-8">-->
<!--                    <textarea name="form[code_eaes]" class="form-control" style="height: 80px;">--><?//=$this->data['form']['code_eaes'] ?? ''?><!--</textarea>-->
<!--                </div>-->
<!--                <div class="col-sm-2"></div>-->
<!--            </div>-->

<!--            <div class="form-group row">-->
<!--                <label class="col-sm-2 col-form-label">Код ОКПД 2</label>-->
<!--                <div class="col-sm-8">-->
<!--                    <textarea name="form[code_okpd2]" class="form-control" style="height: 80px;">--><?//=$this->data['form']['code_okpd2'] ?? ''?><!--</textarea>-->
<!--                </div>-->
<!--                <div class="col-sm-2"></div>-->
<!--            </div>-->

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Область и условия применения</label>
                <div class="col-sm-8">
                    <textarea name="form[area_application]" class="form-control" style="height: 80px;"><?=$this->data['form']['area_application'] ?? ''?></textarea>
                </div>
                <div class="col-sm-2"></div>
            </div>

            <div class="line-dashed"></div>

            <div class="d-flex justify-content-between">
                <button class="btn btn-primary" type="submit">Сохранить</button>

                <?php if ( !empty($this->data['id']) ): ?>
                    <a class="btn btn-danger"
                       href="<?=URI?>/normDocGost/nonActualGost/<?=$this->data['id']?>"
                       title="Отметить все методики в ГОСТе как неактуальные"
                       onclick="return confirm('Отметить все методики в ГОСТе как неактуальные?')"
                    >Не актуально</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>


<div class="panel panel-default" id="methods-block">
    <header class="panel-heading">
        Методики
        <span class="tools float-end">
        <a href="#" class="fa fa-chevron-up"></a>
     </span>
    </header>
    <div class="panel-body">
        <?php if ( isset($this->data['id']) && !empty($this->data['id']) ): ?>
            <button class="btn btn-success popup-with-form1" type="button">Добавить</button>

            <div class="line-dashed"></div>

            <table class="table table-striped" id="table-method">
                <thead>
                <tr class="table-light">
                    <th scope="col"></th>
                    <th scope="col">Определяемая характеристика / показатель</th>
                    <th scope="col">Пункт документа</th>
                    <th scope="col">Ед. измерения</th>
                    <th scope="col"></th>
                </tr>
                <tr class="table-light">
                    <th scope="col"></th>
                    <th scope="col"><input type="text" class="form-control search"></th>
                    <th scope="col"><input type="text" class="form-control search"></th>
                    <th scope="col"><input type="text" class="form-control search"></th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        <?php else: ?>
            <div>Создайте Нормативную документацию, чтобы добавить методики</div>
        <?php endif; ?>
    </div>
</div>



<form id="method-modal-form" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative"
      action="<?=URI?>/normDocGost/insertMethod/" method="post">
    <div class="title mb-3 h-2">
        Добавление методики
    </div>

    <div class="line-dashed-small"></div>

    <input type="hidden" name="form[gost_id]" value="<?=$this->data['id']?>">

    <div class="mb-3">
        <label class="form-label mb-1">Определяемая характеристика / показатель <span class="redStars">*</span></label>
        <input type="text" class="form-control" name="form[name]" maxlength="64" required>
    </div>

    <div class="mb-3">
        <label class="form-label mb-1">Пункт документа</label>
        <input type="text" name="form[clause]" class="form-control">
    </div>

    <div class="line-dashed-small"></div>

    <button type="submit" class="btn btn-primary">Добавить</button>
</form>
