<?php
session_start();

// Initialize game if not already started
if (!isset($_SESSION['hangman_word']) || isset($_POST['new_game'])) {
    // Get random word from API
    $url = "https://random-word-api.herokuapp.com/word?length=6";
    $response = file_get_contents($url);
    $words = json_decode($response, true);
    
    $_SESSION['hangman_word'] = strtolower($words[0]);
    $_SESSION['hangman_guessed'] = array();
    $_SESSION['hangman_wrong'] = 0;
    $_SESSION['hangman_correct'] = 0;
    $_SESSION['hangman_display'] = str_repeat('_', strlen($_SESSION['hangman_word']));
    $_SESSION['hangman_lives'] = 5;
}

$word = $_SESSION['hangman_word'];
$lives = $_SESSION['hangman_lives'];

// Handle guess
if (isset($_POST['guess']) && !empty($_POST['guess'])) {
    $guess = strtolower($_POST['guess']);
    
    if (strlen($guess) == 1 && ctype_alpha($guess)) {
        if (!in_array($guess, $_SESSION['hangman_guessed'])) {
            $_SESSION['hangman_guessed'][] = $guess;
            
            if (strpos($word, $guess) !== false) {
                // Update display with correct guess
                $new_display = '';
                $new_correct = 0;
                
                for ($i = 0; $i < strlen($word); $i++) {
                    if ($word[$i] == $guess || strpos($_SESSION['hangman_display'], $word[$i]) !== false) {
                        $new_display .= $word[$i];
                        $new_correct++;
                    } else {
                        $new_display .= '_';
                    }
                }
                
                $_SESSION['hangman_display'] = $new_display;
                $_SESSION['hangman_correct'] = $new_correct;
            } else {
                $_SESSION['hangman_wrong']++;
                $_SESSION['hangman_lives']--;
            }
        }
    }
}

// Check win/lose
$game_over = false;
$victory = false;
$message = '';

if ($_SESSION['hangman_display'] == $_SESSION['hangman_word']) {
    $game_over = true;
    $victory = true;
    $message = "<div class='victory-box'><p class='victory-text'>🎉 GRATTIS! DU VANN! 🎉</p><p>Ordet var: <span class='word-reveal'>" . $_SESSION['hangman_word'] . "</span></p></div>";
} elseif ($_SESSION['hangman_lives'] <= 0) {
    $game_over = true;
    $message = "<div class='gameover-box'><p class='gameover-text'>💀 GAME OVER! 💀</p><p>Ordet var: <span class='word-reveal'>" . $_SESSION['hangman_word'] . "</span></p></div>";
}

// Draw hangman with 5 lives
function ritaHangman($lives) {
    $steg = [
        "  _______\n  |     |\n  😊    |\n  /|\\   |\n  / \\   |\n        |\n=========", // 5 liv - hel
        "  _______\n  |     |\n  😟    |\n  /|\\   |\n  /     |\n        |\n=========", // 4 liv
        "  _______\n  |     |\n  😨    |\n  /|\\   |\n        |\n        |\n=========", // 3 liv
        "  _______\n  |     |\n  😰    |\n  /|    |\n        |\n        |\n=========", // 2 liv
        "  _______\n  |     |\n  😵    |\n  |     |\n        |\n        |\n=========", // 1 liv
        "  _______\n  |     |\n  💀    |\n        |\n        |\n        |\n========="  // 0 liv
    ];
    
    $index = 5 - $lives;
    return "<pre>" . $steg[$index] . "</pre>";
}

