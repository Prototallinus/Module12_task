<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="vieport" content="width=device=width, initial-scale=1.0">
    <title>Practice Module 12</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css"/>
</head>

<body>

    <div class="header">
        <?php include 'name_list.php' ?>
        <?php include 'functions.php' ?>
    </div>

    <h1>Модуль 12.6. Практическая работа</h1>
    <h1>Мой ФИО:<?php echo $surname_name_patronomyc ?></h1>

    <div id="NamesArray">
        <pre><?php print_r($example_persons_array); ?></pre>
    </div>
    

    <!-- HTML table with function example results -->
    <table>
        <colgroup>
            <col span="1" style= "background-color: #eee; font-weight: bold">
        </colgroup>
        <tr>
            <th><strong>Название Упражнения</strong></th>
            <th><strong>Возврат</strong></th>
        </tr>
        <tr>
            <!-- Results of the getFullnameFromParts function using as inputs the predefined sample name in functions.php file -->
            <td>Разбиение и объединение ФИО<br>getFullnameFromParts:</td>
            <td><?php echo "\"" . $getFullnameFromParts($surname, $name, $patronomyc) . "\""; ?></td>    
        </tr>
        <tr>
            <!-- Results of the getPartsFromFullname function using as inputs the predefined sample name in functions.php file -->
            <td>Разбиение и объединение ФИО<br>getPartsFromFullname:</td>
            <td><?php foreach ($getPartsFromFullname($surname_name_patronomyc) as $key => $value) {echo "{$key} => {$value}" . "</br>";} ?></td>
        </tr>
        <tr>
            <!-- Results of the getShortName function using as inputs the predefined sample name in functions.php file -->
            <td>Сокращение ФИО:</td>
            <td><?php echo "\"" . $getShortName($surname_name_patronomyc) . "\""; ?></td>
        </tr>
        <tr>
            <!-- Results of the getGenderFromName function using as inputs the predefined sample name in functions.php file -->
            <td>Функция определения пола по ФИО:</td>
            <td><?php echo $getGenderFromName($surname_name_patronomyc); ?></td>
        </tr>
        <tr>
            <!-- Results of the getGenderDescription function using as inputs the array populated with Russian names in name_list.php file -->
            <td>Определение возрастно-полового состава:</td>
            <td>
                <?php 
                    echo "Гендерный состав аудитории:</br>----------------------------------------</br>";
                    echo "Мужчины - " . ($getGenderDescription($example_persons_array)[1]) . "%</br>";
                    echo "Женщины - " . ($getGenderDescription($example_persons_array)[-1]) . "%</br>";
                    echo "Не удалось определить - " . ($getGenderDescription($example_persons_array)[0]) . "%</br>"; 
                ?>
            </td>
        </tr>
        <tr>
            <!-- Results of the getPerfectPartner function using as inputs the predefined sample name in functions.php file and the array populated with Russian names in name_list.php file -->
            <td>Идеальный подбор пары:</td>
            <td><?php echo $getPerfectPartner($surname, $name, $patronomyc, $example_persons_array); ?></td>
        </tr>
    </table> 

</body>