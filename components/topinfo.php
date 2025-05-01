<?php
$tiactive = 1;
$tisdate = "";
$tiedate = "v";
$tiurl = "";
$titab = 0;
$ticlose = "Fermer";
$timessage = "Promotion sur le cannapé-lit";
$titextcolor = "#492743";
$tibackgroundcolor = "#FAB";

if ($tiactive == 1) {

    $today = date('Y-m-d');
    if (($today > $tisdate) && ($today < $tiedate)) {
        echo "
        <div id='top-information-banner' role='status'>
        <div>";

        if ($tiurl == "") {
            echo '<p>' . $timessage . '</p>';
        } else {
            echo '<a href="' . $tiurl . '">' . $timessage . '</a>';
        }
        echo '
         <button aria-label="' . $ticlose . '" onclick="closebanner()">
         <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" xml:space="preserve" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:1.5"><path style="fill:none" d="M173.879 114.512h46.806v46.806h-46.806z" transform="translate(-89.1583 -58.71) scale(.51276)"/><path d="m938.2 5.05-9.864 9.864" style="fill:none;stroke:' . $titextcolor  . ';stroke-width:1.26px" transform="matrix(1.19236 0 0 1.19236 -1100.7928 .1051)"/><path d="m938.2 5.05-9.864 9.864" style="fill:none;stroke:' . $titextcolor . ';stroke-width:1.26px" transform="matrix(0 1.19236 -1.19236 0 23.9022 -1100.7819)"/></svg></button>
        </div>
    </div>';



        echo '<style>
    #top-information-banner {
    background-color: ' . $tibackgroundcolor  . ';
    color: ' . $titextcolor . '; 
 }

 #top-information-banner div {
    max-width: 1224px;
    margin:0 auto;
    font-size: 14px;
    padding: 2px 12px;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
    gap:12px;
    font-weight:bold;
 }

 #top-information-banner button {
    background-color: rgba(0, 0, 0, 0);
    color:' . $titextcolor . '; 
    margin:0;
    border:0;
    padding:2px;
    cursor:pointer;
 }
 #top-information-banner svg {
    height: 24px;
    width: 24px;
 }

 #top-information-banner p, #top-information-banner a  {
    margin-bottom:0;
    width:100%;
    max-width: initial;
    padding: 4px 12px;
    text-align: center;
    color: ' . $titextcolor . '; 
    text-decoration:none;
    border-bottom:none;
 }
 #top-information-banner a:focus-visible,
 #top-information-banner button:focus-visible {
    outline: ' . $titextcolor . ' 2px solid;
}
 </style>
 <script>
     function closebanner() {
         document.getElementById("top-information-banner").parentNode.removeChild(document.getElementById("top-information-banner"));
         definir_cookie();
     }

     if (document.cookie.indexOf("hideInfoBanner") === -1) {
       
     } else {
         document.getElementById("top-information-banner").style.display = "none";
     }

     function definir_cookie() {
         var date = new Date();
         date.setTime(date.getTime() + (2 * 24 * 60 * 60 * 1000));
         var expires = "expires=" + date.toUTCString();
         // Définir le cookie avec le nom "hideInfoBanner" et la valeur "true" et la date dexpiration
         document.cookie = "hideInfoBanner=true;" + expires + ";path=/";
     }
 </script>';
    }
}
