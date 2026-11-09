<?php

test('the application redirects successfully', function () {
    $response = $this->get('/');

    $response->assertRedirect();
});