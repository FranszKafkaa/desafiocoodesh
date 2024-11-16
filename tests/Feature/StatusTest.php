<?php

test('teste de controller de status', function () {
    $this->getJson(route('index'))->assertJson(
        [
            'data' => [
                'Status' => 'OK',
                'Cron Executada pela ultima vez' => NULL,
            ]
        ]
    )->assertStatus(200);
});