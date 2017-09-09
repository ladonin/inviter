<!DOCTYPE HTML>
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
        <!--<script src="https://cdn.rawgit.com/zenorocha/clipboard.js/master/dist/clipboard.min.js"></script>-->


        <meta name="webmoney" content="BB2F7E92-03EF-4B7C-801F-8B7369557CA5"/>
        <meta name="verification" content="a6c7a5fbe48026d388b77d21c61830" />

    </head>
    <body>
        <div id="content">
            <script type='text/javascript'>
                function getRandomArbitrary(min, max) {
                    return Math.floor(Math.random() * (max - min + 1)) + min;
                }
                function my_create_password() {








                    var gl = new Array(
                            'a',
                            'e',
                            'i',
                            'o',
                            'u'
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
                                    'z'
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

























                function setcookie(name, value, options) {
                    options = options || {};

                    var expires = options.expires;

                    if (typeof expires == "undefined") {
                        expires = 3600 * 24 * 30;
                    }

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


                function getCookie(name, default_val) {
                    if (typeof(default_val) === 'undefined') {
                        default_val = null;
                    }
                    var nameEQ = name + "=";
                    var ca = document.cookie.split(';');
                    for (var i = 0; i < ca.length; i++) {
                        var c = ca[i];
                        while (c.charAt(0) == ' ')
                            c = c.substring(1, c.length);
                        if (c.indexOf(nameEQ) == 0)
                            return c.substring(nameEQ.length, c.length);
                    }
                    return default_val;
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
                    padding:5px !important;
                    cursor:pointer;
                }
                select{
                    cursor:pointer;
                    padding-left:5px !important;
                    padding-right:5px !important;
                    text-indent:5px;
                }




                .well{
                    margin-bottom:0;
                }



                .p-0 {
                    padding:0px !important;
                }
                .pl-0 {
                    padding-left:0px !important;
                }
                .pr-0 {
                    padding-right:0px !important;
                }
                .pt-0 {
                    padding-top:0px !important;
                }
                .pb-0 {
                    padding-bottom:0px !important;
                }

                .p-5 {
                    padding:5px !important;
                }
                .pl-5 {
                    padding-left:5px !important;
                }
                .pr-5 {
                    padding-right:5px !important;
                }
                .pt-5 {
                    padding-top:5px !important;
                }
                .pb-5 {
                    padding-bottom:5px !important;
                }

                .p-10 {
                    padding:10px !important;
                }
                .pl-10 {
                    padding-left:10px !important;
                }
                .pr-10 {
                    padding-right:10px !important;
                }
                .pt-10 {
                    padding-top:10px !important;
                }
                .pb-10 {
                    padding-bottom:10px !important;
                }

                .p-15 {
                    padding:15px !important;
                }
                .pl-15 {
                    padding-left:15px !important;
                }
                .pr-15 {
                    padding-right:15px !important;
                }
                .pt-15 {
                    padding-top:15px !important;
                }
                .pb-15 {
                    padding-bottom:15px !important;
                }

                .p-20 {
                    padding:20px !important;
                }
                .pl-20 {
                    padding-left:20px !important;
                }
                .pr-20 {
                    padding-right:20px !important;
                }
                .pt-20 {
                    padding-top:20px !important;
                }
                .pb-20 {
                    padding-bottom:20px !important;
                }


                .m-0 {
                    margin:0px !important;
                }
                .ml-0 {
                    margin-left:0px !important;
                }
                .mr-0 {
                    margin-right:0px !important;
                }
                .mt-0 {
                    margin-top:0px !important;
                }
                .mb-0 {
                    margin-bottom:0px !important;
                }

                .m-5 {
                    margin:5px !important;
                }
                .ml-5 {
                    margin-left:5px !important;
                }
                .mr-5 {
                    margin-right:5px !important;
                }
                .mt-5 {
                    margin-top:5px !important;
                }
                .mb-5 {
                    margin-bottom:5px !important;
                }

                .m-10 {
                    margin:10px !important;
                }
                .ml-10 {
                    margin-left:10px !important;
                }
                .mr-10 {
                    margin-right:10px !important;
                }
                .mt-10 {
                    margin-top:10px !important;
                }
                .mb-10 {
                    margin-bottom:10px !important;
                }

                .m-15 {
                    margin:15px !important;
                }
                .ml-15 {
                    margin-left:15px !important;
                }
                .mr-15 {
                    margin-right:15px !important;
                }
                .mt-15 {
                    margin-top:15px !important;
                }
                .mb-15 {
                    margin-bottom:15px !important;
                }

                .m-20 {
                    margin:20px !important;
                }
                .ml-20 {
                    margin-left:20px !important;
                }
                .mr-20 {
                    margin-right:20px !important;
                }
                .mt-20 {
                    margin-top:20px !important;
                }
                .mb-20 {
                    margin-bottom:20px !important;
                }


                    .pv-0 {
                        padding-top:0px !important;
                        padding-bottom:0px !important;
                    }
                    .ph-0 {
                        padding-left:0px !important;
                        padding-right:0px !important;
                    }

                    .pv-5 {
                        padding-top:5px !important;
                        padding-bottom:5px !important;
                    }
                    .ph-5 {
                        padding-left:5px !important;
                        padding-right:5px !important;
                    }

                    .pv-10 {
                        padding-top:10px !important;
                        padding-bottom:10px !important;
                    }
                    .ph-10 {
                        padding-left:10px !important;
                        padding-right:10px !important;
                    }

                    .pv-15 {
                        padding-top:15px !important;
                        padding-bottom:15px !important;
                    }
                    .ph-15 {
                        padding-left:15px !important;
                        padding-right:15px !important;
                    }

                    .pv-20 {
                        padding-top:20px !important;
                        padding-bottom:20px !important;
                    }
                    .ph-20 {
                        padding-left:20px !important;
                        padding-right:20px !important;
                    }

                    .mv-0 {
                        margin-top:0px !important;
                        margin-bottom:0px !important;
                    }
                    .mh-0 {
                        margin-left:0px !important;
                        margin-right:0px !important;
                    }

                    .mv-5 {
                        margin-top:5px !important;
                        margin-bottom:5px !important;
                    }
                    .mh-5 {
                        margin-left:5px !important;
                        margin-right:5px !important;
                    }

                    .mv-10 {
                        margin-top:10px !important;
                        margin-bottom:10px !important;
                    }
                    .mh-10 {
                        margin-left:10px !important;
                        margin-right:10px !important;
                    }

                    .mv-15 {
                        margin-top:15px !important;
                        margin-bottom:15px !important;
                    }
                    .mh-15 {
                        margin-left:15px !important;
                        margin-right:15px !important;
                    }

                    .mv-20 {
                        margin-top:20px !important;
                        margin-bottom:20px !important;
                    }
                    .mh-20 {
                        margin-left:20px !important;
                        margin-right:20px !important;
                    }




                @media (max-width: 767px) {
                    .hidden-xs{
                        display:none;
                    }

                    .no-border-bottom-xs{
                        border-bottom:0 !important;
                    }

                    .pv-0-xs {
                        padding-top:0px !important;
                        padding-bottom:0px !important;
                    }
                    .ph-0-xs {
                        padding-left:0px !important;
                        padding-right:0px !important;
                    }

                    .pv-5-xs {
                        padding-top:5px !important;
                        padding-bottom:5px !important;
                    }
                    .ph-5-xs {
                        padding-left:5px !important;
                        padding-right:5px !important;
                    }

                    .pv-10-xs {
                        padding-top:10px !important;
                        padding-bottom:10px !important;
                    }
                    .ph-10-xs {
                        padding-left:10px !important;
                        padding-right:10px !important;
                    }

                    .pv-15-xs {
                        padding-top:15px !important;
                        padding-bottom:15px !important;
                    }
                    .ph-15-xs {
                        padding-left:15px !important;
                        padding-right:15px !important;
                    }

                    .pv-20-xs {
                        padding-top:20px !important;
                        padding-bottom:20px !important;
                    }
                    .ph-20-xs {
                        padding-left:20px !important;
                        padding-right:20px !important;
                    }

                    .mv-0-xs {
                        margin-top:0px !important;
                        margin-bottom:0px !important;
                    }
                    .mh-0-xs {
                        margin-left:0px !important;
                        margin-right:0px !important;
                    }

                    .mv-5-xs {
                        margin-top:5px !important;
                        margin-bottom:5px !important;
                    }
                    .mh-5-xs {
                        margin-left:5px !important;
                        margin-right:5px !important;
                    }

                    .mv-10-xs {
                        margin-top:10px !important;
                        margin-bottom:10px !important;
                    }
                    .mh-10-xs {
                        margin-left:10px !important;
                        margin-right:10px !important;
                    }

                    .mv-15-xs {
                        margin-top:15px !important;
                        margin-bottom:15px !important;
                    }
                    .mh-15-xs {
                        margin-left:15px !important;
                        margin-right:15px !important;
                    }

                    .mv-20-xs {
                        margin-top:20px !important;
                        margin-bottom:20px !important;
                    }
                    .mh-20-xs {
                        margin-left:20px !important;
                        margin-right:20px !important;
                    }


                    .p-0-xs {
                        padding:0px !important;
                    }
                    .pl-0-xs {
                        padding-left:0px !important;
                    }
                    .pr-0-xs {
                        padding-right:0px !important;
                    }
                    .pt-0-xs {
                        padding-top:0px !important;
                    }
                    .pb-0-xs {
                        padding-bottom:0px !important;
                    }

                    .p-5-xs {
                        padding:5px !important;
                    }
                    .pl-5-xs {
                        padding-left:5px !important;
                    }
                    .pr-5-xs {
                        padding-right:5px !important;
                    }
                    .pt-5-xs {
                        padding-top:5px !important;
                    }
                    .pb-5-xs {
                        padding-bottom:5px !important;
                    }

                    .p-10-xs {
                        padding:10px !important;
                    }
                    .pl-10-xs {
                        padding-left:10px !important;
                    }
                    .pr-10-xs {
                        padding-right:10px !important;
                    }
                    .pt-10-xs {
                        padding-top:10px !important;
                    }
                    .pb-10-xs {
                        padding-bottom:10px !important;
                    }

                    .p-15-xs {
                        padding:15px !important;
                    }
                    .pl-15-xs {
                        padding-left:15px !important;
                    }
                    .pr-15-xs {
                        padding-right:15px !important;
                    }
                    .pt-15-xs {
                        padding-top:15px !important;
                    }
                    .pb-15-xs {
                        padding-bottom:15px !important;
                    }

                    .p-20-xs {
                        padding:20px !important;
                    }
                    .pl-20-xs {
                        padding-left:20px !important;
                    }
                    .pr-20-xs {
                        padding-right:20px !important;
                    }
                    .pt-20-xs {
                        padding-top:20px !important;
                    }
                    .pb-20-xs {
                        padding-bottom:20px !important;
                    }


                    .m-0-xs {
                        margin:0px !important;
                    }
                    .ml-0-xs {
                        margin-left:0px !important;
                    }
                    .mr-0-xs {
                        margin-right:0px !important;
                    }
                    .mt-0-xs {
                        margin-top:0px !important;
                    }
                    .mb-0-xs {
                        margin-bottom:0px !important;
                    }

                    .m-5-xs {
                        margin:5px !important;
                    }
                    .ml-5-xs {
                        margin-left:5px !important;
                    }
                    .mr-5-xs {
                        margin-right:5px !important;
                    }
                    .mt-5-xs {
                        margin-top:5px !important;
                    }
                    .mb-5-xs {
                        margin-bottom:5px !important;
                    }

                    .m-10-xs {
                        margin:10px !important;
                    }
                    .ml-10-xs {
                        margin-left:10px !important;
                    }
                    .mr-10-xs {
                        margin-right:10px !important;
                    }
                    .mt-10-xs {
                        margin-top:10px !important;
                    }
                    .mb-10-xs {
                        margin-bottom:10px !important;
                    }

                    .m-15-xs {
                        margin:15px !important;
                    }
                    .ml-15-xs {
                        margin-left:15px !important;
                    }
                    .mr-15-xs {
                        margin-right:15px !important;
                    }
                    .mt-15-xs {
                        margin-top:15px !important;
                    }
                    .mb-15-xs {
                        margin-bottom:15px !important;
                    }

                    .m-20-xs {
                        margin:20px !important;
                    }
                    .ml-20-xs {
                        margin-left:20px !important;
                    }
                    .mr-20-xs {
                        margin-right:20px !important;
                    }
                    .mt-20-xs {
                        margin-top:20px !important;
                    }
                    .mb-20-xs {
                        margin-bottom:20px !important;
                    }

                }
                @media (min-width: 768px) {
                    .pv-0-sm {
                        padding-top:0px !important;
                        padding-bottom:0px !important;
                    }
                    .ph-0-sm {
                        padding-left:0px !important;
                        padding-right:0px !important;
                    }

                    .pv-5-sm {
                        padding-top:5px !important;
                        padding-bottom:5px !important;
                    }
                    .ph-5-sm {
                        padding-left:5px !important;
                        padding-right:5px !important;
                    }

                    .pv-10-sm {
                        padding-top:10px !important;
                        padding-bottom:10px !important;
                    }
                    .ph-10-sm {
                        padding-left:10px !important;
                        padding-right:10px !important;
                    }

                    .pv-15-sm {
                        padding-top:15px !important;
                        padding-bottom:15px !important;
                    }
                    .ph-15-sm {
                        padding-left:15px !important;
                        padding-right:15px !important;
                    }

                    .pv-20-sm {
                        padding-top:20px !important;
                        padding-bottom:20px !important;
                    }
                    .ph-20-sm {
                        padding-left:20px !important;
                        padding-right:20px !important;
                    }

                    .mv-0-sm {
                        margin-top:0px !important;
                        margin-bottom:0px !important;
                    }
                    .mh-0-sm {
                        margin-left:0px !important;
                        margin-right:0px !important;
                    }

                    .mv-5-sm {
                        margin-top:5px !important;
                        margin-bottom:5px !important;
                    }
                    .mh-5-sm {
                        margin-left:5px !important;
                        margin-right:5px !important;
                    }

                    .mv-10-sm {
                        margin-top:10px !important;
                        margin-bottom:10px !important;
                    }
                    .mh-10-sm {
                        margin-left:10px !important;
                        margin-right:10px !important;
                    }

                    .mv-15-sm {
                        margin-top:15px !important;
                        margin-bottom:15px !important;
                    }
                    .mh-15-sm {
                        margin-left:15px !important;
                        margin-right:15px !important;
                    }

                    .mv-20-sm {
                        margin-top:20px !important;
                        margin-bottom:20px !important;
                    }
                    .mh-20-sm {
                        margin-left:20px !important;
                        margin-right:20px !important;
                    }

                    .p-0-sm {
                        padding:0px !important;
                    }
                    .pl-0-sm {
                        padding-left:0px !important;
                    }
                    .pr-0-sm {
                        padding-right:0px !important;
                    }
                    .pt-0-sm {
                        padding-top:0px !important;
                    }
                    .pb-0-sm {
                        padding-bottom:0px !important;
                    }

                    .p-5-sm {
                        padding:5px !important;
                    }
                    .pl-5-sm {
                        padding-left:5px !important;
                    }
                    .pr-5-sm {
                        padding-right:5px !important;
                    }
                    .pt-5-sm {
                        padding-top:5px !important;
                    }
                    .pb-5-sm {
                        padding-bottom:5px !important;
                    }

                    .p-10-sm {
                        padding:10px !important;
                    }
                    .pl-10-sm {
                        padding-left:10px !important;
                    }
                    .pr-10-sm {
                        padding-right:10px !important;
                    }
                    .pt-10-sm {
                        padding-top:10px !important;
                    }
                    .pb-10-sm {
                        padding-bottom:10px !important;
                    }

                    .p-15-sm {
                        padding:15px !important;
                    }
                    .pl-15-sm {
                        padding-left:15px !important;
                    }
                    .pr-15-sm {
                        padding-right:15px !important;
                    }
                    .pt-15-sm {
                        padding-top:15px !important;
                    }
                    .pb-15-sm {
                        padding-bottom:15px !important;
                    }

                    .p-20-sm {
                        padding:20px !important;
                    }
                    .pl-20-sm {
                        padding-left:20px !important;
                    }
                    .pr-20-sm {
                        padding-right:20px !important;
                    }
                    .pt-20-sm {
                        padding-top:20px !important;
                    }
                    .pb-20-sm {
                        padding-bottom:20px !important;
                    }


                    .m-0-sm {
                        margin:0px !important;
                    }
                    .ml-0-sm {
                        margin-left:0px !important;
                    }
                    .mr-0-sm {
                        margin-right:0px !important;
                    }
                    .mt-0-sm {
                        margin-top:0px !important;
                    }
                    .mb-0-sm {
                        margin-bottom:0px !important;
                    }

                    .m-5-sm {
                        margin:5px !important;
                    }
                    .ml-5-sm {
                        margin-left:5px !important;
                    }
                    .mr-5-sm {
                        margin-right:5px !important;
                    }
                    .mt-5-sm {
                        margin-top:5px !important;
                    }
                    .mb-5-sm {
                        margin-bottom:5px !important;
                    }

                    .m-10-sm {
                        margin:10px !important;
                    }
                    .ml-10-sm {
                        margin-left:10px !important;
                    }
                    .mr-10-sm {
                        margin-right:10px !important;
                    }
                    .mt-10-sm {
                        margin-top:10px !important;
                    }
                    .mb-10-sm {
                        margin-bottom:10px !important;
                    }

                    .m-15-sm {
                        margin:15px !important;
                    }
                    .ml-15-sm {
                        margin-left:15px !important;
                    }
                    .mr-15-sm {
                        margin-right:15px !important;
                    }
                    .mt-15-sm {
                        margin-top:15px !important;
                    }
                    .mb-15-sm {
                        margin-bottom:15px !important;
                    }

                    .m-20-sm {
                        margin:20px !important;
                    }
                    .ml-20-sm {
                        margin-left:20px !important;
                    }
                    .mr-20-sm {
                        margin-right:20px !important;
                    }
                    .mt-20-sm {
                        margin-top:20px !important;
                    }
                    .mb-20-sm {
                        margin-bottom:20px !important;
                    }

                }
                @media (min-width: 992px) {
                    .pv-0-md {
                        padding-top:0px !important;
                        padding-bottom:0px !important;
                    }
                    .ph-0-md {
                        padding-left:0px !important;
                        padding-right:0px !important;
                    }

                    .pv-5-md {
                        padding-top:5px !important;
                        padding-bottom:5px !important;
                    }
                    .ph-5-md {
                        padding-left:5px !important;
                        padding-right:5px !important;
                    }

                    .pv-10-md {
                        padding-top:10px !important;
                        padding-bottom:10px !important;
                    }
                    .ph-10-md {
                        padding-left:10px !important;
                        padding-right:10px !important;
                    }

                    .pv-15-md {
                        padding-top:15px !important;
                        padding-bottom:15px !important;
                    }
                    .ph-15-md {
                        padding-left:15px !important;
                        padding-right:15px !important;
                    }

                    .pv-20-md {
                        padding-top:20px !important;
                        padding-bottom:20px !important;
                    }
                    .ph-20-md {
                        padding-left:20px !important;
                        padding-right:20px !important;
                    }

                    .mv-0-md {
                        margin-top:0px !important;
                        margin-bottom:0px !important;
                    }
                    .mh-0-md {
                        margin-left:0px !important;
                        margin-right:0px !important;
                    }

                    .mv-5-md {
                        margin-top:5px !important;
                        margin-bottom:5px !important;
                    }
                    .mh-5-md {
                        margin-left:5px !important;
                        margin-right:5px !important;
                    }

                    .mv-10-md {
                        margin-top:10px !important;
                        margin-bottom:10px !important;
                    }
                    .mh-10-md {
                        margin-left:10px !important;
                        margin-right:10px !important;
                    }

                    .mv-15-md {
                        margin-top:15px !important;
                        margin-bottom:15px !important;
                    }
                    .mh-15-md {
                        margin-left:15px !important;
                        margin-right:15px !important;
                    }

                    .mv-20-md {
                        margin-top:20px !important;
                        margin-bottom:20px !important;
                    }
                    .mh-20-md {
                        margin-left:20px !important;
                        margin-right:20px !important;
                    }

                    .p-0-md {
                        padding:0px !important;
                    }
                    .pl-0-md {
                        padding-left:0px !important;
                    }
                    .pr-0-md {
                        padding-right:0px !important;
                    }
                    .pt-0-md {
                        padding-top:0px !important;
                    }
                    .pb-0-md {
                        padding-bottom:0px !important;
                    }

                    .p-5-md {
                        padding:5px !important;
                    }
                    .pl-5-md {
                        padding-left:5px !important;
                    }
                    .pr-5-md {
                        padding-right:5px !important;
                    }
                    .pt-5-md {
                        padding-top:5px !important;
                    }
                    .pb-5-md {
                        padding-bottom:5px !important;
                    }

                    .p-10-md {
                        padding:10px !important;
                    }
                    .pl-10-md {
                        padding-left:10px !important;
                    }
                    .pr-10-md {
                        padding-right:10px !important;
                    }
                    .pt-10-md {
                        padding-top:10px !important;
                    }
                    .pb-10-md {
                        padding-bottom:10px !important;
                    }

                    .p-15-md {
                        padding:15px !important;
                    }
                    .pl-15-md {
                        padding-left:15px !important;
                    }
                    .pr-15-md {
                        padding-right:15px !important;
                    }
                    .pt-15-md {
                        padding-top:15px !important;
                    }
                    .pb-15-md {
                        padding-bottom:15px !important;
                    }

                    .p-20-md {
                        padding:20px !important;
                    }
                    .pl-20-md {
                        padding-left:20px !important;
                    }
                    .pr-20-md {
                        padding-right:20px !important;
                    }
                    .pt-20-md {
                        padding-top:20px !important;
                    }
                    .pb-20-md {
                        padding-bottom:20px !important;
                    }


                    .m-0-md {
                        margin:0px !important;
                    }
                    .ml-0-md {
                        margin-left:0px !important;
                    }
                    .mr-0-md {
                        margin-right:0px !important;
                    }
                    .mt-0-md {
                        margin-top:0px !important;
                    }
                    .mb-0-md {
                        margin-bottom:0px !important;
                    }

                    .m-5-md {
                        margin:5px !important;
                    }
                    .ml-5-md {
                        margin-left:5px !important;
                    }
                    .mr-5-md {
                        margin-right:5px !important;
                    }
                    .mt-5-md {
                        margin-top:5px !important;
                    }
                    .mb-5-md {
                        margin-bottom:5px !important;
                    }

                    .m-10-md {
                        margin:10px !important;
                    }
                    .ml-10-md {
                        margin-left:10px !important;
                    }
                    .mr-10-md {
                        margin-right:10px !important;
                    }
                    .mt-10-md {
                        margin-top:10px !important;
                    }
                    .mb-10-md {
                        margin-bottom:10px !important;
                    }

                    .m-15-md {
                        margin:15px !important;
                    }
                    .ml-15-md {
                        margin-left:15px !important;
                    }
                    .mr-15-md {
                        margin-right:15px !important;
                    }
                    .mt-15-md {
                        margin-top:15px !important;
                    }
                    .mb-15-md {
                        margin-bottom:15px !important;
                    }

                    .m-20-md {
                        margin:20px !important;
                    }
                    .ml-20-md {
                        margin-left:20px !important;
                    }
                    .mr-20-md {
                        margin-right:20px !important;
                    }
                    .mt-20-md {
                        margin-top:20px !important;
                    }
                    .mb-20-md {
                        margin-bottom:20px !important;
                    }

                }
                @media (min-width: 1200px) {
                    .pv-0-lg {
                        padding-top:0px !important;
                        padding-bottom:0px !important;
                    }
                    .ph-0-lg {
                        padding-left:0px !important;
                        padding-right:0px !important;
                    }

                    .pv-5-lg {
                        padding-top:5px !important;
                        padding-bottom:5px !important;
                    }
                    .ph-5-lg {
                        padding-left:5px !important;
                        padding-right:5px !important;
                    }

                    .pv-10-lg {
                        padding-top:10px !important;
                        padding-bottom:10px !important;
                    }
                    .ph-10-lg {
                        padding-left:10px !important;
                        padding-right:10px !important;
                    }

                    .pv-15-lg {
                        padding-top:15px !important;
                        padding-bottom:15px !important;
                    }
                    .ph-15-lg {
                        padding-left:15px !important;
                        padding-right:15px !important;
                    }

                    .pv-20-lg {
                        padding-top:20px !important;
                        padding-bottom:20px !important;
                    }
                    .ph-20-lg {
                        padding-left:20px !important;
                        padding-right:20px !important;
                    }

                    .mv-0-lg {
                        margin-top:0px !important;
                        margin-bottom:0px !important;
                    }
                    .mh-0-lg {
                        margin-left:0px !important;
                        margin-right:0px !important;
                    }

                    .mv-5-lg {
                        margin-top:5px !important;
                        margin-bottom:5px !important;
                    }
                    .mh-5-lg {
                        margin-left:5px !important;
                        margin-right:5px !important;
                    }

                    .mv-10-lg {
                        margin-top:10px !important;
                        margin-bottom:10px !important;
                    }
                    .mh-10-lg {
                        margin-left:10px !important;
                        margin-right:10px !important;
                    }

                    .mv-15-lg {
                        margin-top:15px !important;
                        margin-bottom:15px !important;
                    }
                    .mh-15-lg {
                        margin-left:15px !important;
                        margin-right:15px !important;
                    }

                    .mv-20-lg {
                        margin-top:20px !important;
                        margin-bottom:20px !important;
                    }
                    .mh-20-lg {
                        margin-left:20px !important;
                        margin-right:20px !important;
                    }

                    .p-0-lg {
                        padding:0px !important;
                    }
                    .pl-0-lg {
                        padding-left:0px !important;
                    }
                    .pr-0-lg {
                        padding-right:0px !important;
                    }
                    .pt-0-lg {
                        padding-top:0px !important;
                    }
                    .pb-0-lg {
                        padding-bottom:0px !important;
                    }

                    .p-5-lg {
                        padding:5px !important;
                    }
                    .pl-5-lg {
                        padding-left:5px !important;
                    }
                    .pr-5-lg {
                        padding-right:5px !important;
                    }
                    .pt-5-lg {
                        padding-top:5px !important;
                    }
                    .pb-5-lg {
                        padding-bottom:5px !important;
                    }

                    .p-10-lg {
                        padding:10px !important;
                    }
                    .pl-10-lg {
                        padding-left:10px !important;
                    }
                    .pr-10-lg {
                        padding-right:10px !important;
                    }
                    .pt-10-lg {
                        padding-top:10px !important;
                    }
                    .pb-10-lg {
                        padding-bottom:10px !important;
                    }

                    .p-15-lg {
                        padding:15px !important;
                    }
                    .pl-15-lg {
                        padding-left:15px !important;
                    }
                    .pr-15-lg {
                        padding-right:15px !important;
                    }
                    .pt-15-lg {
                        padding-top:15px !important;
                    }
                    .pb-15-lg {
                        padding-bottom:15px !important;
                    }

                    .p-20-lg {
                        padding:20px !important;
                    }
                    .pl-20-lg {
                        padding-left:20px !important;
                    }
                    .pr-20-lg {
                        padding-right:20px !important;
                    }
                    .pt-20-lg {
                        padding-top:20px !important;
                    }
                    .pb-20-lg {
                        padding-bottom:20px !important;
                    }


                    .m-0-lg {
                        margin:0px !important;
                    }
                    .ml-0-lg {
                        margin-left:0px !important;
                    }
                    .mr-0-lg {
                        margin-right:0px !important;
                    }
                    .mt-0-lg {
                        margin-top:0px !important;
                    }
                    .mb-0-lg {
                        margin-bottom:0px !important;
                    }

                    .m-5-lg {
                        margin:5px !important;
                    }
                    .ml-5-lg {
                        margin-left:5px !important;
                    }
                    .mr-5-lg {
                        margin-right:5px !important;
                    }
                    .mt-5-lg {
                        margin-top:5px !important;
                    }
                    .mb-5-lg {
                        margin-bottom:5px !important;
                    }

                    .m-10-lg {
                        margin:10px !important;
                    }
                    .ml-10-lg {
                        margin-left:10px !important;
                    }
                    .mr-10-lg {
                        margin-right:10px !important;
                    }
                    .mt-10-lg {
                        margin-top:10px !important;
                    }
                    .mb-10-lg {
                        margin-bottom:10px !important;
                    }

                    .m-15-lg {
                        margin:15px !important;
                    }
                    .ml-15-lg {
                        margin-left:15px !important;
                    }
                    .mr-15-lg {
                        margin-right:15px !important;
                    }
                    .mt-15-lg {
                        margin-top:15px !important;
                    }
                    .mb-15-lg {
                        margin-bottom:15px !important;
                    }

                    .m-20-lg {
                        margin:20px !important;
                    }
                    .ml-20-lg {
                        margin-left:20px !important;
                    }
                    .mr-20-lg {
                        margin-right:20px !important;
                    }
                    .mt-20-lg {
                        margin-top:20px !important;
                    }
                    .mb-20-lg {
                        margin-bottom:20px !important;
                    }

                }
                .pv-20{
                    padding-top:20px !important;
                    padding-bottom:20px !important;
                }

                .rotate_m90{
                    -webkit-transform: rotate(-90deg);
                    -ms-transform: rotate(-90deg);
                    transform: rotate(-90deg);
                }


                .alert-warning {
                    border-color: #8a6d3b;
                }
                .alert-danger {
                    border-color: #a94442;
                }
                .alert-info {
                    border-color: #31708f;
                }



                .text-muted{
                    color: #777 !important;
                }


                .pointer{
                    cursor:pointer;
                }
select option:disabled {
color: #c5c5c5;
}

button:disabled {
opacity: 1;
}

.form-inline .form-control {
    display: inline-block;
    width: auto;
    vertical-align: middle;
}




.user_seen_label{
    display:none;
    position: absolute;
    color: #13b402;
    top: 2px;
    right: 12px;
    font-size: 12px;
    line-height: 12px;
    border: 0px solid #fff;
    border-radius: 999px;
    padding: 4px;
    background-color: #fff;
}




.main-panel.nav{
    border-bottom:3px solid #6085bc;
}

.main-panel.nav .active a,
.main-panel.nav .active a:hover{
background-color: #6085bc !important;
color: #fff;
border:0;
}

.main-panel.nav a{

   border-bottom:0 !important;
}

.well {
    border-radius:0;
}
.well-lg{
    padding: 20px
}





.muted{
    opacity:0.5;
}


.border_bottom_collection{
    border-bottom:3px solid #6085bc;
}
.border_bottom_self_loaded{
    border-bottom:3px solid #bc6060;
}



#my_base_block .nav a{
    border: 0;
    cursor: pointer;
}
.nav-tabs > li{
    margin-bottom:0;
}




#my_base_block .nav > li > a:hover {
    text-decoration: none;
    background-color: rgba(0, 0, 0, 0);
}

.bg_color_collection,
.bg_color_collection:hover{
    background-color: #bc6060;
}

.bg_color_self_loaded,
.bg_color_self_loaded:hover{
    background-color: #6085bc;
}

#my_base_block .self_loaded_nav,
#my_base_block .self_loaded_nav:hover{
    background-color:rgb(188, 96, 96) !important;
    color:#fff;
}
#my_base_block .imported_nav,
#my_base_block .imported_nav:hover{
    background-color:rgb(96, 133, 188) !important;
    color:#fff;
}

.btn-primary {
    color: #fff;
    background-color: #6085bc !important;
    border-color: #597aab !important;
}

.btn-primary:hover {
    color: #fff;
    background-color: #5475a7 !important;
    border-color: #354c6f !important;
}


            </style>