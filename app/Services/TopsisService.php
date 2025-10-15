<?php 
namespace App\Services; 
use App\Models\Alternative; 
use App\Models\Criterion; 
use App\Models\Score; 
use App\Models\Result; 
class TopsisService 
{ 
public function calculateTopsis() 
{ 
$alternatives = Alternative::all(); 
$criteria = Criterion::all(); 
$scores = Score::all(); 
 
        if ($alternatives->isEmpty() || $criteria->isEmpty()) { 
            return []; // Handle kasus data kosong 
        } 
 
 
        // Langkah 1: Matriks Keputusan 
        $decisionMatrix = $this->buildDecisionMatrix($alternatives, $criteria, $scores); 
 
        // Langkah 2: Normalisasi 
        $normalizedMatrix = $this->normalizeMatrix($decisionMatrix); 
 
        // Langkah 3: Matriks Terbobot 
        $weightedMatrix = $this->applyWeights($normalizedMatrix, $criteria); 
 
        // Langkah 4: Solusi Ideal Positif dan Negatif 
        $idealPositive = $this->calculateIdealPositive($weightedMatrix, $criteria); 
        $idealNegative = $this->calculateIdealNegative($weightedMatrix, $criteria); 
 
        // Langkah 5: Jarak Euclidean 
        $distances = $this->calculateDistances($weightedMatrix, $idealPositive, $idealNegative); 
 
        // Langkah 6: Nilai Preferensi 
        $preferences = $this->calculatePreferences($distances); 
 
        // Langkah 7: Perankingan 
        $results = $this->rankAlternatives($preferences, $alternatives); 
 
        // Simpan hasil ke database 
        Result::truncate(); 
        foreach ($results as $result) { 
            Result::create([ 
                'alternative_id' => $result['alternative_id'], 
                'preference_score' => $result['preference_score'], 
                'rank' => $result['rank'], 
            ]); 
        } 
 
        return $results; 
    } 
 
    // Implementasi Rumus Langkah 1: Build Decision Matrix 
    private function buildDecisionMatrix($alternatives, $criteria, $scores) 
    { 
        $matrix = []; 
        foreach ($alternatives as $alt) { 
            $row = []; 
            foreach ($criteria as $crit) { 
                $score = $scores->where('alternative_id', $alt->id)->where('criterion_id', $crit->id)->first(); 
                $row[$crit->id] = $score ? $score->value : 0; // x_ij 
            } 
            $matrix[$alt->id] = $row; 
        } 
        return $matrix; 
    } 
 
    // Implementasi Rumus Langkah 2: Normalisasi (r_ij = x_ij / sqrt(sum x_ij^2)) 
 
    private function normalizeMatrix($matrix) 
    { 
        $normalized = []; 
        $sums = []; 
        foreach ($matrix as $row) { 
            foreach ($row as $critId => $value) { 
                $sums[$critId] = ($sums[$critId] ?? 0) + pow($value, 2); 
            } 
        } 
        foreach ($matrix as $altId => $row) { 
            $normalized[$altId] = []; 
            foreach ($row as $critId => $value) { 
                $normalized[$altId][$critId] = $sums[$critId] > 0 ? $value / sqrt($sums[$critId]) : 0; 
            } 
        } 
        return $normalized; 
    } 
 
    // Implementasi Rumus Langkah 3: Pembobotan (v_ij = w_j * r_ij) 
    private function applyWeights($normalizedMatrix, $criteria) 
    { 
        $weighted = []; 
        foreach ($normalizedMatrix as $altId => $row) { 
            $weighted[$altId] = []; 
            foreach ($row as $critId => $value) { 
                $weight = $criteria->find($critId)->weight; // w_j 
                $weighted[$altId][$critId] = $value * $weight; 
            } 
        } 
        return $weighted; 
    } 
 
    // Implementasi Rumus Langkah 4: Solusi Ideal Positif (max/min berdasarkan tipe) 
    private function calculateIdealPositive($weightedMatrix, $criteria) 
    { 
        $idealPositive = []; 
        foreach ($criteria as $crit) { 
            $values = array_column($weightedMatrix, $crit->id); 
            $idealPositive[$crit->id] = $crit->type === 'benefit' ? max($values) : min($values); // v_j+ 
        } 
        return $idealPositive; 
    } 
 
    private function calculateIdealNegative($weightedMatrix, $criteria) 
    { 
        $idealNegative = []; 
        foreach ($criteria as $crit) { 
            $values = array_column($weightedMatrix, $crit->id); 
            $idealNegative[$crit->id] = $crit->type === 'benefit' ? min($values) : max($values); // v_j- 
        } 
        return $idealNegative; 
    } 
 
    // Implementasi Rumus Langkah 5: Jarak Euclidean (S_i^+ dan S_i^-) 
    private function calculateDistances($weightedMatrix, $idealPositive, $idealNegative) 
    { 
 
        $distances = []; 
        foreach ($weightedMatrix as $altId => $row) { 
            $positiveDistance = 0; 
            $negativeDistance = 0; 
            foreach ($row as $critId => $value) { 
                $positiveDistance += pow($value - $idealPositive[$critId], 2); 
                $negativeDistance += pow($value - $idealNegative[$critId], 2); 
            } 
            $distances[$altId] = [ 
                'positive' => sqrt($positiveDistance), // S_i^+ 
                'negative' => sqrt($negativeDistance), // S_i^- 
            ]; 
        } 
        return $distances; 
    } 
 
    // Implementasi Rumus Langkah 6: Nilai Preferensi (C_i = S_i^- / (S_i^+ + S_i^-)) 
    private function calculatePreferences($distances) 
    { 
        $preferences = []; 
        foreach ($distances as $altId => $dist) { 
            $denom = $dist['positive'] + $dist['negative']; 
            $preferences[$altId] = $denom > 0 ? $dist['negative'] / $denom : 0; 
        } 
        return $preferences; 
    } 
 
    // Implementasi Langkah 7: Perankingan (urutkan berdasarkan C_i) 
    private function rankAlternatives($preferences, $alternatives) 
    { 
        arsort($preferences); // Urut descending 
        $results = []; 
        $rank = 1; 
        foreach ($preferences as $altId => $score) { 
            $results[] = [ 
                'alternative_id' => $altId, 
                'name' => $alternatives->find($altId)->name, 
                'preference_score' => round($score, 4), 
                'rank' => $rank++, 
            ]; 
        } 
        return $results;
    }
}