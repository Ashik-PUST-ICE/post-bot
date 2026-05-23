<?php
$host = 'gateway01.ap-southeast-1.prod.aws.tidbcloud.com';
$port = 4000;
$db = 'postbot';
$user = '32Yeg9RBb22boRP.root';
$pass = 'ECOupK3I1WKGQO7A';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass, [
        PDO::MYSQL_ATTR_SSL_CA => 'c:\Users\ashik\post-bot\cacert.pem',
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    echo "Connected to TiDB successfully!\n";

    $stmt = $pdo->query("SELECT * FROM meta_app_configs LIMIT 1");
    $config = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($config) {
        echo "Found Meta App Config for user_id: " . $config['user_id'] . "\n";
        echo "FB App ID: " . $config['fb_app_id'] . "\n";
        echo "FB Page ID: " . $config['fb_page_id'] . "\n";
        // Check if page access token is present
        echo "Has Page Token: " . (!empty($config['fb_page_access_token']) ? 'Yes' : 'No') . "\n";
        
        // Let's attempt to subscribe the page manually right now via cURL!
        if (!empty($config['fb_page_id']) && !empty($config['fb_page_access_token'])) {
            echo "Attempting to subscribe page to webhooks...\n";
            $url = "https://graph.facebook.com/v20.0/" . $config['fb_page_id'] . "/subscribed_apps";
            
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                'access_token' => $config['fb_page_access_token'],
                'subscribed_fields' => 'messages,messaging_postbacks,messaging_optins,message_deliveries,message_reads,feed'
            ]));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            $err = curl_error($ch);
            $info = curl_getinfo($ch);
            curl_close($ch);
            echo "Facebook Graph API Response HTTP Status: " . $info['http_code'] . "\n";
            echo "Facebook Graph API Error: " . $err . "\n";
            echo "Facebook Graph API Body: " . $response . "\n";
        }
    } else {
        echo "No meta_app_configs found in production DB.\n";
    }

    $stmt = $pdo->query("SELECT * FROM platform_connections");
    $connections = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Total Platform Connections: " . count($connections) . "\n";
    foreach ($connections as $c) {
        echo "- Platform ID: " . $c['platform_id'] . ", Type: " . $c['platform_type'] . "\n";
    }

    // Check failed jobs
    $stmt = $pdo->query("SELECT COUNT(*) FROM failed_jobs");
    $failed = $stmt->fetchColumn();
    echo "Total Failed Jobs: " . $failed . "\n";

    // Check jobs queue
    $stmt = $pdo->query("SELECT COUNT(*) FROM jobs");
    $jobs = $stmt->fetchColumn();
    echo "Pending Jobs in Queue: " . $jobs . "\n";

} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}
