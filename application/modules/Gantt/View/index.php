<?php include "helpers.php"; ?>

<div class="container">
    <div class="container _table-responsive-xxl" style="overflow: auto;">
        <button data-js-create-user class="btn btn-primary mb-3" style="position: relative; display: block;">Добавить
            пользователя
        </button>
        <button data-js-create-project class="btn btn-primary mb-3" style="position: relative; display: block;">Добавить
            проект
        </button>
        <b onclick="openSecondView();" class="btn btn-secondary mb-3" style="position: relative; display: block;">Пользователи</b>

        <?php include "filter.php"; ?>

        <table class="scrollable-table _table _table-bordered" id="gantt_table">
            <thead>
            <tr>
                <th scope="col" class="text-center fixed-column" style="width: 500px;"><span></span></th>
                <?php foreach ($this->data['calendar'] as $month) { ?>
                    <th scope="col" colspan="<?php echo count($month['days']) ?>"
                        class="text-center"><?php echo $month['local_name'] ?></th>
                <?php } ?>
            </tr>
            <tr>
                <th class="fixed-column"></th>
                <!-- Заголовки для дней в месяце -->
                <?php foreach ($this->data['calendar'] as $month) {
                    foreach ($month['days'] as $day) { ?>
                        <td id="sep_<?= $day ?>" <?php if ($this->data['current_date']['month']['name'] . "_" . $this->data['current_date']['day'] == $month['local_name'] . "_" . $day) {
                            echo 'style="background: #ffa50087;"';
                        } ?>>
                            <span><?= $day ?></span>
                        </td>
                    <?php }
                }
                ?>
            </tr>
            </thead>
            <tbody>

            <?php foreach ($this->data['table']['projects'] as $project) { ?>
                <tr>
                    <td style="display: none;"></td>
                    <td rowspan="1" class="fixed-column"
                        onclick="editProject(<?= $project['id'] ?>);"><?= $project['project_name'] ?></td>
                    <?php echo renderCells($this->data['calendar'], $this->data['current_date']); ?>
                </tr>

                <?php foreach ($project['members'] as $user) {
                    $timeLinesCount = $user->getTimeLinesCountByProjectId();
                    ?>
                    <tr>
                        <td onclick="editUser(<?= $user->getId(); ?>, <?= $user->getProjectId(); ?>)"
                            class="fixed-column"
                            rowspan="<?= $timeLinesCount ?>"
                            style="background: #80808038; text-align: right;">
                            <span><?= $user->getName(); ?></span></td>
                        <?php
                        $timeLines = $user->getTimeLinesByProjectId();

                        for ($i = 0; $i < $timeLinesCount; $i++) {
                            echo renderCells($this->data['calendar'],
                                $this->data['current_date'],
                                $timeLines[$i],
                                $user->getId(),
                                $project['id'],
                                $project['color1'],
                                $project['color2'],
                                $i,
                            );
                        }
                        ?>
                    </tr>
                <?php } ?>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>


<?php include "gantt_modals.php" ?>

<?php include "gantt_script.php"; ?>