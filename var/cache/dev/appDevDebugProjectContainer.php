<?php

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.

if (\class_exists(\ContainerXy27dh8\appDevDebugProjectContainer::class, false)) {
    // no-op
} elseif (!include __DIR__.'/ContainerXy27dh8/appDevDebugProjectContainer.php') {
    touch(__DIR__.'/ContainerXy27dh8.legacy');

    return;
}

if (!\class_exists(appDevDebugProjectContainer::class, false)) {
    \class_alias(\ContainerXy27dh8\appDevDebugProjectContainer::class, appDevDebugProjectContainer::class, false);
}

return new \ContainerXy27dh8\appDevDebugProjectContainer([
    'container.build_hash' => 'Xy27dh8',
    'container.build_id' => 'e4b4d577',
    'container.build_time' => 1582196720,
], __DIR__.\DIRECTORY_SEPARATOR.'ContainerXy27dh8');
