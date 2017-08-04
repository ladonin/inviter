<html>
    <head>
        <title><?php echo($title); ?></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no, maximum-scale=1, width=device-width" />
        <link rel="stylesheet" href="/css/generic/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="/css/generic/swal.css"/>
        <link rel="stylesheet" type="text/css" href="/css/desctop/generic.css"/>
        <script src="/javascript/generic/jQuery/jquery.min.js"></script>
        <script src="/javascript/generic/bootstrap.min.js"></script>
        <script src="/javascript/generic/swal.js"></script>
        <script src="https://cdn.rawgit.com/zenorocha/clipboard.js/master/dist/clipboard.min.js"></script>


        <meta name="webmoney" content="BB2F7E92-03EF-4B7C-801F-8B7369557CA5"/>
        <meta name="verification" content="a6c7a5fbe48026d388b77d21c61830" />

    </head>
    <body>
        <div id="content">
            <script>
                function getRandomArbitrary(min, max) {
                    return Math.floor(Math.random() * (max - min + 1)) + min;
                }
                function my_create_password() {








                    var gl = new Array(
                            'a',
                            'e',
                            'i',
                            'o',
                            'u',
                            );
                            var so = new Array(
                                    'b',
                                    //'c',
                                    'd',
                                    'f',
                                    'g',
                                    'h',
                                    //'j',
                                    'k',
                                    'l',
                                    'm',
                                    'n',
                                    'p',
                                    //'q',
                                    'r',
                                    's',
                                    't',
                                    'v',
                                    //'w',
                                    'x',
                                    //'y',
                                    'z',
                                    );
                            var result = '';
                    var sogl = 0;
                    var word_lenght = 11;
                    word_lenght--;
                    for (var i = 0; i < word_lenght; i++) {

                        if (getRandomArbitrary(0, 1) == 1 && sogl == 0 && i != word_lenght && (i % 2 != 0)) {
                            result += so[getRandomArbitrary(0, 15)];
                            sogl = 1;
                        } else if ((i + sogl) % 2 == 0) {
                            result += so[getRandomArbitrary(0, 15)];
                        } else {
                            result += gl[getRandomArbitrary(0, 4)];
                        }
                    }

                    return result;
                }

                function round_cost(val) {
                    return Number(parseFloat(val).toFixed(2));
                }

                function validate_email(email) {
                    validation = new RegExp('^([a-zA-Z0-9_\\.+-])+@(([a-zA-Z0-9-])+\\.)+[a-zA-Z0-9]+$');
                    if (!validation.test(email)) {
                        return false;
                    } else {
                        return true;
                    }
                }

                function get_type_name_by_id(user_type) {
                    if (user_type == -1) {
                        return 'Любой';
                    } else if (user_type == 1) {
                        return 'Пользователи, поставившие Класс!';
                    } else if (user_type == 2) {
                        return 'Участники группы ';
                    } else if (user_type == 3) {
                        return 'Пользователи из результата поиска';
                    } else if (user_type == 5) {
                        return 'Пользователи, учавствующие в опросах';
                    } else if (user_type == 6) {
                        return 'Пользователи, оставляющие комментарии';
                    }
                }











                function setcookie(name, value, options) {
                    options = options || {};

                    var expires = options.expires;

                    if (typeof expires == "number" && expires) {
                        var d = new Date();
                        d.setTime(d.getTime() + expires * 1000);
                        expires = options.expires = d;
                    }
                    if (expires && expires.toUTCString) {
                        options.expires = expires.toUTCString();
                    }

                    value = encodeURIComponent(value);

                    var updatedCookie = name + "=" + value;

                    for (var propName in options) {
                        updatedCookie += "; " + propName;
                        var propValue = options[propName];
                        if (propValue !== true) {
                            updatedCookie += "=" + propValue;
                        }
                    }

                    document.cookie = updatedCookie;
                }


                function getCookie(name) {

                    var nameEQ = name + "=";
                    var ca = document.cookie.split(';');
                    for (var i = 0; i < ca.length; i++) {
                        var c = ca[i];
                        while (c.charAt(0) == ' ')
                            c = c.substring(1, c.length);
                        if (c.indexOf(nameEQ) == 0)
                            return c.substring(nameEQ.length, c.length);
                    }
                    return null;
                }




















            </script>
            <style>
                .row{
                    margin-left:0 !important;
                    margin-right:0 !important;
                }
                .sa-input-error{
                    display:none !important;
                }








                .icon-spinner {
                    display: inline-block;
                    -webkit-animation: rotation 1s linear infinite;
                    -o-animation: rotation 1s linear infinite;
                    animation: rotation 1s linear infinite;
                }
                [class^="icon-"], [class*=" icon-"] {
                    font-family: 'Glyphicons Halflings';
                    speak: none;
                    font-style: normal;
                    font-weight: normal;
                    font-variant: normal;
                    text-transform: none;
                    line-height: 1;
                    min-width: 1em;
                    display: inline-block;
                    text-align: center;
                    font-size: 16px;
                    vertical-align: middle;
                    position: relative;
                    top: -1px;
                    -webkit-font-smoothing: antialiased;
                    -moz-osx-font-smoothing: grayscale;
                }

                .icon-spinner::before {
                    content: "\e030";
                }




                @-webkit-keyframes rotation {
                    0% {
                        -webkit-transform: rotate(0deg);
                    }
                    100% {
                        -webkit-transform: rotate(360deg);
                    }
                }
                @-moz-keyframes rotation {
                    0% {
                        -moz-transform: rotate(0deg);
                    }
                    100% {
                        -moz-transform: rotate(360deg);
                    }
                }
                @-ms-keyframes rotation {
                    0% {
                        -ms-transform: rotate(0deg);
                    }
                    100% {
                        -ms-transform: rotate(360deg);
                    }
                }
                @-o-keyframes rotation {
                    0% {
                        -o-transform: rotate(0deg);
                    }
                    100% {
                        -o-transform: rotate(360deg);
                    }
                }
                @keyframes rotation {
                    0% {
                        transform: rotate(0deg);
                    }
                    100% {
                        transform: rotate(360deg);
                    }
                }
                @-webkit-keyframes rotation_reverse {
                    0% {
                        -webkit-transform: rotate(0deg);
                    }
                    100% {
                        -webkit-transform: rotate(-360deg);
                    }
                }
                @-moz-keyframes rotation_reverse {
                    0% {
                        -moz-transform: rotate(0deg);
                    }
                    100% {
                        -moz-transform: rotate(-360deg);
                    }
                }
                @-ms-keyframes rotation_reverse {
                    0% {
                        -ms-transform: rotate(0deg);
                    }
                    100% {
                        -ms-transform: rotate(-360deg);
                    }
                }
                @-o-keyframes rotation_reverse {
                    0% {
                        -o-transform: rotate(0deg);
                    }
                    100% {
                        -o-transform: rotate(-360deg);
                    }
                }
                @keyframes rotation_reverse {
                    0% {
                        transform: rotate(0deg);
                    }
                    100% {
                        transform: rotate(-360deg);
                    }
                }
                @-webkit-keyframes bounceIn {
                    0% {
                        opacity: 0;
                    }
                    100% {
                        opacity: 1;
                    }
                }
                @-webkit-keyframes bounceOut {
                    0% {
                        opacity: 1;
                    }
                    100% {
                        opacity: 0;
                    }
                }


                select option{
                    padding:5px;
                    cursor:pointer;
                }
                select{
                    cursor:pointer;
                }




                .well{
                    margin-bottom:0;
                }


            </style>