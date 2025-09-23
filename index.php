<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="image/icon.svg">
    <title>いろは百人一首</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Serif+JP:wght@400;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Noto Serif JP', serif;
            background: linear-gradient(135deg, #f9e1b6 0%, #f4c5a1 25%, #f0998f 50%, #ec7e8c 75%, #d45478 100%);
            min-height: 100vh;
            color: #5a2e4d;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        body.modal-open {
            overflow: hidden;
        }

        .container {
            background: rgba(249, 225, 182, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(90, 46, 77, 0.3);
            overflow: hidden;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(132, 59, 109, 0.3);
            display: flex;
            flex-direction: column;
            width: 90%;
            height: 90vh;
        }

        .header {
            background: linear-gradient(45deg, #843b6d, #a8436f);
            color: #f9e1b6;
            padding: 30px;
            text-align: center;
            position: relative;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="%23f9e1b6" opacity="0.3"/><circle cx="80" cy="30" r="1.5" fill="%23f9e1b6" opacity="0.2"/><circle cx="60" cy="70" r="1" fill="%23f9e1b6" opacity="0.4"/><circle cx="30" cy="80" r="2.5" fill="%23f9e1b6" opacity="0.2"/></svg>');
        }

        h1 {
            font-size: 2.2em;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            z-index: 1;
            position: relative;
            letter-spacing: 0.1em;
        }

        .subtitle {
            font-size: 1.1em;
            opacity: 0.9;
            z-index: 1;
            position: relative;
        }

        .main-content {
            flex-grow: 1;
            padding: 15px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .start-screen {
            text-align: center;
        }

        .description {
            font-size: 1.2em;
            line-height: 1.8;
            margin: 30px auto;
            background: rgba(168, 67, 111, 0.1);
            padding: 30px;
            border-radius: 15px;
            border-left: 5px solid #a8436f;
        }

        .start-btn {
            background: linear-gradient(45deg, #a8436f, #d45478);
            color: white;
            border: none;
            padding: 20px 50px;
            font-size: 1.3em;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 10px 25px rgba(168, 67, 111, 0.4);
            font-family: inherit;
            font-weight: 700;
            letter-spacing: 0.1em;
        }

        .start-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(168, 67, 111, 0.6);
            background: linear-gradient(45deg, #d45478, #ec7e8c);
        }

        .quiz-screen {
            display: none;
            flex: 1;
            flex-direction: column;
        }

        .progress-bar {
            width: 100%;
            height: 8px;
            background: rgba(168, 67, 111, 0.2);
            border-radius: 4px;
            overflow: hidden;
            margin: 0 auto 30px;
            flex-shrink: 0;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(45deg, #a8436f, #d45478);
            transition: width 0.5s ease;
            border-radius: 4px;
        }

        .question-card {
            background: rgba(255, 255, 255, 0.7);
            padding: 30px;
            border-radius: 20px;
            margin: auto;
            border: 2px solid rgba(132, 59, 109, 0.2);
            box-shadow: 0 10px 25px rgba(90, 46, 77, 0.1);
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            flex: 1;
            min-height: 0;
        }

        .question-number {
            color: #a8436f;
            font-weight: 700;
            font-size: 1.2em;
        }

        .kami-no-ku {
            font-size: clamp(1.2em, 2.5vh, 1.8em);
            line-height: 1.6;
            text-align: center;
            color: #5a2e4d;
            margin-bottom: 0;
            font-weight: 700;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }

        .kami-no-ku-yomi {
            font-size: 0.9em;
            text-align: center;
            color: #843b6d;
            margin-bottom: 2vh;
            opacity: 0.8;
        }

        .choices {
            display: grid;
            gap: 1vh;
            flex: 1;
            min-height: 0;
        }

        .choice-btn {
            background: rgba(249, 225, 182, 0.8);
            border: 2px solid rgba(132, 59, 109, 0.3);
            padding: 2vh;
            border-radius: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1.1em;
            font-family: inherit;
            color: #5a2e4d;
            text-align: left;
            box-shadow: 0 5px 15px rgba(90, 46, 77, 0.1);
        }

        .choice-btn:hover {
            background: rgba(168, 67, 111, 0.2);
            border-color: #a8436f;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(90, 46, 77, 0.2);
        }

        .choice-btn.correct {
            background: linear-gradient(45deg, #90EE90, #98FB98);
            border-color: #3A8822;
            color: #2F4F2F;
        }

        .choice-btn.incorrect {
            background: linear-gradient(45deg, #FFB6C1, #FFC0CB);
            border-color: #a32168;
            color: #8B0000;
        }

        .choice-btn:disabled {
            cursor: not-allowed;
            opacity: 0.7;
        }

        .next-btn {
            background: linear-gradient(45deg, #a8436f, #d45478);
            color: white;
            border: none;
            padding: 15px 40px;
            font-size: 1.1em;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
            box-shadow: 0 5px 15px rgba(168, 67, 111, 0.4);
            font-family: inherit;
            font-weight: 700;
        }

        .next-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(168, 67, 111, 0.6);
        }

        .result-screen {
            display: none;
            text-align: center;
            flex: 1;
            flex-direction: column;
            justify-content: center;
        }

        h2 {
            font-size: clamp(1.2em, 3vh, 1.2em);
        }

        .score-display {
            font-size: clamp(1.3em, 5vh, 3em);
            color: #a8436f;
            margin: 0;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .ikai-card {
            background: linear-gradient(135deg, rgba(132, 59, 109, 0.1), rgba(168, 67, 111, 0.1));
            padding: 40px;
            border-radius: 20px;
            margin: 30px 0;
            border: 3px solid rgba(132, 59, 109, 0.3);
            box-shadow: 0 15px 30px rgba(90, 46, 77, 0.2);
        }

        .ikai-title {
            font-size: clamp(1.5em, 3.5vh, 2.5em);
            margin-bottom: 0;
        }

        .ikai-yomi {
            font-size: clamp(1.1em, 2vh, 1.3em);
            color: #843b6d;
            margin-bottom: 2vh;
        }

        .ikai-description {
            font-size: 1.2em;
            line-height: 1.8;
            color: #5a2e4d;
            background: rgba(249, 225, 182, 0.5);
            padding: 2vh;
            border-radius: 15px;
        }

        .restart-btn {
            background: linear-gradient(45deg, #843b6d, #a8436f);
            color: white;
            border: none;
            padding: 18px 45px;
            font-size: 1.2em;
            border-radius: 30px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 2vh;
            box-shadow: 0 8px 20px rgba(132, 59, 109, 0.4);
            font-family: inherit;
            font-weight: 700;
            letter-spacing: 0.05em;
        }

        .restart-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(132, 59, 109, 0.6);
            background: linear-gradient(45deg, #a8436f, #d45478);
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .modal-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            background: rgba(249, 225, 182, 0.98);
            padding: 15px;
            border-radius: 20px;
            width: 90%;
            max-width: 600px;
            transform: translateY(-20px);
            transition: transform 0.3s ease;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            text-align: center;
            border: 5px solid transparent;
            /* 正解・不正解で色を変えるための枠線 */
        }

        .modal-overlay.show .modal-content {
            transform: translateY(0);
        }

        /* 正解・不正解時のモーダルスタイル */
        .modal-content.correct {
            border-color: #3A8822;
            /* 緑色の枠線 */
        }

        .modal-content.incorrect {
            border-color: #a32168;
            /* 赤色の枠線 */
        }

        .modal-result {
            font-size: 1.8em;
            font-weight: 700;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid rgba(0, 0, 0, 0.1);
        }

        .modal-result.correct {
            color: #3A8822;
        }

        .modal-result.incorrect {
            color: #a32168;
        }


        .explanation {
            background: rgba(249, 225, 182, 0.6);
            padding: 25px;
            border-radius: 15px;
            margin-top: 20px;
            border-left: 5px solid #a8436f;
        }

        .sakusha {
            font-weight: 700;
            color: #843b6d;
            margin-bottom: 10px;
        }

        .correct-answer {
            font-size: 1.2em;
            color: #5a2e4d;
            margin-bottom: 15px;
            font-weight: 700;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .choice-btn .choice-yomi {
            font-size: 0.8em;
            color: #843b6d;
            opacity: 0.8;
        }

        .choice-btn.correct .choice-yomi,
        .choice-btn.incorrect .choice-yomi {
            opacity: 1;
        }

        .loading {
            text-align: center;
            padding: 40px;
            color: #a8436f;
            font-size: 1.2em;
        }

        .error {
            background: #ffebee;
            color: #c62828;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            border-left: 5px solid #c62828;
        }

        #wakaKaisetsu {
            margin-bottom: 8px;
        }

        /* PC向けスタイル */
        @media (min-width: 901px) {
            .container {
                max-width: 800px;
                height: 90vh;
            }

            body {
                padding: 20px;
            }

            .choices {
                grid-template-columns: repeat(2, 1fr);
                gap: 1.5vh;
            }

            .progress-bar {
                margin: 0 auto 3vh;
            }

            .question-card {
                padding: 3vh 30px;
            }

            .kami-no-ku {
                font-size: clamp(1.5em, 2.5vh, 1.8em);
                margin-bottom: 1vh;
            }

            .kami-no-ku-yomi {
                font-size: 1em;
                margin-bottom: 3vh;
            }

            .choice-btn {
                padding: 2vh;
                font-size: clamp(1.2em, 1.8vh, 1.1em);
            }

            .choice-btn .choice-yomi {
                font-size: clamp(0.9em, 1.5vh, 1em);
            }

            .next-btn {
                margin-top: 3vh;
            }

            .description {
                margin: 4vh auto;
                padding: 3vh;
                font-size: clamp(1.1em, 1.8vh, 1.2em);
            }

            .start-btn {
                padding: 2.5vh 50px;
            }

            .result-screen {
                padding: 0, 3vh;
            }

            .ikai-card {
                margin-top: 1vh;
                margin-bottom: 1vh;
                padding: 3vh;
            }

            .ikai-description {
                font-size: 1.2em;
            }

            .restart-btn {
                margin-top: 3vh;
            }
        }

        /* タブレット向けスタイル */
        @media (max-width: 900px) and (min-width: 601px) {
            .container {
                max-width: 80%;
                height: 90vh;
            }

            body {
                padding: 10px;
            }
        }

        /* スマートフォン向けスタイル */
        @media (max-width: 600px) {
            body {
                padding: 0;
            }

            .container {
                max-width: 100%;
                width: 100%;
                height: 100vh;
                margin: 0;
                border-radius: 0;
            }

            .header {
                flex-shrink: 0;
            }

            .progress-bar {
                margin-bottom: 1.5vh;
            }

            .choices {
                gap: 1vh;
            }

            .question-number {
                font-size: 1em;
                margin-bottom: 10px;
            }

            .question-card {
                padding: 15px;
                margin: 0;
            }

            .kami-no-ku {
                font-size: 1.1em;
                margin-bottom: 0;
            }

            .kami-no-ku-yomi {
                margin-bottom: 20px;
            }

            .description {
                font-size: 1em;
                padding: 20px;
            }

            .choice-btn {
                font-size: 1.2em;
                padding: 10px;
            }

            .choice-btn .choice-yomi {
                margin: 0;
            }

            @media (max-width: 600px) {

                .score-display {
                    font-size: 2.5em;
                    margin-top: 0;
                    margin-bottom: 1vh;
                }

                .ikai-card {
                    padding: 1.5vh;
                    margin-block: 1.5vh;
                }

                .restart-btn {
                    margin-top: 2vh;
                }

                .result-screen {
                    justify-content: space-evenly;
                    padding: 1.5vh 0;
                }
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>いろは百人一首</h1>
            <div class="subtitle">〜和歌の心を競わん〜</div>
        </div>

        <div class="main-content">
            <div class="start-screen" id="startScreen">
                <div class="description">
                    上の句を読んだ後、正しい下の句を選んでください。<br>
                    五問の問答を通じて、あなたの和歌の知識を試します。<br>
                    成績に応じて、鎌倉時代の位階が与えられます。
                </div>
                <button class="start-btn" onclick="startQuiz()">遊戯開始</button>
            </div>

            <div class="quiz-screen" id="quizScreen">
                <div class="progress-bar">
                    <div class="progress-fill" id="progressFill"></div>
                </div>

                <div class="question-card" id="questionCard">
                    <div class="question-number" id="questionNumber">第一問</div>
                    <div class="kami-no-ku" id="kamiNoKu"></div>
                    <div class="kami-no-ku-yomi" id="kamiNoKuYomi"></div>
                    <div class="choices" id="choices"></div>
                </div>
            </div>

            <div class="result-screen" id="resultScreen">
                <h2>結果発表</h2>
                <div class="score-display" id="scoreDisplay">0点</div>

                <div class="ikai-card" id="ikaiCard">
                    <div class="ikai-title" id="ikaiTitle">位階判定中...</div>
                    <div class="ikai-yomi" id="ikaiYomi"></div>
                    <div class="ikai-description" id="ikaiDescription"></div>
                </div>

                <button class="restart-btn" onclick="restartQuiz()">再び挑戦する</button>
            </div>

            <div class="loading" id="loading" style="display: none;">
                問題を読み込み中...
            </div>

            <div class="error" id="error" style="display: none;"></div>
        </div>
    </div>

    <div class="modal-overlay" id="modalOverlay">
        <div class="modal-content" id="modalContent">
            <div class="modal-result" id="modalResult"></div>
            <div class="explanation" id="explanation">
                <div class="sakusha" id="sakusha"></div>
                <div class="correct-answer" id="correctAnswer"></div>
                <div class="waka-details">
                    <div id="sakushaYomi"></div>
                    <p id="wakaKaisetsu"></p>
                    <p id="wakaHaikei"></p>
                </div>
            </div>
            <button class="next-btn" id="nextBtn"></button>
        </div>
    </div>

    <script>
        let questions = [];
        let currentQuestion = 0;
        let score = 0;
        let selectedAnswer = null;

        // APIのベースURL（実際の環境に合わせて変更してください）
        const API_BASE = 'api.php';

        async function fetchQuestions() {
            try {
                showLoading(true);
                const response = await fetch(`${API_BASE}?action=get_questions`);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data = await response.json();
                if (data.error) {
                    throw new Error(data.error);
                }
                questions = data;
                showLoading(false);
                return true;
            } catch (error) {
                showError('問題の読み込みに失敗しました: ' + error.message);
                showLoading(false);
                return false;
            }
        }

        async function fetchIkai(score) {
            try {
                const response = await fetch(`${API_BASE}?action=get_ikai`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ score: score })
                });
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data = await response.json();
                if (data.error) {
                    throw new Error(data.error);
                }
                return data;
            } catch (error) {
                showError('位階の取得に失敗しました: ' + error.message);
                return null;
            }
        }

        function showLoading(show) {
            document.getElementById('loading').style.display = show ? 'block' : 'none';
        }

        function showError(message) {
            const errorDiv = document.getElementById('error');
            errorDiv.textContent = message;
            errorDiv.style.display = 'block';
            setTimeout(() => {
                errorDiv.style.display = 'none';
            }, 5000);
        }

        async function startQuiz() {
            const success = await fetchQuestions();
            if (!success) return;

            // 初期化
            currentQuestion = 0;
            score = 0;
            selectedAnswer = null;

            // 画面切り替え
            document.getElementById('startScreen').style.display = 'none';
            document.getElementById('quizScreen').style.display = 'flex';
            document.getElementById('resultScreen').style.display = 'none';

            showQuestion();
        }

        function showQuestion() {
            if (currentQuestion >= questions.length) {
                showResult();
                return;
            }

            const question = questions[currentQuestion];
            const questionNumbers = ['第一問', '第二問', '第三問', '第四問', '第五問'];

            // プログレスバー更新
            const progress = ((currentQuestion) / questions.length) * 100;
            document.getElementById('progressFill').style.width = progress + '%';

            // 問題表示
            document.getElementById('questionNumber').textContent = questionNumbers[currentQuestion];
            document.getElementById('kamiNoKu').textContent = question.kami_no_ku;
            document.getElementById('kamiNoKuYomi').textContent = question.kami_no_ku_yomi;

            // 選択肢表示
            const choicesDiv = document.getElementById('choices');
            choicesDiv.innerHTML = '';

            question.choices.forEach((choice, index) => {
                const button = document.createElement('button');
                button.className = 'choice-btn';

                // 下の句のテキストと読みをdivでラップして追加
                const textDiv = document.createElement('div');
                textDiv.textContent = choice.shimo_no_ku;

                const yomiDiv = document.createElement('div');
                yomiDiv.className = 'choice-yomi';
                yomiDiv.textContent = `（${choice.shimo_no_ku_yomi}）`;

                button.appendChild(textDiv);
                button.appendChild(yomiDiv);

                button.onclick = () => selectAnswer(choice.shimo_no_ku, button);
                choicesDiv.appendChild(button);
            });

            // UI初期化
            selectedAnswer = null;
        }

        function selectAnswer(choiceText, button) {
            if (selectedAnswer !== null) return; // 既に選択済み

            selectedAnswer = choiceText;
            const question = questions[currentQuestion];
            const isCorrect = choiceText === question.correct_answer;

            const modalContent = document.getElementById('modalContent');
            const modalResult = document.getElementById('modalResult');

            if (isCorrect) {
                score += 20; // 1問20点
                button.classList.add('correct');
                modalContent.classList.add('correct');
                modalResult.textContent = '正解です！';
                modalResult.classList.add('correct');
            } else {
                button.classList.add('incorrect');
                modalContent.classList.add('incorrect');
                modalResult.textContent = '残念、不正解です...';
                modalResult.classList.add('incorrect');
                // 正解を表示
                const buttons = document.querySelectorAll('.choice-btn');
                buttons.forEach(btn => {
                    // 正解のテキスト（shimo_no_ku）を検索
                    const shimo_no_ku_text = btn.querySelector('div:first-child').textContent;
                    if (shimo_no_ku_text === question.correct_answer) {
                        btn.classList.add('correct');
                    }
                });
            }

            // 全ボタンを無効化
            document.querySelectorAll('.choice-btn').forEach(btn => {
                btn.disabled = true;
            });

            // 解説表示
            document.getElementById('sakusha').textContent = `作者: ${question.sakusha} (${question.sakusha_yomi})`;
            document.getElementById('correctAnswer').textContent = `${question.kami_no_ku} ${question.shimo_no_ku}`;

            // 新しく追加された詳細情報を表示
            document.getElementById('wakaKaisetsu').textContent = `解説: ${question.kaisetsu}`;
            document.getElementById('wakaHaikei').textContent = `背景: ${question.haikei}`;

            // 次へボタンのテキストとアクションを設定
            const nextBtn = document.getElementById('nextBtn');
            if (currentQuestion === questions.length - 1) {
                nextBtn.textContent = '結果発表へ';
                nextBtn.onclick = showResult;
            } else {
                nextBtn.textContent = '次の問題へ';
                nextBtn.onclick = nextQuestion;
            }

            // モーダルを表示
            document.getElementById('modalOverlay').classList.add('show');
            document.body.classList.add('modal-open');
        }

        function nextQuestion() {
            // モーダルを非表示にする前にクラスを削除
            const modalContent = document.getElementById('modalContent');
            const modalResult = document.getElementById('modalResult');
            modalContent.classList.remove('correct', 'incorrect');
            modalResult.classList.remove('correct', 'incorrect');
            document.getElementById('modalOverlay').classList.remove('show');
            document.body.classList.remove('modal-open');
            currentQuestion++;
            showQuestion();
        }

        async function showResult() {
            // モーダルを非表示
            document.getElementById('modalOverlay').classList.remove('show');
            document.body.classList.remove('modal-open');

            // プログレスバー100%
            document.getElementById('progressFill').style.width = '100%';

            // 画面切り替え
            document.getElementById('quizScreen').style.display = 'none';
            document.getElementById('resultScreen').style.display = 'flex';

            // スコア表示
            document.getElementById('scoreDisplay').textContent = score + '点';

            // 位階取得・表示
            const ikai = await fetchIkai(score);
            if (ikai) {
                document.getElementById('ikaiTitle').textContent = ikai.ikai_name;
                document.getElementById('ikaiYomi').textContent = `(${ikai.ikai_yomi})`;
                document.getElementById('ikaiDescription').textContent = ikai.kaisetsu;
            } else {
                document.getElementById('ikaiTitle').textContent = '位階取得エラー';
                document.getElementById('ikaiYomi').textContent = '';
                document.getElementById('ikaiDescription').textContent = '位階の判定に失敗しました。';
            }
        }

        function restartQuiz() {
            document.getElementById('resultScreen').style.display = 'none';
            document.getElementById('startScreen').style.display = 'block';
        }
    </script>
</body>

</html>