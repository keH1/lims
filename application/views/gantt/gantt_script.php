<script>
    const editProject = (projectId, userId = null) => {
        let url = "/ulab/gantt/getProject";
        let data = {
            'id': projectId,
            'user_id': userId,
        };

        $.post(url, data, function (response) {
            response = JSON.parse(response);
            if (response.error) {
                console.log(response.message);
                return;
            }

            const project = response.data;

            $('#proj_name').val(project.project_name);
            $('#project_id').val(project.id);

            // вывод таймлайнов пользователя по выбранному проекту
            if (response.data.timelines) {
                let timeLineContainer = $("#timeline_container_2").empty();
                let timeLines = response.data.timelines;
                let html = "";

                for (let i = 0; i < timeLines.length; i++) {
                    let timeLine = timeLines[i];
                    let start_date = deleteTimeFromDate(timeLine.start_date);
                    let end_date = deleteTimeFromDate(timeLine.end_date);

                    let item = `
                                <div class="row mb-3">
                                    <div class="col">
                                        <label class="form-label">Дата начала</label>
                                        <input name="dates[${i}][start_date]" required value="${start_date}" type="date" class="form-control" />
                                        <input name="dates[${i}][row_id]" value="${timeLine.id}" type="hidden" />
                                    </div>
                                    <div class="col">
                                        <label class="form-label">Дата завершения</label>
                                        <input name="dates[${i}][end_date]" value="${end_date}" type="date" class="form-control" />
                                    </div>
                                </div>
                                <div class="line-dashed-small"></div>`;

                    html += item;
                }
                timeLineContainer.append(html);
            } else{
                $('#timelines_part_2').empty();
            }


            $.magnificPopup.open({
                items: {
                    src: '#gantt-show-project-modal',
                    type: 'inline'
                },
                fixedContentPos: false,
            });
            focusFirstInput("gantt-show-project-modal");

            let users = [
                <?php
                foreach ($this->data['table']['users'] as $user) {
                    echo "
                  {
                        'id': {$user['id']},
                        'name': '" . $user['name'] . "',
                        'salary': {$user['salary']},
                        'position': '" . $user['position'] . "',
                  },
                  ";
                }?>];
            // todo: тут надо вычислить каких пользователей нет в проекте и сунуть их в селект

            putUsersInSelect(users);
        });
    }

    const putUsersInSelect = (users) => {
        let select = $('#add_users_select');
        select.empty();
        select.append(`<option value="-1">Выбрать</option> `);
        for (let user of users) {
            select.append(`<option value='${user.id}'>${user.name}</option>`);
        }
    }

    const deleteTimeFromDate = (date) => {
        if (!date) {
            return date;
        }

        return date.trim().split(" ")[0];
    }

    const editUser = (userId, projectId = null) => {
        let url = "/ulab/gantt/getUser";
        let data = {
            'id': userId,
            'project_id': projectId,
        };

        $.post(url, data, function (response) {
            response = JSON.parse(response);
            if (response.error) {
                console.log(response.message);
                return;
            }

            $('#user_name').val(response.data.name);
            $('#user_salary').val(response.data.salary);
            $('#user_position').val(response.data.position);
            $('#user_id').val(response.data.id);
            $('#user_project_id').val(projectId);

            // вывод таймлайнов пользователя по выбранному проекту
            if (response.data.timelines) {
                let timeLineContainer = $("#timeline_container").empty();
                let timeLines = response.data.timelines;
                let html = "";

                for (let i = 0; i < timeLines.length; i++) {
                    let timeLine = timeLines[i];
                    let start_date = deleteTimeFromDate(timeLine.start_date);
                    let end_date = deleteTimeFromDate(timeLine.end_date);

                    let item = `
                                <div class="row mb-3">
                                    <div class="col">
                                        <label class="form-label">Дата начала</label>
                                        <input name="dates[${i}][start_date]" required value="${start_date}" type="date" class="form-control" />
                                        <input name="dates[${i}][row_id]" value="${timeLine.id}" type="hidden" />
                                    </div>
                                    <div class="col">
                                        <label class="form-label">Дата завершения</label>
                                        <input name="dates[${i}][end_date]" value="${end_date}" type="date" class="form-control" />
                                    </div>
                                </div>
                                <div class="line-dashed-small"></div>`;

                    html += item;
                }
                timeLineContainer.append(html);
            } else{
                $('#timelines_part').empty();
            }


            $.magnificPopup.open({
                items: {
                    src: '#gantt-show-user-modal',
                    type: 'inline'
                },
                fixedContentPos: false,
            });
            focusFirstInput("gantt-show-user-modal");
        });
    }

    const submitCreateForm = (formId) => {
        let form = $(`#${formId}`);

        let inputs = $(`#${formId} input[type='text']`);

        let error = false;
        for (let elem of inputs) {
            if ($(elem).val().trim().length === 0) {
                $(elem).addClass('is-invalid');
                error = true;
            } else {
                $(elem).removeClass('is-invalid');
            }
        }

        if (!error) {
            form.submit();
            return;
        }
    };


    const generateRandomContrastColor = () => {
        // const colors = ['blue', '#3fe54f', '#e53fdd', '#7d23e2', '#14e7e6', '#00adff'];
        const colors = ['#808080', '#f0f0f0', '#e6e6e6', '#d9d9d9',
            '#cccccc', '#bfbfbf', '#b3b3b3', '#a6a6a6',
            '#999999', '#8c8c8c', '#808080', '#737373',
            '#666666', '#595959', '#4c4c4c', '#404040',
            '#333333', '#f5f5f5', '#ececec', '#e2e2e2',
            '#d9d9d9', '#cfcfcf'];
        let firstIndex = Math.floor(Math.random() * colors.length);
        let secondIndex = Math.floor(Math.random() * colors.length);

        if (firstIndex === secondIndex) {
            secondIndex = colors.length - 1 - firstIndex;
        }

        let color1 = colors[firstIndex];
        let color2 = colors[secondIndex];

        return [color1, color2];
    }

    const generateProjectColor = () => {
        const colors = generateRandomContrastColor();
        const color1 = colors[0];
        const color2 = colors[1];


        $('#color1').val(color1);
        $('#color2').val(color2);

        // var timelineElement = document.getElementById("timeline2");
        // var gradient = "linear-gradient(to right, " + color1 + ", " + color2 + ")";
        // timelineElement.style.background = gradient;
    }

    const focusFirstInput = (modalId) => {
        setTimeout(() => {
            $(`#${modalId} input`).first()[0].focus();
        }, 100);
    }

    document.addEventListener("DOMContentLoaded", () => {
        $("body").on("click", "[data-js-create-user]", function (e) {
            $.magnificPopup.open({
                items: {
                    src: '#gantt-create-user-modal',
                    type: 'inline'
                },
                fixedContentPos: false,
            });
            focusFirstInput("gantt-create-user-modal");
        });

        $("body").on("click", "[data-js-create-project]", function (e) {
            $.magnificPopup.open({
                items: {
                    src: '#gantt-create-project-modal',
                    type: 'inline'
                },
                fixedContentPos: false,
            });
            focusFirstInput("gantt-create-project-modal");
        });
    });

    const grabTimeLinesToTargetCells = (timelinesInfo) => {
        for (let elem of timelinesInfo) {
            for (let userTimeLine of elem.projectTimeLines) {
                const endDate = getEndDate(userTimeLine.end_date);

                let timelines = $(`.table_timeline[data-project_id="${userTimeLine.project_id}"][data-user_id="${userTimeLine.user_id}"][data-timeline_id="${userTimeLine.id}"]`);

                let lastCell = $('#gantt_table tbody tr:first .table_cell').last();

                let endDateTimeStamp = new Date(endDate.year, endDate.month - 1, endDate.day);
                let lastDateTimeStamp = new Date(lastCell.attr('data-year'), lastCell.attr('data-month') - 1, lastCell.attr('data-day'));

                let targetCell;
                if (endDateTimeStamp > lastDateTimeStamp) {
                    targetCell = $(`.table_cell[data-project_id="${userTimeLine.project_id}"][data-user_id="${userTimeLine.user_id}"]`).last();
                } else {
                    targetCell = $(`.table_cell[data-project_id="${userTimeLine.project_id}"][data-user_id="${userTimeLine.user_id}"][data-day="${endDate.day}"][data-month="${endDate.month}"][data-year="${endDate.year}"]`);
                }

                if (timelines.length) {
                    v2grabByRightSide(timelines[0], targetCell[0]);
                }
            }
        }
    }

    const getEndDate = (end_date) => {
        let endDate = end_date;
        let endDateDay;
        let endDateMonth;
        let endDateYear;
        if (endDate == null) {
            endDate = new Date();
            endDateDay = endDate.getDate();
            endDateMonth = endDate.getMonth() + 1;
            endDateYear = endDate.getFullYear();
        } else {
            endDate = new Date(endDate.split(' ')[0]);
            endDateDay = endDate.getDate();
            endDateMonth = endDate.getMonth() + 1;
            endDateYear = endDate.getFullYear();
        }

        return {
            'day': endDateDay,
            'month': endDateMonth,
            'year': endDateYear,
        }
    }

    const openSecondView = () => {
        let params = new URLSearchParams(window.location.search);

        if (parseInt(params.get('VIEW_MODE')) === 2) {
            params.set('VIEW_MODE', '1');
        } else /*if (params.get('VIEW_MODE') == 2)*/ {
            params.set('VIEW_MODE', '2');
        }

        window.history.replaceState({}, '', window.location.pathname + '?' + params.toString());
        window.location.reload();
    }


    document.addEventListener("DOMContentLoaded", () => {

        let url = "/ulab/gantt/collectTimeLineInfo";
        let data = {};
        $.post(url, data, function (response) {
            response = JSON.parse(response);
            // console.log(response.data)
            grabTimeLinesToTargetCells(response.data);

            // v2grabByRightSide();
        });
    });
</script>