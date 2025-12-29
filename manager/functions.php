<?php
// functions.php

// Load JSON data
function loadJSON($file) {
    $json = file_get_contents($file);
    return json_decode($json, true);
}

// Save JSON data
function saveJSON($file, $data) {
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
}

// Generate random players
function generatePlayers($count = 60) {
    $players = [];
    $firstNames = ['John', 'Mike', 'Alex', 'Chris', 'David', 'James', 'Robert', 'Kevin', 'Brian', 'Jason'];
    $lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Miller', 'Davis', 'Garcia', 'Rodriguez', 'Wilson'];
    
    for ($i = 1; $i <= $count; $i++) {
        $players[] = [
            'id' => $i,
            'name' => $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)],
            'rating' => rand(60, 90),
            'team_id' => null // null means not drafted
        ];
    }
    
    saveJSON('data/players.json', $players);
    return $players;
}

// Calculate team rating
function calculateTeamRating($team) {
    if (empty($team['players'])) return 0;
    
    $total = 0;
    foreach ($team['players'] as $playerId) {
        $allPlayers = loadJSON('data/players.json');
        foreach ($allPlayers as $player) {
            if ($player['id'] == $playerId) {
                $total += $player['rating'];
                break;
            }
        }
    }
    return $total / max(1, count($team['players']));
}

// Simulate a match
function simulateMatch($team1, $team2) {
    $team1Rating = calculateTeamRating($team1);
    $team2Rating = calculateTeamRating($team2);
    
    // Base score with randomness
    $team1Score = rand(70, 120) + ($team1Rating - 75);
    $team2Score = rand(70, 120) + ($team2Rating - 75);
    
    // Ensure minimum score
    $team1Score = max(60, $team1Score);
    $team2Score = max(60, $team2Score);
    
    // Determine winner
    $winner = $team1Score > $team2Score ? $team1['id'] : $team2['id'];
    
    return [
        'team1' => $team1['name'],
        'team2' => $team2['name'],
        'score1' => $team1Score,
        'score2' => $team2Score,
        'winner' => $winner
    ];
}

// Run a draft round
function draftPlayer() {
    $teams = loadJSON('data/clubs.json');
    $players = loadJSON('data/players.json');
    
    // Get available players (not drafted)
    $availablePlayers = array_filter($players, function($player) {
        return $player['team_id'] === null;
    });
    
    if (empty($availablePlayers)) {
        return false; // Draft complete
    }
    
    // Select random team to draft
    $draftingTeamId = rand(1, 3);
    $teamIndex = $draftingTeamId - 1;
    
    // Select random available player
    $randomPlayerKey = array_rand($availablePlayers);
    $selectedPlayer = $availablePlayers[$randomPlayerKey];
    $selectedPlayerId = $selectedPlayer['id'];
    
    // Update player team
    foreach ($players as &$player) {
        if ($player['id'] == $selectedPlayerId) {
            $player['team_id'] = $draftingTeamId;
        }
    }
    
    // Add player to team
    $teams[$teamIndex]['players'][] = $selectedPlayerId;
    
    // Save changes
    saveJSON('data/players.json', $players);
    saveJSON('data/clubs.json', $teams);
    
    return [
        'team' => $teams[$teamIndex]['name'],
        'player' => $selectedPlayer['name'],
        'rating' => $selectedPlayer['rating']
    ];
}

// Initialize game
function initializeGame() {
    generatePlayers();
    // Reset teams
    $teams = [
        ["id" => 1, "name" => "Team Alpha", "players" => [], "rating" => 0, "wins" => 0, "losses" => 0],
        ["id" => 2, "name" => "Team Beta", "players" => [], "rating" => 0, "wins" => 0, "losses" => 0],
        ["id" => 3, "name" => "Team Gamma", "players" => [], "rating" => 0, "wins" => 0, "losses" => 0]
    ];
    saveJSON('data/clubs.json', $teams);
}
?>