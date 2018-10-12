<!DOCTYPE HTML>
<!--
        Photon by HTML5 UP
        html5up.net | @ajlkn
        Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
    <head>
        <title>Photon by HTML5 UP</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
        <link rel="stylesheet" href="assets/css/main.css" />
        <noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
    </head>
    <body class="is-preload">

        <!-- Header -->
        <section id="header" class="pad-home">
            <div class="inner">
                <span class="icon major fa-cloud"></span>
                <form method="POST" action="{{ route('/import_email_list') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="">
                        <input class="email-file" type="file" id="File" value="Choose Email List" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"  name="email-list">
                        <input type="submit" value="Verify Now">
                    </div>
                </form>
            </div>
            <div class="footer">
                <ul class="copyright" style="">
                    <li>&copy; Untitled</li><li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
                </ul>
            </div>
        </section>
        <!-- Scripts -->
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/jquery.scrolly.min.js"></script>
        <script src="assets/js/browser.min.js"></script>
        <script src="assets/js/breakpoints.min.js"></script>
        <script src="assets/js/util.js"></script>
        <script src="assets/js/main.js"></script>

    </body>
</html>