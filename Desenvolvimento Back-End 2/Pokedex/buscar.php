<?php
header('Content-Type: application/json');

header('Acess-Control-Allow-Origin: *');

header('Acess-Control-Allow-Methods: GET, POST, OPTIONS');

header('Acess-Control-Allow-Headers: Content-Type, Authorization');

$response = array(
    "sucess" => false,
    "name" => "Pokemon não encontrado",
    "id" => "",
    "type" => "",
    "descripction" => "Tente outro nome.",
    "image" => "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/0.png"
);

if (!empty($_GET["pokemonNome"])) {
    $searchName = strtolower(trim($_GET["pokemonNome"]));

    $url = "https://pokeapi.co/api/v2/pokemon/" . $searchName;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $apiResponse = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if($httpcode == 200){
        $response = "SUCESSO!!!";
        $pokemonData = json_decode($apiResponse, true);
        $pokemonName = ucfirst($pokemonData['name']);
        $pokemonId = $pokemonData['id'];
        $pokemonType = ucfirst($pokemonData['types'][0]['type']['name']);
        $pokemonImage = $pokemonData['sprites']['other']['official-artwork']['front_default'];

        $descurl = $pokemonData['species']['url'];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $descurl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $descResponse = curl_exec($ch);
        curl_close($ch);

        $descData = json_decode($descResponse, true);

        $description = "";

        foreach($descData['flavor_text_entries'] as $entry){
            if($entry['language']['name'] == 'en'){
                $description = $entry['flavor_text'];
                break;
            }
        }

        $response = array(
            "sucess" => true,
            "name" => $pokemonName,
            "id" => $pokemonId,
            "type" => $pokemonType,
            "descripction" => $description,
            "image" => $pokemonImage
        );
    }
}

echo json_encode($response);

?>