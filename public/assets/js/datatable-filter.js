(function ($) {

    'use strict';

    window.DataTableFilter = {

        init: function (tableId, options = {}) {

            const table = window.LaravelDataTables[tableId];

            if (!table) {
                console.error(
                    'DataTable tidak ditemukan:',
                    tableId
                );

                return;
            }

            const selector = options.selector
                || '.datatable-filter';

            /*
             * Ketika filter berubah,
             * reload DataTable tanpa reload halaman.
             */
            $(selector).on('change', function () {

                table.ajax.reload(null, false);

            });


            /*
             * Reset filter.
             */
            if (options.resetButton) {

                $(options.resetButton).on('click', function () {

                    $(selector).each(function () {

                        $(this).val('');

                    });
                    table.ajax.reload(null, false);

                });
            }
            /*
             * Masukkan seluruh filter ke request AJAX.
             */
            table.on('preXhr.dt', function (
                e,
                settings,
                data
            ) {

                $(selector).each(function () {

                    const element = $(this);

                    const column =
                        element.data('column');

                    if (!column) {
                        return;
                    }

                    data[column] =
                        element.val() || '';

                });

            });

        }

    };

})(jQuery);