<?php include "helpers.php"; ?>

    <div class="container">
        <div class="container" style="overflow: auto;">
            <button data-js-create-user class="btn btn-primary mb-3" style="position: relative; display: block;">
                Добавить
                пользователя
            </button>
            <button data-js-create-project class="btn btn-primary mb-3" style="position: relative; display: block;">
                Добавить
                проект
            </button>
            <button onclick="openSecondView();" class="btn btn-secondary mb-3" style="position: relative; display: block;">Проекты</button>

            <?php include "filter.php"; ?>

            <table class="scrollable-table" id="gantt_table">
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
                <?php foreach ($this->data['table']['table_info'] as $member) { ?>
                    <tr>
                        <td style="display: none;"></td>
                        <td rowspan="1" class="fixed-column"
                            onclick="editUser(<?= $member->getId(); ?>);"><?= $member->getName(); ?></td>
                        <?php echo renderCells($this->data['calendar'], $this->data['current_date']); ?>
                    </tr>

                    <?php foreach ($member->getProjects() as $project) {
                        $timeLinesCount = count($project->getTimeLines());
                        ?>
                        <tr>
                            <td onclick="editProject(<?= $project->getId(); ?>, <?= $member->getId(); ?>)"
                                class="fixed-column"
                                rowspan="<?= $timeLinesCount ?>"
                                style="background: #80808038; text-align: right;">
                                <span><?= $project->getName(); ?></span></td>
                            <?php
                            $timeLines = $project->getTimeLines();

                            for ($i = 0; $i < $timeLinesCount; $i++) {
                                echo renderCells($this->data['calendar'],
                                    $this->data['current_date'],
                                    $timeLines[$i],
                                    $member->getId(),
                                    $project->getId(),
                                    $project->getColor1(),
                                    $project->getColor2(),
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