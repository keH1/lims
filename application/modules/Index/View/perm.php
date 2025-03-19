<table class="table table-striped">
    <thead>
    <tr class="table-light">
        <th scope="col" rowspan="2">Описание</th>
        <th scope="col" class="text-center" colspan="<?=count($this->data['permission_list']?? [])?>">Роли</th>
    </tr>
    <tr class="table-light">
        <?php foreach ($this->data['permission_list'] as $datum): ?>
            <th scope="col" class="text-center"><?=$datum['name']?></th>
        <?php endforeach; ?>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($this->data['controller_method_list'] as $controller): ?>
        <tr>
            <td colspan="12"><b><?=$controller['desc']?></b></td>
        </tr>
        <?php foreach ($controller['methods'] as $datum): ?>
            <tr>
                <td><?=empty($datum['desc'])? $datum['name'] : $datum['desc']?></td>
                <?php foreach ($this->data['permission_list'] as $perm): ?>
                    <?php if ($perm['view_name'] == 'admin'): ?>
                        <td class="text-center bg-green">да</td>
                    <?php else: ?>
                        <td class="text-center <?=$perm['permission'][$controller['name']][$datum['name']] == 1? 'bg-green' : ''?>"><?=$perm['permission'][$controller['name']][$datum['name']] == 1? 'да' : 'нет'?></td>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    <?php endforeach; ?>
    </tbody>
</table>