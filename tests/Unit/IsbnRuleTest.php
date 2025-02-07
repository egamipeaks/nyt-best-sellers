<?php

test('validates a 10-digit ISBN', function () {
    validateIsbn('1234567890', valid: true);
});

test('validates a 13-digit ISBN', function () {
    validateIsbn('1234567890123', valid: true);
});

test('fails if ISBN is not 10 or 13 digits', function () {
    validateIsbn('123456789', valid: false);
});

test('fails if ISBN contains non-numeric characters', function () {
    validateIsbn('1234567890a', valid: false);
});

test('fails if ISBN is empty', function () {
    validateIsbn('', valid: false);
});
