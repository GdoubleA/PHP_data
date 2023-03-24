<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php
$example_persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];

// razbienie FIO na chasti
function getPartsFromFullname($personName)
{
    $parts = explode(" ", $personName);
    $parts['surname'] = $parts[0];
    unset($parts[0]);
    $parts['name'] = $parts[1];
    unset($parts[1]);
    $parts['patronomyc'] = $parts[2];
    unset($parts[2]);
    return $parts;
}

// ob'edinenie FIO iz chastei
function getFullnameFromParts($surname, $name, $patronomyc)
{
    $fullname = $surname . ' ' . $name . ' ' . $patronomyc;
    return $fullname;
}

// sokrashenie FIO
function getShortName($personName)
{
    $Name = getPartsFromFullname($personName);
    unset($Name['patronomyc']);
    $firstSymbol = mb_substr($Name['name'], 0, 1, 'utf-8');
    $shortName = $Name['surname'] . " " . $firstSymbol . ".";
    return $shortName;
}

// opredelenie pola po FIO
function getGenderFromName($personName)
{
    $partsName = getPartsFromFullname($personName);
    $checkGender = 0;
    // male
    if (mb_substr($partsName['surname'], -1, 1, 'utf-8') === "в") {$checkGender ++;}
    if (mb_substr($partsName['name'], -1, 1, 'utf-8') === "й" || mb_substr($partsName['name'], -1, 1, 'utf-8') === "н" ) {$checkGender ++;}
    if (mb_substr($partsName['patronomyc'], -3, 3, 'utf-8') === "вич") {$checkGender ++;}
    // female
    if (mb_substr($partsName['surname'], -2, 2, 'utf-8') === "ва") {$checkGender --;}
    if (mb_substr($partsName['name'], -1, 1, 'utf-8') === "а") {$checkGender --;}
    if (mb_substr($partsName['patronomyc'], -3, 3, 'utf-8') === "вна") {$checkGender --;}
    if ($checkGender > 0) {return 1;} //male
    if ($checkGender < 0) {return -1;} //female
    else {return 0;}
}

// opredelenie vozrastno-polovogo sostava
function getGenderDescription ($arrGender, $arrLength)
{
    $countMale = 0;
    $countFemale = 0;
    $countUnknown = 0;
    // kolichestvo male, female n others
    foreach($arrGender as $value){
        if ($value == 1) {$countMale++;}
        if ($value == -1) {$countFemale++;}
        if ($value == 0) {$countUnknown++;}
    }
    // % opredelenie iz vsego kolichestva
    $malePercent = ($countMale / $arrLength) * 100;
    $femalePercent = ($countFemale / $arrLength) * 100;
    $unknownPercent = ($countUnknown / $arrLength) * 100;
    echo 'Гендерный состав аудитории:';
    echo "<br>";
    echo "--------------------------------------";
    echo "<br>";
    echo "Мужчины" . " " . "-" . " " . round($malePercent) . "%";
    echo "<br>";
    echo "Женщины" . " " . "-" . " " . round($femalePercent) . "%";
    echo "<br>";
    echo "Не удалось определить" . " " . "-" . " " . round($unknownPercent) . "%";
    echo "<br>";
}

// ideal'niy podbor pari
function getPerfectPartner($surname, $name, $patronomyc, $partners_array)
{
    $personFullName = getFullnameFromParts($surname, $name, $patronomyc);
    $genderPerson = getGenderFromName($personFullName);
    while ($genderPerson === 0){
        return 1;
    }
    // random partner
    $randomPartner = $partners_array[random_int(0, count($partners_array)-1)]['fullname'];
    // proverka pola
    $genderPartner = getGenderFromName($randomPartner);
    // proverka na sovpadenie
    while ($genderPerson === $genderPartner || $genderPartner === 0 || $personFullName === $randomPartner)
    {
        $randomPartner = $partners_array[random_int(0, count($partners_array)-1)]['fullname'];
        $genderPartner = getGenderFromName($randomPartner);
    }
    $shortPersonName = getShortName($personFullName);
    $shortPartnerName = getShortName($randomPartner);
    $percentCompatibility = mt_rand(50, 100) + mt_rand(0, 100) / 100;
    echo $shortPersonName . "+" . " " . $shortPartnerName . " " . "=" . "<br>" . "♡" . "Идеально на" . " " . $percentCompatibility . "%". " " .  "♡";
    return 0;
}

// vizov 1 funkcii

    $arrParts = getPartsFromFullname($example_persons_array[2]['fullname']);
    echo "Result 1 Function: " . "<br>";
    print_r($arrParts);
    echo "<br>". "<br>";


// vizov 2 funkcii

    $arrFullName = getFullnameFromParts($arrParts['surname'], $arrParts['name'], $arrParts['patronomyc']);
    echo "Result 2 Function: " . "<br>";
    print_r($arrFullName);
    echo "<br>". "<br>";


// vizov 3 funkcii

    $nameShort = getShortName($example_persons_array[2]['fullname']);
    echo "Result 3 Function: " . "<br>";
    print_r($nameShort);
    echo "<br>". "<br>";


// vizov 4 funkcii

    for ($i = 0; $i < count($example_persons_array); $i++){
        $arrGender[$example_persons_array[$i]['fullname']] = getGenderFromName($example_persons_array[$i]['fullname']);
    }
    echo "Result 4 Function: " . "<br>";
    print_r($arrGender);
    echo "<br>"."<br>";


// vizov 5 funkcii

    echo "Result 5 Function: " . "<br>";
    getGenderDescription($arrGender, $arrLenght = count($example_persons_array));
    echo "<br>";


// vizov ideal'niy partner

    echo "Result 6 Function: " . "<br>";
    $arrParts = getPartsFromFullname($example_persons_array[random_int(0, count($example_persons_array)-1)]['fullname']);
    $choosePartner = getPerfectPartner($arrParts['surname'], $arrParts['name'], $arrParts['patronomyc'],$example_persons_array);
    while ($choosePartner === 1)
    {  
        $arrParts = getPartsFromFullname($example_persons_array[random_int(0, count($example_persons_array)-1)]['fullname']);
        $choosePartner = getPerfectPartner($arrParts['surname'], $arrParts['name'], $arrParts['patronomyc'],$example_persons_array);
    }


?>
</body>
</html>

