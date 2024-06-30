<?php

namespace App\Http\Controllers\backend;

use App\Models\Course;
use App\Models\Category;
use App\Models\Course_goal;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Models\Course_Lecture;
use App\Models\Course_Section;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Imagick\Driver;

class CourseController extends Controller
{

    public function all_courses_by_instructor(string $id) {
        $courses = Course::where('instructor_id', $id)->orderBy('id', 'desc')->get();
        return view('instructor.course.all_courses', compact('courses'));
    }

    public function add_course() {
        $categories = Category::orderBy('category_name', 'asc')->get();
        return view('instructor.course.add_course', compact('categories'));
    }

    /**
     * Returns the list of sub categories based on the given category ID
     *
     * @param string $id The category ID
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_subCategories(string $id) {
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
     * @param \Illuminate\Http\Request $request The request object
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store_course(Request $request) {
        // Validate the request data
        $request->validate([
            'title' => 'required|string|max:60',
            'video_link' => 'required|mimes:mp4,webm|max:10000',
            'category_id' => 'required',
            'sub_category_id' => 'required',
        ]);

        try {
            // Create an image manager instance with the GD driver
            $manager = new ImageManager(new Driver());

            // Get the image file name with extension
            $imgName = hexdec(uniqid()) . '.' . $request->file('image')->getClientOriginalExtension();

            // Get the real path of the uploaded file
            $path = $request->file('image')->getRealPath();

            // Check if the file exists and is readable
            if (!file_exists($path) || !is_readable($path)) {
                throw new \Exception('File not found or not readable.');
            }

            // Process the image
            $img = $manager->read($request->file('image'))->resize(370, 246)->toJpeg(80);

            // Save the image to storage
            $img->save('storage/upload/course/images/' . $imgName);

            // Get the video file from the request
            $video = $request->file('video_link');

            // Get the video file name with extension
            $videoName = date('YmdHis') . '.' . $video->getClientOriginalExtension();

            // Save the video to storage
            $video->move(public_path('storage/upload/course/videos/'), $videoName);

            // Create a new course object
            $course = new Course([
                'category_id'     => $request->category_id,
                'sub_category_id' => $request->sub_category_id,
                'instructor_id'   => auth()->user()->id,
                'image'           => $imgName,
                'name'            => $request->name,
                'title'           => $request->title,
                'slug'            => strtolower(str_replace(' ', '-', $request->name)),
                'description'     => $request->description,
                'video_link'      => $videoName,
                'course_level'    => $request->level,
                'duration'        => $request->duration,
                'resources'       => $request->resources,
                'selling_price'   => $request->selling_price,
                'discount_price'  => $request->discount_price,
                'certificate'     => $request->certificate,
                'prerequisites'   => $request->prerequisites,
                'best_seller'     => $request->best_seller,
                'featured'        => $request->featured,
                'highest_rated'   => $request->highest_rated,
                'status'          => '1',
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

            // Set a success message and redirect to the all courses page
            $notification = [
                'message' => 'Course added successfully',
                'alert-type' => 'success',
            ];

            return redirect()->route('instructor.all_courses', auth()->user()->id)->with($notification);
        } catch (\Exception $e) {
            // Set an error message and redirect back to the form
            $notification = [
                'message' =>  'Oops! something went wrong, Please try again.',
                'alert-type' => 'error',
            ];

            return back()->with($notification);
        }
    }



    public function edit_course(string $id) {
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
     * @param \Illuminate\Http\Request $request The request object
     * @param string $id The ID of the course
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update_course(Request $request, string $id) {
        // Validate the request data
        $request->validate([
            'category_id' => 'required',
            'sub_category_id' => 'required',
        ]);
    
        // Retrieve the course details
        $course = Course::find($id);
    
        try {
            if($request->hasFile('image')) {
                // For resize image
                $manager = new ImageManager(new Driver());
    
                // If the file exists in database and exists in storage folder
                if(!empty($course->image) && Storage::exists('public/upload/course/images/' . $course->image)) {
                    Storage::delete('public/upload/course/images/' . $course->image);
                }
    
                $imgName = date('YmdHis') . '.' . $request->file('image')->getClientOriginalExtension();
                // Get the real path of the uploaded file
                $path = $request->file('image')->getRealPath();
    
                // Check if the file exists and is readable
                if (!file_exists($path) || !is_readable($path)) {
                    throw new \Exception('File not found or not readable.');
                }
    
                $img = $manager->read($request->file('image'))->resize(370, 246)->toJpeg(80);
    
                // Save the image to storage
                $img->save('storage/upload/course/images/' . $imgName);
    
                $data = $request->except('image');
                $data['image'] = $imgName;
            } else {
                $data = $request->except('image');
            }
    
            // Handle checkboxes
            $data['best_seller'] = isset($data['best_seller']) ? '1' : '0';
            $data['featured'] = isset($data['featured']) ? '1' : '0';
            $data['highest_rated'] = isset($data['highest_rated']) ? '1' : '0';
    
            $course->update($data);
    
            $notification = array(
                'message' => 'Course updated successfully.',
                'alert-type' => 'success',
            );

            return redirect()->route('instructor.all_courses', auth()->user()->id)->with($notification);
        } catch (\Exception $e) {
            $notification = array(
                'message' => 'Oops! something went wrong, Please try again.',
                'alert-type' => 'error',
            );
            return back()->with($notification);
        }
    }


    /**
     * Update the video of the course
     *
     * @param \Illuminate\Http\Request $request The request object
     * @param string $id The ID of the course
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update_video(Request $request, string $id) {
        try {
            $course = Course::find($id);

            // If the previous video exists, delete it
            if (!empty($course->video_link) && Storage::exists('public/upload/course/videos/' . $course->video_link)) {
                Storage::delete('public/upload/course/videos/' . $course->video_link);
            }

            // Generate a unique name for the video file
            $videoName = date('YmdHis') . '.' . $request->file('video_link')->getClientOriginalExtension();

            // Store the video file in the 'public/upload/course/videos' directory
            $request->file('video_link')->storeAs('public/upload/course/videos', $videoName);

            // Update the course with the new video link
            $course->update([
                'video_link' => $videoName
            ]);

            $notification = array(
                'message' => 'Video updated successfully.',
                'alert-type' => 'success',
            );

            // Redirect the user back to the instructor's all courses page with a success message
            return back()->with($notification);
        } catch (\Exception $e) {
            $notification = array(
                'message' => 'Oops! something went wrong, Please try again.',
                'alert-type' => 'error',
            );
            // Return an error message if an error occurred
            return back()->with($notification);
        }
    }


    public function update_goals(Request $request, string $id) {
        $course = Course::find($id);

        // Filter out any empty values from the course_goals array
        $filteredGoals = array_filter($request->course_goals, function($value) {
            return !is_null($value) && $value !== '';
        });

        if (empty($filteredGoals)) {
            $notification = array(
                'message' => 'Please select at least one goal.',
                'alert-type' => 'error',
            );
            return back()->with($notification);
        } else {
            // Delete existing goals for the course
            $course->goals()->delete();

            // Create new goals based on the filtered input
            foreach ($filteredGoals as $goalText) {
                $course->goals()->create([
                    'goal' => $goalText,
                ]);
            }

            $notification = array(
                'message' => 'Course goals updated successfully.',
                'alert-type' => 'success',
            );
            return back()->with($notification);
        }
    }



    /**
     * Delete a course
     *
     * @param string $id The ID of the course to delete
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destory_course(string $id) {
        $course = Course::find($id);
        try {
            // If the course has an image, delete it
            if (!empty($course->image) && Storage::exists('public/upload/course/images/' . $course->image)) {
                Storage::delete('public/upload/course/images/' . $course->image);
            }

            // If the course has a video, delete it
            if (!empty($course->video_link) && Storage::exists('public/upload/course/videos/' . $course->video_link)) {
                Storage::delete('public/upload/course/videos/' . $course->video_link);
            }

            // Delete the course goals
            $course->goals()->delete();

            // Delete the course
            $course->delete();

            $notification = array(
                'message' => 'Course deleted successfully.',
                'alert-type' => 'success',
            );

            // Redirect the user to the instructor's all courses page
            return redirect()->route('instructor.all_courses', auth()->user()->id)->with($notification);
        } catch (\Exception $e) {
            $notification = array(
                'message' => 'Oops! something went wrong, Please try again.',
                'alert-type' => 'error',
            );
            // Return an error message if an error occurred
            return back()->with($notification);
        }
    }



    public function create_section(string $id) {
        $course = Course::find($id);
        return view('instructor.course.section.create_section', compact('course'));
    }


    /**
     * Store a newly created section in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string $courseId The ID of the course
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store_section(Request $request, string $courseId) {
        Course::find($courseId)
            ->sections()
            ->create([  // Create a new section based on the input
                'section_title' => $request->section_title,  // Set the title of the section
            ]);

        $notification = [  // Create a notification message
            'message' => 'Section added successfully.',
            'alert-type' => 'success',
        ];

        return back()->with($notification);  // Redirect the user back to the previous page with the notification message
    }



    public function destory_section(string $id) {
        $section = Course_Section::find($id);
        try {
            $section->lectures()->delete();
            $section->delete();
            $notification = array(
                'message' => 'Section deleted successfully.',
                'alert-type' => 'success',
            );
            return back()->with($notification);
        } catch (\Exception $e) {
            $notification = array(
                'message' => 'Oops! something went wrong, Please try again.',
                'alert-type' => 'error',
            );
            return back()->with($notification);
        }
    }




    /**
     * Store a newly created lecture in storage.
     *
     * This method stores a newly created lecture in the database.
     *
     * @param \Illuminate\Http\Request $request The request object
     * @param string $id The ID of the section
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store_lecture(Request $request, string $id) {
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




    public function edit_lecture(Request $request, string $id) {
        $lecture = Course_Lecture::find($id);
        return view('instructor.course.lecture.edit_lecture', compact('lecture'));
    }


    /**
     * Update a lecture in storage.
     *
     * This method updates an existing lecture in the database.
     *
     * @param \Illuminate\Http\Request $request The request object
     * @param string $id The ID of the lecture
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update_lecture(Request $request, string $id) {
        try {
            $lecture = Course_Lecture::find($id);  // Find the lecture with the given ID
            $lecture->update([  // Update the lecture with the given data
                'lecture_title' => $request->lecture_title,
                'content' => $request->content,
                'url' => $request->url,
            ]);
            $notification = [  // Create a success message
                'message' => 'Course Lecture updated successfully.',
                'alert-type' => 'success',
            ];
            return redirect()->back()->with($notification);  // Redirect the user back with the success message
        } catch (\Exception $e) {  // If an error occurred
            $notification = [  // Create an error message
                'message' => $e->getMessage(),
                'alert-type' => 'error',
            ];
            return back()->with($notification);  // Return an error message if an error occurred
        }
    }


    /**
     * Delete a lecture
     *
     * This method deletes a lecture from the database.
     *
     * @param string $id The ID of the lecture
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destory_lecture(string $id) {
        $lecture = Course_Lecture::find($id);  // Find the lecture with the given ID
        $lecture->delete();  // Delete the lecture
        $notification = [  // Create a success message
            'message' => 'Course Lecture deleted successfully.',
            'alert-type' => 'success',
        ];
        return back()->with($notification);  // Redirect the user back with the success message
    }

}