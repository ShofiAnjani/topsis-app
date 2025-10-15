<!DOCTYPE html> 
<html> 
<head> 
    <title>Hasil TOPSIS</title> 
    <style> 
        body { font-family: sans-serif; } 
        table { border-collapse: collapse; width: 100%; } 
        th, td { border: 1px solid #333; padding: 8px; text-align: left; } 
        th { background-color: #f2f2f2; } 
    </style> 
</head> 
<body> 
    <h2>Hasil TOPSIS</h2> 
    <table> 
        <thead> 
            <tr> 
                <th>Alternative</th> 
                <th>Preference Score</th> 
                <th>Rank</th> 
            </tr> 
        </thead> 
        <tbody> 
            @foreach($results as $row) 
                <tr> 
                    <td>{{ $row->alternative->name }}</td> 
                    <td>{{ number_format($row->preference_score, 4) }}</td> 
                    <td>{{ $row->rank }}</td> 
                </tr> 
            @endforeach 
        </tbody> 
    </table> 
</body> 
</html>