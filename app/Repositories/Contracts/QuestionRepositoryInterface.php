<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Question;
use App\Models\ReplyQuestion;
use Illuminate\Database\Eloquent\Collection;

interface QuestionRepositoryInterface
{
    public function create(array $data): Question;

    public function getByInstructorIdUnread(int $instructorId): Collection;

    public function getByCourseAndUser(int $courseId, int $userId): Collection;

    public function markAsRead(Question $question): Question;

    public function createReply(array $data): ReplyQuestion;
}