// Calculate score
$score = 0;
if ($game_over && $victory) {
    $score = ($_SESSION['hangman_lives'] * 100) + (strlen($word) * 50);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Hänga Gubbe - 5 Liv</title>
    <link rel="stylesheet" href="style.css">
    <link href='https://fonts.googleapis.com/css?family=Cherry Bomb One' rel='stylesheet'>
    <link rel="shortcut icon" type="image/x-icon" href="logo.png" />
</head>
<body>
    <div class="hangman-box">
        <h1 class="hangman-title">Hänga Gubbe - 5 Liv</h1>
        
        <!-- Victory Banner (only shows when won) -->
        <?php if ($victory): ?>
        <div class="victory-banner">
            <div class="confetti">🎉</div>
            <div class="confetti">🎊</div>
            <div class="confetti">🥳</div>
            <h2>SEGER! DU ÄR EN HJÄLTE!</h2>
            <div class="confetti">🏆</div>
            <div class="confetti">🌟</div>
            <div class="confetti">⭐</div>
        </div>
        <?php endif; ?>
        
        <div class="game-area">
            <div class="hangman-section">
                <div class="lives-display">
                    <h3>Liv kvar:</h3>
                    <div class="hearts">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <span class="heart <?php echo $i <= $lives ? 'active' : 'lost'; ?>">❤️</span>
                        <?php endfor; ?>
                    </div>
                    <p class="lives-count"><?php echo $lives; ?> / 5</p>
                </div>
                
                <div class="hangman-drawing">
                    <?php echo ritaHangman($lives); ?>
                </div>
            </div>
            
            <div class="game-info">
                <div class="word-display">
                    <p class="word-text"><?php echo str_replace('', ' ', $_SESSION['hangman_display']); ?></p>
                    <p class="word-length">Ord längd: <?php echo strlen($word); ?> bokstäver</p>
                </div>
                
                <div class="stats">
                    <div class="stat-row">
                        <p class="stat-label">Fel gissningar:</p>
                        <p class="stat-value"><?php echo $_SESSION['hangman_wrong']; ?></p>
                    </div>
                    
                    <div class="stat-row">
                        <p class="stat-label">Rätt bokstäver:</p>
                        <p class="stat-value"><?php echo $_SESSION['hangman_correct']; ?>/<?php echo strlen($word); ?></p>
                    </div>
                    
                    <div class="stat-row">
                        <p class="stat-label">Gissade bokstäver:</p>
                        <p class="stat-value letters"><?php 
                            $guessed = $_SESSION['hangman_guessed'];
                            if (empty($guessed)) {
                                echo "Inga än";
                            } else {
                                echo implode(' ', $guessed);
                            }
                        ?></p>
                    </div>
                    
                    <?php if ($victory): ?>
                    <div class="score-display">
                        <div class="stat-row">
                            <p class="stat-label">Din poäng:</p>
                            <p class="stat-value score"><?php echo $score; ?> poäng</p>
                        </div>
                        <div class="score-breakdown">
                            <p>+<?php echo $lives * 100; ?> poäng för <?php echo $lives; ?> liv kvar</p>
                            <p>+<?php echo strlen($word) * 50; ?> poäng för ordlängd</p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <?php if (!$game_over): ?>
                <form method="POST" class="guess-form">
                    <div class="form-row">
                        <input type="text" name="guess" maxlength="1" placeholder="Skriv en bokstav" required 
                               pattern="[A-Za-zÅÄÖåäö]" title="Endast en bokstav (A-Ö)"
                               autocomplete="off" autofocus>
                    </div>
                    <div class="form-row">
                        <button type="submit" class="guess-button">🎯 Gissa</button>
                    </div>
                </form>
                <?php endif; ?>
                
                <?php 
                if ($message) echo $message;
                ?>
                
                <div class="button-row">
                    <form method="POST" class="new-game-form">
                        <button type="submit" name="new_game" value="1" class="new-game-button">🔄 Nytt Spel</button>
                    </form>
                    
                    <?php if (!$game_over): ?>
                    <form method="POST" class="hint-form">
                        <button type="submit" name="hint" value="1" class="hint-button">💡 Tips</button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="instructions">
            <h3>📖 Spelregler:</h3>
            <p>✅ Gissa bokstäver för att lösa det hemliga ordet</p>
            <p>💖 Du har 5 liv (hjärtan)</p>
            <p>❌ Varje fel gissning kostar 1 liv</p>
            <p>🎯 Fler liv kvar = högre poäng!</p>
        </div>
    </div>
    
    <script>
    // Simple celebration effect for victory
    <?php if ($victory): ?>
    setTimeout(() => {
        const banner = document.querySelector('.victory-banner');
        if (banner) {
            banner.style.animation = 'celebrate 2s ease-in-out';
        }
    }, 500);
    <?php endif; ?>
    </script>
</body>
</html>