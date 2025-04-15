<header class="header-requirement mb-3">
    <div class="row">
        <div class="col-2">
            <nav class="header-menu">
                <ul class="nav">
                    <li class="nav-item me-2">
                        <a class="nav-link popup-with-form" href="#zern-modal-form" title="Добавить новый зерновой состав">
                            <i class="fa-solid fa-plus icon-fix"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</header>

<table id="journal_grain" class="table table-striped journal">
    <thead>
        <tr class="table-light">
            <th scope="col" class="text-nowrap">Название материала</th>
        </tr>
        <tr class="header-search">
            <th scope="col">
                <input type="text" class="form-control search">
            </th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<form id="zern-modal-form" class="bg-light mfp-hide col-md-4 m-auto p-3 position-relative"
      action="<?=URI?>/grain/addZern/" method="post">
    <div class="title mb-3 h-2">
        Добавить сита
    </div>

    <div class="line-dashed-small"></div>

    <div class="mb-3 col">
        <label class="form-label">Название <span class="redStars">*</span></label>
        <input type="text" class="form-control" name="name" maxlength="255" value="" required>
    </div>

    <div class="line-dashed-small"></div>

    <button type="submit" class="btn btn-primary">Сохранить</button>
</form>