<?php

include 'name_list.php';
//-----------------------------
//sample name section (male name) to populate the HTML table in index.php file and therefore prove the effectiveness of the functions in this file 
//-----------------------------

// name parts
$surname = "Талларико"; 
$name = "Луиджи";
$patronomyc = "Франческович";

//full name string
$surname_name_patronomyc = "Таллариков Луиджи Франческович"; 

//-------------------------------------------------------------------------
/////////////////
/*  
    This is the section of the first function requested as exercise 
    getFullnameFromParts assembles in a single string the surname, name and patronomyc given as inputs.
*/
/////////////////

function getFullnameFromParts ($surname, $name, $patronomyc) {
    return $surname . ' ' . $name . ' ' . $patronomyc; //Use of concatenation 
}
$getFullnameFromParts = 'getFullnameFromParts';


/////////////////
/*
    This is the section of the second function requested.
    getPartsFromFullname returns the array of the surname, name and patronomyc, in this order, inputted as a whole string.
    The function replace_key was added since the usage of explode forced us to change the key names in order to get as requested the following key names in this order: "surname", "name", "patronomyc". This might have been done more easily, creating directly a new array with the desired keys, but we opted for a more general and instructive way. 
*/
/////////////////
function replace_key($arr, $oldkey, $newkey) { //This function changes the value assigned to a key to a new one without changing the order of the data in the array
    if(array_key_exists($oldkey, $arr)) { //check for oldkey existence
        $keys = array_keys($arr); //displsys the current keys
        $keys[array_search($oldkey, $keys)] = $newkey; //substitutes the old key with the new one
        return array_combine($keys, $arr); //combines again the keys with their respective value to get the array updated
    }
    return $arr;    
}
function getPartsFromFullname ($surname_name_patronomyc) {
    
    $NameParts = explode(' ', $surname_name_patronomyc); //gets the keys and the values separated when encounters a single space

    for ($oldkey = 0; $oldkey <= count($NameParts)-1; $oldkey++){ //"for" cycle to define the new desired keys, knowing already the order of the values
        
        if ($oldkey == "0") {
            $newkey = "surname";
        } elseif ($oldkey == "1") {
            $newkey = "name";
        } else {
            $newkey = "patronomyc";
        }
        $NameParts = replace_key($NameParts, $oldkey, $newkey); //calls function to replace the keys
        
    }
    return $NameParts; //returns array with surname, name and patronomyc
}
$getPartsFromFullname = 'getPartsFromFullname';


//-------------------------------------------------------------------------
/////////////////
/* 
    This is the function to get the name and the first letter of the surname, while eliminating the patronymic
*/
/////////////////

function getShortName ($surname_name_patronomyc) {
    //Assignes the name to NameComponents array making use of getPartsFromFullname function above. No mb_substr is required in this case.
    $NameComponents[0] = getPartsFromFullname($surname_name_patronomyc)['name'];
    //Assignes the surname to NameComponents array making use of getPartsFromFullname function above. mb_substr is required since we do want only a portion of a cyrillic string
    $NameComponents[1] = mb_substr(getPartsFromFullname($surname_name_patronomyc)['surname'], 0, 1) . '.'; 
    return $NameComponents[0] . " " . $NameComponents[1]; //returns a string, as requested. This function is easily customizable if returning an array is needed 
}
$getShortName = 'getShortName';



//-------------------------------------------------------------------------
/////////////////
/* 
    This is the section with the function dedicated to verify the gender of a person's surname, name and patronymic, making use of a probabilistic approach based on Russian language.
*/
/////////////////

function getGenderFromName ($surname_name_patronomyc) {
    $NameComponents = getPartsFromFullname($surname_name_patronomyc); //gets the name parts
    $Total_Sex_Attribute = 0; //assigns an initial value (if Total_Sex_Attribute > 1 => man, if , 1 => woman, else not clear (undefined))

    //foreach function goes through the array to check for signs of male or female name characteristics
    foreach ($NameComponents as $key => $value) {
        //if section for surname hints check
        if ($key == "surname") {
            if (mb_substr($value, (mb_strlen($value)-2)) == "ва") {
                $Total_Sex_Attribute--; //female hints detected
            } elseif (mb_substr($value, (mb_strlen($value)-1)) == "в") {
                $Total_Sex_Attribute++; //male hints detected
            }
        }
        //if section for name hints check
        if ($key == "name") {
            if (mb_substr($value, (mb_strlen($value)-1)) == "а") {
                $Total_Sex_Attribute--; //female hints detected
            } elseif ( (mb_substr($value, (mb_strlen($value)-1)) == "й" ) || (mb_substr($value, (mb_strlen($value)-1)) == "н") ) {
                $Total_Sex_Attribute++; //male hints detected
            }
        }
        //if section for patronymic hints check
        if ($key == "patronomyc") {
            if (mb_substr($value, (mb_strlen($value)-3)) == "вна") {
                $Total_Sex_Attribute--; //female hints detected
            } elseif (mb_substr($value, (mb_strlen($value)-2)) == "ич") {
                $Total_Sex_Attribute++; //male hints detected
            }
        }
    }
    //spaceship to assign 1 if man, -1 if woman, 0 if undefined
    return $Total_Sex_Attribute <=> 0; 
}
$getGenderFromName = 'getGenderFromName';


