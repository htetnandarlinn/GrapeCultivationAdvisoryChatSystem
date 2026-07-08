<?php

namespace App\Presentation\Controllers\Farmer;

use App\Infrastructure\Persistence\Repositories\QuestionRepository;
use App\Presentation\Views\FarmerView;

class FarmerDashboardController
{
    private QuestionRepository $questionRepository;

    public function __construct()
    {
        $this->questionRepository = new QuestionRepository();
    }

    /**
     * Farmer Dashboard
     */
    public function index(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user']) || ($_SESSION['user_role'] ?? '') !== 'farmer') {
            redirect('/access-denied');
            return;
        }

        $farmerId = (int) $_SESSION['user']['id'];

        $questions = $this->questionRepository->findByFarmer($farmerId);

        $totalQuestions = count($questions);
        $pendingQuestions = 0;
        $answeredQuestions = 0;
        $imageCount = 0;

        foreach ($questions as $question) {

            if (($question['status_name'] ?? '') === 'Pending') {
                $pendingQuestions++;
            }

            if (($question['status_name'] ?? '') === 'Answered') {
                $answeredQuestions++;
            }

            if (!empty($question['image'])) {
                $imageCount++;
            }
        }

        FarmerView::render('farmer/farmer-dashboard', [
            'activePage'        => 'dashboard',
            'questions'         => $questions,
            'totalQuestions'    => $totalQuestions,
            'pendingQuestions'  => $pendingQuestions,
            'answeredQuestions' => $answeredQuestions,
            'imageCount'        => $imageCount
        ]);
    }

    /**
     * Show Ask Question Page
     */
    public function askQuestion(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user']) || ($_SESSION['user_role'] ?? '') !== 'farmer') {
            redirect('/access-denied');
            return;
        }

        $categories = $this->questionRepository->findCategories();

        FarmerView::render('farmer/ask-question', [
            'activePage' => 'ask',
            'categories' => $categories
        ]);
    }

    /**
     * Question Submitted Page
     */
    public function submitQuestion(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user']) || ($_SESSION['user_role'] ?? '') !== 'farmer') {
            redirect('/access-denied');
            return;
        }

        FarmerView::render('farmer/question-submitted', [
            'activePage' => 'submit'
        ]);
    }

    /**
     * Total Questions
     */
    public function totalQuestions(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user']) || ($_SESSION['user_role'] ?? '') !== 'farmer') {
            redirect('/access-denied');
            return;
        }

        $farmerId = (int) $_SESSION['user']['id'];

        $questions = $this->questionRepository->findByFarmer($farmerId);

        FarmerView::render('farmer/farmer-dashboard', [
            'activePage'        => 'dashboard',
            'questions'         => $questions,
            'totalQuestions'    => count($questions),
            'pendingQuestions'  => count(array_filter($questions, fn($q) => ($q['status_name'] ?? '') === 'Pending')),
            'answeredQuestions' => count(array_filter($questions, fn($q) => ($q['status_name'] ?? '') === 'Answered')),
            'imageCount'        => count(array_filter($questions, fn($q) => !empty($q['image'])))
        ]);
    }
}