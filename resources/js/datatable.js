var dt = require('datatables.net-bs4');
window.$.DataTable = dt;

$(document).ready(function () {
    if (window.tables == undefined) {
        return;
    }

    for (let index = 0; index < window.tables.length; index++) {
        const table = window.tables[index];
        let url = table.url;
        let columns = table.cols

        window.$('#'+ table.id).DataTable({
            processing: true,
            serverSide: true,
            ajax: url,
            columns: columns,
            language: {
                url: "/js/datatable-fr.json"
            }
        });
    }
});
