<?php
$password = 'admin123';
$hash = '$2y$10$Jfn.lvHKU8RNT0wnel.i7.eGiwaO5ms.UNLZ.wf9P/OvfF/ni1KSm';
var_dump(password_verify($password, $hash));
