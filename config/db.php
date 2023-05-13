<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=' . ($_ENV["DB_NAME"] ?? 'quiz'),
    'username' => $_ENV["DB_USER"] ?? 'root',
    'password' => $_ENV["DB_PASSWORD"] ?? '',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
