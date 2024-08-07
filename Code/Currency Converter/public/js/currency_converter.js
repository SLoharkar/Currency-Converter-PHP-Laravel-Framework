$(document).ready(function() {
    function generateLimitOptions(totalCurrencies) {
        var limits = [10, 25, 50, 100];
        var options = [];

        // Include predefined limits that are less than or equal to totalCurrencies
        for (var i = 0; i < limits.length; i++) {
            if (limits[i] <= totalCurrencies) {
                options.push(limits[i]);
            } else {
                break;
            }
        }

        // If totalCurrencies is greater than the highest limit and not already included, add it
        if (totalCurrencies > limits[limits.length - 1] || !options.includes(totalCurrencies)) {
            options.push(totalCurrencies);
        }

        // Generate the options in the select
        var $limitSelect = $('#limit');
        $limitSelect.empty();
        options.forEach(function(option) {
            $limitSelect.append(`<option value="${option}">${option}</option>`);
        });
    }

    $('#to_currency').change(function() {
        var limitGroup = $('#limit-group');
        var totalCurrencies = parseInt($('#total-currencies').text(), 10);

        if ($(this).val() === 'all') {
            limitGroup.show();
            generateLimitOptions(totalCurrencies);
        } else {
            limitGroup.hide();
        }
    });
});
