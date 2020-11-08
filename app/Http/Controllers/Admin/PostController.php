<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\Controller;

use App\Post;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isAdmin']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validData = $this->validation($request);

        $storage = env('APP_ENV') === 'testing' ? 'test' : 'public';

        $imagePath = $validData['image'] !== null ?
            $validData['image']->store('images/blog', $storage) :
            null;

        Post::create([
            'title' => $validData['title'],
            'content' => str_replace(
                ['_:p:o_', '_:p:c_'],
                ['<p>', '</p>'],
                $validData['content']
            ),
            'excerpt' => $validData['excerpt'],
            'status' => $validData['status'],
            'category_id' => $validData['category_id'],
            'image' => $imagePath,
            'user_id' => Auth::user()->id
        ]);

        return redirect('admin/posts/create');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $validData = $this->validation($request);

        $storage = env('APP_ENV') === 'testing' ? 'test' : 'public';

        if ($validData['image'] === null && $request->input('leave_old_image') == false) {
         
            $imagePath = null;
            $post->deleteImageFile();

        } else if ($validData['image'] !== null && $request->input('leave_old_image') == false) {

            $post->deleteImageFile();
            $imagePath = $validData['image']->store('images/blog', $storage);

        } else {

            $imagePath = $post->image;

        }

        $post->update([
            'title' => $validData['title'],
            'content' => $validData['content'],
            'excerpt' => $validData['excerpt'],
            'status' => $validData['status'],
            'category_id' => $validData['category_id'],
            'image' => $imagePath
        ]);

        return redirect($post->specificResourcePath('/admin'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->deleteImageFile();
        $post->delete();

        return redirect('admin/posts');
    }

    private function validation(Request $request)
    {
        return $request->validate([
            'title' => 'required|unique:posts|string|max:255',
            'content' => 'required',
            'excerpt' => 'required',
            'status' => 'required|integer',
            'image' => 'nullable|image',
            'category_id' => 'required|integer'
        ]);
    }
}
