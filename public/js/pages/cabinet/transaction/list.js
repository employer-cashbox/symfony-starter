$(document).ready(function () {
    renderPagination();

    function renderPagination() {
        let paginationTransactionList, transactionTotal, elementOnPage, totalPages, page;

        paginationTransactionList = $('#pagination-transaction-list');
        transactionTotal = paginationTransactionList.data('transactionTotal');
        elementOnPage = paginationTransactionList.data('elementOnPage');
        page = paginationTransactionList.data('page');
        totalPages = Math.ceil(transactionTotal / elementOnPage);

        paginationTransactionList.bootpag({
            total: totalPages,
            page: page,
            maxVisible: 3,
            leaps: true,
            firstLastUse: true,
            first: '←',
            last: '→',
            wrapClass: 'pagination',
            activeClass: 'active',
            disabledClass: 'disabled',
            nextClass: 'next',
            prevClass: 'prev',
            lastClass: 'last',
            firstClass: 'first'
        }).on('page', function (e, page) {
            let url;

            url = location.href.match(/page=\d+/)
                ? location.href.replace(/page=\d+/, `page=${page}`)
                : `${location.href}?page=${page}`;

            $(location).attr('href', url);
        });

        $('#pagination-transaction-list li').addClass('page-item');
        $('#pagination-transaction-list a').addClass('page-link');
    }
});
