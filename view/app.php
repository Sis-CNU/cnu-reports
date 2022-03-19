<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main</title>
    <link rel="apple-touch-icon" sizes="180x180" href="/images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon/favicon-16x16.png">
    <link rel="manifest" href="/images/favicon/site.webmanifest">
    <link rel="stylesheet" href="/css/normalize.css">
    <link rel="stylesheet" href="/css/ui-bic.css">
</head>

<body>
    <form action="/article/create" method="post" enctype="multipart/form-data" class="w-50 m-auto" name="article-form" id="article-form" style="border: 1px solid black;">
        <input name="id" id="id" value="5" hidden>

        <div class="form-group">
            <input type="text" class="control w-100" name="name" id="article-name" placeholder="Article Name" required tabindex="0">
        </div>

        <div class="form-group">
            <input type="text" class="control w-100" name="brand" id="brand" placeholder="Brand" required tabindex="0">
        </div>

        <div class="form-group">
            <div class="spin-container w-100">
                <input type="number" class="spin-custom" name="price" id="price" step=".01" placeholder="Price" min="0" required tabindex="0">
                <span class="spin-buttons">
                    <button id="btn-price-up" class="btn-spin-up" tabindex="-1"></button>
                    <button id="btn-price-down" class="btn-spin-down" tabindex="-1"></button>
                </span>
            </div>
        </div>

        <div class="form-group">
            <div class="list w-100">
                <select class="list-data" data-select="colors" tabindex="0">
                    <option value="opcion-1">Morado</option>
                    <option value="opcion-2">Verde</option>
                    <option value="opcion-x">Azul</option>
                </select>
                <button class="list-button" data-select="colors" tabindex="-1"></button>
            </div>
        </div>

        <div class="form-group">
            <textarea class="control w-100" style="height: 200px;"></textarea>
        </div>

        <div class="form-group">
            <input type="submit" class="btn-primary" name="btn-send" id="btn-send" value="Send">
        </div>

    </form>
    <a href="/link">Link</a>
    <p>
        <?php
        echo "<pre>" . print_r($data, true) . "</pre>";
        ?>
    </p>

    <script type="module" src="/js/ui-bic.js"></script>
    <script type="module" src="/js/test.js"></script>
</body>

</html>