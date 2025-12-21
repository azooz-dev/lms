<?php

namespace App\Http\Controllers\backend;

use App\Actions\Course\CreateCourseAction;
use App\Actions\Course\DeleteCourseAction;
use App\Actions\Course\UpdateCourseAction;
use App\Helpers\FlashNotification;
use App\Http\Controllers\Controller;
use App\Http\Requests\Course\StoreCourseRequest;
use App\Http\Requests\Course\UpdateCourseRequest;
use App\Models\Category;
use App\Models\Course;
use App\Models\Course_goal;
use App\Models\Course_Lecture;
use App\Models\Course_Section;
use App\Models\SubCategory;
use App\Services\CourseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function __construct(
        private readonly CourseService $courseService,
        private readonly CreateCourseAction $createCourseAction,
        private readonly UpdateCourseAction $updateCourseAction,
        private readonly DeleteCourseAction $deleteCourseAction
    ) {}

    public function all_courses_by_instructor(string $id)
    {
        $courses = Course::where('instructor_id', $id)->orderBy('id', 'desc')->get();

        return view('instructor.course.all_courses', compact('courses'));
    }

    public function add_course()
    {
        $categories = Category::orderBy('category_name', 'asc')->get();

        return view('instructor.course.add_course', compact('categories'));
    }

    /**
     * Returns the list of sub categories based on the given category ID
     *
     * @param  string  $id  The category ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_subCategories(string $id)
    {
        try {
            $subCategories = SubCategory::where('category_id', $id)
                ->orderBy('subCategory_name', 'asc')
                ->get();

            return response()->json($subCategories, 200);
        } catch (\Exception $e) {
            return response()->json(
                ['error' => 'An error occurred while processing your request.'],
                500
            );
        }
    }

    /**
     * Stores the course information to the database
     *
     * @param  StoreCourseRequest  $request  The validated request object
     */
    public function store_course(StoreCourseRequest $request): RedirectResponse
    {
        try {
            $this->createCourseAction->handle(
                $request->validated(),
                $request->file('image'),
                $request->file('video_link'),
                $request->course_goals ?? [],
                auth()->id()
            );

            return redirect()
                ->route('instructor.all_courses', auth()->id())
                ->with(FlashNotification::success('Course added successfully.'));
        } catch (\Exception $e) {
            return back()->with(FlashNotification::error('Oops! Something went wrong. '.$e->getMessage()));
        }
    }

    public function edit_course(string $id)
    {
        // Retrieve the course details
        $course = Course::find($id);

        // Retrieve the course goals
        $goals = Course_goal::where('course_id', $id)->get();

        // Retrieve all categories
        $categories = Category::orderBy('category_name', 'asc')->get();

        // Pass the course, categories and goals to the view
        return view('instructor.course.edit_course', compact('course', 'categories', 'goals'));
    }

    /**
     * Update the course information
     *
     * @param  UpdateCourseRequest  $request  The validated request object
     * @param  string  $id  The ID of the course
     */
    public function update_course(UpdateCourseRequest $request, string $id): RedirectResponse
    {
        $course = Course::find($id);

        try {
            $this->updateCourseAction->handle(
                $course,
                $request->validated(),
                $request->file('image')
            );

            return redirect()
                ->route('instructor.all_courses', auth()->id())
                ->with(FlashNotification::success('Course updated successfully.'));
        } catch (\Exception $e) {
            return back()->with(FlashNotification::error('Oops! Something went wrong. '.$e->getMessage()));
        }
    }

    /**
     * Update the video of the course
     *
     * @param  string  $id  The ID of the course
     */
    public function update_video(Request $request, string $id): RedirectResponse
    {
        try {
            $course = Course::find($id);
            $this->courseService->updateCourseVideo($course, $request->file('video_link'));

            return back()->with(FlashNotification::success('Video updated successfully.'));
        } catch (\Exception $e) {
            return back()->with(FlashNotification::error('Oops! Something went wrong. '.$e->getMessage()));
        }
    }

    public function update_goals(Request $request, string $id): RedirectResponse
    {
        $course = Course::find($id);

        $success = $this->courseService->updateCourseGoals($course, $request->course_goals ?? []);

        if (! $success) {
            return back()->with(FlashNotification::error('Please select at least one goal.'));
        }

        return back()->with(FlashNotification::success('Course goals updated successfully.'));
    }

    /**
     * Delete a course
     *
     * @param  string  $id  The ID of the course to delete
     */
    public function destroy_course(string $id): RedirectResponse
    {
        $course = Course::find($id);

        try {
            $this->deleteCourseAction->handle($course);

            return redirect()
                ->route('instructor.all_courses', auth()->id())
                ->with(FlashNotification::success('Course deleted successfully.'));
        } catch (\Exception $e) {
            return back()->with(FlashNotification::error('Oops! Something went wrong. '.$e->getMessage()));
        }
    }

    public function create_section(string $id)
    {
        $course = Course::find($id);

        return view('instructor.course.section.create_section', compact('course'));
    }

    /**
     * Store a newly created section in storage.
     *
     * @param  string  $courseId  The ID of the course
     */
    public function store_section(Request $request, string $courseId): RedirectResponse
    {
        $course = Course::find($courseId);
        $this->courseService->createSection($course, $request->section_title);

        return back()->with(FlashNotification::success('Section added successfully.'));
    }

    public function destroy_section(string $id): RedirectResponse
    {
        $section = Course_Section::find($id);

        try {
            $this->courseService->deleteSection($section);

            return back()->with(FlashNotification::success('Section deleted successfully.'));
        } catch (\Exception $e) {
            return back()->with(FlashNotification::error('Oops! Something went wrong. '.$e->getMessage()));
        }
    }

    /**
     * Store a newly created lecture in storage.
     *
     * @param  string  $id  The ID of the section
     */
    public function store_lecture(Request $request, string $id): JsonResponse
    {
        try {
            $section = Course_Section::find($id);
            $lecture = $this->courseService->createLecture($section, $request->all());

            return response()->json([
                'success' => 'Lecture saved successfully.',
                'data' => $lecture,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Oops! something went wrong, Please try again.',
            ], 500);
        }
    }

    public function edit_lecture(Request $request, string $id)
    {
        $lecture = Course_Lecture::find($id);

        return view('instructor.course.lecture.edit_lecture', compact('lecture'));
    }

    /**
     * Update a lecture in storage.
     *
     * @param  string  $id  The ID of the lecture
     */
    public function update_lecture(Request $request, string $id): RedirectResponse
    {
        try {
            $lecture = Course_Lecture::find($id);
            $this->courseService->updateLecture($lecture, $request->all());

            return redirect()->back()->with(FlashNotification::success('Course Lecture updated successfully.'));
        } catch (\Exception $e) {
            return back()->with(FlashNotification::error('Oops! Something went wrong. '.$e->getMessage()));
        }
    }

    /**
     * Delete a lecture
     *
     * @param  string  $id  The ID of the lecture
     */
    public function destroy_lecture(string $id): RedirectResponse
    {
        $lecture = Course_Lecture::find($id);
        $this->courseService->deleteLecture($lecture);

        return back()->with(FlashNotification::success('Course Lecture deleted successfully.'));
    }
}
