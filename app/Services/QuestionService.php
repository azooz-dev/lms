<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Question;
use App\Models\User;
use App\Repositories\Contracts\QuestionRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class QuestionService
{
    public function __construct(
        private readonly QuestionRepositoryInterface $questionRepository,
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function sendQuestion(int $courseId, int $instructorId, int $userId, array $data): Question
    {
        return $this->questionRepository->create([
            'course_id' => $courseId,
            'instructor_id' => $instructorId,
            'user_id' => $userId,
            'subject' => $data['subject'],
            'question' => $data['question'],
        ]);
    }

    public function getInstructorUnreadQuestions(int $instructorId): Collection
    {
        return $this->questionRepository->getByInstructorIdUnread($instructorId);
    }

    public function getQuestionsByCourseAndUser(int $courseId, int $userId): Collection
    {
        return $this->questionRepository->getByCourseAndUser($courseId, $userId);
    }

    public function findUserById(int $id): ?User
    {
        return $this->userRepository->find($id);
    }

    public function replyToQuestion(int $courseId, int $userId, string $reply): void
    {
        $questions = $this->questionRepository->getByCourseAndUser($courseId, $userId);

        foreach ($questions as $key => $question) {
            // Create reply only for the last question
            if ($key == count($questions) - 1) {
                $this->questionRepository->createReply([
                    'course_id' => $courseId,
                    'user_id' => $userId,
                    'question_id' => $question->id,
                    'reply' => $reply,
                    'instructor_id' => $question->id,
                ]);
            }

            // Mark all questions as read
            $this->questionRepository->markAsRead($question);
        }
    }
}
