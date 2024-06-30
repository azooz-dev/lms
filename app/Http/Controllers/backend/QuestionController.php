<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\ReplyQuestion;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{

    public function send_question(string $course, string $instructor, string $user, Request $request) {
        Question::create([
            'course_id' => $course,
            'instructor_id' => $instructor,
            'user_id' => $user,
            'subject' => $request->subject,
            'question' => $request->question,
        ]);

        $notification = [
            'message' => 'Question send successfully.',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification); 
    }


    public function all_instructor_question(string $id) {
        $questions = Question::where('instructor_id', $id)->where('read_status', false)->where('read_status', false)->get();

        return view('instructor.question.all_questions', compact('questions'));
    }

    public function details_instructor_question(string $course, string $user) {
        $questions = Question::where('course_id', $course)->where('user_id', $user)->get();

        $user = User::find($user);

        return view('instructor.question.details_question', compact('questions', 'user'));
    }

    /**
     * Send a reply to an instructor for a question
     *
     * @param Request $request The request object
     * @param string $user The id of the user
     * @param string $course The id of the course
     *
     * @return RedirectResponse
     */
    public function reply_instructor_question(Request $request,  string $user, string $course) {
        // Get all the questions for the given course and user
        $questions = Question::where('course_id', $course)
            ->where('user_id', $user)
            ->get();

        // Loop through the questions and update the read status to true and add a reply
        foreach($questions as $key => $question) {
            if($key == count($questions) - 1) {
                // Create a new ReplyQuestion model
                ReplyQuestion::create([
                    'course_id' => $course,
                    'user_id' => $user,
                    'question_id' => $question->id,
                    'reply' => $request->reply,
                    'instructor_id' => $question->id
                ]);
            }

            // Update the read status of the question
            $question->update(['read_status' => true]);
        }

        // Create a notification array
        $notification = [
            'message' => 'Message send successfully.',
            'alert-type' => 'success'
        ];

        // Redirect back to the all questions page
        return redirect()->route('instructor.all_questions', Auth::user()->id)->with($notification);
    }


}
