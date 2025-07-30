<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

     /* データベースの設定 */
    $dsn = 'mysql:dbname=qrcode;host=localhost;charset=utf8'; // データソース
    $user = 'qrcode'; // データベースアクセスのユーザ名
    $password = 'qrcode'; // データベースアクセスのパスワード

     /* データベース接続 */
    try{
        $db = new PDO($dsn, $user, $password); // インスタンス作成
    }catch (PDOException $e){ // エラー処理
        print('データベースに接続できません'.$e->getMessage()); 
        die();
     }
    /*データベース操作*/
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        header('Content-Type: application/json');

        $json = json_decode(file_get_contents("php://input"), true);

        if (isset($json['action'])) {
            switch ($json['action']) {

                     /*データベース確認及び新規作成*/
                case "start":
                    if (!isset($_COOKIE['user_id'])) {
                               //ユーザid作成
                        $random_id = mt_rand(100000, 999999);
                        setcookie('user_id', $random_id, time() + (86400*30), "/");
                        $user_id = $random_id;
                    } else {
                              //既存idを使用
                        $user_id = $_COOKIE['user_id'];
                          }
		        try {
		            $stmt = $db->prepare("SELECT * FROM user_data WHERE id = ? LIMIT 1");
		            $stmt->execute([$user_id]);
		            $data = $stmt->fetch(PDO::FETCH_ASSOC);
		            header('Content-Type: application/json');
		            if ($data){
                                     // 既存データベースの使用
		                echo json_encode(['status' => 'ok']);
		                exit;
		            } else{
                                     // データベースの新規作成
		                $stmt = $db->prepare("INSERT INTO user_data (id, w_ridge, s_ridge, n_ridge, g_ridge) VALUES (?, 0, 0, 0, 0)");
		                $stmt->execute([$user_id]);
		                echo json_encode(['status' => 'add']);
		                exit;
		               }
		        } catch (PDOException $e) {
		            http_response_code(500);
		            echo json_encode(['error' => $e->getMessage()]);
		            exit;
		          }

                     /*データ取得*/
                case "get_spot":
                    if (isset($_COOKIE['user_id'])){
                        $user_id = $_COOKIE['user_id']; //COOKIEがあれば使用
                    } else{
                        echo json_encode("Cookieが設定されていません"); //なければエラー
                        exit;
                          }
                    $stmt = $db->prepare("SELECT * FROM user_data WHERE id = ?");
                    $stmt->execute([$user_id]);
                    $data = $stmt->fetch(PDO::FETCH_ASSOC);
                    header('Content-Type: application/json');
                    echo json_encode($data);
                    exit;

                     /*データベース更新*/
                case "change_spot":
                    if (isset($_COOKIE['user_id'])){
                        $user_id = $_COOKIE['user_id']; //COOKIEがあれば使用
                    } else{
                        echo json_encode('Cookieが設定されていません'); //なければエラー
                        exit;
                          }
                    $ridge = $json['ridge'] ?? null;
                          // 読み取ったQRコードの値が関係無いものなら無視
                    if (!in_array($ridge, ['w_ridge', 's_ridge', 'n_ridge', 'g_ridge'])) {
                        http_response_code(400);
                        echo json_encode(['error' => 'invalid ridge']);
                        exit;
                          }
                         // 読み取ったQRコードの値がデータベースにあればその棟の値を1に変更
                    $stmt = $db->prepare("UPDATE user_data SET $ridge = 1 WHERE id = ?");
                    $stmt->execute([$user_id]);
                    echo json_encode(['data' => 'ok']);
                    exit;

                default:
                    echo json_encode(['error' => 'Unknown action']);
                    break;
            }
        } else {
            echo json_encode(['error' => 'No action']);
        }
        exit;
    }
?>
