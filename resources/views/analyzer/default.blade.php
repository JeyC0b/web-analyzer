<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Analýza webové stránky</title>

    <link rel="stylesheet" href="{{ mix('/css/app.css') }}" />
    <script src="{{ mix('/js/app.js') }}"></script>
    <script src="{{ mix('/js/modernizr-custom.js') }}"></script>
</head>
<body>
    <div class="container">
        <h3>Analýza webové stránky</h3>
        <form method="POST">
            <input type="hidden" id="token" value="{{ csrf_token() }}" />

            <label for="url">Url adresa stránky:</label>
            <input type="text" name="url" id="url" value="" size="30" required/>
            <button type="submit" id="btn-submit">Alalyzovat</button>
            <img id="spinner" style="display: none;" width="30px" alt="Načítání" src="/images/loader.gif"/>
        </form>
        <div id="result"></div>
    </div>
</body>
</html>