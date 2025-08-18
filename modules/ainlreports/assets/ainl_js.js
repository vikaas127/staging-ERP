(function () {
    'use strict';

    const openBtn = document.getElementById('open-ai-reports');
    const menuOpenBtn = document.querySelector('.menu-item-ainlreports');
    const modal = document.getElementById('ai-modal');
    const closeBtn = document.getElementById('ai-close');
    const generate = document.getElementById('ai-generate');
    const loader = document.getElementById('ai-loader');
    const resultBox = document.getElementById('ai-result');
    const chartEl = document.getElementById('ai-chart');
    const tableEl = document.getElementById('ai-table');
    let chartObj = null;
    let lastQuery = null;

    function openModal() {
        modal.style.display = 'block';
    }

    function closeModal() {
        modal.style.display = 'none';
    }

    function show(v) {
        v.style.display = '';
    }

    function hide(v) {
        v.style.display = 'none';
    }

    function renderTable(rows) {
        if (!rows.length) {
            tableEl.innerHTML = '<tr><td>No data</td></tr>';
            return;
        }
        const cols = Object.keys(rows[0]);
        let html = '<thead><tr>';
        cols.forEach(c => {
            html += `<th>${prettifyColumn(c)}</th>`;
        });
        html += '</tr></thead><tbody>';
        rows.forEach(r => {
            html += '<tr>';
            cols.forEach(c => {
                html += `<td>${r[c]}</td>`;
            });
            html += '</tr>';
        });
        html += '</tbody>';
        tableEl.innerHTML = html;
    }

    function renderChart(type, rows) {
        const wrapper = document.querySelector('.ai-chart-wrapper');
        if (!rows.length || type === 'table') {
            if (chartObj) chartObj.destroy();
            wrapper.style.display = 'none';
            return;
        }

        wrapper.style.display = '';
        const labels = rows.map(r => Object.values(r)[0]);
        const data = rows.map(r => Object.values(r)[1]);

        if (chartObj) chartObj.destroy();
        chartObj = new Chart(chartEl, {
            type: type === 'pie' ? 'pie' : (type === 'bar' ? 'bar' : 'line'),
            data: {
                labels: labels,
                datasets: [{label: '', data: data}]
            },
            options: {responsive: true, maintainAspectRatio: false}
        });
    }

    function prettifyColumn(col) {
        return col
            .replace(/_/g, ' ')
            .replace(/([a-z])([A-Z])/g, '$1 $2')
            .replace(/\b\w/g, ch => ch.toUpperCase());
    }

    function AinlshowModalAlert(message, type = 'danger') {
        const container = document.querySelector('#ai-modal .ai-modal-content');
        if (!container) {
            console.error('Modal container not found!');
            return;
        }
        const old = container.querySelector('.alert');
        if (old) old.remove();
        container.insertAdjacentHTML('afterbegin', `
            <div class="alert alert-${type}" role="alert">
              ${message}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
        `);
    }

    async function callAIReport(question, skipCache = false) {
        const formData = new FormData();
        formData.append('question', question);

        if (skipCache) {
            formData.append('skip_cache', '1');
        }

        if (typeof csrfData !== 'undefined') {
            formData.append(csrfData.token_name, csrfData.hash);
        }

        const r = await fetch(site_url + 'ainlreports/query', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {'X-Requested-With': 'XMLHttpRequest'},
            body: formData
        });

        if (!r.ok) {
            throw new Error('Network error: ' + r.status);
        }

        const data = await r.json();
        if (data.status === 'error') {
            throw new Error(data.message);
        }
        return data;
    }

    function randomFilename(prefix, ext) {
        const rand = Math.random().toString(36).substring(2, 8);
        const ts = Date.now();
        return `${prefix}_${ts}_${rand}.${ext}`;
    }

    function AinltableToCSV(tableId) {
        const rows = Array.from(document.querySelectorAll(`#${tableId} tr`));
        return rows.map(row => {
            const cells = Array.from(row.querySelectorAll('th, td'))
                .map(cell => `"${cell.textContent.trim().replace(/"/g, '""')}"`);
            return cells.join(',');
        }).join('\n');
    }

    // Event bindings
    openBtn.addEventListener('click', openModal);
    if (menuOpenBtn) {
        menuOpenBtn.addEventListener('click', openModal);
    }
    closeBtn.addEventListener('click', closeModal);
    window.addEventListener('click', e => {
        if (e.target === modal) closeModal();
    });

    generate.addEventListener('click', async () => {
        const q = document.getElementById('ai-question').value.trim();
        if (!q) {
            AinlshowModalAlert('Write a question first', 'warning');
            return;
        }

        const skipCache = (q === lastQuery);
        lastQuery = q;

        hide(resultBox);
        show(loader);

        try {
            const res = await callAIReport(q, skipCache);
            renderTable(res.tableData);
            renderChart(res.chartType, res.data);
            hide(loader);
            show(resultBox);
        } catch (err) {
            hide(loader);
            AinlshowModalAlert(err.message);
        }
    });

    document.getElementById('ainl-export-chart-png').addEventListener('click', () => {
        const url = chartObj.toBase64Image();
        const link = document.createElement('a');
        link.href = url;
        link.download = randomFilename('chart', 'png');
        link.click();
    });

    document.getElementById('ainl-export-chart-pdf').addEventListener('click', () => {
        const {jsPDF} = window.jspdf;
        const pdf = new jsPDF('landscape');
        const imgData = chartObj.toBase64Image();
        const pdfWidth = pdf.internal.pageSize.getWidth();
        const ratio = chartObj.width / chartObj.height;
        const pdfHeight = pdfWidth / ratio;

        pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
        pdf.save(randomFilename('chart', 'pdf'));
    });

    document.getElementById('ainl-export-table-csv').addEventListener('click', () => {
        const csv = AinltableToCSV('ai-table');
        const blob = new Blob([csv], {type: 'text/csv;charset=utf-8;'});
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = randomFilename('table', 'csv');
        link.click();
    });

    document.getElementById('ainl-export-table-pdf').addEventListener('click', () => {
        const {jsPDF} = window.jspdf;
        const pdf = new jsPDF();

        const headers = Array.from(document.querySelectorAll('#ai-table thead th'))
            .map(th => th.textContent.trim());
        const data = Array.from(document.querySelectorAll('#ai-table tbody tr'))
            .map(tr => Array.from(tr.querySelectorAll('td'))
                .map(td => td.textContent.trim())
            );

        pdf.autoTable({
            head: [headers],
            body: data,
            startY: 20,
            theme: 'grid',
            headStyles: {fillColor: [200, 200, 200]}
        });
        pdf.save(randomFilename('table', 'pdf'));
    });

    $('#ainl-history').on('toggle', function () {
        if (this.open && !$('#ainl-history-list').children().length) {
            $.get(admin_url + 'ainlreports/get_history', function (data) {
                data.forEach(row => {
                    $('<li>')
                        .text(row.user_query)
                        .addClass('history-item')
                        .appendTo('#ainl-history-list');
                });
            });
        }
    });

    $(document).on('click', '.history-item', function () {
        const txt = $(this).text();
        $('#ai-question').val(txt).focus();
        $('#ainl-history').prop('open', false);
    });

})();
