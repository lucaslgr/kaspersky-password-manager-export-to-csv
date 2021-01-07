<?php
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="kaspersky-passwords.csv"');
readfile('kaspersky-passwords.csv');
exit;