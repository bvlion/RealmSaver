<?php

// get backup data
function select_backup($user_hash, $con) {
    $stmt = $con->prepare('SELECT json_data, downloaded FROM realm_save_data WHERE user_hash = :user_hash');
    $stmt->bindValue(':user_hash', $user_hash, PDO::PARAM_STR);

    $stmt->execute();

    $data = "";
    while($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
        $data = $row['json_data'];
        if ($row['downloaded']) {
            $data = '{"backUps":[]}';
            Log::info("Content already downloaded:" . $user_hash);
        } else {
            Log::info("Content download:" . $user_hash);
        }
    }
    if (empty($data)) {
        Log::warn('Content Not Found:' . $user_hash);
    }

    return $data;
}

// set downloaded backup data
function downloaded($user_hash, $con) {
    $stmt = $con->prepare("UPDATE realm_save_data SET downloaded = 1, update_date = NOW() WHERE user_hash = :user_hash");
    $stmt->bindValue(':user_hash', $user_hash, PDO::PARAM_STR);

    return $stmt->execute();
}

// save backup data
function insert_backup($user_hash, $json_data, $con) {
    try {
        $stmt = $con->prepare("INSERT INTO realm_save_data (user_hash, json_data) VALUES (:user_hash, :json_data)");
        $stmt->bindValue(':user_hash', $user_hash, PDO::PARAM_STR);
        $stmt->bindValue(':json_data', $json_data, PDO::PARAM_STR);
        $exec = $stmt->execute();
        $result = $exec == 1;
        Log::info("Content save:" . $user_hash . " -> " . var_export($result, true));
        return $result;
    } catch(Exception $e) {
        Log::error("Content save error:" . $user_hash, $e);
        return false;
    }
}
