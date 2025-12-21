<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Question;
use App\Models\ReplyQuestion;
use App\Repositories\Contracts\QuestionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class QuestionRepository implements QuestionRepositoryInterface
{
    public function create(array $data): Question
    {
        return Question::create($data);
    }

    public function getByInstructorIdUnread(int $instructorId): Collection
    {
        return Question::where('instructor_id', $instructorId)
            ->where('read_status', false)
            ->get();
    }

    public function getByCourseAndUser(int $courseId, int $userId): Collection
    {
        return Question::where('course_id', $courseId)
            ->where('user_id', $userId)
            ->get();
    }

    public function markAsRead(Question $question): Question
    {
        $question->update(['read_status' => true]);

        return $question;
    }

    public function createReply(array $data): ReplyQuestion
    {
        return ReplyQuestion::create($data);
    }
}
