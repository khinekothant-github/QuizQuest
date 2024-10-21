<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{

    public function getQuizzes()
    {
        $client = new Client(['verify' => false]);

        try {
            $response = $client->get('https://opentdb.com/api.php?amount=10&type=multiple', [
                'timeout' => 10, // Set a timeout of 10 seconds
            ]);

            $data = json_decode($response->getBody(), true);

            if (isset($data['results'])) {
                // Decode HTML entities in each question
                foreach ($data['results'] as &$question) {
                    $question['question'] = html_entity_decode($question['question']);
                    $question['correct_answer'] = html_entity_decode($question['correct_answer']);
                    $question['incorrect_answers'] = array_map('html_entity_decode', $question['incorrect_answers']);
                }

                // Return questions as JSON
                return response()->json(['questions' => $data['results']]);
            } else {
                return response()->json(['questions' => []]);
            }
        } catch (RequestException $e) {
            return response()->json(['error' => 'Failed to fetch quizzes'], 500);
        }
    }

    public function submit(Request $request)
    {
        $questions = $request->input('questions'); // Get the questions from the API request
        $userAnswers = $request->input('answers'); // Get user answers from the API request
        $userId = Auth::id();
        $results = [];
        $score = 0;

        // Process the user's answers and calculate score
        foreach ($questions as $index => $question) {
            $correctAnswer = $question['correct_answer'];
            $userAnswer = $userAnswers[$index];

            $answers = array_merge($question['incorrect_answers'], [$correctAnswer]);
            shuffle($answers);

            $results[] = [
                'question' => $question['question'],
                'correct_answer' => $correctAnswer,
                'user_answer' => $userAnswer,
                'is_correct' => $userAnswer === $correctAnswer,
                'answers' => $answers
            ];

            if ($userAnswer === $correctAnswer) {
                $score++;
            }
        }

        // Store or update the user's score
        $existingScore = \DB::table('scores')->where('user_id', $userId)->first();

        if ($existingScore) {
            \DB::table('scores')
                ->where('user_id', $userId)
                ->update([
                    'score' => $existingScore->score + $score,
                    'updated_at' => now(),
                ]);
        } else {
            \DB::table('scores')->insert([
                'user_id' => $userId,
                'score' => $score,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Return the quiz results as JSON
        return response()->json([
            'questions' => $questions,
            'results' => $results,
            'score' => $score
        ]);
    }
}
