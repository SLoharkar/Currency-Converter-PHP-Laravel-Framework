// public/js/currencies_export.js

document.addEventListener('DOMContentLoaded', function () {
    const setDefaultsButton = document.getElementById('setDefaults');
    let isDefaultsSet = false;

    function setDefaults() {
        document.getElementById('dbHost').value = 'localhost';
        document.getElementById('dbPort').value = '3306';
        document.getElementById('dbName').value = 'Currencies';
        document.getElementById('dbTableName').value = ''; // Leave empty for default
        document.getElementById('dbUsername').value = 'root';
        document.getElementById('dbPassword').value = ''; // Leave empty if no password
    }

    function unsetDefaults() {
        document.getElementById('dbHost').value = '';
        document.getElementById('dbPort').value = '';
        document.getElementById('dbName').value = '';
        document.getElementById('dbTableName').value = '';
        document.getElementById('dbUsername').value = '';
        document.getElementById('dbPassword').value = '';
    }

    setDefaultsButton.addEventListener('click', function () {
        if (isDefaultsSet) {
            unsetDefaults();
            setDefaultsButton.textContent = 'Set Default Values';
        } else {
            setDefaults();
            setDefaultsButton.textContent = 'Unset Default Values';
        }
        isDefaultsSet = !isDefaultsSet;
    });
});
