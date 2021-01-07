<?php

//Gettind all content in export passwords of Kaspersky Password Manager
$password_content = trim(file_get_contents('kaspersky-passwords.txt'));

//Spliting all registers
$passwords_splited = (explode('---', $password_content));

//Taking off the last one that will be empty
array_pop($passwords_splited);

//Counting how many are there
$qtd_passwords = count($passwords_splited);

//Store the info of each row
$passwords_splited_info = [];

//Browsing all registers
foreach($passwords_splited as $key => $each_password){
    //Spliting for break lines
    $each_password = preg_split('/\r\n|\n|\r/', $each_password);
    
    //Removing all empty items from array
    $passwords_splited_info[$key] = array_filter(
        $each_password,
        fn($each_row) => empty($each_row)?false:true
    );    
}

//Getting the key and the value
foreach($passwords_splited_info as $key => $each_password_info){

    foreach($each_password_info as $row_info){
        if(strpos($row_info, ':') !== false){
            $entries = explode(': ', $row_info);

            $passwords_splited_info[$key][trim($entries[0])] = trim($entries[1]);
        }
        else
            $passwords_splited_info[$key][trim($row_info)] = '';
    }
}

//Putting all content in the correct place of array template to csv file
$template_output = [
    'name,url,username,password'
];

foreach ($passwords_splited_info as $key => $each_passwords_splited_info) {

    if(!isset($each_passwords_splited_info['Website name'])){
        $template_output[] = "{$each_passwords_splited_info['Application']}," .
            "," .
            "{$each_passwords_splited_info['Login']}," .
            "{$each_passwords_splited_info['Password']}";
    }
    else {
        $template_output[] = "{$each_passwords_splited_info['Website name']}," .
            "{$each_passwords_splited_info['Website URL']}," .
            "{$each_passwords_splited_info['Login']}," .
            "{$each_passwords_splited_info['Password']}";
    }
}



//Converting to csv file
$fp = fopen('kaspersky-passwords.csv', 'wb');
foreach ( $template_output as $line ) {
    $val = explode(",", $line);
    fputcsv($fp, $val);
}
fclose($fp);

?>

<style>
    pre {
        background-color: #222;
        color: #01F702;
        padding-left: 30px;
        padding-top: 20px;
    }
</style>

<h1>Number of entries: <?= $qtd_passwords ?></h1>
<a target="_blank" href="csv_file.php">Download your file here</a>

<pre>
    <?php// print_r($passwords_splited); ?>
    <?php //print_r($passwords_splited_info); ?>
    <?php print_r($template_output); ?>
</pre>