//-------------------------------------------------------------------------
/////////////////
/* 
    This is the section where the raw statistical percentage of male and female presence is calculated.
    People with an undefined name are also taken into account.
    The function used is array_filter combined with the previous function getGenderFromName, as requeted.
*/
/////////////////

function getGenderDescription ($example_persons_array) {
    //counts the total number of persons, so as to fix the denominator of the ratio of men, women and undefined people
    $TotalPersons = count($example_persons_array); 
    
    //Male names are filtered. 1 is the filter condition, in that it is the male name result
    $Men = array_filter($example_persons_array, function ($person) {
        return getGenderFromName($person['fullname']) == 1;
    });
    $MenPercentage = round(count($Men)/$TotalPersons*100,1);
    
    //Female names are filtered. -1 is the filter condition, in that it is the female name result
    $Women = array_filter($example_persons_array, function ($person) {
        return getGenderFromName($person['fullname']) == -1;
    });
    $WomenPercentage = round(count($Women)/$TotalPersons*100,1);
    
    //Unclear names are filtered. 0 is the filter condition, in that it is the undefined name result
    $Unclear = array_filter($example_persons_array, function ($person) {
        return getGenderFromName($person['fullname']) == 0;
    });
    $UnclearPercentage = round(count($Unclear)/$TotalPersons*100,1);

    //An array with all percentages is returned. Keys have been set to ease the writing of the following getPerfectPartner function 
    return ["1" => $MenPercentage, "-1" => $WomenPercentage, "0" => $UnclearPercentage];
}
$getGenderDescription = 'getGenderDescription';


//-------------------------------------------------------------------------
/////////////////
/* 
    This is the section for the getPerfectPartner functions. 
    It pairs an imposed input name with a name of an opposite gender from our example_persons_array list, and then outputs a random love compatibility value between 50% and 100%.  
*/
/////////////////

function getPerfectPartner ($surname, $name, $patronomyc, $example_persons_array) {
    
    //only first letter upper-case is imposed
    $surname = mb_convert_case($surname, MB_CASE_TITLE_SIMPLE);
    $name = mb_convert_case($name, MB_CASE_TITLE_SIMPLE);
    $patronomyc = mb_convert_case($patronomyc, MB_CASE_TITLE_SIMPLE);
 
    //Full name assembled in a single string
    $FullName = getFullnameFromParts ($surname, $name, $patronomyc);
    
    //defines gender
    $gender = getGenderFromName($FullName);
    
    //surname shortened and patronymic omitted as per privacy reasons 
    $ShortName = getShortName($FullName);

    //Casually extracts a person from our example_persons_array list
    $CasualMate = $example_persons_array[rand(0, count($example_persons_array) - 1)]['fullname'];
    //while cycle to "couple" this casually extracted person with our first inputted person.
    //gender opposition is required: if getGenderFromName outputs an opposite number of gender for the random person (product yields -1), it means they are compatible 
    while ( ((getGenderFromName($CasualMate) * $gender) != -1) ) {
        //if section to esclude the case in which there are not people of the opposite gender available in our list
        if ( (getGenderDescription($example_persons_array))[$gender*-1] == 0) {
            break;
        }
        //picks a new casual person from the list until it meets requirements
        $CasualMate = $example_persons_array[rand(0, count($example_persons_array) - 1)]['fullname'];
    }
    $CasualMateShortName = getShortName($CasualMate); // shortens the name of the person picked from the list
    
    $LoveCompatibility = round(mt_rand(5000, 10000)/100,2); //calculates love compatibility randomly between 50% and 100%

    return $ShortName . " + " . $CasualMateShortName . " =<br/>" . "♡ Идеально на " . $LoveCompatibility . "% ♡"; //returns result for the couple
}
$getPerfectPartner = 'getPerfectPartner';

?>