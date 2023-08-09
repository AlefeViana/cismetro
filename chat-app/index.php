<!DOCTYPE html>
<html>

<head>
    <title> Teste </title>

    <script src="https://static.opentok.com/v2/js/opentok.min.js"></script>
    <!-- Polyfill for fetch API so that we can fetch the sessionId and token in IE11 -->
    <script src="https://cdn.jsdelivr.net/npm/promise-polyfill@7/dist/polyfill.min.js" charset="utf-8"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fetch/2.0.3/fetch.min.js" charset="utf-8"></script>
    <link href="css/app.css" rel="stylesheet" type="text/css">
</head>

<body>
    teste
    <div id="videos">
        <div id="subscriber"></div>
        <div id="publisher"></div>
    </div>

    <div id="textchat">
         <p id="history"></p>
         <form>
              <input type="text" placeholder="Input your text here" id="msgTxt"></input>
         </form>
    </div>

    <script type="text/javascript" src="js/config.js"></script>
    <script type="text/javascript" src="js/app.js"></script>
</body>

</html>
