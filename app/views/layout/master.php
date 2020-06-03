<?php
return function ($object) {
    if (!property_exists($object, "contentView")) {
        exit();
    }
    $css = (property_exists($object, "css") && is_array($object->css)) ? $object->css : [];
    $js = (property_exists($object, "js") && is_array($object->js)) ? $object->js : [];
    $modals = (property_exists($object, "modals") && is_array($object->modals)) ? $object->modals : [];
    unset($object->css, $object->js);
    $cssRelPath = NULL;
    $jsRelPath = NULL;
    ?>
    <!doctype html>
    <html lang="it">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css"
              integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS"
              crossorigin="anonymous">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css"
              integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/"
              crossorigin="anonymous">

        <link rel="stylesheet" href="/resources/css/master.css">
        <?php foreach ($css as $cssRelPath): ?>
            <link rel="stylesheet" href="/resources/css/<?= $cssRelPath ?>.css">
        <?php endforeach ?>

        <title>Civic Sense
            <?php if (property_exists($object, "title")):
                echo '| ', $object->title;
            endif; ?></title>
    </head>
    <body>

    <?php view('layout/navbar') ?>

    <div class="container">
        <?php if (property_exists($object, "title")): ?>
            <h1 class="text-center"><?= $object->title ?></h1>
            <hr>
        <?php endif ?>
        <?php view($object->contentView, (array) $object) ?>
    </div>

    <!-- Modals -->
    <?php view('layout/modal-map', []) ?>
    <?php foreach ($modals as $modal) {
        view($modal, (array) $object);
    } ?>

    <!-- icona inferiore CDT -->
    <?php view('layout/nav-tickets') ?>

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"
            integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"
            integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k"
            crossorigin="anonymous"></script>

    <!-- Optional JavaScript -->
    <script src="/resources/js/master.js"></script>
    <script src="/resources/js/map.js"></script>
    <?php foreach ($js as $jsRelPath):
        $isHttp = strpos($jsRelPath, "http") === 0; ?>
        <script src="<?= $isHttp ? NULL : "/resources/js/", $jsRelPath, $isHttp ? NULL : ".js" ?>"></script>
    <?php endforeach ?>
    </body>
    </html>
    <?php
};