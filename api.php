

<?php
header('Content-Type: application/json');

// データベース接続情報
$host = 'localhost'; // ※データ保護のため実際の情報とは異なります。
$dbname = 'sample'; // ※データ保護のため実際の情報とは異なります。
$user = 'sample'; // ※データ保護のため実際の情報とは異なります。
$password = 'sample'; // ※データ保護のため実際の情報とは異なります。
$port = 3306;

// PDO (PHP Data Objects) を使用してデータベースに接続
try {
    $dsn = "mysql:host=$host;dbname=$dbname;port=$port;pcharset=utf8mb4";
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
    exit();
}

// リクエストの処理
$action = $_GET['action'] ?? null;

if ($action === 'get_questions') {
    // 5つのランダムな和歌を取得する処理
    try {
        $stmt = $pdo->query("SELECT waka_id, kami_no_ku, kami_no_ku_yomi, shimo_no_ku, shimo_no_ku_yomi, sakusha, sakusha_yomi, kaisetsu, haikei FROM waka ORDER BY RAND() LIMIT 5");
        $questions = $stmt->fetchAll();

        $response = [];
        foreach ($questions as $question) {
            // 正解の下の句
            $correct_answer = [
                'shimo_no_ku' => $question['shimo_no_ku'],
                'shimo_no_ku_yomi' => $question['shimo_no_ku_yomi']
            ];

            // 他のランダムな下の句を3つ取得（正解を除く）
            $stmt = $pdo->prepare("SELECT shimo_no_ku, shimo_no_ku_yomi FROM waka WHERE waka_id != ? ORDER BY RAND() LIMIT 3");
            $stmt->execute([$question['waka_id']]);
            $incorrect_answers = $stmt->fetchAll();

            $choices = array_merge([$correct_answer], $incorrect_answers);
            shuffle($choices); // 選択肢をシャッフル

            $response[] = [
                'kami_no_ku' => $question['kami_no_ku'],
                'kami_no_ku_yomi' => $question['kami_no_ku_yomi'],
                'shimo_no_ku' => $question['shimo_no_ku'],
                'shimo_no_ku_yomi' => $question['shimo_no_ku_yomi'],
                'sakusha' => $question['sakusha'],
                'sakusha_yomi' => $question['sakusha_yomi'],
                'kaisetsu' => $question['kaisetsu'],
                'haikei' => $question['haikei'],
                'correct_answer' => $correct_answer['shimo_no_ku'],
                'choices' => $choices
            ];
        }
        echo json_encode($response);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Failed to retrieve questions: ' . $e->getMessage()]);
    }
} elseif ($action === 'get_ikai') {
    // スコアに応じて位階を返す処理
    $input = json_decode(file_get_contents('php://input'), true);
    $score = $input['score'] ?? null;

    if ($score === null || !is_numeric($score)) {
        echo json_encode(['error' => 'Invalid score provided.']);
        exit();
    }

    $ikai_id = 1; // デフォルトは「地下人」
    if ($score > 80) {
        $ikai_id = 5; // 摂政・関白
    } elseif ($score > 60) {
        $ikai_id = 4; // 太政大臣
    } elseif ($score > 40) {
        $ikai_id = 3; // 公卿
    } elseif ($score > 20) {
        $ikai_id = 2; // 諸大夫
    }

    try {
        $stmt = $pdo->prepare("SELECT ikai_name, ikai_yomi, kaisetsu FROM ikai WHERE ikai_id = ?");
        $stmt->execute([$ikai_id]);
        $ikai = $stmt->fetch();

        if ($ikai) {
            echo json_encode($ikai);
        } else {
            echo json_encode(['error' => 'Rank not found.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Failed to retrieve rank: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Invalid action specified.']);
}