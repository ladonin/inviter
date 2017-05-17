<?php
    setcookie("au",hashing($email.$password),time()+(3600*24*31), '/');
    setcookie("du",$result['id'],time()+(3600*24*31), '/');