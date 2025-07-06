<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if ($input) {
        $_SESSION['gmrt_test_results'] = [
            'gmrt_vocab_score' => $input['gmrt_vocab_score'],
            'gmrt_speed_score' => $input['gmrt_speed_score'],
            'gmrt_comprehension_score' => $input['gmrt_comprehension_score'],
            'gmrt_vocab_answers' => $input['gmrt_vocab_answers'],
            'gmrt_speed_answers' => $input['gmrt_speed_answers'],
            'gmrt_comprehension_answers' => $input['gmrt_comprehension_answers'],
            'gmrt_vocab_total' => $input['gmrt_vocab_total'],
            'gmrt_speed_total' => $input['gmrt_speed_total'],
            'gmrt_comprehension_total' => $input['gmrt_comprehension_total'],
            'overall_score' => $input['gmrt_vocab_score'] + $input['gmrt_speed_score'] + $input['gmrt_comprehension_score'],
            'test_completed_at' => date('Y-m-d H:i:s')
        ];
        
        error_log("DEBUG: GMRT test results saved to session");
        error_log("DEBUG: Session data: " . json_encode($_SESSION['gmrt_test_results']));
        
        echo json_encode(['success' => true]);
    } else {
        error_log("ERROR: Invalid JSON data received");
        echo json_encode(['success' => false, 'error' => 'Invalid data']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>