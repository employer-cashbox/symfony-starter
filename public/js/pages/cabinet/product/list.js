$(document).ready(function () {
    renderPagination();

    function renderPagination () {
        let paginationProductList, productTotal, elementOnPage, totalPages, page;

        paginationProductList = $('#pagination-product-list');
        productTotal = paginationProductList.data('productTotal');
        elementOnPage = paginationProductList.data('elementOnPage');
        page = paginationProductList.data('page');
        totalPages = Math.ceil(productTotal / elementOnPage);

        paginationProductList.bootpag({
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

        $('#pagination-product-list li').addClass('page-item');
        $('#pagination-product-list a').addClass('page-link');
    }
});
