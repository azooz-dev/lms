<?php

declare(strict_types=1);

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Services\QuestionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class QuestionController extends Controller
{
    public function __construct(
        private readonly QuestionService $questionService
    ) {}

    public function send_question(string $course, string $instructor, string $user, Request $request): RedirectResponse
    {
        $this->questionService->sendQuestion(
            (int) $course,
            (int) $instructor,
            (int) $user,
            $request->only(['subject', 'question'])
        );

        $notification = [
            'message' => 'Question send successfully.',
            'alert-type' => 'success',
        ];

        return redirect()->back()->with($notification);
    }

    public function all_instructor_question(string $id): View
    {
        $questions = $this->questionService->getInstructorUnreadQuestions((int) $id);

        return view('instructor.question.all_questions', compact('questions'));
    }

    public function details_instructor_question(string $course, string $user): View
    {
        $questions = $this->questionService->getQuestionsByCourseAndUser((int) $course, (int) $user);
        $user = $this->questionService->findUserById((int) $user);

        return view('instructor.question.details_question', compact('questions', 'user'));
    }

    public function reply_instructor_question(Request $request, string $user, string $course): RedirectResponse
    {
        $this->questionService->replyToQuestion((int) $course, (int) $user, $request->reply);

        $notification = [
            'message' => 'Message send successfully.',
            'alert-type' => 'success',
        ];

        return redirect()->route('instructor.all_questions', Auth::user()->id)->with($notification);
    }
}
