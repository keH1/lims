class SolutionHelper {


    constructor(reactive, solution) {
        this.reactive = reactive;
        this.solution = solution;
    }


    getSolution() {
        return this.solution;
    }

    getReactive() {
        return this.reactive;
    }


    createBlockReactive() {
        let content = "";
        let reactiveDataArray, reactiveName;
        this.reactive.forEach(item => {
            reactiveName = Object.keys(item)[0];
            reactiveDataArray = item[reactiveName];
            let flag = 0;
            if (reactiveDataArray.length > 0) {
                reactiveDataArray.forEach((reactiveData, i) => {
                    content += this.createRow(reactiveData, reactiveName, flag,'reactive');
                    flag = 1;
                })

            } else {
                content += this.emptyRow(reactiveName, flag);
                flag = 1;
            }
        })

        return content;
    }

    createBlockSolution() {
        let content = "";
        let solutionDataArray, solutionName;


        this.solution.forEach(item => {
            solutionName = Object.keys(item)[0];
            solutionDataArray = item[solutionName];
            let flag = 0;
            if (solutionDataArray.length > 0) {
                solutionDataArray.forEach((solutionData, i) => {
                    content += this.createRow(solutionData, solutionName, flag,'solution');
                    flag = 1;
                })
            } else {
                content += this.emptyRow(solutionName, flag);
                flag = 1;

            }
        })

        return content;
    }

    emptyRow(name, flag) {
        let content = "";
        if (flag === 0) {
            content +=
                `<tr class="disabled_save_button">
                <td></td>
                <td>${name}</td>
                <td colspan="4">Нет достаточного количества реактива или срок годности истек</td>
            </tr>`
        }

        return content;
    }

    createRow(data, name, flag,type) {

        let content = "";
        if (flag === 0) {
            content += `
            <tr class="main-reactive" data-tr-id="${name}" data-name="${name}">
            <td></td>
            <td>${name}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>`;
        }
        if(type === 'reactive'){
            content += `
            <tr class="reactive_dropdown" id="${name}" data-name="${name}">
            <td>
                <input type="checkbox" name="reactive[reactive_choose]" id="reactive">
                <input type="hidden" name="toSQL[reactive][${data.id_library_reactive}][id_receive]" value="${data.id_receive}">
                <input type="hidden" name="reactive[date_expired_dateformat]" value="${data.date_expired_dateformat}">
                <input type="hidden" name="toSQL[reactive][${data.id_library_reactive}][id_library_reactive]" value="${data.id_library_reactive}">
                <input type="hidden" name="" value="${data.name}">
                <input type="hidden" name="toSQL[reactive][${data.id_library_reactive}][quantity_consume]" value="${data.quantity_consume}">
                <input type="hidden" name="reactive[quantity_consume_full]" value="${data.quantity_consume_full}">
                <input type="hidden" name="reactive[quantity_full]" value="${data.quantity_full}">
                <input type="hidden" name="reactive[total_full]" value="${data.total_full}">
            </td>
            <td>${ data.name }</td>
            <td>${ data.quantity_full }</td>
            <td>${ data.quantity_consume_full }</td>
            <td>${ data.total_full }</td>
            <td>${ data.date_expired_dateformat }</td>
            </tr>`;
        }
        else
            content += `
            <tr class="reactive_dropdown" id="${name}" data-name="${name}">
            <td>
                <input type="checkbox" name="reactive[reactive_choose]" id="solvent">
                <input type="hidden" name="toSQL[reactive][${data.id_library_reactive}][id_receive]" value="${data.id_receive}">
                <input type="hidden" name="reactive[date_expired_dateformat]" value="${data.date_expired_dateformat}">
                <input type="hidden" name="toSQL[reactive][${data.id_library_reactive}][id_library_reactive]" value="${data.id_library_reactive}">
                <input type="hidden" name="" value="${data.name}">
                <input type="hidden" name="toSQL[reactive][${data.id_library_reactive}][quantity_consume]" value="${data.quantity_consume}">
                <input type="hidden" name="reactive[quantity_consume_full]" value="${data.quantity_consume_full}">
                <input type="hidden" name="reactive[quantity_full]" value="${data.quantity_full}">
                <input type="hidden" name="reactive[total_full]" value="${data.total_full}">
            </td>
            <td>${data.name}</td>
            <td>${  this.checkSolutionName(name) === false ? data.quantity_full : ""}</td>
            <td>${data.quantity_consume_full}</td>
            <td>${this.checkSolutionName(name) === false ? data.total_full : ""}</td>
            <td>${this.checkSolutionName(name) === false ? data.date_expired_dateformat : ""}</td>
            </tr>`;
        return content;
    }

    checkSolutionName(name) {
        let checker;
        name === "Лаб. реактив Дистиллированная вода ГОСТ Р 58144-2018" ||
        name === "Лаб. реактив Бидистиллированная вода" ?
            checker = true :
            checker = false

        return checker
    }



}