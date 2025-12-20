<?php

namespace App\Http\Controllers\backend;

use App\Helpers\FlashNotification;
use App\Helpers\ImageResizer;
use App\Http\Controllers\Controller;
use App\Http\Requests\Course\StoreCourseRequest;
use App\Http\Requests\Course\UpdateCourseRequest;
use App\Models\Category;
use App\Models\Course;
use App\Models\Course_goal;
use App\Models\Course_Lecture;
use App\Models\Course_Section;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store_course(StoreCourseRequest $request)
    {
        try {
            $image = $request->file('image');
            // Get the image file name with extension
            $imgName = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();

            // Get the real path of the uploaded file
            $path = $image->getRealPath();

            // Check if the file exists and is readable
            if (! file_exists($path) || ! is_readable($path)) {
                throw new \Exception('File not found or not readable.');
            }

            // Process the image
            $resizedPath = public_path('storage/upload/course/images/'.$imgName);
            ImageResizer::resize($image, 370, 246, $resizedPath);

            // Save the image to storage
            // $img->save('storage/upload/course/images/' . $imgName);

            // Get the video file from the request
            $video = $request->file('video_link');

            // Get the video file name with extension
            $videoName = date('YmdHis').'.'.$video->getClientOriginalExtension();

            // Save the video to storage
            $video->move(public_path('storage/upload/course/videos/'), $videoName);

            // Create a new course object
            $course = new Course([
                'category_id' => $request->category_id,
                'sub_category_id' => $request->sub_category_id,
                'instructor_id' => auth()->user()->id,
                'image' => $imgName,
                'name' => $request->name,
                'title' => $request->title,
                'slug' => strtolower(str_replace(' ', '-', $request->name)),
                'description' => $request->description,
                'video_link' => $videoName,
                'course_level' => $request->level,
                'duration' => $request->duration,
                'resources' => $request->resources,
                'selling_price' => $request->selling_price,
                'discount_price' => $request->discount_price,
                'certificate' => $request->certificate,
                'prerequisites' => $request->prerequisites,
                'best_seller' => $request->best_seller,
                'featured' => $request->featured,
                'highest_rated' => $request->highest_rated,
                'status' => '1',
            ]);

            // Save the course to get the ID
            $course->save();

            // Get the course goals from the request
            $goals = $request->course_goals;

            // Loop through the goals and save them to the database
            foreach ($goals as $goalText) {
                $course->goals()->create([
                    'course_id' => $course->id,
                    'goal' => $goalText,
                ]);
            }

            return redirect()
                ->route('instructor.all_courses', auth()->user()->id)
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update_course(UpdateCourseRequest $request, string $id)
    {
        $course = Course::find($id);

        try {
            $data = $request->validated();

            if ($request->hasFile('image')) {
                $imageName = hexdec(uniqid()).'.'.$request->file('image')->getClientOriginalExtension();
                $resizedPath = public_path('storage/upload/course/images/'.$imageName);
                ImageResizer::resize($request->file('image'), 370, 246, $resizedPath);

                // Delete old image if exists
                if (! empty($course->image) && file_exists(public_path('storage/upload/course/images/'.$course->image))) {
                    unlink(public_path('storage/upload/course/images/'.$course->image));
                }

                $data['image'] = $imageName;
            } else {
                unset($data['image']);
            }

            // Handle checkboxes
            $data['best_seller'] = isset($data['best_seller']) ? '1' : '0';
            $data['featured'] = isset($data['featured']) ? '1' : '0';
            $data['highest_rated'] = isset($data['highest_rated']) ? '1' : '0';

            $course->update($data);

            return redirect()
                ->route('instructor.all_courses', auth()->user()->id)
                ->with(FlashNotification::success('Course updated successfully.'));
        } catch (\Exception $e) {
            return back()->with(FlashNotification::error('Oops! Something went wrong. '.$e->getMessage()));
        }
    }

    /**
     * Update the video of the course
     *
     * @param  \Illuminate\Http\Request  $request  The request object
     * @param  string  $id  The ID of the course
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update_video(Request $request, string $id)
    {
        try {
            $course = Course::find($id);

            // If the previous video exists, delete it
            if (! empty($course->video_link) && Storage::exists('public/upload/course/videos/'.$course->video_link)) {
                Storage::delete('public/upload/course/videos/'.$course->video_link);
            }

            // Generate a unique name for the video file
            $videoName = date('YmdHis').'.'.$request->file('video_link')->getClientOriginalExtension();

            // Store the video file in the 'public/upload/course/videos' directory
            $request->file('video_link')->storeAs('public/upload/course/videos', $videoName);

            // Update the course with the new video link
            $course->update([
                'video_link' => $videoName,
            ]);

            return back()->with(FlashNotification::success('Video updated successfully.'));
        } catch (\Exception $e) {
            return back()->with(FlashNotification::error('Oops! Something went wrong. '.$e->getMessage()));
        }
    }

    public function update_goals(Request $request, string $id)
    {
        $course = Course::find($id);

        // Filter out any empty values from the course_goals array
        $filteredGoals = array_filter($request->course_goals, function ($value) {
            return ! is_null($value) && $value !== '';
        });

        if (empty($filteredGoals)) {
            return back()->with(FlashNotification::error('Please select at least one goal.'));
        }

        // Delete existing goals for the course
        $course->goals()->delete();

        // Create new goals based on the filtered input
        foreach ($filteredGoals as $goalText) {
            $course->goals()->create([
                'goal' => $goalText,
            ]);
        }

        return back()->with(FlashNotification::success('Course goals updated successfully.'));
    }

    /**
     * Delete a course
     *
     * @param  string  $id  The ID of the course to delete
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy_course(string $id)
    {
        $course = Course::find($id);

        try {
            // If the course has an image, delete it
            if (! empty($course->image) && Storage::exists('public/upload/course/images/'.$course->image)) {
                Storage::delete('public/upload/course/images/'.$course->image);
            }

            // If the course has a video, delete it
            if (! empty($course->video_link) && Storage::exists('public/upload/course/videos/'.$course->video_link)) {
                Storage::delete('public/upload/course/videos/'.$course->video_link);
            }

            // Delete the course goals
            $course->goals()->delete();

            // Delete the course
            $course->delete();

            return redirect()
                ->route('instructor.all_courses', auth()->user()->id)
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store_section(Request $request, string $courseId)
    {
        Course::find($courseId)
            ->sections()
            ->create([  // Create a new section based on the input
                'section_title' => $request->section_title,  // Set the title of the section
            ]);

        return back()->with(FlashNotification::success('Section added successfully.'));
    }

    public function destroy_section(string $id)
    {
        $section = Course_Section::find($id);

        try {
            $section->lectures()->delete();
            $section->delete();

            return back()->with(FlashNotification::success('Section deleted successfully.'));
        } catch (\Exception $e) {
            return back()->with(FlashNotification::error('Oops! Something went wrong. '.$e->getMessage()));
        }
    }

    /**
     * Store a newly created lecture in storage.
     *
     * This method stores a newly created lecture in the database.
     *
     * @param  \Illuminate\Http\Request  $request  The request object
     * @param  string  $id  The ID of the section
     * @return \Illuminate\Http\JsonResponse
     */
    public function store_lecture(Request $request, string $id)
    {
        try {
            $section = Course_Section::find($id);  // Find the section with the given ID

            $lecture = $section->lectures()->create([  // Create a new lecture
                'course_id' => $section->course_id, // Set the course ID
                'lecture_title' => $request->lecture_title,  // Set the title of the lecture
                'content' => $request->content,  // Set the content of the lecture
                'url' => $request->url,  // Set the URL of the lecture video
            ]);

            return response()->json([  // Return a JSON response
                'success' => 'Lecture saved successfully.',  // with a success message
                'data' => $lecture, // and the newly created lecture
            ]);
        } catch (\Exception $e) {  // If an error occurred
            return response()->json([  // Return a JSON response
                'error' => 'Oops! something went wrong, Please try again.',  // with an error message
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
     * This method updates an existing lecture in the database.
     *
     * @param  \Illuminate\Http\Request  $request  The request object
     * @param  string  $id  The ID of the lecture
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update_lecture(Request $request, string $id)
    {
        try {
            $lecture = Course_Lecture::find($id);
            $lecture->update([
                'lecture_title' => $request->lecture_title,
                'content' => $request->content,
                'url' => $request->url,
            ]);

            return redirect()->back()->with(FlashNotification::success('Course Lecture updated successfully.'));
        } catch (\Exception $e) {
            return back()->with(FlashNotification::error('Oops! Something went wrong. '.$e->getMessage()));
        }
    }

    /**
     * Delete a lecture
     *
     * This method deletes a lecture from the database.
     *
     * @param  string  $id  The ID of the lecture
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy_lecture(string $id)
    {
        $lecture = Course_Lecture::find($id);
        $lecture->delete();

        return back()->with(FlashNotification::success('Course Lecture deleted successfully.'));
    }
}
